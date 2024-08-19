<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LaravelEstriController extends Controller
{

    public function syncDataWithIthub()
    {
        $addedUsers = []; // Array to hold the newly added users

        $subQueryIthubUsers = DB::connection('ithub')->table(DB::raw('users a'))
            ->select([
                'a.created_at',
                'a.id',
                'a.name',
                'a.password',
                'a.username',
                'a.email',
                'a.is_active',
                'b.is_approval',
                'a.last_login',
                'a.pin',
                'b.gender',
                'b.image',
                'b.sign',
                'b.nip',
                DB::raw("(SELECT json_build_object('id', d.id, 'name', d.name)
                          FROM u_employees b
                          JOIN m_departments c ON b.department_id = c.id
                          JOIN m_divisions d ON c.division_id = d.id
                          WHERE b.user_id = a.id
                          LIMIT 1) AS division"),
                DB::raw("(SELECT json_build_object('id', mub.id, 'name', mub.name)
                          FROM u_structure_user usu
                          JOIN m_unit_businesses mub ON usu.unit_business_id = mub.id
                          WHERE usu.user_employee_id = a.id
                          LIMIT 1) AS business_unit"),
                DB::raw("(SELECT json_build_object('id', b.department_id, 'name', c.name)
                          FROM u_employees b
                          JOIN m_departments c ON b.department_id = c.id
                          WHERE b.user_id = a.id
                          LIMIT 1) AS department"),
                DB::raw("(SELECT json_build_object('id', b.sub_department_id, 'name', c.name)
                          FROM u_sub_department_user b
                          JOIN m_sub_departments c ON b.sub_department_id = c.id
                          WHERE b.user_id = a.id
                          LIMIT 1) AS sub_department"),
                DB::raw("(SELECT json_build_object('id', b.position_id, 'name', c.name)
                          FROM u_employees b
                          JOIN m_positions c ON b.position_id = c.id
                          WHERE b.user_id = a.id
                          LIMIT 1) AS position"),
                DB::raw("(SELECT json_agg(json_build_object('id', b.id, 'role_id', b.role_id, 'name', c.name))
                          FROM u_role_user b
                          JOIN m_roles c ON b.role_id = c.id
                          WHERE b.user_id = a.id) AS roles"),
                DB::raw("(SELECT json_agg(json_build_object('id', b.id, 'site_id', b.site_id, 'name', c.name, 'code', c.code))
                          FROM u_site_user b
                          JOIN m_sites c ON b.site_id = c.id
                          WHERE b.user_id = a.id) AS sites")
            ])
            ->join('u_employees as b', 'a.id', '=', 'b.user_id')
            ->whereNull('a.deleted_at')
            ->where('a.is_active', true);

        $ithubUsers = DB::connection('ithub')->table(DB::raw("({$subQueryIthubUsers->toSql()}) as sss"))
            ->mergeBindings($subQueryIthubUsers)
            ->whereRaw("(SELECT json_build_object('id', mub.id, 'name', mub.name)
                         FROM u_structure_user usu
                         JOIN m_unit_businesses mub ON usu.unit_business_id = mub.id
                         WHERE usu.user_employee_id = sss.id
                         LIMIT 1) ->> 'id' = '8997302f-0d4f-49ce-8d1d-b4a556d88291'")
            ->get();

        foreach ($ithubUsers as $ithubUser) {
            if ($ithubUser) {
                // Check if the user already exists in the second table
                $check = User::where('mdln_username', $ithubUser->id)->first();

                // If user does not exist yet in LMS table
                if ($check == null) {
                    // Check if a user with the same email already exists
                    $existingUserWithEmail = User::where('email', $ithubUser->email)->first();

                    if ($existingUserWithEmail != null) {
                        // If email already exists, append '_temp_' and current datetime
                        $ithubUser->email = $ithubUser->email . '_temp_' . now()->format('Ymd_His');
                    }

                    $user = new User();
                    $user->email = $ithubUser->email;
                    $user->name = $ithubUser->name;
                    $user->mdln_username = $ithubUser->id;
                    $user->role = "student";
                    $user->is_testing = "n";
                    $user->password = $ithubUser->password; // Assuming the passwords are already hashed

                    if (isset($ithubUser->department)) {
                        $department = json_decode($ithubUser->department);
                        if ($department != null && isset($department->id)) {
                            $user->department_id = $department->id;
                        }
                    }

                    if (isset($ithubUser->position)) {
                        $position = json_decode($ithubUser->position);
                        if ($position != null && isset($position->id)) {
                            $user->position_id = $position->id;
                        }
                    }

                    if (isset($ithubUser->business_unit) && $ithubUser->business_unit != null) {
                        // Decode the business_unit JSON into an array
                        $business_unit = json_decode($ithubUser->business_unit, true);

                        // Initialize an empty array to store the formatted data
                        $siteIds = [];

                        // Check if the 'business_unit' has the necessary data
                        if (isset($business_unit['id'])) {
                            // Map the data to the format you want
                            $siteIds[] = [
                                'site_id' => $business_unit['id']
                            ];
                        }

                        // Convert the array to a JSON string
                        $user->bu = json_encode($siteIds);
                    }

                    // Check if any site_id contains "xyz" before saving
                    if (isset($ithubUser->sites)) {
                        $sites = json_decode($ithubUser->sites, true);
                        foreach ($sites as $site) {
                            if (strpos($site['site_id'], '8997302f-0d4f-49ce-8d1d-b4a556d88291') !== false) {
                                $user->save(); // Save the user if site_id contains "xyz"
                                $addedUsers[] = $user;
                                break; // Exit loop after saving
                            }
                        }
                    }
                }
            }
        }

        // Return the array of added users
        return $addedUsers;
    }



    public function imageUpload()
    {
        return view('s3.image_upload');
    }

    public function getAllFilesWithUrls()
    {
        // Get all files from the 'testing' directory
        $files = Storage::disk('s3')->allFiles('testing');

        // Array to store file URLs
        $fileUrls = [];

        // Generate signed URLs for each file
        foreach ($files as $file) {
            $url = Storage::disk('s3')->temporaryUrl($file, now()->addMinutes(60)); // Adjust expiry time as needed
            $fileUrls[] = $url;
        }

        return $fileUrls;
    }

    public function imageUploadPost(Request $request)
    {
        $request->validate([
            //            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->extension();

        // Upload the file to S3
        $path = Storage::disk('s3')->put('testing', $request->image);

        return $path;
        // Generate a signed URL for accessing the file
        $url = Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(60)); // Adjust expiry time as needed


        /* Store $imageName name in DATABASE from HERE */

        return back()
            ->with('success', 'You have successfully uploaded the image.')
            ->with('image', $url);
    }
}
