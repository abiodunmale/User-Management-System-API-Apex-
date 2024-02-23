<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function loginUser(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:7'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'Validation failed',
                'data' => $validator->errors()
            ], 422);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();
            $token = $user->createToken('UserApp')->accessToken;

            return response()->json([
                'success' => true,
                'data' => [
                    'access_token' => $token,
                    'user' => $user
                ],
                'msg' => 'User logged in successfully'
            ]);

        } 

        return response()->json([
            'success' => false, 
            'msg' => 'Invalid credentials'
        ], 401);
    }

    public function registerUser(Request $request){
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:7',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'Validation failed',
                'data' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if (!$user) {
            return response()->json([
                'success' => false,
                'msg' => 'Failed to create user'
            ], 500);
        }

        // Return response
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user
            ],
            'msg' => 'User registered successfully'
        ], 201);

    }

    public function logoutUser(Request $request){

        $user = Auth::user();
        
        // Revoke the user's access token
        $user->token()->revoke();

        return response()->json([
            'success' => true,
            'msg' => 'User logged out successfully'
        ], 200);
    }
}
