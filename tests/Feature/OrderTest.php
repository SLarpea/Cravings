<?php

namespace Tests\Feature;

use App\Models\User;
use JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * Test where the user is successfully ordered.
     *
     * @return void
     */
    public function testSuccessOrder()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $orderData = [
            'product_id' => 3,
            'quantity' => 4,
        ];
        
        $this->json('POST', 'api/order', $orderData, ['Authorization' => 'Bearer '. $token])
            ->assertStatus(200)
            ->assertJson([
                "message" => "You have successfully ordered this product."
            ]);
    }

    /**
     * Test where the user is failed to order because of unavailability of the product.
     *
     * @return void
     */
    public function testFailedOrder()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $orderData = [
            'product_id' => 3,
            'quantity' => 99999,
        ];
        
        $this->json('POST', 'api/order', $orderData, ['Authorization' => 'Bearer '. $token])
            ->assertStatus(200)
            ->assertJson([
                "message" => "Failed to order this product due to unavailability of the stock"
            ]);
    }
}
