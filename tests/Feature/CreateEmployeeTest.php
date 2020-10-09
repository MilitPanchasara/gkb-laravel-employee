<?php

namespace Tests\Feature;

use App\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Event;

class CreateEmployeeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testWithAuthenticationButNotSignedIn()
    {
        $response = $this->get('/employees/create');
        $response->assertRedirect('/login');
    }

    public function testWithAuthenticationAndSignedIn()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->get('employees/create');
        $response->assertStatus(200);
    }

    public function testEmployeeDataIsStoredProperly()
    {
        Event::fake();
        $exist = Employee::where('email','test@test.com')->delete();
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->post('/employees',[
                             'fname' => 'Testfname',
                             'lname' => 'Testlname',
                             'email' => 'test@test.com',
                             'gender' => 'male',
                             'hobbies' => ['TV','Reading','Coding'],
                         ]);
        $response->assertRedirect('/employees');
        $this->assertCount(1,Employee::where('email','test@test.com')->get());
    }

    public function testInvalidEmailInsertion()
    {
        Event::fake();
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->post('/employees',[
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
        $response = $this->actingAs($user)
                         ->post('/employees',[
                             'fname' => 'Test fname',
                             'lname' => 'Test lname',
                             'email' => 'test@test.com',
                             'gender' => 'male',
                             'hobbies' => ['TV','Reading','Coding'],
                         ]);
        $response->assertSessionHasErrors('email');
        $this->assertCount(1,Employee::where('email','test@test.com')->get());
    }
    
    public function testBadFirstNameInsertion()
    {
        Event::fake();
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->post('/employees',[
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
                         ->post('/employees',[
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
                         ->post('/employees',[
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
