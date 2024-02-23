<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{

    public function profileUser(){

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => $user,
            'msg' => 'User profile retrieved successfully'
        ], 200);
    }

    public function updateProfile(Request $request){

        $user = Auth::user();

        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        // Check if neither name nor email is provided
        if (!$request->filled('name') && !$request->filled('email')) {
            return response()->json([
                'success' => false,
                'msg' => 'No data provided for update'
            ], 422);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update user profile
        if ($request->filled('name')) {
            $user->name = $request->input('name');
        }
        
        if ($request->filled('email')) {
            $user->email = $request->input('email');
        }

        $user->save();

        return response()->json([
            'success' => true,
            'msg' => 'User profile updated successfully',
            'data' => $user
        ], 200);
    }


    public function updatePassword(Request $request){

        $user = Auth::user();

        // Validate input
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:7|different:current_password',
            'confirm_password' => 'required|string|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'msg' => 'Validation failed',
                'data' => $validator->errors()
            ], 422);
        }

        // Check if current password matches
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json([
                'success' => false,
                'msg' => 'Current password is incorrect'
            ], 422);
        }

        // Update user's password
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return response()->json([
            'success' => true,
            'msg' => 'Password changed successfully'
        ], 200);
    }

    public function deleteUser($id){

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'msg' => 'User not found'
            ], 404);
        }

        
        $user->delete();

        return response()->json([
            'success' => true,
            'msg' => 'User deleted successfully'
        ], 200);
    }

    public function createUser(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:7',
        ]);

        if ($validator->fails()) {
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
            'roles' => 'admin',
        ]);

        return response()->json([
            'success' => true,
            'data' => $user,
            'msg' => 'User created successfully'
        ], 201); 
    }
}