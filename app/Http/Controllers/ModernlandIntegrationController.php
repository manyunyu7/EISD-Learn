<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Helpers\MyResponseHelper;
use App\Models\User;
use Illuminate\Http\Request;

class ModernlandIntegrationController extends Controller
{
    public function createNewUser(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mdln_username' => 'required|string',
            'profile_url' => 'nullable|string',
            'role' => 'nullable|string',
            'contact' => 'nullable|string',
            'jobs' => 'nullable|string',
            'institute' => 'nullable|string',
            'motto' => 'nullable|string',
            'university' => 'nullable|string',
            'major' => 'nullable|string',
            'interest' => 'nullable|string',
            'cv' => 'nullable|string',
            'department' => 'nullable|string',
            'sub_department' => 'nullable|string',
            'location' => 'nullable|string',
            'jabatan' => 'nullable|string',
            // Add other necessary validation rules
        ]);

        // Create a new user instance
        $user = new User();

        // Set individual attributes without using mass assignment
        $user->name = $request->input('name') ?? 'John Doe';
        $user->email = $request->input('email') ?? 'john.doe@example.com';
        $user->password = bcrypt('modern888');
        $user->profile_url = $request->input('profile_url') ?? 'default_profile.png';
        $user->role = $request->input('role') ?? "student";
        $user->contact = $request->input('contact') ?? 'N/A';
        $user->jobs = $request->input('jobs') ?? 'N/A';
        $user->institute = $request->input('institute') ?? 'N/A';
        $user->mdln_username = $request->input('mdln_username') ?? 'N/A';
        $user->motto = $request->input('motto') ?? 'No motto';
        $user->university = $request->input('university') ?? 'N/A';
        $user->major = $request->input('major') ?? 'N/A';
        $user->interest = $request->input('interest') ?? 'N/A';
        $user->cv = $request->input('cv') ?? 'N/A';
        $user->department = $request->input('department') ?? 'N/A';
        $user->sub_department = $request->input('sub_department') ?? 'N/A';
        $user->location = $request->input('location') ?? 'N/A';
        $user->jabatan = $request->input('jabatan') ?? 'N/A';
        // Save the user to the database
        $user->save();

        // You may also return a response or token depending on your authentication system
        return MyHelper::mySuccess(['user' => $user], 'User created successfully', 201);
    }

    public
    function getAvailableUsers(Request $request)
    {
        try {
            $user = User::whereNull('mdln_username')
                ->orWhere('mdln_username', '')
                ->get();

            if ($user->isEmpty()) {
                return MyHelper::myError('User not found', 404);
            }
            return MyHelper::mySuccess($user, 'Get data successfully', 200);
        } catch (\Exception $e) {
            return MyHelper::myError($e->getMessage(), 500);
        }
    }

    public
    function checkId(Request $request, $mdlnUserId)
    {
        try {
            $userCount = User::where("mdln_username", '=', $mdlnUserId)->count();

            if ($userCount === 0) {
                return MyHelper::myError("User $mdlnUserId not found", 404);
            }

            return MyHelper::mySuccess(true, 'Get data successfully', 200);
        } catch (\Exception $e) {
            return MyHelper::myError($e->getMessage(), 500);
        }
    }

    /*
     * this function will check if the MDLN userId is already registered on Learning
     */
    public
    function checkUsername(Request $request)
    {
        $mdlnUserId = $request->mdlnUserId;
    }


}
