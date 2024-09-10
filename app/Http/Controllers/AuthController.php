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
public function updateUser(Request $request, $id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Ensure only admins can edit users
    if (auth()->user()->role !== 'admin') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $validatedData = $request->validate([
        'user_name' => 'required|string|max:255',
        'password' => 'required|string',
    ]);

    $user->user_name = $validatedData['user_name'];

    if (!empty($validatedData['password'])) {
        $user->password = bcrypt($validatedData['password']);
    }

    $user->save();

    return response()->json(['message' => 'User updated successfully']);
}

public function deleteUser($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Ensure only admins can delete users
    if (auth()->user()->role !== 'admin') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $user->delete();

    return response()->json(['message' => 'User deleted successfully']);
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
