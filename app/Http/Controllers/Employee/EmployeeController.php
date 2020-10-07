<?php

namespace App\Http\Controllers\Employee;

use App\Employee;
use App\EmployeesHobby;
use App\Http\Controllers\Controller;
use Dotenv\Result\Success;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::all();
        
        return view('employees')->with('employees',$employees);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('createEmployee');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new_emp = new Employee();
        $new_emp->first_name = $request->fname;
        $new_emp->last_name = $request->lname;
        $if_exist = Employee::where('email',$request->email)->get();
        if($if_exist->count() != 0){
            return back()->withInput()->with('error','E-mail already exists.');
        }
        $new_emp->email = $request->email;
        $new_emp->gender = $request->gender;
        if($request->hasFile('photo')){
            $fileNameWithExt = $request->file('photo')->getClientOriginalName();
            //get just file name
            $filename = pathinfo($fileNameWithExt,PATHINFO_FILENAME);
            //get just ext
            $extension = $request->file('photo')->getClientOriginalExtension();
            //unique filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            ini_set('max_execution_time', 300);
            $path = $request->file('photo')->storeAs('public/profile_pictures', $fileNameToStore);
            $new_emp->profile_picture = $fileNameToStore;
        }
        $new_emp->save();

        if($request->hobbies != null)
        { 
            foreach($request->hobbies as $hobby)
            {
                $new_hobby = new EmployeesHobby();
                
                $new_hobby->hobby = $hobby;
                $new_hobby->employee()->associate($new_emp);
                $new_hobby->save();
            }
        }
        return redirect('/employees')->with('success','New employee added with ID:'.$new_emp->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);
        $hobbies = $employee->hobbies()->get();
        return view('employeeProfile')->with('employee',$employee)->with('hobbies',$hobbies);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $emp = Employee::find($id);
        $hobbies = DB::table('employees_hobbies')->where('employee_id','=',$id)->pluck('hobby');
        // return $hobbies->toArray();
        return view('editEmployee') ->with('employee',$emp)
                                    ->with('hobbies',$hobbies->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $emp = Employee::find($id);
        $emp->first_name = $request->fname;
        $emp->last_name = $request->lname;
        $if_exist = Employee::where('email',$request->email)->get();
        if($if_exist->count() != 0){
            return back()->withInput()->with('error','E-mail already exists.');
        }
        $emp->email = $request->email;
        $emp->gender = $request->gender;
        if($request->hasFile('photo')){
            $fileNameWithExt = $request->file('photo')->getClientOriginalName();
            //get just file name
            $filename = pathinfo($fileNameWithExt,PATHINFO_FILENAME);
            //get just ext
            $extension = $request->file('photo')->getClientOriginalExtension();
            $imageExtensions = ['png','jpg','JPG','PNG','jpeg'];
            if(!in_array($extension,$imageExtensions))
            {
                return back()->withInput();
            }
            //unique filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image 
            $request->file('photo')->storeAs('public/profile_pictures', $fileNameToStore);
            if($emp->profile_picture != null)
            {
                unlink(storage_path('app/public/profile_pictures/'.$emp->profile_picture));
            }
            $emp->profile_picture = $fileNameToStore;
        }
        $emp->save();

        $old_hobbies = $emp->hobbies()->get();
        
        
        foreach($old_hobbies as $hobby)
            $hobby->delete();

        if($request->hobbies != null)
        {    
            foreach($request->hobbies as $hobby)
            {
                $new_hobby = new EmployeesHobby();
                $new_hobby->hobby = $hobby;
                $new_hobby->employee()->associate($emp);
                $new_hobby->save();
            }
        }
        return redirect('/employees')->with('success','Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $emp = Employee::find($id);
        if($emp->profile_picture != null)
            unlink(storage_path('app/public/profile_pictures/'.$emp->profile_picture));
        $emp->delete();
        return back()->with('success','Deleted!');
    }

    public function importCSV()
    {
        return view('importCSV');
    }

    public function saveCSVData(Request $request)
    {
        if($request->hasFile('csv')){
            $fileNameWithExt = $request->file('csv')->getClientOriginalName();
            $filename = pathinfo($fileNameWithExt,PATHINFO_FILENAME);
            $extension = $request->file('csv')->getClientOriginalExtension();
            if($extension != 'csv')
            {
                return back()->with('error','Uploaded file is not CSV.');
            }
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $request->file('csv')->storeAs('public/csv_files', $fileNameToStore);
            if (($handle = fopen (public_path () . '/storage/csv_files/'.$fileNameToStore, 'r' )) !== FALSE) {
                while ( ($data = fgetcsv ( $handle, 0, ',' )) !== FALSE ) {
                    $dataArray[] = $data;
                }
                fclose ( $handle );
            }
            $columns = array_shift($dataArray);//returns 1st row and removes from array

            if(!$this->verifyColumnHeaders($columns))
            {
                return back()->with('error','Cannot recognise columns, please follow the format.');
            }
            $success = 0;
            $failed = 0;
            
            foreach ($dataArray as $records ) {
                try {
                    if($records[0] == null || $records[1] == null || $records[2] == null)
                    {
                        $failed++;
                        continue;
                    }
                    if($this->has_numbers($records[0]) || $this->has_special_chars($records[0]) || $this->has_numbers($records[1]) || $this->has_special_chars($records[1]))
                    {
                        $failed++;
                        continue;
                    }
                    if(strpos($records[2],'@') == false)
                    {
                        $failed++;
                        continue;
                    }
                    $new_emp = new Employee();
                    $new_emp->first_name = $records[0];
                    $new_emp->last_name = $records[1];
                    $new_emp->email = $records[2];
                    $new_emp->gender = $records[3];
                    $new_emp->save();
                    $hobbiesArray = explode(',',$records[4]);
                    foreach($hobbiesArray as $hobby)
                    {
                        $new_hobby = new EmployeesHobby();
                        $new_hobby->hobby = $hobby;
                        $new_hobby->employee()->associate($new_emp);
                        $new_hobby->save();
                    }
                    $success++;
                } catch (\Throwable $th) {
                    $failed++;
                    continue;
                }
                
            }

            unlink(storage_path('app/public/csv_files/'.$fileNameToStore));
            return redirect('/employees')->with('success','Data saved... Succeed: '.$success.', Failed: '.$failed);
        }
        return back()->with('error','No file selected.');
    }

    // Does string contain numbers?
    private function has_numbers( $string ) 
    {
        return preg_match( '/\d/', $string );
    }
    
    // Does string contain special characters?
    private function has_special_chars( $string ) 
    {
        return preg_match('/[^a-zA-Z\d]/', $string);
    }

    private function verifyColumnHeaders($columns)
    {
        $retVal = false;
        if($columns[0] != 'first_name')
        {
            return $retVal;
        }
        if($columns[1] != 'last_name')
        {
            return $retVal;
        }
        if($columns[2] != 'email')
        {
            return $retVal;
        }
        $retVal = true;
        return $retVal;
    }

    public function dataTable(Request $request)
    {
        $employees = Employee::all();
        $empJSON = $employees->toJson();
        return json_encode([
            'data'=>$empJSON
        ]);
    }
}
