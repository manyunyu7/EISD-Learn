<?php
namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\MyLoginQrCode;
use Illuminate\Http\Request;
use App\Models\QRCode;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;
use Tymon\JWTAuth\Facades\JWTAuth;

class QRLoginController extends Controller
{

    public function checkToken(Request $request)
    {
        try {
            // Attempt to parse the JWT from the request Authorization header
            $token = $request->jwt_token;
            // Validate and decode the token to retrieve user details
            $user = JWTAuth::setToken($token)->toUser();
            // Generate JWT token

            if ($user!=null) {
                $userId = $user->id;
                Auth::loginUsingId($userId);
            }

            return response()->json([
                'user' => Auth::user(),
            ]);

        } catch (\Throwable $e) {
            // Handle token errors or invalid tokens
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function generateQRCode(Request $request)
    {
        // Generate a unique token
        $token = Str::random(60);

        // Create a new QR code record in the database
        $qrCode = MyLoginQrCode::create([
            'token' => $token,
            'is_taken' => false,
            'expires_at' => Carbon::now()->addMinutes(5), // Token expires in 5 minutes
        ]);

        return response()->json([
            'token' => $token,
            'expires_at_date' => Carbon::parse($qrCode->expires_at->timestamp),
            'expires_at' => $qrCode->expires_at->timestamp,
        ]);
    }

    public function processQRCodeLogin(Request $request)
    {
        $token = $request->input('token');
        $userId = $request->input('user_id');

        // Validate the token
        $qrCode = MyLoginQrCode::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->where('is_taken', false) // Assuming is_taken must be false to proceed
            ->first();

        if ($qrCode && $userId) {
            // Authenticate the user
            Auth::loginUsingId($userId);

            // Generate JWT token
            $jwtToken = JWTAuth::fromUser(Auth::user());

            // Mark the QR code as taken and associate with the user
            $qrCode->update([
                'is_taken' => true,
                'user_id' => $userId,
            ]);

            // Return JWT token in the response
            return MyHelper::responseSuccessWithData(
                200,
                200,
                2,
                "Success",
                "Success",
                "Berhasil Melakukan Login di Website dengan QR"
            );

        } else {
            return MyHelper::responseErrorWithData(
                400,
                400,
                0,
                "Error",
                "Error",
                "Invalid or Expired QR Code"
            );
            return response()->json(['message' => 'Invalid or expired QR code.'], 401);
        }
    }
    public function showQRForm()
    {
        // Generate a unique token
        $token = Str::random(60);

        // Create a new QR code record in the database
        $qrCode = FacadesQrCode::create([
            'token' => $token,
            'expires_at' => Carbon::now()->addMinutes(15), // Token expires in 5 minutes
        ]);

        // Generate the QR code URL or data
        $qrCodeUrl = route('qr-login.process', ['token' => $token]);

        return view('auth.qr-login', compact('qrCodeUrl', 'token'));
    }



    public function checkQRCodeAuthentication(Request $request)
    {
        $token = $request->input('token');

        $qrCode = MyLoginQrCode::where('token', $token)->where('is_taken', '=', 1)->where('expires_at', '>', Carbon::now())->first();

        $userId = $qrCode->user_id;
        Auth::loginUsingId($userId);

        // Generate JWT token
        $jwtToken = JWTAuth::fromUser(Auth::user());

        $authenticated = $qrCode ? true : false;


        return response()->json(['authenticated' => $authenticated,'jwt'=> $jwtToken]);
    }

    public function checkQRCodeStatus(Request $request)
    {
        $token = $request->input('token');

        // Check if the QR code has been used
        $qrCode = MyLoginQrCode::where('token', $token)->first();

        if ($qrCode && is_null($qrCode->token)) {
            return response()->json(['status' => 'authenticated']);
        } else {
            return response()->json(['status' => 'pending']);
        }
    }
}
