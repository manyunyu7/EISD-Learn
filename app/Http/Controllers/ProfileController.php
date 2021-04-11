<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;


class ProfileController extends Controller
{

    public function index()
    {
        return view('profile.profile');
    }
    /**
     * update
     *
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
            'jobs'   => 'required',
            'motto'   => 'required',
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
                'institute' => $request->institute,
                'motto' => $request->motto,
                'jobs' => $request->jobs,
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
