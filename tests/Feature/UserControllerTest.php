<?php

namespace Tests\Unit\App\Http\Controllers\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetUserProfileTokenFailure(){

        $response = $this->getJson('/api/v1/profile');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    public function testGetProfileSuccess(){
        $user = User::factory()->create();
        Passport::actingAs($user, 'api');

        $response = $this->getJson('/api/v1/profile');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'msg' => 'User profile retrieved successfully'
            ]);

    }

    public function testUpdateProfileNameSuccess(){
        $user = User::factory()->create();
        Passport::actingAs($user, 'api');

        $data = [
            'name' => 'Samuel James'
        ];

        $response = $this->putJson('/api/v1/profile', $data);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'msg' => 'User profile updated successfully',
                'data' => [
                    'name' => 'Samuel James',
                ]
            ]);
    }

    public function testUpdateProfileEmailSuccess(){
        $user = User::factory()->create();
        Passport::actingAs($user, 'api');

        $data = [
            'email' => 'james@gamil.com',
        ];

        $response = $this->putJson('/api/v1/profile', $data);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'msg' => 'User profile updated successfully',
                'data' => [
                    'email' => 'james@gamil.com',
                ]
            ]);
    }

    public function testUpdatePasswordValidationFailureForSamePasswordAsOldAndNonMatchingNewAndConfirmPassword(){
        $user = User::factory()->create([
            'password' => Hash::make('password@123'),
        ]);

        Passport::actingAs($user, 'api');

        $data = [
            'current_password' => 'password@123',
            'new_password' => 'password@123',
            'confirm_password' => 'password',
        ];

        $response = $this->putJson('/api/v1/update-password', $data);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'msg' => 'Validation failed',
                'data' => [
                    'new_password' => ['The new password and current password must be different.'],
                    'confirm_password' => ['The confirm password and new password must match.']
                ]
            ]);

    }

    public function testUpdatePasswordWrongOldPassword(){
        $user = User::factory()->create([
            'password' => Hash::make('password@123'),
        ]);

        Passport::actingAs($user, 'api');

        $data = [
            'current_password' => 'password',
            'new_password' => 'password123',
            'confirm_password' => 'password123',
        ];

        $response = $this->putJson('/api/v1/update-password', $data);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'msg' => 'Current password is incorrect',
            ]);
    }

    public function testUpdatePasswordSuccess(){
        $user = User::factory()->create([
            'password' => Hash::make('password@123'),
        ]);

        Passport::actingAs($user, 'api');

        $data = [
            'current_password' => 'password@123',
            'new_password' => 'password123',
            'confirm_password' => 'password123',
        ];

        $response = $this->putJson('/api/v1/update-password', $data);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'msg' => 'Password changed successfully',
            ]);
    }

    public function testDeleteUserFailureWithRolesUser(){
        
        $user = User::factory()->create();

        Passport::actingAs($user, 'api');

        $response = $this->deleteJson('/api/v1/user/'.$user->id);

        $response->assertStatus(403)
            ->assertJson([
                'error' => 'Unauthorized for users with role "user" ',
            ]);
    }

    public function testDeleteUserSuccess(){
        $user = User::factory()->create([
            'roles' => 'admin',
        ]);

        Passport::actingAs($user, 'api');

        $response = $this->deleteJson('/api/v1/user/'.$user->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'msg' => 'User deleted successfully',
            ]); 
    }

    public function testCreateUserAsAdminFromRoleAdmin(){
        $user = User::factory()->create([
            'roles' => 'admin',
        ]);

        Passport::actingAs($user, 'api');

        $data = [
            'name' => 'sane jane',
            'email' => 'sanej@gmail.com',
            'password' => 'sanej@1960',
        ];

        $response = $this->postJson('/api/v1/user', $data);

        $response->assertStatus(201) // Assert that the response status code is 201 (Created)
            ->assertJson([
                'success' => true,
                'msg' => 'User created successfully',
                'data' => [
                    'name' => 'sane jane',
                    'email' => 'sanej@gmail.com',
                    'roles' => 'admin',
                ],
            ]);
    }

}