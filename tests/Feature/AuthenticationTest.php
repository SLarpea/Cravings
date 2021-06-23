<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * Test for the data that is not in the database.
     *
     * @return void
     */
    public function testInvalidCredentials()
    {
        $userData = [
            'email' => 'dlonra@gmail.com',
            'password' => 'password1234'
        ];

        $this->json('POST', 'api/login', $userData)
            ->assertStatus(200)
            ->assertJson([
                "message" => "Invalid credentials"
            ]);
    }

    /**
     * Test if data is login.
     *
     * @return void
     */
    public function testSuccessLogin()
    {
        $userData = [
            'email' => 'sulanaarnold@gmail.com',
            'password' => 'password1234'
        ];
        $this->json('POST', 'api/login', $userData)
            ->assertStatus(200)
            ->assertJsonStructure([
                "access_token"
            ]);
    }
}
