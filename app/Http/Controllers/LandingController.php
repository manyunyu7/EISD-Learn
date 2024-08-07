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

class LandingController extends Controller
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
        return view('landing168');
    }

    public function classes()
    {
        $dayta = DB::select("select * from view_course");
        MyHelper::addAnalyticEvent(
            "Buka Halaman Kelas","Kelas"
        );
        return view('classes')->with('dayta', $dayta);
    }

    public function portfolios()
    {
        $classes = DB::select("SELECT
                        a.*,
                        b.name AS mentor_name,
                        b.profile_url,
                        COUNT(c.student_id) AS num_students_registered
                    FROM
                        lessons a
                    LEFT JOIN
                        users b ON a.mentor_id = b.id
                    LEFT JOIN
                        student_lesson c ON a.id = c.lesson_id
                    GROUP BY
                        a.id, b.name, b.profile_url;");

        return view('portfolio')->with(compact('classes'));
    }

    public function blogs()
    {
        $dayta = DB::select("select * from view_blog");
        // $user_id  = Auth::id();
        // $lesson_id = $lesson->id;
        // if($user_id != $lesson->mentor_id){
        //     abort(401,'Unauthorized');
        // }
        // $dayta = DB::select("select * from view_course_section where lesson_id = $lesson_id ORDER BY section_order ASC");
        // $dayta = DB::select("select * from view_course_section  where mentor_id = $user_id");
        return view('blogs')->with('dayta',$dayta);
    }



}
