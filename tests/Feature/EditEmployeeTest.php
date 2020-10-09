<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Event;
use App\Employee;
use Facade\Ignition\Support\FakeComposer;
use Faker\Generator as Faker;

class EditEmployeeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testWithAuthenticationButNotSignedIn()
    {
        $response = $this->get('/employees/2/edit');

        $response->assertRedirect('/login');
    }

    public function testWithAuthenticationAndSignedIn()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->get('employees/2/edit');
        $response->assertStatus(200);
    }

    public function testEmployeeDataIsStoredProperly()
    {
        Event::fake();
        
        $user = factory(User::class)->create();
        $response1 = $this->actingAs($user)
                         ->put('/employees/2',[
                             'fname' => 'Testeditfname',
                             'lname' => 'Testeditlname',
                             'email' => 'testedit@testedit.com',
                             'gender' => 'male',
                             'hobbies' => ['TV','Reading','Coding'],
                         ]);
        $emp = Employee::where('email','testedit@testedit.com')->get();
        

        $response1->assertRedirect('/employees');
        $response2 = $this->actingAs($user)
                         ->put('/employees/2',[
                             'fname' => 'x',
                             'lname' => 'x',
                             'email' => 'x@x.x',
                             'gender' => 'male',
                             'hobbies' => ['TV','Reading','Coding'],
                         ]);
        $response2->assertRedirect('/employees');
        $this->assertCount(1,$emp);
        
    }

    public function testInvalidEmailInsertion()
    {
        Event::fake();
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->put('/employees/2',[
                             'fname' => 'Test fname',
                             'lname' => 'Test lname',
                             'email' => 'test',
                             'gender' => 'male',
                             'hobbies' => ['TV','Reading','Coding'],
                         ]);
        $this->assertCount(0,Employee::where('email','test')->get());
    }

    public function testExistingEmailInsertion()
    {
        Event::fake();
        $user = factory(User::class)->create();
        
        $response2 = $this->actingAs($user)
        ->put('/employees/2',[ 
            'fname' => 'Test fname',
            'lname' => 'Test lname',
            'email' => 'testExisting@test.com',
            'gender' => 'male',
            'hobbies' => ['TV','Reading','Coding'],
        ]);
        $this->assertEquals('E-mail already exists.',session()->get('error'));
        $this->assertCount(1,Employee::where('email','testExisting@test.com')->get());
    }
    
    public function testBadFirstNameInsertion()
    {
        Event::fake();
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->put('/employees/2',[
                             'fname' => 'Test1234',
                             'lname' => 'Test lname',
                             'email' => 'test2@test2.com',
                             'gender' => 'male',
                             'hobbies' => ['TV','Reading','Coding'],
                         ]);
        // $response->assertSessionHasErrors('error');
        $response->assertSessionHasErrors('fname');
        $this->assertCount(0,Employee::where('email','test2@test2.com')->get());
    }

    public function testBadLastNameInsertion()
    {
        Event::fake();
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->put('/employees/2',[
                             'fname' => 'Test fname',
                             'lname' => 'Test 1234',
                             'email' => 'test2@test2.com',
                             'gender' => 'male',
                             'hobbies' => ['TV','Reading','Coding'],
                         ]);
        // $response->assertSessionHasErrors('error');
        $response->assertSessionHasErrors('lname');
        $this->assertCount(0,Employee::where('email','test2@test2.com')->get());
    }

    public function testInsertionWithMissingRequiredFields()
    {
        Event::fake();
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->put('/employees/2',[
                             'fname' => '',
                             'lname' => '',
                             'email' => '',
                             'gender' => 'male',
                             'hobbies' => ['TV','Reading','Coding'],
                         ]);
        // $response->assertSessionHasErrors('error');
        $response->assertSessionHasErrors('lname');
        $response->assertSessionHasErrors('fname');
        $response->assertSessionHasErrors('email');
    }
}
