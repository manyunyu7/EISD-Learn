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
use App\Models\StudentSection;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use DB;
use File;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB as FacadesDB;

class OpenClassController extends Controller
{
    public function openClass($lessonId, $sectionId, Lesson $lesson, Request $request)
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
        
        // $sectionTable = CourseSection::join('lessons', 'course_section.course_id', '=', 'lessons.id')
        //                 ->where('lessons.id', '=', $lessonId)
        //                 ->select('course_section.id', 'course_section.section_title')
        //                 ->distinct()
        //                 // ->skip(1) // Melewatkan satu baris (baris pertama)
        //                 ->first();
        $section_spec = DB::select("select * from view_course_section where section_id = '$sectionId' ");
        

        // HANDLING CHECKBOX
        $sections = FacadesDB::select("select * from view_course_section where lesson_id = $lessonId ORDER BY section_order ASC");
        $section = $sections;
        $firstSectionId = null;
        if(!empty($section)){
            $firstSectionId = $section[1]->section_id;
        }

        $student = Auth::id();
        $studentSectionValue = "$student" . "-" . "$sectionId";

        // Check if the student-section already exists
        $existingRecord = StudentSection::where('student-section', $studentSectionValue)->first();

        if ($existingRecord) {
            // return $existingRecord;
            $checking_record = $existingRecord ? true : false;
        } else {
            // Create a new instance of StudentSection
            $data = new StudentSection();
            $data->student_id = $student;
            $data->section_id = $sectionId;
            $data->setAttribute('student-section', $studentSectionValue);
            // Save the data
            $data->save();
            $checking_record = $existingRecord ? true : false;
        }


        // HANDLING PREV AND NEXT ID
        // Find the next and previous sections
        $nextSectionId = null;
        $prevSectionId = null;
        $currentSectionId = $sectionId;
        $currentSection = CourseSection::findOrFail($currentSectionId);

        // Get the preceding sections
        $precedingSections = DB::select("
         SELECT * FROM course_section WHERE course_id = :course_id ORDER BY section_order ASC", [
            'course_id' => $lessonId,
        ]);
        $precedingSectionIds = array_map(function ($section) {
            return $section->id;
        }, $precedingSections);
        
        
        $currentSectionIndex = array_search($sectionId, $precedingSectionIds);
        if ($currentSectionIndex !== false) {
            if ($currentSectionIndex < count($precedingSectionIds) - 1) {
                $nextSectionId = $precedingSectionIds[$currentSectionIndex + 1];
            }

            if ($currentSectionIndex > 0) {
                $prevSectionId = $precedingSectionIds[$currentSectionIndex - 1];
            }
        }
        // Check if the student has taken all the preceding sections
        $isPrecedingTaken = StudentSection::whereIn('section_id', $precedingSectionIds)
            ->where('student_id', $user_id)
            ->exists();
        


        
        // RETURN VALUE
        // return $silabusClass;
        // return $sectionTable;
        // return $sectionId;
        // return $existingRecord;
        // return $nextUrl;
        // return $section_spec;
        // return $section;
        // return $currentSectionId;
        // return $currentSection;
        // dd($prevSectionId, $nextSectionId);
        // return $checking_record;
        $compact = compact('classInfo', 'silabusClass', 'totalSections', 'firstSectionId', 'section_spec', 'section', 'nextSectionId', 'prevSectionId', 'checking_record');
        return view('lessons.open_class', $compact);
    }


    
}
