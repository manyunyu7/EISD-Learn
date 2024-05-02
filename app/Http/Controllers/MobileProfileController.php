<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MobileProfileController extends Controller
{
    public function updatePhoto(Request $request)
    {
        try {
            // Update the user with filtered data
            $user = User::findOrFail($request->lms_user_id);

            //hapus old image
            if ($user->profile_url != "error.png") {
                Storage::disk('s3')->delete('profile-s3/' . $user->profile_url);
            }

            //upload new image
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imagePath = 'profile-s3/' . $image->hashName();
                Storage::disk('s3')->put($imagePath, file_get_contents($image));
                $user->profile_url = $imagePath;
            }


            $user->save();

            return MyHelper::responseSuccessWithData(
                200,
                200,
                2,
                "success",
                "success",
                $user
            );
        } catch (\Exception $e) {
            return MyHelper::responseErrorWithData(
                400,
                400,
                0,
                "Error updating user photo",
                $e->getMessage(),
                null
            );
        }
    }

    public function updateUser(Request $request){
        $requestData = $request->all();

        // Filter out null values
        $filteredData = array_filter($requestData, function ($value) {
            return $value !== null;
        });

        // Update the user with filtered data
        $user = User::findOrFail($request->lms_user_id);
        $user->update($filteredData);

        if($user){
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
                "User Tidak Ditemukan",
                "User Tidak Ditemukan",
                null
            );
        }
    }

    public function getUserProfile(Request $request){
        $userID = $request->lms_user_id;

        $user  = User::find($userID);

        if($user!=null){
            return MyHelper::responseSuccessWithData(
                200,
                200,
                2,
                "success",
                "success",
                User::findOrFail($userID)
            );
        }else{
            return MyHelper::responseErrorWithData(
                400,
                400,
                0,
                "User Tidak Ditemukan",
                "User Tidak Ditemukan",
                null
            );
        }

    }
}
