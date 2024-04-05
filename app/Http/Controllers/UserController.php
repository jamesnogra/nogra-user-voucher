<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all(), 201);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'username' => 'required|unique:users',
            'first_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        // Create the user using the validated data
        $user = User::create($validatedData);

        // Return a response indicating success
        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }
}
