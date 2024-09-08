<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
public function createUser(Request $request)
{
    // Validate input fields for user creation
    $fields = $request->validate([
        'user_name' => 'required|string|unique:users,user_name',
        'password' => 'required|string|confirmed',
    ]);

    // Create the new user (with 'user' role by default)
    $user = User::create([
        'user_name' => $fields['user_name'],
        'password' => bcrypt($fields['password']),
        'role' => 'user', // Force the 'user' role, preventing the creation of new admins
    ]);

    return response()->json($user, 201);
// Return the new user details with a 201 Created status
}

    public function login(Request $request)
    {
        $fields = $request->validate([
            'user_name' => 'required',
            'password' => 'required|string',
        ]);

        // Check email
        $user = User::where('user_name', $fields['user_name'])->first();

        // Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Wrong creds'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You Are Logged Out'
        ];
    }
}
