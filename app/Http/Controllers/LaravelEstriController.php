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
        $ar = [];
        $batchSize = 10;

        // Fetch total user count from the 'ithub' database connection
        $totalUsers = DB::connection('ithub')->table('users')->count();

        // Calculate the total number of batches
        $totalBatches = ceil($totalUsers / $batchSize);

        // Process users in batches
        for ($batch = 0; $batch < $totalBatches; $batch++) {
            // Fetch users for the current batch
            $ithubUsers = DB::connection('ithub')
                ->table('users')
                ->offset($batch * $batchSize)
                ->limit($batchSize)
                ->get();

            foreach ($ithubUsers as $ithubUser) {
                $userIthub = DB::connection('ithub')->selectOne("
                SELECT
                    a.created_at,
                    a.id,
                    a.name,
                    a.username,
                    a.email,
                    a.is_active,
                    b.is_approval,
                    a.last_login,
                    a.pin,
                    b.gender,
                    b.image,
                    b.sign,
                    b.nip,
                    (
                        SELECT json_build_object('id', d.id, 'name', d.name)
                        FROM u_employees b
                        JOIN m_departments c ON b.department_id = c.id
                        JOIN m_divisions d ON c.division_id = d.id
                        WHERE b.user_id = a.id
                        LIMIT 1
                    ) AS division,
                    (
                        SELECT json_build_object('id', b.department_id, 'name', c.name)
                        FROM u_employees b
                        JOIN m_departments c ON b.department_id = c.id
                        WHERE b.user_id = a.id
                        LIMIT 1
                    ) AS department,
                    (
                        SELECT json_build_object('id', b.sub_department_id, 'name', c.name)
                        FROM u_sub_department_user b
                        JOIN m_sub_departments c ON b.sub_department_id = c.id
                        WHERE b.user_id = a.id
                        LIMIT 1
                    ) AS sub_department,
                    (
                        SELECT json_build_object('id', b.position_id, 'name', c.name)
                        FROM u_employees b
                        JOIN m_positions c ON b.position_id = c.id
                        WHERE b.user_id = a.id
                        LIMIT 1
                    ) AS position,
                    (
                        SELECT json_agg(json_build_object('id', b.id, 'role_id', b.role_id, 'name', c.name))
                        FROM u_role_user b
                        JOIN m_roles c ON b.role_id = c.id
                        WHERE b.user_id = a.id
                    ) AS roles,
                    (
                        SELECT json_agg(json_build_object('id', b.id, 'site_id', b.site_id, 'name', c.name, 'code', c.code))
                        FROM u_site_user b
                        JOIN m_sites c ON b.site_id = c.id
                        WHERE b.user_id = a.id
                    ) AS sites
                FROM users a
                JOIN u_employees b ON a.id = b.user_id
                WHERE a.deleted_at IS NULL AND a.id = ?
                AND is_active = true
                LIMIT 1;
            ", [$ithubUser->id]);

                array_push($ar, $userIthub);

                //check if the user already exists in the second table
                $check = User::where('mdln_username', $ithubUser->id)->first();

                // if user not exist yet on LMS table
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
                    $user->save();

                    if ($user->department_id != null) {
                        $user->department_id = json_decode($userIthub->department)->id;
                    }

                    if ($userIthub->position != null) {
                        $user->position_id = json_decode($userIthub->position)->id;
                        // Assuming you have the $userIthub object and it has a sites property
                        $sites = json_decode($userIthub->sites, true);
                        // Use array_map to transform the array
                        $location = array_map(function ($site) {
                            return ['site_id' => $site['site_id']];
                        }, $sites);

                        // Assign the transformed array to the user's location
                        $user->location = json_encode($location);

                        $idHolding = '0d73ca4e-ff81-441b-ab11-0d19af87f76d';

                        // Extract site_ids from $location
                        $siteIds = array_column($location, 'site_id');

                        //Yang holding holding aja
                        if (in_array($idHolding, $siteIds)) {
                            $user->save();
                        }
                    }
                }
            }
        }

        return $ar;
    }

    public function syncDataWithIxthub()
    {

        // Fetch users from the 'ithub' database connection
        $ithubUsers = DB::connection('ithub')->table('users')->limit(10)->get();

        $ar = [];
        // Loop through the fetched users and update the second table
        foreach ($ithubUsers as $ithubUser) {
            $userIthub = DB::connection('ithub')->selectOne("
            SELECT
                a.created_at,
                a.id,
                a.name,
                a.username,
                a.email,
                a.is_active,
                b.is_approval,
                a.last_login,
                a.pin,
                b.gender,
                b.image,
                b.sign,
                b.nip,
                (
                    SELECT json_build_object('id', d.id, 'name', d.name)
                    FROM u_employees b
                    JOIN m_departments c ON b.department_id = c.id
                    JOIN m_divisions d ON c.division_id = d.id
                    WHERE b.user_id = a.id
                    LIMIT 1
                ) AS division,
                (
                    SELECT json_build_object('id', b.department_id, 'name', c.name)
                    FROM u_employees b
                    JOIN m_departments c ON b.department_id = c.id
                    WHERE b.user_id = a.id
                    LIMIT 1
                ) AS department,
                (
                    SELECT json_build_object('id', b.sub_department_id, 'name', c.name)
                    FROM u_sub_department_user b
                    JOIN m_sub_departments c ON b.sub_department_id = c.id
                    WHERE b.user_id = a.id
                    LIMIT 1
                ) AS sub_department,
                (
                    SELECT json_build_object('id', b.position_id, 'name', c.name)
                    FROM u_employees b
                    JOIN m_positions c ON b.position_id = c.id
                    WHERE b.user_id = a.id
                    LIMIT 1
                ) AS position,
                (
                    SELECT json_agg(json_build_object('id', b.id, 'role_id', b.role_id, 'name', c.name))
                    FROM u_role_user b
                    JOIN m_roles c ON b.role_id = c.id
                    WHERE b.user_id = a.id
                ) AS roles,
                (
                    SELECT json_agg(json_build_object('id', b.id, 'site_id', b.site_id, 'name', c.name, 'code', c.code))
                    FROM u_site_user b
                    JOIN m_sites c ON b.site_id = c.id
                    WHERE b.user_id = a.id
                ) AS sites
            FROM users a
            JOIN u_employees b ON a.id = b.user_id
            WHERE a.deleted_at IS NULL AND a.id = ?
            AND is_active = true
            LIMIT 1;
        ", [$ithubUser->id]);


            array_push($ar, $userIthub);

            //check if the user already exists in the second table
            $check = User::where('mdln_username', $ithubUser->id)->first();

            // if user not exist yet on LMS table
            if ($check != null) {
                $user = new User();
                $user->email = $ithubUser->email;
                $user->mdln_username = $ithubUser->id;
                $user->password = $ithubUser->password; // Assuming the passwords are already hashed
                $user->save();

                if ($userIthub->position != null) {
                    $user->department_id = json_decode($userIthub->department)->id;
                    $user->position_id = json_decode($userIthub->position)->id;
                    // Assuming you have the $userIthub object and it has a sites property
                    $sites = json_decode($userIthub->sites, true);
                    // Use array_map to transform the array
                    $location = array_map(function ($site) {
                        return ['site_id' => $site['site_id']];
                    }, $sites);

                    // Assign the transformed array to the user's location
                    $user->location = json_encode($location);
                    $user->save();
                }
            }
        }

        return $ar;
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
