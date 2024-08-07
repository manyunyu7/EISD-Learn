<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LaravelEstriController extends Controller
{

    public function subhan()
    {


        $users = User::all();

        $arrayOfUsers = [];
        foreach ($users as $user) {

            $mdlnUserId = $user->mdln_username;

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
            LIMIT 1;
        ", [$mdlnUserId]);




            if ($userIthub != null) {
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



        // Fetch users from the 'ithub' database connection
        //  $ithubUsers = DB::connection('ithub')->table('users')->get();

        //  // Loop through the fetched users and update the second table
        //  foreach ($ithubUsers as $ithubUser) {
        //      // Find a user in the second table where the name is similar
        //      $user = User::where('name', 'LIKE', '%' . $ithubUser->name . '%')->first();


        //      // If a similar user exists in the second table, update the email and password
        //      if ($user) {
        //          $user->email = $ithubUser->email;
        //          $user->mdln_username = $ithubUser->id;
        //          $user->password = $ithubUser->password; // Assuming the passwords are already hashed
        //          $user->save();
        //      } else {
        //          // If user does not exist, you can create a new user (optional)
        //         //  User::create([
        //         //      'name' => $ithubUser->name,
        //         //      'email' => $ithubUser->email."_new",
        //         //      'password' => $ithubUser->password, // Assuming the passwords are already hashed
        //         //      // Add other necessary fields here
        //         //  ]);
        //      }
        //  }



        // // Fetch users whose email does not end with @modernland.co.id
        // $users = User::where('email', 'NOT LIKE', '%@modernland.co.id')->get();

        // // Loop through the users and update their emails
        // foreach ($users as $user) {
        //     $email_prefix = strstr($user->email, '@', true); // Extract part before '@'
        //     $user->email = $email_prefix . '@modernland.co.id';
        //     $user->save();
        // }

        // return "Email addresses updated successfully.";
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
