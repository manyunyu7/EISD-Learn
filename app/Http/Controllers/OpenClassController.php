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

class OpenClassController extends Controller
{
    public function openClass()
    {
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
        // return $lessonId;
        return view('lessons.open_class')->with(compact('classes', 'view_course'));
    }
}