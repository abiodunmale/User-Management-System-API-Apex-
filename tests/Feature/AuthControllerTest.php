<?php

namespace Tests\Unit\App\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserRegistrationSuccess(){

        $response = $this->postJson('/api/v1/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'user' => true,
                ],
                'msg' => 'User registered successfully',
            ]);
    }

    public function testUserRegistrationValidationFailure(){

        $response = $this->postJson('/api/v1/register', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'pass',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'msg' => 'Validation failed',
            ]);
    }

    public function testUserLoginSuccess(){

        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);


        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);


        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'msg' => 'User logged in successfully',
            ]);
    }

    public function testUserLoginIncorrectPassword(){

        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);


        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);


        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'msg' => 'Invalid credentials',
            ]);
    }
}