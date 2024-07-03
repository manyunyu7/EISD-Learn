<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModernlandIntegrationController extends Controller
{

    public function getLearningUsers(Request $request)
    {
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
    public function checkUsername(Request $request)
    {
        $mdlnUserId = $request->mdlnUserId;
    }


    public function createNewLMSUser(Request $request)
    {
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
        ]);

        // If validation fails, return error response
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

        // Create a new User instance
        $user = new User();

        // Set each attribute on the user instance
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt("modern888");
        $user->role = $request->input('role');
        $user->contact = $request->input('contact');
        $user->mdln_username = $request->input('mdln_username');
        $user->department = $request->input('department_name');
        $user->sub_department = $request->input('sub_department');
        $user->department_id = $request->input('department_id');
        $user->position_id = $request->input('position_id');
        // Save the user instance
        $user->save();

        // Return success response
        return response()->json([
            'meta' => [
                'success' => true,
                'status' => 200,
                'message' => 'User created successfully',
            ],
            'result' => [
                'data' => $user,
            ],
        ], 200);
    }

    public function updateLMSUser(Request $request)
    {
        $mdln_username = $request->mdln_username;
        // Find the user by mdln_username
        $user = User::where('mdln_username', $mdln_username)->first();

        // If user not found, return error response
        if (!$user) {
            return response()->json([
                'meta' => [
                    'success' => false,
                    'status' => 404,
                    'message' => 'User not found',
                ],
            ], 404);
        }

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'department_name' => 'nullable|string|max:255',
            'department_id' => 'nullable|string|max:255',
            'sub_department' => 'nullable|string|max:255',
            'position_id' => 'nullable|string|max:255',
        ]);

        // If validation fails, return error response
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

        // Set each attribute on the user instance
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->contact = $request->input('contact');
        $user->department = $request->input('department_name');
        $user->sub_department = $request->input('sub_department');
        $user->department_id = $request->input('department_id');
        $user->position_id = $request->input('position_id');
        // Save the user instance
        $user->save();

        // Return success response
        return response()->json([
            'meta' => [
                'success' => true,
                'status' => 200,
                'message' => 'User updated successfully',
            ],
            'result' => [
                'data' => $user,
            ],
        ], 200);
    }
}
