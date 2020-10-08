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
        $response->assertStatus(302);
    }

    // public function testWithAuthenticationAndSignedIn()
    // {
    //     $user = factory(User::class)->create();
    //     $emp = factory(Employee::class)->create();
    //     $response = $this->actingAs($user)
    //                      ->delete('employees/'.$emp->id);
    //     $response->dumpHeaders();
    //     // $response->assertStatus(302);
    // }
}
