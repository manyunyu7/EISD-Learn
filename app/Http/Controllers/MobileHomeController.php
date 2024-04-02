<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\LessonCategory;
use App\Models\User;
use App\Models\UserMdln;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MobileHomeController extends Controller
{

    public function claimAccount(Request $request){
        // Validating the incoming request data
//        $request->validate([
//            'target_account_id' => 'required|exists:users,id',
//            'mdln_user_id' => 'required',
//            'password' => 'required',
//        ]);



        // Extracting data from the validated request
        $accountId = $request->target_account_id;
        $mdlnUserId = $request->mdln_user_id;
        $password = $request->password;

        $mdlnUser = UserMdln::find($mdlnUserId);
        // Finding the user account by ID
        $account = User::find($accountId);


        if ($mdlnUser==null){
            return MyHelper::responseErrorWithData(
                404,
                404,
                0,
                "Akun ITHUB Tidak Ditemukan",
                "ITHUB account not found",
                null
            );
        }

        if ($account==null){
            return MyHelper::responseErrorWithData(
                404,
                404,
                0,
                "Akun LMS Tidak Ditemukan",
                "LMS account not found",
                null
            );
        }

        // Check if the password is correct
        if($this->checkPassword($account, $password)) {
            // Password is correct, proceed with claiming the account
            $account->mdln_username = $mdlnUserId;
            $account->save();

            // Return success response
            return MyHelper::responseSuccessWithData(
                200,
                200,
                2,
                "Akun Berhasil Terhubung",
                "Account Successfully Connected",
                $account
            );
        } else {
            // Password is incorrect, return error response
            return MyHelper::responseErrorWithData(
                404,
                404,
                0,
                "Password Yang Anda Input Tidak Sesuai",
                "Inputted Password is incorrect",
                null
            );
        }
    }

    private function checkPassword(User $user, $password) {
        return Hash::check($password, $user->password);
    }

    public function searchUnclaimedAccount(Request $request)
    {
        $name = $request->input('name');

        $query = User::whereNull("mdln_username")
            ->orWhere("mdln_username", "")
            ->where('role', '!=', "mentor") // Exclude users with role = 1
            ->select('id', 'name','profile_url','email');

        // If a name is provided, add a search condition
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        $data = $query->get();

        return MyHelper::responseSuccessWithData(
            200,
            200,
            2,
            "success",
            "success",
            $data
        );
    }

    public function checkIfAccountConnected(Request $request)
    {
        $mdlnUserId = $request->mdln_user_id;

        $mdlnUser = UserMdln::find($mdlnUserId);

        if ($mdlnUser == null) {
            return MyHelper::responseErrorWithData(
                404,
                404,
                0,
                "Pengguna Tidak Ditemukan",
                "User Not Found on Ithub",
                null);
        }

        $userLMS = User::where('mdln_username', $mdlnUserId)->first();

        if ($userLMS == null) {
            return MyHelper::responseErrorWithData(
                200,
                200,
                0,
                "Pengguna Tidak Ditemukan",
                "User Not Found on Ithub",
                null);
        }

        return MyHelper::responseSuccessWithData(
            200,
            200,
            2,
            "success",
            "success",
            $userLMS);
    }

    public function classCategories(Request $request)
    {
        $userID = Auth::id();

        $data = LessonCategory::whereNull('deleted_at')
            ->orWhere('deleted_at', '') // Assuming empty string represents empty value
            ->get();

        $data = DB::connection('ithub')->table('m_departments')->get();
        $newData = [];

        foreach ($data as $item) {
            $newItem = [
                "id" => 1,
                "name" => $item->name,
                "img_path" => "qYJgIKFdohPsLTdGYhOuJcGgdABZllJpVZbh1NzV.png",
                "created_by" => null,
                "deleted_at" => null,
                "created_at" => "2024-03-04T06:42:14.000000Z",
                "updated_at" => "2024-03-25T02:09:30.000000Z",
                "color_of_categories" => "#20cf2c",
                "full_img_path" => "http://0.0.0.0:5253/storage/class/category/qYJgIKFdohPsLTdGYhOuJcGgdABZllJpVZbh1NzV.png"
            ];

            $newData[] = $newItem;
        }

        return MyHelper::responseSuccessWithData(
            200,
            200,
            2,
            "success",
            "success",
            $newData);
    }


    public function classList(Request $request)
    {
        $userID = Auth::id();

        $classes = DB::table('lessons AS a')
            ->select(
                'a.*',
                'b.name AS mentor_name',
                'b.profile_url',
                DB::raw('COUNT(c.student_id) AS num_students_registered'),
                DB::raw('COUNT(DISTINCT d.student_id) AS num_students'),
                DB::raw('CASE WHEN COUNT(c.student_id) > 0 THEN 1 ELSE 0 END AS is_registered'),
                DB::raw('CAST(cs.id AS CHAR) AS first_section')
            )
            ->leftJoin('users AS b', 'a.mentor_id', '=', 'b.id')
            ->leftJoin('student_lesson AS c', function ($join) use ($userID) {
                $join->on('a.id', '=', 'c.lesson_id')
                    ->where('c.student_id', '=', $userID);
            })
            ->leftJoin('student_lesson AS d', 'a.id', '=', 'd.lesson_id')
            ->join('course_section AS cs', function($join) {
                $join->on('a.id', '=', 'cs.course_id')
                    ->whereRaw('cs.section_order = (select min(section_order) from course_section where course_id = a.id)');
            })
            ->groupBy('a.id', 'b.name', 'b.profile_url', 'cs.id')
            ->get();

        foreach ($classes as $data) {
            // Ambil warna kategori jika kategori ada dalam $lessonCategories
            $color = $lessonCategories[$data->course_category]->color_of_categories ?? '#007bff';
            $numStudents = DB::table('student_lesson')
                ->where('lesson_id', $data->id)
                ->get();
            $numStudentsCount = $numStudents->count();

            $data->color = $color;
            $data->full_img_path = url("/") . Storage::url('public/class/cover/') . $data->course_cover_image;
            $data->num_students = $numStudentsCount;
        }

        foreach ($classes as $data) {
            // Ambil warna kategori jika kategori ada dalam $lessonCategories
            $color = $lessonCategories[$data->course_category]->color_of_categories ?? '#007bff';
            $numStudents = DB::table('student_lesson')
                ->where('lesson_id', $data->id)
                ->get();
            $numStudentsCount = $numStudents->count();

            $data->color = $color;
            $data->full_img_path = url("/") . Storage::url('public/class/cover/') . $data->course_cover_image;
            $data->num_students = $numStudentsCount;
        }


        return MyHelper::responseSuccessWithData(
            200,
            200,
            2,
            "success",
            "success",
            $classes);
    }
}
