<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * Test if order is successful
     *
     * @return void
     */
    public function testOrder()
    {
        $user = User::where('email', 'backend@multisyscorp.com')->first();
        Auth::login($user);                 //ensure user's logged in

        $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $user->access_token,
            ])->json('POST', '/order', [
                'product_id' => 1,
                'quantity' => 2,
            ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'You have successfully ordered this product.',
            ]);
    }

    /**
     * Test if order is unsuccessful
     *
     * @return void
     */
    public function testUnsuccessfulOrder()
    {
        $user = User::where('email', 'backend@multisyscorp.com')->first();
        Auth::login($user);                 //ensure user's logged in

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->access_token,
        ])->json('POST', '/order', [
            'product_id' => 2,
            'quantity' => 9999,
        ]);

        $response
            ->assertStatus(400)
            ->assertJson([
                'message' => 'Failed to order this product due to unavailability of the stock',
            ]);
    }
}
