<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserToken;
use App\Models\Voucher;
use App\Mail\WelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

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
     * For testing only to display email format
     */
    public function testEmail()
    {
        $emailData = [
            'first_name' => 'James',
            'code' => 'X5Y7Z'
        ];
        return view('emails.welcome-user-code', $emailData);
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

        // Send email using the Mail facade
        $emailData = [
            'first_name' => $user->first_name,
            'code' => $voucher->code
        ];
        Mail::to($user->email)->send(new WelcomeEmail($emailData));

        // Return a response indicating success
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'voucher' => $voucher
        ], Response::HTTP_CREATED);
    }

    /**
     * Login and create a login token
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        // Login fail
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'error' => 'Unauthorized, incorrect username and/or password'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Store token for this user
        $userToken = UserToken::store(Auth::user()->id);
        return response()->json([
            'token' => $userToken->token,
            'message' => 'Successfully logged in',
            'expiry' => $userToken->expiry
        ], Response::HTTP_OK);
    }
}
