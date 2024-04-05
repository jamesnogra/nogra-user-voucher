<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Voucher;

class UserController extends Controller
{
    /**
     * For testing only to see all users
     */
    public function index()
    {
        return response()->json(User::with('vouchers')->get(), 201);
    }

    /**
     * Creating user via the POST request
     */
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
        $voucher = Voucher::store($user->id);

        // Return a response indicating success
        return response()->json(['message' => 'User created successfully', 'user' => $user, 'voucher' => $voucher], 201);
    }
}
