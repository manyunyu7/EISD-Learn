<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\CourseSection;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\ExamTaker;
use App\Models\Lesson;
use App\Models\StudentSection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MobileSeeCourseController extends Controller
{
    public function see_section(Request $request, Lesson $lesson, CourseSection $section)
    {
        // Find the next and previous sections
        $nextSectionId = null;
        $prevSectionId = null;
        $currentSectionId = $section->id;
        $questions = [];
        $currentSection = CourseSection::findOrFail($currentSectionId);
        $isExam = false;
        $title = "";

        $userId = $request->userId;
        Auth::loginUsingId($userId);

        if (!Auth::check()) {
            MyHelper::addAnalyticEventMobile(
                "Mobile Buka Section",
                "Course Section",
                $userId
            );
            abort(401, "Anda Harus Login Untuk Melanjutkan " . $lesson->name);
        }

        $user_id = Auth::user()->id;
        $lessonId = $lesson->id;
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

        $sectionId = $section->id;
        $lessonId = $lesson->id;

        $lessonObject = Lesson::findOrFail($lessonId);
        if (Auth::user()->role == "student") {
            if ($lessonObject->can_be_accessed == "n") {
                MyHelper::addAnalyticEventMobile(
                    "Reject Section Diluar Jadwal",
                    "Course Section",
                    $userId
                );
                //                abort(401, "Kelas ini hanya bisa diakses pada jadwal yang telah ditentukan #922 $lessonObject");
            }
        }


        // Get the preceding sections
        $precedingSections = DB::table('course_section')
            ->where('course_id', $lessonId)
            ->orderByRaw("CAST(section_order AS UNSIGNED)")
            ->get()
            ->toArray();

        $precedingSectionIds = array_map(function ($section) {
            return $section->id;
        }, $precedingSections);


        $studentTakenSections = DB::table('student_section AS ss')
            ->select(
                'ss.student_id',
                'users.name',
                'lessons.course_title',
                'lessons.id AS lessons_id',
                'ss.section_id',
                'ss.student-section'
            )
            ->leftJoin('users', 'users.id', '=', 'ss.student_id')
            ->leftJoin('course_section', 'ss.section_id', '=', 'course_section.id')
            ->leftJoin('lessons', 'course_section.course_id', '=', 'lessons.id')
            ->where('users.id', Auth::id())
            ->where('lessons.id', $lessonId)
            ->get();

        $studentTakenSectionIds = $studentTakenSections->pluck('section_id')->toArray();

        $currentSectionIndex = array_search($sectionId, $precedingSectionIds);
        if ($currentSectionIndex !== false) {
            if ($currentSectionIndex < count($precedingSectionIds) - 1) {
                $nextSectionId = $precedingSectionIds[$currentSectionIndex + 1];
            }

            if ($currentSectionIndex > 0) {
                $prevSectionId = $precedingSectionIds[$currentSectionIndex - 1];
            }
        }

        $sectionTakenByStudent = null;
        $lastSectionTaken = null;

        if (Auth::check()) {
            if (Auth::user()->role == "student") {

                if ($section->can_be_accessed == "n") {
                    abort(401, "Materi baru dapat diakses pada jadwal yang telah ditentukan");
                }
                $sectionTakenByStudent = DB::table('student_section as ss')
                    ->select('section_id')
                    ->leftJoin('users', 'users.id', '=', 'ss.student_id')
                    ->leftJoin('course_section', 'ss.section_id', '=', 'course_section.id')
                    ->leftJoin('lessons', 'course_section.course_id', '=', 'lessons.id')
                    ->where('ss.student_id', \Illuminate\Support\Facades\Auth::id())
                    ->where('lessons.id', $lessonId) // Add the condition lessons.id = 5
                    ->pluck('ss.section_id')
                    ->toArray();

                $lastSectionTaken = DB::table('student_section as ss')
                    ->leftJoin('users', 'users.id', '=', 'ss.student_id')
                    ->leftJoin('course_section', 'ss.section_id', '=', 'course_section.id')
                    ->leftJoin('lessons', 'course_section.course_id', '=', 'lessons.id')
                    ->where('ss.student_id', \Illuminate\Support\Facades\Auth::id())
                    ->where('lessons.id', $lessonId)
                    ->orderBy('ss.id', 'desc') // Assuming 'id' is the primary key column in 'student_section' table
                    ->first();
            }
        }

        //return $precedingSectionIds;
        // Check if the student has taken all the preceding sections
        $isPrecedingTaken = StudentSection::whereIn('section_id', $precedingSectionIds)
            ->where('student_id', $user_id)
            ->exists();

        $sectionTakenOnCourseCount = DB::table('student_section as ss')
            ->leftJoin('users', 'users.id', '=', 'ss.student_id')
            ->leftJoin('course_section', 'ss.section_id', '=', 'course_section.id')
            ->leftJoin('lessons', 'course_section.course_id', '=', 'lessons.id')
            ->where('ss.student_id', Auth::id())
            ->where('lessons.id', $lessonId)
            ->count();

        // $section = DB::select("select * from view_course_section where lesson_id = $lesson_id ORDER BY section_order ASC");
        // Fetch all sections for the lesson
        $student_sections = DB::select("select * from student_section ");

        $sections = CourseSection::select(
            'lessons.id as lesson_id',
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
            'course_section.can_be_accessed'
        )
            ->leftJoin('lessons', 'lessons.id', '=', 'course_section.course_id')
            ->leftJoin('users', 'users.id', '=', 'lessons.mentor_id')
            ->leftJoin('exam_sessions', 'exam_sessions.id', '=', 'course_section.quiz_session_id') // Left join to quiz_session
            ->where('course_section.course_id', $lessonId)
            ->orderBy(DB::raw('CAST(course_section.section_order AS UNSIGNED)'), 'ASC')
            ->get();


        $sectionDetail = CourseSection::findOrFail($sectionId);
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

        $section = $sections;
        $firstSectionId = null;
        $lastSectionId = null;

        $next_section = $nextSectionId;
        $prev_section = $prevSectionId;
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
                    if ($sectionTakenOnCourseCount != 0) {
                        //                        abort(401, "Anda Harus Menyelesaikan Bagian-bagian Sebelumnya Untuk Mengakses Bagian Ini");
                    }
                }
            }
            if ($isEligibleStudent) {
                $this->startSection($currentSectionId);
            }
        }

        $examSession = null;
        $exam = null;
        $question_count = 0;
        $totalScore = 0;
        $session = null;

        if (
            $currentSection->quiz_session_id != null &&
            $currentSection->quiz_session_id != "" &&
            $currentSection->quiz_session_id != "null" &&
            $currentSection->quiz_session_id != "-" &&
            $currentSection->quiz_session_id != "Tidak Ada Quiz"
        ) {
            $isExam = true;
            $examSession = ExamSession::find($currentSection->quiz_session_id);
            $exam = Exam::find($examSession->exam_id);
            $session = $examSession;
            $questions = json_decode($session->questions_answers);
            $totalScore = 0;
            $title = $exam->title;
            foreach ($questions as $question) {
                if (isset($question->choices)) {
                    $choices = json_decode($question->choices, true);

                    foreach ($choices as $choice) {
                        if (isset($choice['score']) && $choice['score'] !== null && $choice['score'] >= 0) {
                            $totalScore += (int)$choice['score'];
                        }
                    }
                }
            }
            $question_count = count($questions);
        }

        //check if student has taken any exam on this session
        $hasTakenAnyExam = false;
        $examResults = ExamTaker::where(
            "course_flag",
            "=",
            $courseId
        )->where(
            "course_section_flag",
            "=",
            $sectionId
        )
            ->where("user_id", '=', Auth::id())
            ->get();


        if (count($examResults) > 0) {
            $hasTakenAnyExam = true;
        }


        $classInfo = DB::select("SELECT
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
                            a.id, b.name, b.profile_url
                        LIMIT 1;");
        // $sections = FacadesDB::select("select * from view_course_section where lesson_id = $lessonId ORDER BY section_order ASC");
        // $section = $sections;


        if (count($classInfo) != 0) {
            $classInfo = $classInfo[0];
        }

        $mentor = User::where("id", '=', "");
        $sectionCount = count($sections);

        // taken $sectionTakenOnCourseCount;
        // all sections $sectionCount;
        $progressPercentage = round(($sectionTakenOnCourseCount / $sectionCount) * 100);

        $compact = compact(
            'isEligibleStudent',
            'hasTakenAnyExam',
            'examResults',
            'currentSectionId',
            'courseId',
            'next_section',
            'prev_section',
            'isStudent',
            'sectionTakenByStudent',
            'sectionTakenOnCourseCount',
            'isFirstSection',
            'isExam',
            'title',
            'sectionDetail',
            'sections',
            'sectionCount',
            'questions',
            'progressPercentage',
            'firstSectionId',
            'lastSectionId',
            'isPrecedingTaken',
            'examSession',
            'exam',
            'session',
            'question_count',
            'totalScore',
            'sectionOrder',
            'lesson',
            'section',
            'isRegistered',
            'classInfo'
        );


        if ($request->dump == true) {
            return $compact;
        }

        MyHelper::addAnalyticEventMobile(
            "Buka Section",
            "Course Section",
            $userId
        );


        return view('lessons.play.course_play_mobile', $compact);
    }

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
