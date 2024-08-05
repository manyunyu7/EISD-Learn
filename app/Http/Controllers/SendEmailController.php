<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\TestSendingEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailController extends Controller
{
    public function index(Request $request)
    {
        $email = $request->email;
        // dd($email);
        Log::info('Sending email to kevinvalerian@gmail.com');
        Mail::to($email)->send(new TestSendingEmail($email));
        Log::info('Email sent successfully');

        return redirect()->route('password.sent')->with('success', "We have sent an email to $email along with a link to return to your account.");

    }
}
