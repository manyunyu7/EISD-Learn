<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\RegistrationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use stdClass;

class MitraController extends Controller
{

    public function login(Request $request)
    {
        //username or email or phone
        $username = $request->username;
        $password = $request->password;

        //validate both field must not empty and valid
        if (!$username ||!$password) {
            return MyHelper::responseErrorWithData(
                400,
                400,
                1,
                "All fields are required",
                "All fields are required",
                null
            );
        }

        //validate email format
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $username)->first();
        } else if (preg_match("/^[0-9]{10}$/", $username)) {
            $user = User::where('contact', $username)->first();
        } else {
            return MyHelper::responseErrorWithData(
                400,
                400,
                1,
                "Invalid credentials",
                "Invalid credentials",
                null
            );
        }

        if (!$user) {
            return MyHelper::responseErrorWithData(
                400,
                400,
                1,
                "Invalid credentials",
                "Invalid credentials",
                null
            );
        }


        //validate wether email,phone or username that being used by user
        $user = User::Where('email', $username)
            ->orWhere('contact', $username)
            ->first();


        if (!$user) {
            return MyHelper::responseErrorWithData(
                400,
                400,
                1,
                "Invalid credentials",
                "Invalid credentials",
                null
            );
        }

        //check if password match
        if (!Hash::check($password, $user->password)) {
            return MyHelper::responseErrorWithData(
                400,
                400,
                1,
                "Invalid credentials",
                "Invalid credentials",
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

        if($checkRegistrationCode==null){
            return MyHelper::responseErrorWithData(
                400,
                400,
                1,
                "Kode Registrasi Tidak Ditemukan atau Sudah Expired",
                "Kode Registrasi Tidak Ditemukan atau Sudah Expired",
                null);
        }

        if($registrationCode->is_active=="n"){
            return MyHelper::responseErrorWithData(
                400,
                400,
                1,
                "Kode Registrasi Tidak Ditemukan atau Sudah Expired",
                "Kode Registrasi Tidak Ditemukan atau Sudah Expired",
                null);
        }

        //validate field
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'contact' => 'required',
            'password' => 'required',
        ]);

        // new object to save user registration
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password =  Hash::make($password); // dont forget to hash the password
        $user->contact = $contact;

        if($registrationCode!=null || $registrationCode!=""){
            $user->department_id = $registrationCode->department_id;
            $user->location=$registrationCode->location;
            $user->registration_code = $registrationCode->registration_code;
            $user->position_id=$registrationCode->position_id;
        }

        if($user->save()){
            return MyHelper::responseSuccessWithData(
                200,
                200,
                2,
                "success",
                "success",
                $user
            );
        }else{
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
