<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Validation\ValidationException;


class ProfileController extends Controller
{

    public function index()
    {
        return view('profile.profile');
    }


    public function updatePasswordz(Request $request)
    {
        $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|min:8',
        ]);

        $user = Auth::user();
        $currentPassword = $request->input('current-password');
        $newPassword = $request->input('new-password');

        if ($this->verifyPassword($currentPassword, $user->password)) {
            $hashedNewPassword = Hash::make($newPassword);

            // Update the user's password
            $user->password = $hashedNewPassword;
            $user->save();

            // Redirect the user back to their profile page with a success message
            return redirect()->back()->with('success', 'Password updated successfully.');
        }

        throw ValidationException::withMessages([
            'current_password' => __('The provided password does not match your current password.'),
        ]);
    }

    private function verifyPassword($plainPassword, $hashedPassword)
    {
        // Implement your password verification logic here
        // For example, you can use password_verify() if you're using PHP's built-in password_hash() for hashing.
        // Make sure to adjust this based on how you've hashed the passwords in your application.
        return password_verify($plainPassword, $hashedPassword);
    }


    /**
     * update
     * @param  mixed $request
     * @return void
     */
    public function update(Request $request)
    {

        $this->validate($request, [
            'imagez'     => 'image|mimes:png,jpg,jpeg',
            'name'     => 'required',
            'phone'   => 'required',
            'phone'   => 'required',
//            'jobs'   => 'required',
//            'motto'   => 'required',
            'email'   => 'required',
        ]);

        $user = User::findOrFail(Auth::user()->id);
        // if ($user) {
        //     abort(401,$user);
        // }

        if ($request->file('imagez') == "") {
            $user->update([
                'name' => $request->name,
                'contact' => $request->phone,
                'institute' => "",
                'motto' => "",
                'jobs' => "",
                'email' => $request->email,
            ]);
        } else if($request->file('imagez') != "") {
            // Storage::url('public/profile/') . Auth::user()->profile_url;
             //hapus old image
             if ($user->profile_url == "error.png") {
             }else{
             }
             Storage::disk('local')->delete('public/profile/' . $user->profile_url);
             //upload new image
             $image = $request->file('imagez');
             $image->storeAs('public/profile/', $image->hashName());
             $user->update([
                'profile_url' => $image->hashName(),
                'contact' => $request->phone,
                'institute' => $request->institute,
                'motto' => $request->motto,
                'jobs' => $request->jobs,
                'email' => $request->email,
            ]);
        }
        if ($user) {
            //redirect dengan pesan sukses
            return redirect('profile')->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            //redirect dengan pesan error
            return redirect('profile')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }
}
