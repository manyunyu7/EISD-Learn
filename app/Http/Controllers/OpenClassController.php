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
use PhpParser\Node\Stmt\Return_;

class OpenClassController extends Controller
{
    public function openClass($lessonId, $sectionId, Lesson $lesson, Request $request, CourseSection $section)
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
        $user_id = Auth::user()->id;
        $isRegistered = false;        

        // HANDLING SECTION
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
        

        // HANDLING CHECKBOX
            $sections = DB::select("select * from view_course_section where lesson_id = $lessonId ORDER BY section_order ASC");
            $section_spec = DB::select("select * from view_course_section where section_id = '$sectionId' ");
            $student_sections = DB::select("select * from student_section ");
            

            // Iterate over the sections and check if each one is already added to the student-section
            foreach ($sections as $key => $section) {
                // Check if the section is already added to the student-section
                $isTaken = StudentSection::where('section_id', $section->section_id)
                    ->where('student_id', Auth::id())
                    ->exists();

                // Add the 'isTaken' attribute to the section object
                $section->isTaken = $isTaken;
                $section->isCurrent = $sectionId;

                if ($section->section_id == $sectionId) {
                    $section->isCurrent = true;
                } else {
                    $section->isCurrent = false;
                }
            }

            // $section = $sections;
       
        // CHECKING AND COUNTS OF TAKEN MATERI
            $hasTaken  = DB::select("SELECT
                            a.*
                            FROM
                                student_section a
                            LEFT JOIN
                                course_section b  ON a.section_id = b.id
                            WHERE
                                a.student_id = $userID AND b.course_id = $lessonId;
                            "
                        );
            


        $firstSectionId = null;
        if(!empty($sections)){
            //Lenght = 1 Skip
            if (!count($sections) == 1){
                $firstSectionId = $sections[1]->section_id;
            }
        }

        $student = Auth::id();
        $studentSectionValue = "$student" . "-" . "$sectionId";



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
        


        // CHECKING HISTORY STUDENT SECTION
            $sectionTakenByStudent = null;
            $lastSectionTaken = null;

            if (Auth::check()) {
                if (Auth::user()->role == "student") {
                    if ($section->can_be_accessed == "n") {
                        abort(401, "Materi baru dapat diakses pada jadwal yang telah ditentukan");
                    }
                    $sectionTakenByStudent = FacadesDB::table('student_section as ss')
                        ->select('section_id')
                        ->leftJoin('users', 'users.id', '=', 'ss.student_id')
                        ->leftJoin('course_section', 'ss.section_id', '=', 'course_section.id')
                        ->leftJoin('lessons', 'course_section.course_id', '=', 'lessons.id')
                        ->where('ss.student_id', \Illuminate\Support\Facades\Auth::id())
                        ->where('lessons.id', $lessonId) // Add the condition lessons.id = 5
                        ->pluck('ss.section_id')
                        ->toArray();

                    $lastSectionTaken = FacadesDB::table('student_section as ss')
                        ->leftJoin('users', 'users.id', '=', 'ss.student_id')
                        ->leftJoin('course_section', 'ss.section_id', '=', 'course_section.id')
                        ->leftJoin('lessons', 'course_section.course_id', '=', 'lessons.id')
                        ->where('ss.student_id', \Illuminate\Support\Facades\Auth::id())
                        ->where('lessons.id', $lessonId)
                        ->orderBy('ss.id', 'desc') // Assuming 'id' is the primary key column in 'student_section' table
                        ->first();
                }
            }
            $sectionOrder = $precedingSectionIds;

            if (!empty($sectionOrder)) {
                $firstSectionId = $sectionOrder[0];
                $lastSectionId = end($sectionOrder);
            }

            $isFirstSection = false;
            if ($firstSectionId == $sectionId) {
                $isFirstSection = true;
            }

            $courseId = $lessonId;
            $isStudent = false;


            // VARIABLE USING FORMULA
            $totalSections = count($silabusClass);
            $total_hasTaken = count($hasTaken);

            $isEligibleStudent = true; //eligible to open the section
            if (Auth::user()->role == "student") {
                $isStudent = true;
                $completedSections = $sectionTakenByStudent;

                // Get the index of the current section in the sectionOrder array
                $currentSectionIndex = array_search($currentSectionId, $sectionOrder);

                // Loop through the sectionOrder array from the beginning until the current section index
                for ($i = 0; $i < $currentSectionIndex; $i++) {
                    // Check if the section from sectionOrder exists in completedSections
                    if (!in_array($sectionOrder[$i], $completedSections)) {
                        $isEligibleStudent = false;
                        abort(401, "Anda Harus Menyelesaikan Bagian-bagian Sebelumnya Untuk Mengakses Bagian Ini");
                    }
                }
                if ($isEligibleStudent) {
                    $this->startSection($currentSectionId);
                    // VARIABLE USING FORMULA
                    $silabusClass = DB::select("SELECT
                                    a.*
                                    FROM
                                        course_section a
                                    WHERE
                                        a.course_id = $lessonId
                                    ");

                    $hasTaken  = DB::select("SELECT
                                a.*
                                FROM
                                    student_section a
                                LEFT JOIN
                                    course_section b  ON a.section_id = b.id
                                WHERE
                                    a.student_id = $userID AND b.course_id = $lessonId;
                                "
                                );

                    $totalSections = count($silabusClass);
                    $total_hasTaken = count($hasTaken);
                    $progressPercentage = round(($total_hasTaken / $totalSections) * 100);

                }
            }
        // RETURN VALUE
            $compact = compact('classInfo', 'silabusClass', 'totalSections', 'firstSectionId', 
                                'section_spec', 'sections', 'section', 'nextSectionId', 'prevSectionId', 'total_hasTaken', 'progressPercentage',
                                );
        return view('lessons.open_class', $compact);
    }


    // SUPPORT FUNCTIONS
    function startSection($sectionId)
    {
        $section = $sectionId;
        $student = Auth::id();

        $studentSectionValue = "$student" . "-" . "$section";

        // Check if the student-section already exists
        $existingRecord = StudentSection::where('student-section', $studentSectionValue)->first();

        if ($existingRecord) {
            // Handle the case when the record already exists
            // For example, you can return an error message or redirect back with an error
            // return back()->with('error', 'Student-section already exists.');
        } else {
            // Create a new instance of StudentSection
            $data = new StudentSection();
            $data->student_id = $student;
            $data->section_id = $section;
            $data->setAttribute('student-section', $studentSectionValue);
            // Save the data
            $data->save();

            // Perform any additional actions after saving
            // Redirect or return a success message
            // return redirect()->route('success')->with('success', 'Student-section saved successfully.');
        }
    }

    
}