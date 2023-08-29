<?php

namespace App\Http\Controllers;

use App\Helper\RazkyFeb;
use App\Models\KTPIdentification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class StaffController extends Controller
{

    public function checkIfNumberRegistered(Request $request)
    {
        $number = $request->number;
        $findNumber = User::where('contact', '=', $number)->first();

        if ($findNumber != null) {
            return RazkyFeb::responseErrorWithData(200, 1, 1,
                "Nomor Ditemukan", "Number Found", $findNumber);
        } else {
            return RazkyFeb::responseErrorWithData(200, 0, 0,
                "Nomor Tidak Ditemukan", "Number Not Found", null);
        }
    }

    public function viewAdminManage()
    {
        $datas = User::all();
        return view('user.manage')->with(compact('datas'));
    }

    public function viewAdminEdit(Request $request, $id)
    {
        $data = User::findOrFail($id);
        if($request->dump=="true"){
            return $data;
        }
        return view('user.edit')->with(compact('data'));
    }

    public function viewDetail($id)
    {
        $data = User::findOrFail($id);
        return view('user.detail')->with(compact('data'));
    }

    public function viewAdminCreate()
    {
        return view('user.create');
    }

    public function checkPassword($id, Request $request)
    {
        $user = User::findOrFail($id);
        $hasher = app('hash');
        //If Password Sesuai
        if (!$hasher->check($request->password, $user->password)) {
            if ($request->is('api/*'))
                return RazkyFeb::error(400, "Password Lama Tidak Sesuai");

            return back()->with(["errors" => "Password Lama Tidak Sesuai"]);
        } else {
            return RazkyFeb::success(200, "Password Sesuai");
        }
    }


    function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->delete()) {
            if (Auth::user()->role == 1) {
                return back()->with(["success" => "Berhasil Menghapus User $user->name"]);
            } else {
                return back()->with(["success" => "Berhasil Menghapus User $user->name"]);
            }
        } else {
            return back()->with(["error" => "Gagal Menghapus User Baru"]);
        }
    }

    function delete($id)
    {
        $user = User::findOrFail($id);
        $user->status = 0;
        if ($user->save()) {
            if (Auth::user()->role == 1) {
                return back()->with(["success" => "Berhasil Menghapus User $user->name"]);
            } else {
                return back()->with(["success" => "Berhasil Menghapus User $user->name"]);
            }
        } else {
            return back()->with(["error" => "Gagal Menghapus User Baru"]);
        }
    }

    function store(Request $request)
    {
        $validateComponent = [
            "user_name" => "required",
            "user_email" => "required",
            "user_password" => "required",
//            "user_role" => "required",
        ];


        $this->validate($request, $validateComponent);

        $role = "";

        if ($request->user_role == "" || $request->user_role == null) {
            $role = 3;
        } else {
            $role = $request->user_role;
        }


        $user = new User();
        $user->name = $request->user_name;
        $user->email = $request->user_email;
        $user->contact = $request->user_contact;
        $user->password = bcrypt($request->user_password);
        $user->role = $role;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension(); // you can also use file name
            $fileName = time() . '.' . $extension;

            Storage::disk('local')->delete('public/profile/' . $user->profile_url);
            $image = $request->file('photo');
            $image->storeAs('public/profile/', $image->hashName());
            $savePath = "/web_files/user_profile/";
            $savePathDB = "$savePath$fileName";
            $path = public_path() . "$savePath";
            $file->move($path, $fileName);

            $user->photo = $savePathDB;
        }

        if ($user->save()) {
            if ($request->is('api/*'))
                return RazkyFeb::responseSuccessWithData(
                    200,
                    1,
                    200,
                    "Berhasil Mengupdate Foto Profil",
                    "Success",
                    Auth::user()
                );

            return back()->with(["success" => "Berhasil Mengupdate Profil"]);
        } else {
            if ($request->is('api/*'))
                return RazkyFeb::responseErrorWithData(
                    400,
                    3,
                    400,
                    "Gagal Mengupdate Foto Profil",
                    "Error",
                    ""
                );

            return back()->with(["errors" => "Gagal Mengupdate Foto Profil"]);
        }
    }


    function update(Request $request)
    {
        $validateComponent = [
            "user_name" => "required",
            "user_email" => "required",
            "user_role" => "required",
        ];

        $this->validate($request, $validateComponent);

        $user = User::find($request->id);
        if ($request->id == null)
            $user = Auth::user();

        $user->name = $request->user_name;
        $user->email = $request->user_email;
        $user->contact = $request->user_contact;
        $user->role = ($request->user_role);


        if ($user->update()) {
            // IF REQUEST IS FROM API
            if ($request->is('api/*'))
                return RazkyFeb::responseSuccessWithData(
                    200,
                    1,
                    200,
                    "Berhasil Mengupdate Profil",
                    "Success",
                    $user
                );

            // IF REQUEST IS FROM WEB
            if (Auth::user()->role == 1) {
                return back()->with(["success" => "Berhasil Mengupdate Data User"]);
            }
            return back()->with(["success" => "Berhasil Mengupdate Data User"]);
        } else {
            if ($request->is('api/*'))
                return RazkyFeb::responseErrorWithData(
                    400,
                    3,
                    400,
                    "Gagal Mengupdate Profil",
                    "Error",
                    ""
                );

            return back()->with(["failed" => "Gagal Mengupdate Data User"]);
        }
    }


    function updateProfilePhoto(Request $request)
    {
        $response = array();
        $user = Auth::user();
        $id = $user->id;

        if ($request->hasFile('photo')) {
            if ($user->profile_url == "error.png") {
            } else {
                Storage::disk('local')->delete('public/profile/' . $user->profile_url);
            }
            //upload new image
            $image = $request->file('photo');
            $image->storeAs('public/profile/', $image->hashName());
            $user->profile_url = $image->hashName();
            $user->save();
        }

        if ($user->save()) {
            if ($request->is('api/*'))
                return RazkyFeb::responseSuccessWithData(
                    200,
                    1,
                    200,
                    "Berhasil Mengupdate Foto Profil",
                    "Success",
                    Auth::user()
                );

            return back()->with(["success" => "Berhasil Mengupdate Profil"]);
        } else {
            if ($request->is('api/*'))
                return RazkyFeb::responseErrorWithData(
                    400,
                    3,
                    400,
                    "Gagal Mengupdate Foto Profil",
                    "Error",
                    ""
                );

            return back()->with(["errors" => "Gagal Mengupdate Foto Profil"]);
        }
    }

    function updatePassword(Request $request)
    {
        // IF ID IS NOT NULL (MOST LIKELY FROM WEB)
        $user = User::find($request->id);
        if ($request->id == null) {
            $user = Auth::user(); // IF FROM API -> WITH TOKEN
        }

        $this->validate($request, [
            'new_password' => 'required|min:6',
            'old_password' => 'required|min:6'
        ]);

        $hasher = app('hash');
        //If Password Sesuai
        if (!$hasher->check($request->old_password, $user->password)) {
            if ($request->is('api/*'))
                return RazkyFeb::responseErrorWithData(
                    400,
                    3,
                    400,
                    "Password Lama Tidak Sesuai",
                    "Old Password Didnt Match",
                    ""
                );

            return back()->with(["errors" => "Password Lama Tidak Sesuai"]);
        } else {
            $user->password = Hash::make($request->new_password);
            if ($user->save()) {
                if ($request->is('api/*'))
                    return RazkyFeb::responseSuccessWithData(
                        200,
                        1,
                        200,
                        "Berhasil Mengupdate Password",
                        "Success",
                        Auth::user()
                    );

                return back()->with(["success" => "Berhasil Mengupdate Password"]);
            } else {
                if ($request->is('api/*'))
                    return RazkyFeb::responseErrorWithData(
                        400,
                        3,
                        400,
                        "Gagal Mengupdate Password",
                        "Error",
                        ""
                    );
                return back()->with(["errors" => "Gagal Mengupdate Password"]);
            }
        }
    }

    function updatePasswordCompact(Request $request, $id)
    {
        $user = User::find($id);
        if ($request->id == null) {
            $user = Auth::user(); // IF FROM API -> WITH TOKEN
        }

        $user->password = Hash::make($request->password);

        if ($user->save()) {
            if ($request->is('api/*'))
                return RazkyFeb::responseSuccessWithData(
                    200,
                    1,
                    1,
                    "Berhasil Mengupdate Password",
                    "Success",
                    Auth::user()
                );

            return back()->with(["success" => "Berhasil Mengupdate Password"]);
        } else {
            if ($request->is('api/*'))
                return RazkyFeb::responseErrorWithData(
                    200,
                    3,
                    3,
                    "Gagal Mengupdate Password",
                    "Error",
                    ""
                );
            return back()->with(["errors" => "Gagal Mengupdate Password"]);
        }
    }


    function profile(Request $request, $id)
    {

        $data = array(
            "user" => User::find($id),
            "ktp" => KTPIdentification::where("user_id", "=", $id)->first()
        );
        return RazkyFeb::responseSuccessWithData(200, 1, 1, "Berhasil", "Success", $data);

    }

    function registerNumber(Request $request)
    {
        $validateComponent = [
            // "user_contact" => "required",
            // "user_password" => "required",
        ];

        $this->validate($request, $validateComponent);

        $role = "";

        if ($request->user_role == "" || $request->user_role == null) {
            $role = 3;
        }

        $user = new User();
        $user->contact = $request->contact;
        $user->password = bcrypt($request->password);
        $user->role = $role;

        if ($user->save()) {
            $url = url('/login');
            $data = array(
                "id" => $user->id,
                "number" => $user->contact
            );
            if (RazkyFeb::isAPI($request)) {

                RazkyFeb::insertNotification(
                    $user->id,
                    "Selamat Datang",
                    "Hallo",
                    "Selamat Menggunakan Aplikasi Sumbangsih !",
                    1
                );

                $credentials = request(['contact', 'password']);

                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }


                $token =  $this->respondWithToken($token, "");
                $data = array(
                    "id" => $user->id,
                    "number" => $user->contact,
                    "token" => $token,
                    "user" => Auth::user()
                );

                return RazkyFeb::responseSuccessWithData(200, 1, 1, "Success", "Success", $data);
            } else {
                return back()->with(["success" => "Berhasil Mendaftar, Silakan Login Menggunakan Akun Anda  <a href='$url'> Disini</a > "]);
            }
        } else {
            if (RazkyFeb::isAPI($request)) {
                return RazkyFeb::error(400, "Gagal Menambahkan User");
            } else {
                return back()->with(["failed" => "Gagal Menambahkan User Baru"]);
            }
        }
    }


    protected function respondWithToken($token, $message)
    {
        /*
        FOR CUSTOM JWT
        $user = Auth::user();
        $payload = JWTFactory::sub($user->id)
            ->user('Foo Bar')
            ->message(['Apples', 'Oranges'])
            ->myCustomObject($user)
            ->make();

        $token = JWTAuth::encode($payload);

        return $token;
        */
        return $token;
    }
}
