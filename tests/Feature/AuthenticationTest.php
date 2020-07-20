<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * Test if authentication is successful
     *
     * @return void
     */
    public function testAuthentication()
    {
        $response = $this->json('POST', '/login', [
            'email' => 'backend@multisyscorp.com',
            'password' => 'test123'
        ]);

        $accessToken = User::where('email', 'backend@multisyscorp.com')->first()->access_token;

        $response
            ->assertStatus(201)
            ->assertJson([
                'access_token' => $accessToken,
            ]);
    }

    /**
     * Test if authentication is unsuccessful
     *
     * @return void
     */
    public function testUnsuccessffulAuthentication()
    {
        $response = $this->json('POST', '/login', [
            'email' => 'backend@multisyscorp.com',
            'password' => 'test123123'
        ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials',
            ]);
    }
}
