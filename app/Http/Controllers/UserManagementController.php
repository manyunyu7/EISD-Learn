<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        // Fetch departments and locations
        $departments = DB::connection('ithub')
            ->table('m_departments')
            ->select('id', 'code', 'name')
            ->get()
            ->keyBy('id'); // Convert to associative array for quick lookup

            $positions = DB::connection('ithub')
            ->table('m_group_employees')
            ->select('id', 'name')
            ->get()
            ->keyBy('id'); // Convert to associative array for quick lookup


        $locations = DB::connection('ithub')
            ->table('m_unit_businesses')
            ->select('id', 'code', 'name')
            ->get()
            ->keyBy('id'); // Convert to associative array for quick lookup

        // Fetch users
        $users = User::all()->map(function ($user) use ($departments, $locations,$positions) {
            // Map department name
            $user->department_name = $departments->get($user->department_id)->name ?? 'Unknown';
            // Map position name
            $user->position_name = $positions->get($user->position_id)->name ?? 'Unknown';
            // Parse location JSON and map location names
            $user->location_names = collect(json_decode($user->location, true))->map(function ($loc) use ($locations) {
                return $locations->get($loc['site_id'])->name ?? 'Unknown';
            });

            return $user;
        });

        if ($request->dump == true) {
            return $users;
        }

        return view('users.index', compact('users'));
    }

    public function create()
    {

        $departments = DB::connection('ithub')
            ->table('m_departments')
            ->whereNull('deleted_at')
            ->where('code', 'like', '%_NEW%')
            ->get();

        return view('users.create')->with(compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            // Add validation rules for other fields as needed
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => $request->email_verified_at,
            'password' => bcrypt($request->password),
            'profile_url' => $request->profile_url,
            'role' => $request->role,
            'contact' => $request->contact,
            'jobs' => $request->jobs,
            'institute' => $request->institute,
            'mdln_username' => $request->mdln_username,
            'motto' => $request->motto,
            'remember_token' => $request->remember_token,
            'university' => $request->university,
            'major' => $request->major,
            'interest' => $request->interest,
            'cv' => $request->cv,
            'sub_department' => $request->sub_department,
            'location' => $request->location,
            'url_personal_website' => $request->url_personal_website,
            'url_facebook' => $request->url_facebook,
            'url_instagram' => $request->url_instagram,
            'url_linkedin' => $request->url_linkedin,
            'url_twitter' => $request->url_twitter,
            'url_whatsapp' => $request->url_whatsapp,
            'url_youtube' => $request->url_youtube,
            // 'department_id' => $request->department_id,
            // 'position_id' => $request->position_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $departments = DB::connection('ithub')
            ->table('m_departments')
            ->whereNull('deleted_at')
            ->where('code', 'like', '%_NEW%')
            ->get();

        return view('users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            // Add validation rules for other fields as needed
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => $request->email_verified_at,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'profile_url' => $request->profile_url,
            'role' => $request->role,
            'contact' => $request->contact,
            'jobs' => $request->jobs,
            'institute' => $request->institute,
            'mdln_username' => $request->mdln_username,
            'motto' => $request->motto,
            'remember_token' => $request->remember_token,
            'university' => $request->university,
            'major' => $request->major,
            'interest' => $request->interest,
            'cv' => $request->cv,
            'sub_department' => $request->sub_department,
            'location' => $request->location,
            'url_personal_website' => $request->url_personal_website,
            'url_facebook' => $request->url_facebook,
            'url_instagram' => $request->url_instagram,
            'url_linkedin' => $request->url_linkedin,
            'url_twitter' => $request->url_twitter,
            'url_whatsapp' => $request->url_whatsapp,
            'url_youtube' => $request->url_youtube,
            // 'department_id' => $request->department_id,
            // 'position_id' => $request->position_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
