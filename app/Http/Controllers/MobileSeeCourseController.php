<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\CourseSection;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\ExamTaker;
use App\Models\Lesson;
use App\Models\StudentLesson;
use App\Models\StudentSection;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MobileSeeCourseController extends Controller
{
    public function seeSection(Request $request, Lesson $lesson, CourseSection $section)
    {
        // Find the next and previous sections
        $nextSectionId = null;
        $prevSectionId = null;
        $currentSectionId = $section->id;
        $questions = [];
        $currentSection = CourseSection::findOrFail($currentSectionId);
        $isExam = false;
        $title = "";

        $userId = $request->user_id;
        Auth::loginUsingId($userId);

        if (!Auth::check()) {
            MyHelper::addAnalyticEventMobile(
                "Mobile Buka Section",
                "Course Section",
                $userId
            );

            if ($userId != null) {
                Auth::loginUsingId($userId);
            } else {
                abort(401, "Anda Harus Login Untuk Melanjutkan " . $lesson->name);
            }
        }

        $user_id = Auth::user()->id;
        $lessonId = $lesson->id;
        $isRegistered = false;
        if (Auth::user()->role == "student") {
            $student_lesson = DB::table('student_lesson')
                ->where('student_lesson', "$user_id-$lessonId")
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
                'ss.student_section'
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
                    ->where('lessons.id', $lessonId)
                    ->pluck('ss.section_id')
                    ->toArray();

                $lastSectionTaken = DB::table('student_section as ss')
                    ->leftJoin('users', 'users.id', '=', 'ss.student_id')
                    ->leftJoin('course_section', 'ss.section_id', '=', 'course_section.id')
                    ->leftJoin('lessons', 'course_section.course_id', '=', 'lessons.id')
                    ->where('ss.student_id', \Illuminate\Support\Facades\Auth::id())
                    ->where('lessons.id', $lessonId)
                    ->orderBy('ss.id', 'desc')
                    ->first();
            }
        }

        // Check if the student has taken all the preceding sections
        $isPrecedingTaken = StudentSection::whereIn('section_id', $precedingSectionIds)
            ->where('student_id', $user_id)
            ->exists();

        // ✅ NEW: Calculate completion progress properly (like in main controller)
        // Fetch all sections for the lesson
        $allSectionsInLesson = CourseSection::where('course_id', $lessonId)
            ->orderByRaw("CAST(section_order AS UNSIGNED)")
            ->get();

        $completedAndPassedSectionsCount = 0;

        // ✅ NEW: Check each section for completion AND passing score
        foreach ($allSectionsInLesson as $sectionItem) {
            // Check if student has taken this section
            $isSectionTaken = StudentSection::where('section_id', $sectionItem->id)
                ->where('student_id', Auth::id())
                ->exists();

            if ($isSectionTaken) {
                // If this section is a quiz
                if (
                    $sectionItem->quiz_session_id != null &&
                    $sectionItem->quiz_session_id != "" &&
                    $sectionItem->quiz_session_id != "null" &&
                    $sectionItem->quiz_session_id != "-" &&
                    $sectionItem->quiz_session_id != "Tidak Ada Quiz"
                ) {

                    $examSession = ExamSession::find($sectionItem->quiz_session_id);

                    if ($examSession && $examSession->standard_pass_score !== null) {
                        // Get student's highest score for this quiz
                        $highestScoreAchieved = (int) ExamTaker::where('user_id', Auth::id())
                            ->where('course_section_flag', $sectionItem->id)
                            ->where('session_id', $sectionItem->quiz_session_id)
                            ->where('is_finished', 'y')
                            ->whereNotNull('finished_at')
                            ->selectRaw('MAX(CAST(current_score AS SIGNED)) as max_score')
                            ->value('max_score');

                        // If highest score meets passing score, count as completed
                        if ($highestScoreAchieved >= $examSession->standard_pass_score) {
                            $completedAndPassedSectionsCount++;
                        }
                    } else {
                        // If quiz but no passing score, count as completed if taken
                        $completedAndPassedSectionsCount++;
                    }
                } else {
                    // If not a quiz, count as completed if taken
                    $completedAndPassedSectionsCount++;
                }
            }
        }

        // ✅ NEW: Update lesson completion status
        $total_section = count($allSectionsInLesson);

        if ($completedAndPassedSectionsCount == $total_section) {
            $u_student_lesson = StudentLesson::where('student_id', '=', $user_id)
                ->where('lesson_id', '=', $lessonId)
                ->first();
            if ($u_student_lesson && $u_student_lesson->learn_status != 1) {
                $u_student_lesson->finished_at = Carbon::now();
                $u_student_lesson->learn_status = 1;
                $u_student_lesson->save();
            }
        } else {
            // Reset completion status if no longer all sections completed
            $u_student_lesson = StudentLesson::where('student_id', '=', $user_id)
                ->where('lesson_id', '=', $lessonId)
                ->first();
            if ($u_student_lesson && $u_student_lesson->learn_status == 1) {
                $u_student_lesson->learn_status = 0;
                $u_student_lesson->finished_at = null;
                $u_student_lesson->save();
            }
        }

        // Get section count for progress calculation
        $sectionTakenOnCourseCount = DB::table('student_section as ss')
            ->leftJoin('users', 'users.id', '=', 'ss.student_id')
            ->leftJoin('course_section', 'ss.section_id', '=', 'course_section.id')
            ->leftJoin('lessons', 'course_section.course_id', '=', 'lessons.id')
            ->where('ss.student_id', Auth::id())
            ->where('lessons.id', $lessonId)
            ->count();

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
            'exam_sessions.time_limit_minute',
            'course_section.section_content',
            'course_section.section_video',
            'course_section.created_at',
            'course_section.updated_at',
            'course_section.can_be_accessed'
        )
            ->leftJoin('lessons', 'lessons.id', '=', 'course_section.course_id')
            ->leftJoin('users', 'users.id', '=', 'lessons.mentor_id')
            ->leftJoin('exam_sessions', 'exam_sessions.id', '=', 'course_section.quiz_session_id')
            ->where('course_section.course_id', $lessonId)
            ->orderBy(DB::raw('CAST(course_section.section_order AS UNSIGNED)'), 'ASC')
            ->get();

        $sectionDetail = CourseSection::findOrFail($sectionId);

        // ✅ UPDATED: Enhanced section completion checking with quiz score bypass
        foreach ($sections as $key => $sectionItem) {
            // Check if the section is already added to the student_section
            $isTaken = StudentSection::where('section_id', $sectionItem->section_id)
                ->where('student_id', Auth::id())
                ->exists();

            // ✅ NEW: Add quiz logic to allow progression regardless of score
            if (
                $isTaken &&
                $sectionItem->quiz_session_id !== null &&
                $sectionItem->quiz_session_id != "" &&
                $sectionItem->quiz_session_id != "null" &&
                $sectionItem->quiz_session_id != "-"
            ) {

                $examSession = ExamSession::find($sectionItem->quiz_session_id);
                if ($examSession && $examSession->standard_pass_score !== null) {
                    $highestScoreAchieved = (int) ExamTaker::where('user_id', Auth::id())
                        ->where('course_section_flag', $sectionItem->section_id)
                        ->where('is_finished', 'y')
                        ->whereNotNull('finished_at')
                        ->max('current_score');

                    // ✅ CRITICAL CHANGE: Even if score is below passing grade, allow progression
                    if ($highestScoreAchieved < $examSession->standard_pass_score) {
                        $isTaken = false;  // This line gets overridden below
                    }
                    $isTaken = true;      // ✅ Always allow progression regardless of quiz score
                }
            }

            // Add the 'isTaken' attribute to the section object
            $sectionItem->isTaken = $isTaken;
            $sectionItem->user_id = Auth::id();
            $sectionItem->isCurrent = ($sectionItem->section_id == $currentSectionId);
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
        $timezone = config('app.timezone');

        $alreadyTakeNeededExam = true;
        $isEligibleStudent = true;

        if (Auth::user()->role == "student") {
            $isStudent = true;
            $completedSections = $sectionTakenByStudent;
            $currentSectionIndex = array_search($currentSectionId, $sectionOrder);

            // ✅ UPDATED: Enhanced prerequisite checking (but still allows progression)
            for ($i = 0; $i < $currentSectionIndex; $i++) {
                $currentIndexedSection = CourseSection::find($sectionOrder[$i]);

                if ($currentIndexedSection != null && $currentIndexedSection->quiz_session_id != null) {
                    $zquizSession = ExamSession::find($currentIndexedSection->quiz_session_id);

                    if ($zquizSession) {
                        $now = Carbon::now($timezone)->toDateTimeString();

                        $zcheckIfStudentAlreadyTake = ExamTaker::where('user_id', Auth::id())
                            ->where('course_section_flag', $sectionOrder[$i])
                            ->where('is_finished', 'y')
                            ->whereNotNull('finished_at')
                            ->count();

                        $zquizResults = ExamTaker::where('user_id', Auth::id())
                            ->where('course_section_flag', $sectionOrder[$i])
                            ->where('is_finished', 'y')
                            ->whereNotNull('finished_at')
                            ->get();

                        $zexam = Exam::find("$zquizSession->exam_id");
                        $zsectionTitle = $currentIndexedSection->section_title;
                        $zsectionId = $currentIndexedSection->id;
                        $examTitle = "";
                        if ($zexam != null) {
                            $examTitle = $zexam->title;
                        }

                        // Only require quiz to be attempted, not passed
                        if ($zcheckIfStudentAlreadyTake == 0) {
                            $alreadyTakeNeededExam = false;
                            $zlink = url()->to("/course/$lessonId/section/$zsectionId");
                            $additional = "<a href='$zlink'>$examTitle</a>";
                            $message = "Terdapat Quiz pada Bagian $zsectionTitle yang Belum Anda Kerjakan.\n";
                            return response()->view('errors.sesval', [
                                'sectionTitle' => $zsectionTitle,
                                'message' => $message,
                                'link' => $zlink
                            ], 401);
                        }

                        // ✅ REMOVED: Standard pass score checking for progression
                        // Students can now proceed even if they didn't meet the passing score
                    }
                }

                // Check if the section from sectionOrder exists in completedSections
                if (!in_array($sectionOrder[$i], $completedSections)) {
                    if ($sectionTakenOnCourseCount != 0) {
                        $zsectionTitle = $currentIndexedSection->section_title;
                        $zsectionId = $currentIndexedSection->id;
                        $zlink = url()->to("/course/$lessonId/section/$zsectionId");
                        return response()->view('errors.sesval', [
                            'sectionTitle' => $zsectionTitle,
                            'message' => "Anda Harus Menyelesaikan Bagian-bagian Sebelumnya Untuk Mengakses Bagian Ini",
                            'link' => $zlink
                        ], 401);
                    } else {
                        $isEligibleStudent = false;
                    }
                }
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

            $currentDate = new DateTime();
            $startDate = new DateTime($examSession->start_date);
            $endDate = new DateTime($examSession->end_date);

            $currentDate = Carbon::now();
            $startDate = Carbon::parse($examSession->start_date);
            $endDate = Carbon::parse($examSession->end_date);

            if ($currentDate->lt($startDate) || $currentDate->gt($endDate)) {
                abort(401, "Kelas hanya bisa diakses pada $startDate - $endDate");
            }

            foreach ($questions as $question) {
                if (isset($question->choices)) {
                    $choices = json_decode($question->choices, true);

                    foreach ($choices as $choice) {
                        if (isset($choice['score']) && $choice['score'] !== null && $choice['score'] >= 0) {
                            $totalScore += (int) $choice['score'];
                        }
                    }
                }
            }

            // Randomize question order if enabled
            if ($examSession->random_sort_exam == "y") {
                shuffle($questions);
            }
            $question_count = count($questions);
        }

        // Check if student has taken any exam on this session
        $hasTakenAnyExam = false;
        $examResults = ExamTaker::where('user_id', Auth::id())
            ->where('course_section_flag', $currentSectionId)
            ->where('is_finished', 'y')
            ->whereNotNull('finished_at')
            ->leftJoin('exam_sessions as es', 'es.id', '=', 'exam_takers.session_id')
            ->leftJoin('exams as e', 'e.id', '=', 'es.exam_id')
            ->select('exam_takers.*', 'e.title as exam_title')
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

        if (count($classInfo) != 0) {
            $classInfo = $classInfo[0];
        }

        $mentor = User::where("id", '=', "");
        $sectionCount = count($sections);

        // ✅ UPDATED: Use proper completion count for progress
        $progressPercentage = round(($completedAndPassedSectionsCount / $sectionCount) * 100);

        // Check if exam is in time
        $isExamInTime = true;
        if ($isExam == true) {
            if ($examSession != null) {
                $startDate_exam = $examSession->start_date;
                $endDate_exam   = $examSession->end_date;
                $now = Carbon::now();
                if ($now->between($startDate_exam, $endDate_exam)) {
                    $isExamInTime = true;
                } else {
                    $isExamInTime = false;
                }
            }
        }

        // Check if exam on first section is already finished
        if (Auth::user()->role == "student") {
            $isFirstExamTaken = true;
            $quizSession = ExamSession::find($currentSection->quiz_session_id);
            if ($quizSession != null) {
                $now = Carbon::now($timezone)->toDateTimeString();

                $checkIfStudentAlreadyTake = ExamTaker::where('user_id', Auth::id())
                    ->where('course_section_flag', $sectionOrder[$i] ?? $currentSectionId)
                    ->where('is_finished', 'y')
                    ->count();

                if ($checkIfStudentAlreadyTake != 0) {
                    $isFirstExamTaken = true;
                } else {
                    $isFirstExamTaken = false;
                }
            } else {
                $isFirstExamTaken = true;
            }
        }

        if (Auth::user()->role == "student") {
            if ($isEligibleStudent && $alreadyTakeNeededExam && $isFirstExamTaken) {
                if ($isExamInTime) {
                    $this->startSection($currentSectionId);
                }
            }
        }

        $compact = compact(
            'userId',
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
            'completedAndPassedSectionsCount', // ✅ NEW: Added proper completion tracking
            'total_section', // ✅ NEW: Added total sections count
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

        // Check if the student_section already exists
        $existingRecord = StudentSection::where('student_section', $studentSectionValue)->first();

        if ($existingRecord) {
            // Handle the case when the record already exists
            // For example, you can return an error message or redirect back with an error
            // return back()->with('error', 'student_section already exists.');
        } else {
            // Create a new instance of StudentSection
            $data = new StudentSection();
            $data->student_id = $student;
            $data->section_id = $section;
            $data->setAttribute('student-section', $studentSectionValue);
            $data->setAttribute('student_section', $studentSectionValue);
            // Save the data
            $data->save();

            // Perform any additional actions after saving
            // Redirect or return a success message
            // return redirect()->route('success')->with('success', 'student_section saved successfully.');
        }
    }
}
