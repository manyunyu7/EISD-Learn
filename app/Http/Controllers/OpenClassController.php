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
    public function openClass($lessonId, CourseSection $section, Lesson $lesson, Request $request)
    {
        $userID     = Auth::id();
        $classInfo  = DB::select("SELECT
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
                            EXISTS (
                                SELECT 1
                                FROM student_lesson sl
                                WHERE a.id = $lessonId
                                
                            )
                        GROUP BY
                            a.id, b.name, b.profile_url;
                        ");


        $silabusClass = DB::select("SELECT
                        a.*
                        FROM
                            course_section a
                        WHERE
                            a.course_id = $lessonId
                        ");

        $totalSections = count($silabusClass);
        // $sectionId = $section->id;
        // $sectionId = $section->course_id;


        // HANDLING SECTION
        $user_id = Auth::user()->id;
        // $lessonId = $lesson->id;
        $isRegistered = false;
        if (Auth::user()->role == "student") {
            $student_lesson = DB::table('student_lesson')
                ->where('student-lesson', "$user_id-$lessonId")
                ->get()
                ->toArray();

            $isRegistered = false;
            if ($student_lesson == null) {
                abort(401, "Anda Belum Mendaftar ke Kelas " . $lesson->name);
            } else {
                $isRegistered = true;
            }
        }

        // Get the preceding sections
        $precedingSections = DB::select("
            SELECT * FROM course_section WHERE course_id = :course_id ORDER BY section_order ASC", [
                'course_id' => $lessonId,
            ]);
        $precedingSectionIds = array_map(function ($section) {
            return $section->id;
        }, $precedingSections);


        $studentTakenSections = DB::select("
                                    SELECT
                                        ss.student_id,
                                        users.name,
                                        lessons.course_title,
                                        lessons.id AS lessons_id,
                                        ss.section_id,
                                        ss.`student-section`
                                    FROM
                                        student_section AS ss
                                    LEFT JOIN users ON users.id = ss.student_id
                                    LEFT JOIN course_section ON ss.section_id = course_section.id
                                    LEFT JOIN lessons ON course_section.course_id = lessons.id
                                    WHERE users.id = :user_id AND lessons.id = :lessons_id
                                ", [
                                        'user_id' => Auth::id(),
                                        'lessons_id' => $lessonId,
                                    ]
                                );
        $studentTakenSectionIds = array_map(function ($section) {
            return $section->section_id;
        }, $studentTakenSections);

        // $sectionId = $section->course_id;
        // $section_spec = DB::select("select * from view_course_section where section_id = '$sectionId' ");
        
        
        $sectionTable = CourseSection::join('lessons', 'course_section.course_id', '=', 'lessons.id')
                        ->where('lessons.id', '=', $lessonId)
                        ->select('course_section.id', 'course_section.section_title')
                        ->distinct()
                        ->skip(1) // Melewatkan satu baris (baris pertama)
                        ->first();
        // RETURN VALUE
        // return $sectionTable;
        return view('lessons.open_class')->with(compact('classInfo', 'silabusClass', 'totalSections', 'sectionTable'));
    }


    
}
