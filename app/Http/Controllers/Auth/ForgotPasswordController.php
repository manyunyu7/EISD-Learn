<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgotpass');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;
        $token = Str::random(60);

        // Insert token into password_resets table
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $resetLink = url('/password/reset/'.$token.'?email='.urlencode($email));

        // Send email with reset link
        Mail::send('emails.emailMessages_toResetPass', ['resetLink' => $resetLink], function ($message) use ($email) {
            $message->to($email);
            $message->subject('Reset Password');
        });

        // Redirect user with success message
        return redirect()->route('password.sent')->with('success', "We have sent an email to $email along with a link to reset your password.");
    }
}
