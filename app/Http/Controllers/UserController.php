<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {

        // Validate incoming request data
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|min:3',  // Name is required, must be a string, and at least 3 characters long
            'email' => 'required|email|unique:users,email',  // Email is required, must be a valid email, and unique in the 'users' table
            'password' => 'required|min:8',  // Password is required and must be at least 8 characters long
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create a new user with validated data and hash the password
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),  // Hash the password before storing in database
        ]);

        // Return a success response with newly created user details, excluding password
        return response()->json($user->only('id', 'name', 'email', 'created_at'), 201);
    }
}
