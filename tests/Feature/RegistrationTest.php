<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class RegistrationTest extends TestCase
{
    /**
     * Test if all the fields are null.
     *
     * @return void
     */
    public function testNullField()
    {
        $this->json('POST', 'api/register')
            ->assertStatus(200)
            ->assertJson([
                "message" => "The email field is required."
            ]);
    }

    /**
     * Test if the password field is null.
     *
     * @return void
     */
    public function testPasswordField()
    {
        $userData = [
            "email" => "admin@admin.com",
            "password" => null,
        ];

        $this->json('POST', 'api/register', $userData)
            ->assertStatus(200)
            ->assertJson([
                "message" => "The password field is required."
            ]);
    }

    /**
     * Test if the fields are defined.
     *
     * @return void
     */
    public function testSuccessRegistration()
    {
        $userData = [
            "email" => "mico@gmail.com",
            "password" => "password1234",
        ];

        $this->json('POST', 'api/register', $userData)
            ->assertStatus(200)
            ->assertJson([
                "message" => "User successfully registered"
            ]);
    }

    /**
     * Test if the fields are repeated.
     *
     * @return void
     */
    public function testRepeatedRegistration()
    {
        $userData = [
            "email" => "mico@gmail.com",
            "password" => "password1234",
        ];

        $this->json('POST', 'api/register', $userData)
            ->assertStatus(200)
            ->assertJson([
                "message" => "The email has already been taken."
            ]);
    }
}
