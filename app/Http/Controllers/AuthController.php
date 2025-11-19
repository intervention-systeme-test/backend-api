<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 🔹 1. Inscription
    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6',
            'account_type'  => 'required|in:private,pro',

            // For pro accounts
            'company_name'  => 'nullable|string',
            'cfe_number'    => 'nullable|string',
            'address'       => 'nullable|string',
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'account_type'  => $request->account_type,
            'company_name'  => $request->company_name,
            'cfe_number'    => $request->cfe_number,
            'address'       => $request->address,
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user
        ], 201);
    }

    // 🔹 2. Login
    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user
        ]);
    }

    // 🔹 3. Profil (auth required)
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    // 🔹 4. Logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
