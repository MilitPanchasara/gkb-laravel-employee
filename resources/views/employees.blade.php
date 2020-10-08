@extends('layouts.app')

@section('content')
<div class="container">
    <a class="btn btn-info" href="/employees/create">Create</a> <a class="btn btn-info" href="/employees/import">Import CSV</a><br><br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Employees') }}</div>

                <div class="card-body">
                    {{-- <table class="table table-bordered" id="employeeTable">
                        <thead>
                            <tr class="bg-dark text-light">
                                <th>ID</th>
                                <th>Profile Picture</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>E-mail</th>
                                <th>Gender</th>
                                <th>Hobbies</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $emp)
                                <tr>
                                    <td>{{$emp->id}}</td>
                                    <td>
                                        @if ($emp->profile_picture)
                                        <img id="blah" src="/storage/profile_pictures/{{$emp->profile_picture}}" alt="your image" height="100px" width="100px"/>
                                        @else
                                        Not Uploaded
                                        @endif
                                    </td>
                                    <td>{{$emp->first_name}}</td>
                                    <td>{{$emp->last_name}}</td>
                                    <td>{{$emp->email}}</td>
                                    <td>{{$emp->gender}}</td>
                                    <td>@foreach ($emp->hobbies()->get() as $hobby)
                                        {{$hobby->hobby}}<br>
                                    @endforeach</td>
                                    <td>{{$emp->created_at}}</td>
                                    <td><a class="btn btn-primary" href="/employees/{{$emp->id}}" style="height: 27px;padding:2px 4px 3px 4px">Show</a><br><br>
                                        <form action="{{url('employees', [$emp->id])}}" method="POST">
                                        @csrf
                                        {{method_field('DELETE')}}
                                        <input type="submit" class="btn btn-danger delete" value="Delete" style="height: 27px;padding:2px 4px 3px 4px"/>
                                     </form>
                                    <br><a class="btn btn-success delete" href="/employees/{{$emp->id}}/edit" style="height: 27px;padding:2px 4px 3px 4px">Edit</a></td> 
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>  --}}
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                            <tr class="bg-dark text-light">
                                <th>ID</th>
                                <th>Profile Picture</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>E-mail</th>
                                <th>Gender</th>
                                <th>Hobbies</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="bg-dark text-light">
                                <th>ID</th>
                                <th>Profile Picture</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>E-mail</th>
                                <th>Gender</th>
                                <th>Hobbies</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table><hr>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/js/app.js"></script>
<script>
    $(document).ready( function () {
        $('#employeeTable').DataTable();

        $('#dataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax" : {
                "url" : "/employees/dataTable",
                "type" : "POST",
                "data": "data",
            },
            "columns": [
                { "data": "id" },
                { "data": "profile_picture" },
                { "data": "first_name" },
                { "data": "last_name" },
                { "data": "email" },
                { "data": "gender" },
                { "data": "hobbies" },
                { "data": "created_at" },
                { "data": "actions" }
            ],
            "columnDefs": [
                { 
                    targets: 1,
                    render: function(data) {
                        
                        if(data != "no_image")
                            return '<img src="/storage/profile_pictures/'+data+'"  height="100px" width="100px">'
                        else 
                            return data
                    }
                },
                { 
                    targets: 8,
                    render: function(data) {
                            return '<a class="btn btn-primary" href="/employees/'+data+'" style="height: 27px;padding:2px 4px 3px 4px">Show</a><br><br>\
                                    <a class="btn btn-success delete" href="/employees/'+data+'/edit" style="height: 27px;padding:2px 4px 3px 4px">Edit</a><br><br>\
                                    <form method="post" action="/employees/'+data+'"> \
                                        @csrf\
                                        <input type="hidden" name="_method" value="DELETE">\
                                        <button type="submit" class="btn btn-danger delete" style="height: 27px;padding:2px 4px 3px 4px">Delete</button>\
                                    </form>'
                    }
                },
                {
                    'targets': [1,6,8],
                    'orderable': false,
                }
            ]
        });
    });

    $("#dataTable").delegate("tbody > tr > td > .delete","click", function(){
        return confirm("Are you sure?");
    });

</script>
@endsection
