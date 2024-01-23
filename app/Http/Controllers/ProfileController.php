<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use DB;

class ProfileController extends Controller
{

    public function index()
    {
        $fullName = Auth::user()->name;
        $nameParts = explode(' ', $fullName);
        $twoWords_ofName = implode(' ', array_slice($nameParts, 0, 2));
        $end_ofName = implode(' ', array_slice($nameParts, 2));
        // return $fullName;
        return view('profile.profile', compact('twoWords_ofName', 'end_ofName'));
    }


    public function updatePasswordz(Request $request)
    {

        MyHelper::addAnalyticEvent(
            "Ganti Password","Profile"
        );

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

    // // Contoh di dalam controller
    // public function showFullName()
    // {
        

    //     return view('profile.profile', compact('twoWords_ofName', 'end_ofName'));
    // }



    /**
     * update
     * @param mixed $request
     * @return void
     */
    public function update(Request $request)
    {
        MyHelper::addAnalyticEvent(
            "Update Foto Profile","Profile"
        );

        // $this->validate($request, [
        //     'imagez' => 'image|mimes:png,jpg,jpeg',
        //     'name' => 'required',
        //     'phone' => 'required',
        //     'phone' => 'required',
        //    'jobs'   => 'required',
        //    'motto'   => 'required',
        //     'email' => 'required',
        // ]);

        $user = User::findOrFail(Auth::user()->id);
        $first_name = $request->input('first_name');
        $end_name = $request->input('end_name');
        $complete_name = $first_name.' '.$end_name;
        
        if ($request->file('profile_image') == "") {
            $user->update([
                'name' => $complete_name,
                'contact' => $request->phone,
                'institute' => "",
                'motto' => "",
                'jobs' => "",
                'email' => $request->email,
            ]);
        } else if ($request->file('profile_image') != "") {
            // Storage::url('public/profile/') . Auth::user()->profile_url;
            //hapus old image
            if ($user->profile_url == "error.png") {
            } else {
                Storage::disk('local')->delete('public/profile/' . $user->profile_url);
            }
            //upload new image
            $image = $request->file('profile_image');
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




    public function updateSocMed(Request $request)
    {
        // return $request->all();
        MyHelper::addAnalyticEvent(
            "Update Foto Profile","Profile"
        );


        $user = User::findOrFail(Auth::user()->id);
        $website = $request->input("website");
        

        $fb = $request->input("facebook");
        $ig = $request->input("instagram");
        $li = $request->input("linkedin");
        $twt = $request->input("twitter");
        $wa = $request->input("whatsapp");
        $yt = $request->input("youtube");

        // URL SOCMED
        

        $user->update([
            'url_personal_website' => $website,
            'url_facebook' => $fb,
            'url_instagram' => $ig,
            'url_linkedin' => $li,
            'url_twitter' => $twt,
            'url_whatsapp' => $wa,
            'url_youtube' => $yt,
        ]);
        
        if ($user) {
            //redirect dengan pesan sukses
            return redirect('profile')->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            //redirect dengan pesan error
            return redirect('profile')->with(['error' => 'Data Gagal Diupdate!']);
        }

       
    }

}
