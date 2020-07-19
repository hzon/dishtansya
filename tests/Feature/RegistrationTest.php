<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    /**
     * Test if registration is successful
     *
     * @return void
     */
    public function testRegistration()
    {
        $response = $this->json('POST', '/register', ['email' => 'backend@multisyscorp.com', 'password' => 'test123']);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'User successfully registered',
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUnsuccessfulRegistration()
    {
        $response = $this->json('POST', '/register', ['email' => 'backend@multisyscorp.com', 'password' => 'test123']);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Email already taken',
            ]);
    }
}
