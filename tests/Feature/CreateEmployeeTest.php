<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

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
        $response->assertStatus(302);
    }

    public function testWithAuthenticationAndSignedIn()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->get('employees/create');
        $response->assertStatus(200);
    }
}
