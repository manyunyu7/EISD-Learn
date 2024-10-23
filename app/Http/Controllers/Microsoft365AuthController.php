<?php

namespace App\Http\Controllers;

use App\Models\MDLNUserLogin;
use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class Microsoft365AuthController extends Controller
{
    // Redirect user to Microsoft 365 login // Redirect to Microsoft login page
    public function redirectToProvider()
    {
        return Socialite::driver('microsoft')->redirect();
    }


    //using ROPC
    public function ropc(Request $request)
    {


        // Prepare variables for ROPC
        $clientId = env('PRIV_AZURE_CLIENT_ID');
        $clientSecret = env('PRIV_AZURE_CLIENT_SECRET');
        $username = $request->input('username');
        $password = $request->input('password');

        $username = "henry@MDLN2.onmicrosoft.com";
        $password = "j8srtj@Razkyyy";

        // Use the tenant-specific endpoint or /organizations
        $tokenUrl = 'https://login.microsoftonline.com/organizations/oauth2/v2.0/token'; // Change this line

        // Step 1: Request access token using ROPC
        $response = Http::asForm()->post($tokenUrl, [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'grant_type' => 'password',
            'username' => $username,
            'password' => $password,
            'scope' => 'offline_access User.Read',
        ]);

        // Step 2: Check if the token request was successful
        if ($response->successful()) {
            $data = $response->json();
            $accessToken = $data['access_token'];
            $refreshToken = $data['refresh_token'];

            // Store the access token and refresh token in the session
            session([
                'microsoft_access_token' => $accessToken,
                'microsoft_refresh_token' => $refreshToken,
            ]);

            // Fetch user information
            $userResponse = Http::withToken($accessToken)->get('https://graph.microsoft.com/v1.0/me');

            // Step 3: Check if the user information request was successful
            if ($userResponse->successful()) {
                return response()->json($userResponse->json(), 200);
            } else {
                // Return detailed error message if user information request fails
                return response()->json([
                    'error' => 'Could not retrieve user information from Microsoft.',
                    'details' => [
                        'status' => $userResponse->status(),
                        'error' => $userResponse->json('error', 'Unknown error'),
                        'error_description' => $userResponse->json('error_description', 'No description available')
                    ]
                ], 500);
            }
        } else {
            // Return detailed error message if token request fails
            return response()->json([
                'error' => 'Could not retrieve access token from Microsoft.',
                'details' => [
                    'status' => $response->status(),
                    'error' => $response->json('error', 'Unknown error'),
                    'error_description' => $response->json('error_description', 'No description available')
                ]
            ], 500);
        }
    }


    // Handle the callback from Microsoft
    public function handleProviderCallback(Request $request)
    {
        // Step 1: Retrieve the authorization code from the request
        $code = $request->query('code');

        // Check if the code is present
        if (!$code) {
            return response()->json(['error' => 'Authorization code not found.'], 400);
        }

        // Step 2: Prepare variables for token exchange
        $clientId = env('PRIV_AZURE_CLIENT_ID');
        $clientSecret = env('PRIV_AZURE_CLIENT_SECRET');
        $redirectUri = env('PRIV_AZURE_REDIRECT_URI');

        // Step 3: Exchange the authorization code for an access token and refresh token
        $response = Http::asForm()->post('https://login.microsoftonline.com/common/oauth2/v2.0/token', [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code',
            'scope' => 'offline_access User.Read'
        ]);

        // Step 4: Check if the token request was successful
        if ($response->successful()) {
            $data = $response->json();
            $accessToken = $data['access_token'];
            $refreshToken = $data['refresh_token']; // Get the refresh token

            // Step 5: Fetch user information using the access token
            $userResponse = Http::withToken($accessToken)->get('https://graph.microsoft.com/v1.0/me');

            // Step 6: Check if the user information request was successful
            if ($userResponse->successful()) {
                // Store the access token and refresh token in the session
                session([
                    'microsoft_access_token' => $accessToken,
                    'microsoft_refresh_token' => $refreshToken,
                ]);

                // Return the user information as JSON
                return response()->json($userResponse->json(), 200);
            } else {
                // Return detailed error message if user information request fails
                return response()->json([
                    'error' => 'Could not retrieve user information from Microsoft.',
                    'details' => [
                        'status' => $userResponse->status(),
                        'error' => $userResponse->json('error', 'Unknown error'),
                        'error_description' => $userResponse->json('error_description', 'No description available')
                    ]
                ], 500);
            }
        } else {
            // Return detailed error message if token request fails
            return response()->json([
                'error' => 'Could not retrieve access token from Microsoft.',
                'details' => [
                    'status' => $response->status(),
                    'error' => $response->json('error', 'Unknown error'),
                    'error_description' => $response->json('error_description', 'No description available')
                ]
            ], 500);
        }
    }

    public function refreshAccessToken()
    {
        // Step 1: Retrieve the refresh token from the session
        $refreshToken = session('microsoft_refresh_token');

        // Check if the refresh token is present
        if (!$refreshToken) {
            return response()->json(['error' => 'Refresh token not found.'], 401);
        }

        // Step 2: Prepare variables for token refresh
        $clientId = env('PRIV_AZURE_CLIENT_ID');
        $clientSecret = env('PRIV_AZURE_CLIENT_SECRET');

        // Step 3: Exchange the refresh token for a new access token
        $response = Http::asForm()->post('https://login.microsoftonline.com/common/oauth2/v2.0/token', [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
        ]);

        // Step 4: Check if the refresh token request was successful
        if ($response->successful()) {
            $data = $response->json();
            $newAccessToken = $data['access_token'];
            $newRefreshToken = $data['refresh_token']; // Get the new refresh token

            // Store the new access token and refresh token in the session
            session([
                'microsoft_access_token' => $newAccessToken,
                'microsoft_refresh_token' => $newRefreshToken,
            ]);

            // Return the new access token
            return response()->json(['access_token' => $newAccessToken], 200);
        } else {
            // Return detailed error message if refresh token request fails
            return response()->json([
                'error' => 'Could not refresh access token from Microsoft.',
                'details' => [
                    'status' => $response->status(),
                    'error' => $response->json('error', 'Unknown error'),
                    'error_description' => $response->json('error_description', 'No description available')
                ]
            ], 500);
        }
    }

    // Example method to find or create a user in your database
    protected function findOrCreateUser($microsoftUser)
    {
        return User::firstOrCreate([
            'email' => $microsoftUser->email,
        ], [
            'name' => $microsoftUser->name,
        ]);
    }
}
