<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use Illuminate\Http\Request;
use Auth;
use Exception;

use App\Models\Blog;
use App\Models\Lesson;
use App\Models\User;
use App\Models\CourseSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use DB;
use File;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use LDAP\Result;

class ClassListController extends Controller
{
    public function landing()
    {

        // $user_id  = Auth::id();
        // $lesson_id = $lesson->id;
        // if($user_id != $lesson->mentor_id){
        //     abort(401,'Unauthorized');
        // }
        // $dayta = DB::select("select * from view_course_section where lesson_id = $lesson_id ORDER BY section_order ASC");
        // $dayta = DB::select("select * from view_course_section  where mentor_id = $user_id");
        return view('landing');
    }

    public function classes()
    {
        $dayta = DB::select("select * from view_course");
        MyHelper::addAnalyticEvent(
            "Buka Halaman Kelas","Kelas"
        );
        return view('classes')->with('dayta', $dayta);
    }

    public function classList()
    {
        if (!Auth::check()) {
            abort(401, "Anda Harus Login Untuk Melanjutkan ");
        }

        $userID = Auth::id();
        $classes = DB::select("SELECT
                    a.*,
                    b.name AS mentor_name,
                    b.profile_url,
                    COUNT(c.student_id) AS num_students_registered,
                    CASE WHEN COUNT(c.student_id) > 0 THEN 1 ELSE 0 END AS is_registered
                FROM
                    lessons a
                LEFT JOIN
                    users b ON a.mentor_id = b.id
                LEFT JOIN
                    student_lesson c ON a.id = c.lesson_id
                WHERE
                    NOT EXISTS (
                        SELECT 1
                        FROM student_lesson sl
                        WHERE sl.lesson_id = a.id
                        AND sl.student_id = $userID -- Replace :user_id with the actual user ID you're interested in
                    )
                GROUP BY
                    a.id, b.name, b.profile_url");

        $view_course = DB::select("select * from view_course");

        // return $classes;
        return view('student.all_class')->with(compact('classes', 'view_course'));
    }

    public function blogs()
    {
        $dayta = DB::select("select * from view_blog");
        return view('blogs')->with('dayta',$dayta);
    }

    public function validatePIN(Request $request)
    {
        $pin = $request->input('pin');
        $idClass = $request->input('idClass');
        $userID = Auth::id();
        $classes = DB::select("SELECT
                    a.*,
                    b.name AS mentor_name,
                    b.profile_url,
                    COUNT(c.student_id) AS num_students_registered,
                    CASE WHEN COUNT(c.student_id) > 0 THEN 1 ELSE 0 END AS is_registered
                FROM
                    lessons a
                LEFT JOIN
                    users b ON a.mentor_id = b.id
                LEFT JOIN
                    student_lesson c ON a.id = c.lesson_id
                WHERE
                    NOT EXISTS (
                        SELECT 1
                        FROM student_lesson sl
                        WHERE sl.lesson_id = a.id
                        AND sl.student_id = $userID -- Replace :user_id with the actual user ID you're interested in
                    )
                GROUP BY
                    a.id, b.name, b.profile_url");
        // Melakukan kueri ke database menggunakan Query Builder
        $course = DB::table('lessons')
        ->where('id', $idClass)
        ->where('pin', $pin)
        ->first();

        // return $course;

        // Jika kelas yang sesuai ditemukan, mengaitkan pengguna dengan kelas tersebut
        if ($course) {
            // Jika kelas ditemukan, Anda dapat menambahkan data ke tabel lain
            $dataToInsert = [
                'student_id' => $userID,
                'lesson_id' => $idClass,
                'student-lesson' => "$userID-$idClass",
                'learn_status' => 0,
                'certificate_file' => "",
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
                // ...
            ];


            // Melakukan penambahan data ke tabel lain (contoh: lessons)
            DB::table('student_lesson')->insert($dataToInsert);

            return redirect('/class/my-class')->with('success', 'Berhasil bergabung dalam kelas!');
        } else {
            // Jika PIN atau ID Kelas tidak valid, memberikan respons dengan status 422 (Unprocessable Entity)
            // return response()->json(['message' => 'PIN atau ID Kelas tidak valid'], 422);
            return redirect('/class/class-list')->with('error', 'PIN kelas tidak valid');
        }

    }

}
