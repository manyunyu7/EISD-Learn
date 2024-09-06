<?php

namespace App\Http\Controllers;

use App\Models\CourseSection;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Lesson;
use App\Models\StudentLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class VisualizationDetailController extends Controller
{
    public function seeMainPieChartDetail(Request $request)
    {

        $locationId = $request->input('location') ?? "all";
        $departmentId = $request->input('department') ?? "all";
        $classId = $request->input('class') ?? "all";
        $month = $request->input('month') ?? "all";
        $learnStatus = request('learn_status');

        if ($classId == null) {
            $classId = "all";
        }


        // department for pie chart
        $departments = DB::connection('ithub')
            ->table('m_departments')
            ->select('id', 'code', 'name')
            ->get();

        // Fetch positions
        $positions = DB::connection('ithub')
            ->table('m_group_employees')
            ->select('id', 'code', 'name')
            ->get();

        //Department for filter latest post test
        $departmentsForFilter = DB::connection('ithub')
            ->table('m_departments')
            ->select('id', 'code', 'name')
            ->where('code', 'like', '%_NEW%')
            ->get();

        $locations = DB::connection('ithub')
            ->table('m_unit_businesses')
            ->select('id', 'code', 'name')
            ->get();

        $classes = DB::table('lessons')
            ->where(function ($query) {
                $query->whereNull('deleted_at');
            })
            ->where('mentor_id', '=', Auth::user()->id)
            ->get();

        $userFilters = DB::connection('mysql')
            ->table('users')
            ->select('mdln_username', 'name', 'users.position_id', 'users.department_id', 'lessons.course_title', 'users.location')
            ->where('role', '=', 'student')
            ->where('users.is_testing', '=', 'n')
            ->where(function ($query) use ($locationId) {
                if (!empty($locationId)) {
                    if ($locationId !== 'all') {
                        $query->whereJsonContains('location', ['site_id' => $locationId]);
                    }
                }
            })
            ->where(function ($query) use ($departmentId) {
                if (!empty($departmentId)) {
                    if ($departmentId != "all") {
                        $query->where('users.department_id', '=', $departmentId);
                    }
                }
            })
            ->where(function ($query) use ($classId) {
                if (!empty($classId)) {
                    if ($classId != "all") {
                        $query->where('lessons.id', '=', $classId);
                    }
                }
            })
            ->leftJoin('student_lesson', 'users.id', '=', 'student_lesson.student_id') // Join with student_lesson table
            ->leftJoin('lessons', 'lessons.id', '=', 'student_lesson.lesson_id') // Join with student_lesson table
            ->where(function ($query) use ($learnStatus) {
                if (!empty($learnStatus) && $learnStatus !== 'all') {
                    if ($learnStatus === 'finished') {
                        $query->where('student_lesson.learn_status', '=', 1);
                    } elseif ($learnStatus === 'not_finished') {
                        $query->where('student_lesson.learn_status', '=', 0);
                    }
                }
            })
            ->where(function ($query) {
                // Check that the lesson is not deleted
                $query->whereNull('lessons.deleted_at');
            })
            ->get();

        $users_departments = $userFilters->map(function ($userLMS) use ($departments) {
            $userLMS->department = $departments->firstWhere('id', $userLMS->department_id);
            return $userLMS;
        });

        // Kelompokkan berdasarkan nama departemen
        $mainPieChartData = $users_departments->groupBy(function ($userLMS) {
            return $userLMS->department->name ?? 'Undefined Department';
        })->map(function ($group) {
            return $group->count();
        });


        // Create an associative array of departments for quick lookup
        $departmentMap = $departments->pluck('name', 'id')->toArray();


        // Create an associative array of positions for quick lookup
        $positionMap = $positions->pluck('name', 'id')->toArray();



        // Add department names to user filters
        // Add department names and position names to user filters
        foreach ($userFilters as $userFilterItem) {
            // Assign the department name based on the department_id
            $userFilterItem->department_name = $departmentMap[$userFilterItem->department_id] ?? '';

            $userFilterItem->locations = json_decode($userFilterItem->location);
            // Assign the position name based on the position_id (assuming you have a position_id field)
            $userFilterItem->position_name = $positionMap[$userFilterItem->position_id] ?? '';
        }

        foreach ($userFilters as $key) {

            if ($key->locations != null) {
                foreach ($key->locations as $locationItem) {
                    $locationItem->site_name = $locations->firstWhere('id', $locationItem->site_id)->name ?? "-";
                }
            }
        }

        foreach ($userFilters as $key) {
            unset($key->location);

            if($key->locations==null || $key->locations==""){
                $key->locations = [];
            }
        }





        // ============ FOR RANK TABLE AND MORE DETAILED CLASS DASHBOARD =============
        // Find lesson details
        $lesson = Lesson::find($classId);

        $studentCount = StudentLesson::where("lesson_id", "=", $classId)->count();

        // Get all sections of the lesson
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
            'exam_sessions.exam_type',
            'exam_sessions.id as exam_session_id',
            'course_section.section_content',
            'course_section.section_video',
            'course_section.created_at',
            'course_section.updated_at',
            'course_section.can_be_accessed'
        )
            ->leftJoin('lessons', 'lessons.id', '=', 'course_section.course_id')
            ->leftJoin('users', 'users.id', '=', 'lessons.mentor_id')
            ->leftJoin('exam_sessions', 'exam_sessions.id', '=', 'course_section.quiz_session_id')
            ->where('course_section.course_id', $classId)
            // ->where('users.is_testing', '=', 'n')
            ->orderByRaw('CAST(course_section.section_order AS UNSIGNED) ASC')
            ->get();

        // Extract quiz session IDs
        $quizSessionIds = [];

        $sectionIds = [];
        $lessonIds = [];

        foreach ($sections as $section) {
            $quizSessionIds[] = (int)$section->quiz_session_id;
            $sectionIds[] = (int)$section->section_id;
            $lessonIds[] = (int)$section->lesson_id;
        }


        // Find the latest pre-test and post-test sections
        $postTest = null;
        $preTest = null;
        foreach ($sections as $section) {
            if ($section->exam_type === 'Post Test') {
                $postTest = $section;
            } elseif ($section->exam_type === 'Pre Test') {
                $preTest = $section;
            }
        }

        $lastPostTestDetail = null;

        if ($postTest != null) {
            $lastPostTestDetail = DB::table('exam_sessions as es')
                ->select('e.title as exam_title', 'es.*')
                ->leftJoin('exams as e', 'e.id', '=', 'es.exam_id')
                ->whereIn('es.id', $quizSessionIds)
                ->first();
        }

        // return $lastPostTestDetail;


        // Get students enrolled in the lesson
        $studentOnLesson = DB::table('student_lesson as sl')
            ->leftJoin('users as u', 'sl.student_id', '=', 'u.id')
            ->leftJoin('lessons as l', 'sl.lesson_id', '=', 'l.id')
            ->select('u.id as student_id', 'u.profile_url', 'u.name as student_name', 'l.course_title', 'sl.lesson_id')
            ->where('sl.lesson_id', $classId)
            ->where(function ($query) use ($departmentId) {
                if (!empty($departmentId)) {
                    if ($departmentId != "all") {
                        $query->where('u.department_id', '=', $departmentId);
                    }
                }
            })
            ->where(function ($query) use ($locationId) {
                if (!empty($locationId)) {
                    if ($locationId !== 'all') {
                        $query->whereJsonContains('u.location', ['site_id' => $locationId]);
                    }
                }
            })
            ->get();

        $examResults = DB::table('exam_takers as et')
            ->leftJoin('exam_sessions as es', 'et.session_id', '=', 'es.id')
            ->leftJoin('course_section as cs', 'cs.id', '=', 'et.course_section_flag')
            ->leftJoin('exams as e', 'e.id', '=', 'es.exam_id')
            ->leftJoin('users as u', 'et.user_id', '=', 'u.id')
            ->leftJoin('lessons as l', 'l.id', '=', 'cs.course_id')
            ->select('e.id as exam_id', 'e.title as exam_title', 'l.course_title as course', 'l.id as course_id', 'et.current_score as exam_score', 'u.name as takers_name', 'es.exam_type as type', 'cs.section_title as course_section_title', 'es.id as exam_session_id', 'cs.id as course_section_id')
            ->whereIn('es.id', $quizSessionIds)
            ->whereIn('et.course_section_flag', $sectionIds)
            ->whereIn('et.course_flag', $lessonIds)
            ->where('et.is_finished', 'y')
            ->where(function ($query) use ($departmentId) {
                if (!empty($departmentId)) {
                    if ($departmentId != "all") {
                        $query->where('u.department_id', '=', $departmentId);
                    }
                }
            })
            ->where(function ($query) use ($locationId) {
                if (!empty($locationId)) {
                    if ($locationId !== 'all') {
                        $query->whereJsonContains('u.location', ['site_id' => $locationId]);
                    }
                }
            })
            ->get();

        // Initialize arrays to store summary information
        $summaryPrePost = [];
        $totalScores = [];

        // Iterate over each student to calculate summary information
        foreach ($studentOnLesson as $student) {
            $studentId = $student->student_id;
            $studentName = $student->student_name;
            $studentPhoto = $student->profile_url;
            $quizScore = 0;
            $preTestScore = 0;
            $postTestScore = 0;
            $takenSections = [];
            $untakenSections = [];

            // return $examResults;
            // Calculate highest pre-test and post-test scores for the student
            foreach ($examResults as $exam) {
                if ($exam->takers_name === $studentName) {
                    if ($exam->type === 'Pre Test' && $exam->exam_score > $preTestScore) {
                        $preTestScore = $exam->exam_score;
                    }
                    if ($exam->type === 'Quiz' && $exam->exam_score > $quizScore) {
                        $quizScore = $exam->exam_score;
                    }
                    if ($exam->type === 'Post Test' && $exam->exam_score > $postTestScore) {
                        $postTestScore = $exam->exam_score;
                    }
                }
            }

            // Calculate total score taking only the highest post-test score for each student
            $totalScores[$studentId] = max($totalScores[$studentId] ?? 0, $postTestScore);

            // Determine which sections are taken and untaken by the student
            foreach ($sections as $section) {
                $sectionId = $section->section_id;
                $sectionTitle = $section->section_title;
                $sectionTaken = DB::table('student_section')
                    ->where('student_id', $studentId)
                    ->where('section_id', $sectionId)
                    ->exists();
                if ($sectionTaken) {
                    $takenSections[] = $sectionTitle;
                } else {
                    $untakenSections[] = $sectionTitle;
                }
            }

            $percentage = 0;
            // Check if $sections is not empty and $takenSections is not empty before calculating percentage
            if (!empty($sections) && !empty($takenSections)) {
                // Calculate the percentage completion for the student
                $percentage = round(count($takenSections) / count($sections) * 100);
            } elseif (empty($sections)) {
                // Handle the case when $sections is empty (avoid division by zero)
                $percentage = 0; // or any other appropriate value
            } else {
                // Handle the case when $takenSections is empty
                $percentage = 0; // or any other appropriate value
            }

            // Add summary information for the student to the array
            $summaryPrePost[] = (object)[
                'student_id' => $studentId,
                'student_name' => $studentName,
                'student_photo' => $studentPhoto,
                'highest_pre_test_score' => $preTestScore,
                'highest_quiz_score' => $quizScore,
                'highest_post_test_score' => $postTestScore,
                'taken_section' => $takenSections,
                'untaken_section' => $untakenSections,
                'percentage' => round($percentage, 2) . '%',
            ];
        }

        // Sort students based on total scores and assign ranks
        $rank = 1;
        foreach ($summaryPrePost as $student) {
            $studentId = $student->student_id;
            $totalScore = $totalScores[$studentId] ?? 0;
            $student->total_score = $totalScore;
            $student->rank = $rank++;
        }


        // Bubble sort implementation
        $n = count($summaryPrePost);
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($summaryPrePost[$j]->total_score < $summaryPrePost[$j + 1]->total_score) {
                    // Swap elements if they are in the wrong order
                    $temp = $summaryPrePost[$j];
                    $summaryPrePost[$j] = $summaryPrePost[$j + 1];
                    $summaryPrePost[$j + 1] = $temp;
                }
            }
        }

        // Assign ranks
        $rank = 1;
        foreach ($summaryPrePost as $student) {
            $student->rank = $rank++;
        }

        // Find students who have not taken the last post test
        $studentsTakenPostTest = [];
        foreach ($examResults as $exam) {
            if ($exam->type === 'Post Test') {
                $studentsTakenPostTest[] = $exam->takers_name;
            }
        }
        $studentsNotTakenPostTest = [];
        foreach ($studentOnLesson as $student) {
            $studentName = $student->student_name;
            if (!in_array($studentName, $studentsTakenPostTest)) {
                $studentsNotTakenPostTest[] = $student;
            }
        }


        $rankingCard = [];
        if ($summaryPrePost && count($summaryPrePost) >= 3) {
            $rankingCard = array_slice($summaryPrePost, 0, 3);
        } else {
            // Handle the case where $summaryPrePost is null or has less than 3 elements
            $rankingCard = $summaryPrePost; // Or any other fallback logic you want to implement
        }

        // return $rankingCard;

        $takenCount = count($studentsTakenPostTest);
        $notTakenCount = count($studentsNotTakenPostTest);



        $listExamInClass = CourseSection::where('course_id', $classId)
            ->whereNotNull('quiz_session_id')
            ->where('quiz_session_id', '!=', '')
            ->where('quiz_session_id', '!=', '-')
            ->get();

        foreach ($listExamInClass as $la) {
            $examSession = ExamSession::where('id', $la->quiz_session_id)->first();
            if ($examSession != null) {
                $section = CourseSection::find($la->id);
                $exam = Exam::where('id', $examSession->exam_id)->first();
                $la->exam_id = $exam->id;
                $la->exam_session_id = $examSession->id;
                $la->type = $examSession->exam_type;
                $la->exam_title = $exam->title;
                $la->section_title = $section->section_title;
            }
        }

        $compact = compact(
            'sections',
            'listExamInClass',
            'postTest',
            'lastPostTestDetail',
            'preTest',
            'lesson',
            'takenCount',
            'studentCount',
            'notTakenCount',
            'studentsTakenPostTest',
            'studentsNotTakenPostTest',
            'rankingCard',
            'studentOnLesson',
            'examResults',
            'summaryPrePost',
            'userFilters',
            'mainPieChartData',
            'departmentsForFilter',
            'locations',
            'learnStatus',
            'locationId',
            'departmentId',
            'classes'
        );


        if ($request->dump == true) {

            return $compact;
        }

        return view('vis.main_pie_chart')->with($compact);
    }
}
