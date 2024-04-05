<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

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

        // Send email using the Mail facade
        $emailData = [
            'first_name' => $user->first_name,
            'code' => $voucher->code
        ];
        $recipientsCount = Mail::to($user->email)
            ->send(new WelcomeEmail($emailData));

        $to = "jamesnogra@gmail.com";
        // Set the subject of the email
        $subject = "Test Email";
        // Set the message body
        $message = "This is a test email sent from PHP.";
        // Set additional headers
        $headers = "From: jegcadayona@gmail.com\r\n";
        $headers .= "Reply-To: jegcadayona@gmail.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        // Attempt to send the email
        if (mail($to, $subject, $message, $headers)) {
            echo "Email sent successfully.";
        } else {
            echo "Email sending failed.";
        }

        // Return a response indicating success
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'voucher' => $voucher,
            'mail_info' => $recipientsCount
        ], 201);
    }
}
