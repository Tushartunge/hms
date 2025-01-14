<?php

namespace App\Http\Controllers;

// app/Http/Controllers/AuthController.php
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate login
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user is super admin
            if ($user->roles->contains('name', 'super_admin')) {
                return response()->json(['message' => 'Login successful', 'user' => $user]);
            } else {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
