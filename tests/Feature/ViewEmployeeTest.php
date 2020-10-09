<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class ViewEmployeeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testWithAuthenticationButNotSignedIn()
    {
        $response = $this->get('/employees/1');
        $response->assertRedirect('/login');
    }

    public function testWithAuthenticationAndSignedIn()
    {
        // auth()->loginUsingId(1);
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->get('employees/2');
        $response->assertStatus(200);
    }
    
}
