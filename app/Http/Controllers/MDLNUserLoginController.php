<?php

namespace App\Http\Controllers;

use App\Models\MDLNUserLogin;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

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
            $existingLog->set_logged_out = null;
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
            $newLog->set_logged_out = null;
            // Set other fields
            $newLog->save();
        }

        // You can return a response or perform other actions here
        return response()->json(['message' => 'Login log saved successfully']);
    }

    public function logout(Request $request)
    {
        try {
            $existingLog = MDLNUserLogin::where('firebase_token', $request->firebase_token)->first();

            if ($existingLog) {
                $existingLog->delete();
                return response()->json(['message' => 'Logout successful'], 200);
            } else {
                return response()->json(['error' => 'No matching login log found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Logout successful'], 200);
            return response()->json(['error' => 'Failed to delete login log'], 400);
        }
    }

    public function broadcastMessage(Request $request)
    {
        $tokens = MDLNUserLogin::pluck('firebase_token')->toArray();
        $client = new Client();
        $title = $request->title;
        $message = $request->message;
        $image = $request->image;
        foreach ($tokens as $token) {
            try {
                $response = $client->post('https://fcm.googleapis.com/fcm/send', [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'key=AAAABQqk_i8:APA91bHr8FbrMj_sBsP5kf5NcYzarH64Dk-FJ0huxavInbS-qWPK-_h2DO2iX68z02O2nA520CKJg9auUI9FDSJxu7TXuhvXRj0xIGvvaCErhXzY2pNXR9NG82L6OOC0QsqLJ-7JmSr8'
                    ],
                    'json' => [
                        "notification" => [
                            "title" => "$title",
                            "body" => "$message",
                            "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                            "content_available" => true,
                            "mutable_content" => true,
                            "image"=> "$image"
                        ],
                        "data" => [
                            "stock_symbol" => "MDLN",
                            "price_increase_percent" => 2.5
                        ],
                        "to" => $token
                    ]
                ]);

            } catch (\Exception $e) {
                // Handle errors gracefully
                echo "Error sending notification: " . $e->getMessage();
            }
        }
    }
}
