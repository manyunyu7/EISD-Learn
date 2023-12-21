<?php

namespace App\Http\Controllers;

use App\Models\MDLNUserLogin;
use Illuminate\Http\Request;

class MDLNUserLoginController extends Controller
{
    public function saveLoginInfo(Request $request)
    {
        $existingLog = MDLNUserLogin::where('firebase_token', $request->firebase_token)->first();

        if ($existingLog) {
            // Update the existing row
            $existingLog->user_id = $request->user_id;
            $existingLog->device_type = $request->device_type;
            $existingLog->user_name = $request->user_name;
            $existingLog->user_dept = $request->user_dept;
            // Update other fields if needed
            $existingLog->save();
        } else {
            // Create a new row
            $newLog = new MDLNUserLogin();
            $newLog->user_id = $request->user_id;
            $newLog->firebase_token = $request->firebase_token;
            $newLog->device_type = $request->device_type;
            $newLog->user_name = $request->user_name;
            $newLog->user_dept = $request->user_dept;
            // Set other fields
            $newLog->save();
        }

        // You can return a response or perform other actions here
        return response()->json(['message' => 'Login log saved successfully']);
    }
}
