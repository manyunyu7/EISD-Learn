<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ChangePasswordController extends Controller
{
    public function showChangeForm()
    {
        return view('auth.passwords.form_changePassword');
    }

    public function change(Request $request)
    {
        $user = Auth::user();
        $realPass = $user->password;
        $lastPass = $request->lastPass;

        $passConfirm = $request->password_confirmation;

        $lastPass_hashed = Hash::make($lastPass);

        if(Hash::check($lastPass, $realPass)){
            $user->password = Hash::make($passConfirm);
            $user->setRememberToken(Str::random(60));
            $user->save();

            return redirect()->route('profile')->with('success', 'Password has been changed successfully!');

        }else{
            return redirect()->back()->withErrors(['lastPass' => 'Your current password does not match our records.']);
        }

    }
}
