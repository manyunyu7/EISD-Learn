<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\RegistrationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use stdClass;

class MitraController extends Controller
{

    public function registerOnWeb(Request $request)
    {
        $name = $request->name;
        $contact = $request->contact;
        $email = $request->email;
        $password = $request->password;

        $registrationCode = $request->registration_code;
        $checkRegistrationCode = RegistrationCode::where('registration_code', $registrationCode)->first();

        $errors = [];

        // Check if email is already taken
        if (User::where('email', $email)->first()) {
            $errors['email'] = 'Email sudah terdaftar';
        }

        // Check if contact is already taken
        if (User::where('contact', $contact)->first()) {
            $errors['contact'] = 'Nomor Kontak sudah terdaftar';
        }

        // Check if contact start with 0 or 62
        if (substr($contact, 0, 1) != "0" && substr($contact, 0, 2) != "62") {
            $errors['contact'] = 'Nomor Kontak harus diawali dengan 0 atau 62';
        }

        // Check if registration code is valid
        if ($checkRegistrationCode === null) {
            $errors['registration_code'] = 'Kode Registrasi Tidak Ditemukan atau Sudah Expired';
        }

        // Check if registration code is active
        if ($checkRegistrationCode && $checkRegistrationCode->is_active == "n") {
            $errors['registration_code'] = 'Kode Registrasi Tidak Ditemukan atau Sudah Expired';
        }

        // If there are any errors, redirect back with the errors
        // If there are any errors, redirect back with the errors and old input
        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput($request->all());
        }

        // Create new user object
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password); // Hash the password
        $user->contact = $contact;
        $user->role = "student";

        // Set additional fields from registration code data
        $user->department_id = $checkRegistrationCode->department_id;
        $user->location = $checkRegistrationCode->location;
        $user->registration_code = $checkRegistrationCode->registration_code;
        $user->position_id = $checkRegistrationCode->position_id;

        // Save user and return response
        if ($user->save()) {
            Auth::loginUsingId($user->id);
            return redirect('/login');
        } else {
            return redirect()->back()->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    public function login(Request $request)
    {
        //username or email or phone
        $username = $request->username;
        $password = $request->password;

        //validate both field must not empty and valid
        if (!$username || !$password) {
            return MyHelper::responseErrorWithData(
                400,
                400,
                1,
                "Mohon periksa kembali semua inputan",
                "Mohon periksa kembali semua inputan",
                null
            );
        }

        //validate email format
        // if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        //     $user = User::where('email', $username)->first();
        // } else if (preg_match("/^[0-9]{10}$/", $username)) {
        //     $user = User::where('contact', $username)->first();
        // } else {
        //     return MyHelper::responseErrorWithData(
        //         400,
        //         400,
        //         1,
        //         "Invalid credentials",
        //         "Invalid credentials",
        //         null
        //     );
        // }

        // if (!$user) {
        //     return MyHelper::responseErrorWithData(
        //         400,
        //         400,
        //         1,
        //         "Invalid credentials",
        //         "Invalid credentials",
        //         null
        //     );
        // }


        //validate wether email,phone or username that being used by user
        $user = User::Where('email', $username)
            ->first();

        if (!$user) {
            return MyHelper::responseErrorWithData(
                400,
                400,
                1,
                "Email tidak ditemukan",
                "Email tidak ditemukan",
                null
            );
        }

        //check if password match
        if (!Hash::check($password, $user->password)) {
            return MyHelper::responseErrorWithData(
                400,
                400,
                1,
                "Password tidak sesuai, mohon coba kembali",
                "Password tidak sesuai, mohon coba kembali",
                null
            );
        }

        //generate token
        $token = MyHelper::generateJwtToken($user->id);

        $responseData = new stdClass();

        $responseData->user = $user;
        $responseData->token = $token;

        return MyHelper::responseSuccessWithData(
            200,
            200,
            2,
            "success",
            "success",
            $responseData
        );
    }


    public function register(Request $request)
    {
        $name = $request->name;
        $contact = $request->contact;
        $email = $request->email;
        $password = $request->password;

        $registrationCode = $request->registration_code;
        $checkRegistrationCode = RegistrationCode::where('registration_code', $registrationCode)->first();


        //check if email, contact, or name is already taken
        if (User::where('email', $email)->first()) {
            return MyHelper::responseErrorWithData(
                400,
                400,
                1,
                "Email sudah terdaftar, silakan login dengan akun yang sudah dibuat",
                "Email sudah terdaftar, silakan login dengan akun yang sudah dibuat",
                null
            );
        }

        if (User::where('contact', $contact)->first()) {
            return MyHelper::responseErrorWithData(
                400,
                400,
                1,
                "Nomor Kontak sudah terdaftar, silakan login dengan akun yang sudah dibuat",
                "Nomor Kontak sudah terdaftar, silakan login dengan akun yang sudah dibuat",
                null
            );
        }


        // Check if registration code is valid
        if ($checkRegistrationCode === null) {
            return MyHelper::responseErrorWithData(
                400,
                400,
                1,
                "Kode Registrasi Tidak Ditemukan atau Sudah Expired",
                "Kode Registrasi Tidak Ditemukan atau Sudah Expired",
                null
            );
        }

        // Check if registration code is active
        if ($checkRegistrationCode->is_active == "n") {
            return MyHelper::responseErrorWithData(
                400,
                400,
                1,
                "Kode Registrasi Tidak Ditemukan atau Sudah Expired",
                "Kode Registrasi Tidak Ditemukan atau Sudah Expired",
                null
            );
        }

        // Validate required fields
        // $this->validate($request, [
        //     'name' => 'required',
        //     'email' => 'required|email|unique:users',
        //     'contact' => 'required',
        //     'password' => 'required|min:6', // Recommend setting a minimum length for passwords
        // ]);

        // Create new user object
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password); // Hash the password
        $user->contact = $contact;
        $user->role = "student";

        // Set additional fields from registration code data
        $user->department_id = $checkRegistrationCode->department_id;
        $user->location = $checkRegistrationCode->location;
        $user->registration_code = $checkRegistrationCode->registration_code;
        $user->position_id = $checkRegistrationCode->position_id;

        // Save user and return response
        if ($user->save()) {
            return MyHelper::responseSuccessWithData(
                200,
                200,
                2,
                "success",
                "success",
                $user
            );
        } else {
            return MyHelper::responseErrorWithData(
                400,
                400,
                0,
                "Error creating user",
                "Error creating user",
                null
            );
        }
    }
}
