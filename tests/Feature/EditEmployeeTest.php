<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class EditEmployeeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testWithAuthenticationButNotSignedIn()
    {
        $response = $this->get('/employees/3/edit');

        $response->assertStatus(302);
    }

    public function testWithAuthenticationAndSignedIn()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
                         ->get('employees/2/edit');
        $response->assertStatus(200);
    }
}
