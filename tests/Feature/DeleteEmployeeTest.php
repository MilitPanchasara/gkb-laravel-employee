<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Employee;
use App\User;

class DeleteEmployeeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testWithAuthenticationButNotSignedIn()
    {
        $response = $this->delete('/employees/3');
        $response->assertRedirect('/login');
    }

    public function testWithAuthenticationAndSignedIn()
    {
        $user = factory(User::class)->create();
        $response1 = $this->actingAs($user)
                         ->post('/employees',[
                             'fname' => 'Testfname',
                             'lname' => 'Testlname',
                             'email' => 'testDelete@test.com',
                             'gender' => 'male',
                             'hobbies' => ['TV','Reading','Coding'],
                         ]);
        $response1->assertRedirect('/employees');
        $employee = Employee::where('email','testDelete@test.com')->get();

        $response2 = $this->actingAs($user)
        ->delete('/employees/'.$employee[0]->id,[$employee[0]->id]);

        $response2->assertRedirect('/employees');
        $this->assertCount(0,Employee::where('email','testDelete@test.com')->get());
    }
}
