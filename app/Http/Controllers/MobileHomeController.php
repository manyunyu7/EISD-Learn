<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Models\LessonCategory;
use App\Models\StudentLesson;
use App\Models\User;
use App\Models\UserMdln;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MobileHomeController extends Controller
{


    public function classDetail(Request $request, $id)
    {
        $courseId = $id;
        $userId = $request->lms_user_id;

        $lesson = Lesson::findOrFail($courseId);

        // Your existing query to fetch data
        $classes = DB::table('lessons AS a')
            ->select(
                'a.*', // Select all other columns from the lessons table
                'b.name AS mentor_name', // Select mentor's name and alias it as mentor_name
                'b.profile_url', // Select mentor's profile URL
                DB::raw('COUNT(c.student_id) AS num_students_registered'), // Count the number of registered students for each class and alias it as num_students_registered
                DB::raw('COUNT(DISTINCT d.student_id) AS num_students'), // Count the total number of students for each class (excluding duplicates) and alias it as num_students
                DB::raw('CASE WHEN COUNT(c.student_id) > 0 THEN 1 ELSE 0 END AS is_registered') // Check if the current user is registered for each class and alias it as is_registered
            )
            ->leftJoin('users AS b', 'a.mentor_id', '=', 'b.id') // Left join the users table to get mentor information
            ->leftJoin('student_lesson AS c', function ($join) use ($userId) { // Left join the student_lesson table to check if the user is registered for each class
                $join->on('a.id', '=', 'c.lesson_id')
                    ->where('c.student_id', '=', $userId); // Filter the join by the current user ID
            })
            ->leftJoin('student_lesson AS d', 'a.id', '=', 'd.lesson_id') // Left join the student_lesson table again to count the total number of students for each class
            ->whereNull('a.deleted_at') // Filter out deleted records from the lessons table
            ->where('a.id', '=', $courseId) // Filter out deleted records from the lessons table
            ->groupBy('a.id', 'b.name', 'b.profile_url') // Group the results by lesson ID, mentor name, and mentor profile URL
            ->get(); // Execute the query and get the result set

        // Add a new attribute to each item in the $classes array
        foreach ($classes as $class) {

            if($class->new_class=="Aktif"){
                $class->new_class = true;
            }else{
                $class->new_class = false;
            }
        }


        if (count($classes) != 0) {
            $classes = $classes[0];
        }

        // Fetching lesson categories if not already available
        $lessonCategories = LessonCategory::all()->pluck('color_of_categories', 'id')->toArray();

        // Adding full image path to each class
        $section = CourseSection::where("course_id", "=", $classes->id)
            ->orderByRaw("CAST(section_order AS UNSIGNED) ASC")
            ->first();
        if ($section != null) {
            $classes->first_section = (string)$section->id;
        } else {
            $classes->first_section = null;
        }

        $sections = CourseSection::select('lessons.id as lesson_id',
            'lessons.course_title as lessons_title',
            'lessons.mentor_id',
            'users.name as mentor_name',
            'course_section.id as section_id',
            'course_section.section_order',
            'course_section.section_title',
            'course_section.quiz_session_id',
            'exam_sessions.time_limit_minute', // Include quiz duration
            'course_section.section_content',
            'course_section.section_video',
            'course_section.created_at',
            'course_section.updated_at',
            'course_section.can_be_accessed')
            ->leftJoin('lessons', 'lessons.id', '=', 'course_section.course_id')
            ->leftJoin('users', 'users.id', '=', 'lessons.mentor_id')
            ->leftJoin('exam_sessions', 'exam_sessions.id', '=', 'course_section.quiz_session_id') // Left join to quiz_session
            ->where('course_section.course_id', $id)
            ->orderBy(DB::raw('CAST(course_section.section_order AS UNSIGNED)'), 'ASC')
            ->get();


        // Convert the course description into plain text or simplified HTML
        $classes->course_description = strip_tags($classes->course_description); // Remove HTML tags
        $classes->course_description = htmlspecialchars_decode($classes->course_description); // Decode HTML entities
        $classes->sections = $sections; // Decode HTML entities
        $classes->full_img_path = url("/") . Storage::url('public/class/cover/') . $classes->course_cover_image;
        $classInfo = new \stdClass();
        $isRegistered = false;

        $checkIsRegistered = StudentLesson::where("student_id", "=", $userId)
            ->where("lesson_id", '=', "$courseId")
            ->count();


        if ($checkIsRegistered != 0) {
            $isRegistered = true;
        }


        return MyHelper::responseSuccessWithData(
            200,
            200,
            2,
            "Success",
            "Success",
            $classes
        );
    }

    public function registerClass(Request $request)
    {
        try {
            $user_id = $request->lms_user_id;

            $course = Lesson::findOrFail($request->course_id);
            $coursePassword = $course->pin;

            if ($request->password != $coursePassword) {
                return MyHelper::responseErrorWithData(
                    400,
                    400,
                    0,
                    "Password Kelas Salah",
                    "Password Kelas Salah",
                    null
                );
            }

            $registerLesson = StudentLesson::create([
                'student_id' => $user_id,
                'lesson_id' => $request->course_id,
                'learn_status' => 0,
                'certificate_file' => "",
                'student-lesson' => "$user_id-$request->course_id",
            ]);

            if ($registerLesson) {
                return MyHelper::responseSuccessWithData(
                    200,
                    200,
                    2,
                    "Berhasil Mendaftar Kelas $course->course_title!",
                    "Berhasil Mendaftar Kelas $course->course_title",
                    $registerLesson
                );
            } else {
                return MyHelper::responseErrorWithData(
                    400,
                    400,
                    0,
                    "Gagal Mendaftar Kelas",
                    "Gagal Mendaftar Kelas",
                    null
                );
            }
        } catch (QueryException $e) {
            return MyHelper::responseErrorWithData(
                400,
                400,
                0,
                "Anda Sudah Mendaftar di Kelas $course->course_title",
                "Anda Sudah Mendaftar di Kelas Ini",
                null
            );
        }
    }

    public function claimAccount(Request $request)
    {
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


        if ($mdlnUser == null) {
            return MyHelper::responseErrorWithData(
                404,
                404,
                0,
                "Akun ITHUB Tidak Ditemukan",
                "ITHUB account not found",
                null
            );
        }

        if ($account == null) {
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
        if ($this->checkPassword($account, $password)) {
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

    private function checkPassword(User $user, $password)
    {
        return Hash::check($password, $user->password);
    }

    public function searchUnclaimedAccount(Request $request)
    {
        $name = $request->input('name');

        $query = User::whereNull("mdln_username")
            ->orWhere("mdln_username", "")
            ->where('role', '!=', "mentor") // Exclude users with role = 1
            ->select('id', 'name', 'profile_url', 'email');

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

//        $data = DB::connection('ithub')->table('m_departments')->get();
        $newData = [];

//        foreach ($data as $item) {
//            $newItem = [
//                "id" => 1,
//                "name" => $item->name,
//                "img_path" => "qYJgIKFdohPsLTdGYhOuJcGgdABZllJpVZbh1NzV.png",
//                "created_by" => null,
//                "deleted_at" => null,
//                "created_at" => "2024-03-04T06:42:14.000000Z",
//                "updated_at" => "2024-03-25T02:09:30.000000Z",
//                "color_of_categories" => "#20cf2c",
//                "full_img_path" => "http://0.0.0.0:5253/storage/class/category/qYJgIKFdohPsLTdGYhOuJcGgdABZllJpVZbh1NzV.png"
//            ];
//
//            $newData[] = $newItem;
//        }

        return MyHelper::responseSuccessWithData(
            200,
            200,
            2,
            "success",
            "success",
            $data);
    }

    public function classList(Request $request)
    {
        // Extract user ID and class status from request
        $userID = $request->lms_user_id;
        $isMyClass = $request->isMyClass;

        // Query to fetch classes with necessary information
        $classes = DB::table('lessons AS a')
            ->select(
                'a.*', // Select all columns from the lessons table
                'b.name AS mentor_name', // Select mentor's name and alias it as mentor_name
                'b.profile_url', // Select mentor's profile URL
                DB::raw('COUNT(c.student_id) AS num_students_registered'), // Count the number of registered students for each class and alias it as num_students_registered
                DB::raw('COUNT(DISTINCT d.student_id) AS num_students'), // Count the total number of students for each class (excluding duplicates) and alias it as num_students
                DB::raw('CASE WHEN COUNT(c.student_id) > 0 THEN 1 ELSE 0 END AS is_registered') // Check if the current user is registered for each class and alias it as is_registered
            )
            ->leftJoin('users AS b', 'a.mentor_id', '=', 'b.id') // Left join the users table to get mentor information
            ->leftJoin('student_lesson AS c', function ($join) use ($userID) { // Left join the student_lesson table to check if the user is registered for each class
                $join->on('a.id', '=', 'c.lesson_id')
                    ->where('c.student_id', '=', $userID); // Filter the join by the current user ID
            })
            ->leftJoin('student_lesson AS d', 'a.id', '=', 'd.lesson_id') // Left join the student_lesson table again to count the total number of students for each class
            ->whereNull('a.deleted_at') // Filter out deleted records from the lessons table
            ->groupBy('a.id', 'b.name', 'b.profile_url') // Group the results by lesson ID, mentor name, and mentor profile URL
            ->get(); // Execute the query and get the result set

        // Fetching lesson categories if not already available
        $lessonCategories = LessonCategory::all()->pluck('color_of_categories', 'id')->toArray();

        // Adding full image path to each class
        foreach ($classes as $data) {

            $section = CourseSection::where("course_id", "=", $data->id)
                ->orderByRaw("CAST(section_order AS UNSIGNED) ASC")
                ->first();

            if($data->new_class=="Aktif"){
                $data->new_class = true;
            }else{
                $data->new_class = false;
            }

            if ($section != null) {
                $data->first_section = (string)$section->id;
            } else {
                $data->first_section = null;
            }
            $data->full_img_path = url("/") . Storage::url('public/class/cover/') . $data->course_cover_image;
        }

        // Filter classes based on user's registration status
        $shownClass = $classes->filter(function ($data) use ($isMyClass, $userID) {
            $checkCount = StudentLesson::where("student_id", $userID)
                ->where("lesson_id", $data->id)
                ->count();
            return ($isMyClass == "true" && $checkCount > 0) || ($isMyClass != "true" && $checkCount == 0);
        });

        // Return success response with filtered classes
        return MyHelper::responseSuccessWithData(
            200,
            200,
            2,
            "success",
            "success",
            $shownClass->values()->all()
        );
    }
}
