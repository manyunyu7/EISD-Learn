<?php

namespace App\Http\Controllers;

use App\Models\User;
use Google\Service\CloudSourceRepositories\Repo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ModernlandIntegrationController extends Controller
{

    public function loginFromIthub(Request $request)
    {
        // Step 1: Get the token from the request
        $token = $request->input('token');


        // Step 2: Hit the external API with the Bearer token
        $response = Http::withToken($token)
            ->get('https://api-ithub.modernland.co.id/api/v1/profile');

        // Step 3: Check if the response is successful
        if ($response->successful()) {

            $data = $response->json();
            // Step 4: Extract the userId from the response
            $userId = $data['result']['id'];
            $userAccount = User::where('mdln_username', $userId)->first();

            if ($userAccount == null) {
                abort('403', "Anda Belum Terdaftar Sebagai User di LMS, Hubungi Tim Training untuk Mendaftarkan Akun");
            } else {
                // Step 6: Log in the user
                Auth::loginUsingId($userId);
            }

            // Step 7: Redirect to the home page
            return redirect()->url('/home');
        } else {
            // Handle error response if needed
            return redirect()->back()->with('error', 'Failed to authenticate with Ithub');
        }
    }

    public function getLearningUsers(Request $request)
    {
        // Get the API credentials header
        $apiCredentials = $request->header('lms');
        if ($apiCredentials != "$2a$12$19qKjda8n9dqzygyzr3/sOvU/uhztedmcfyWOaLiqbzmidN.AI3K.") {
            $response = [
                'meta' => [
                    'success' => false,
                    'status' => 403,
                    'message' => 'Invalid API Key'
                ],
            ];

            return response()->json($response);
        }

        $query = User::query();
        // Optional filter for unclaimed=true
        if ($request->has('unclaimed') && $request->unclaimed == 'true') {
            $query->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereNull('mdln_username')
                        ->orWhere('mdln_username', '');
                });
            });
        }

        // Optional search filter
        if ($request->has('q')) {
            $search = $request->input('q');
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Get limit and page from request or set default values
        $limit = $request->input('limit', 10); // Default limit is 10
        $page = $request->input('page', 1); // Default page is 1

        // Paginate the results
        $users = $query->paginate($limit, ['*'], 'page', $page);

        // Prepare the response structure
        $response = [
            'meta' => [
                'success' => true,
                'status' => 200,
                'message' => 'Get data successfully'
            ],
            'result' => [
                'data' => $users->items(),
                'pagination' => [
                    'page' => $users->currentPage(),
                    'limit' => $users->perPage(),
                    'total_rows' => $users->total(),
                    'total_page' => $users->lastPage()
                ]
            ]
        ];

        return response()->json($response);
    }

    /*
     * this function will check if the MDLN userId is already registered on Learning
     */
    protected function isUserExist(Request $request, $mdln_username)
    {
        // Get the API credentials header
        $apiCredentials = $request->header('lms');
        if ($apiCredentials != "$2a$12$19qKjda8n9dqzygyzr3/sOvU/uhztedmcfyWOaLiqbzmidN.AI3K.") {
            return response()->json([
                'meta' => [
                    'success' => false,
                    'status' => 403,
                    'message' => 'Invalid API Key'
                ]
            ], 403);
        }

        $user = User::where('mdln_username', $mdln_username)->first();
        if (!$user) {
            return response()->json([
                'meta' => [
                    'success' => false,
                    'status' => 403,
                    'message' => 'User doesnt exist'
                ]
            ], 403);
        }

        return response()->json([
            'meta' => [
                'success' => true,
                'status' => 200,
                'message' => 'User exists'
            ],
            'data' => $user
        ], 200);
    }


    public function createOrUpdateLMSUser(Request $request)
    {
        // Get the API credentials header
        $apiCredentials = $request->header('lms');
        if ($apiCredentials != "$2a$12$19qKjda8n9dqzygyzr3/sOvU/uhztedmcfyWOaLiqbzmidN.AI3K.") {
            return response()->json([
                'meta' => [
                    'success' => false,
                    'status' => 403,
                    'message' => 'Invalid API Key'
                ]
            ], 403);
        }

        if ($request->department_id)
            $departmentExists = DB::connection('ithub')
                ->table('m_departments')
                ->where('id', '=', $request->department_id)
                ->whereNull('deleted_at')
                // ->where('code', 'like', '%_NEW%')
                ->exists();

        if ($request->position_id)
            $positionExists = DB::connection('ithub')
                ->table('m_positions')
                ->where('id', '=', $request->position_id)
                ->exists();

        if ($request->department_id)
            $departments = DB::connection('ithub')
                ->table('m_departments')
                ->whereNull('deleted_at')
                // ->where('code', 'like', '%_NEW%')
                ->get();

        if ($request->position_id)
            $positions = DB::connection('ithub')
                ->table('m_positions')
                ->whereNull('deleted_at')
                // ->where('code', 'like', '%_NEW%')
                ->get();

        if ($request->department_id)
            if (!$departmentExists) {
                return response()->json([
                    'meta' => [
                        'success' => false,
                        'status' => 403,
                        'message' => "Department Id $request->department_id doesnt exist"
                    ],
                    'data' => [
                        $departments
                    ]
                ], 403);
            }

        if ($request->position_id)
            if (!$positionExists) {
                return response()->json([
                    'meta' => [
                        'success' => false,
                        'status' => 403,
                        'message' => "Position Id $request->position_id doesnt exist"
                    ],
                    'data' => [
                        $positions
                    ]
                ], 403);
            }

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mdln_username' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'role' => 'nullable|string|max:20',
            'department_name' => 'nullable|string|max:255',
            'department_id' => 'nullable|string|max:255',
            'sub_department' => 'nullable|string|max:255',
            'position_id' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255', // Ensure email is validated
        ]);

        if ($validator->fails()) {
            return response()->json([
                'meta' => [
                    'success' => false,
                    'status' => 422,
                    'message' => 'Validation errors',
                ],
                'errors' => $validator->errors(),
            ], 422);
        }

        // Find existing user or create a new instance
        $user = User::firstOrNew(['mdln_username' => $request->input('mdln_username')]);

        // Set user attributes
        $user->name = $request->input('name');
        $user->email = $request->input('email', $user->email);
        $user->password = $user->exists ? $user->password : bcrypt($request->password);
        $user->role = $request->input('role', $user->role);
        $user->contact = $request->input('contact', $user->contact);
        $user->sub_department = $request->input('sub_department', $user->sub_department);
        $user->department_id = $request->input('department_id', $user->department_id);
        $user->position_id = $request->input('position_id', $user->position_id);
        $user->location = json_encode($request->input('location', $user->location));

        // Save user
        $user->save();

        $user->location = json_decode($user->location);

        // Specify the attributes you want to return
        $attributesToReturn = ['name', 'email', 'role', 'contact', 'department_id', 'position_id', 'location', 'mdln_username'];

        return response()->json([
            'meta' => [
                'success' => true,
                'status' => 200,
                'message' => $user->wasRecentlyCreated ? 'User created successfully' : 'User updated successfully',
            ],
            'result' => [
                'user' => $user->only($attributesToReturn),
            ],
        ], 200);
    }
}
