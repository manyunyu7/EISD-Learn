<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\ExamSession;
use App\Models\ExamTaker;
use App\Models\StudentSection;
use App\Models\Exam;
use Auth;
use DB;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\Return_;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if (!Auth::check()) {
            return Redirect::away("/");
        }

        $averageScoreSubquery = DB::table('student_section')
            ->select('student_id', DB::raw('AVG(score) as average_score'))
            ->groupBy('student_id');


        $leaderboardQuery = DB::table(DB::raw('student_section ss'))
            ->select(
                'u.name as student_name', 'u.profile_url', 'u.id',
                DB::raw('SUM(ss.score) as total_score'),
                'avg_subquery.average_score as average_score',
                DB::raw('(SELECT MAX(score) FROM student_section WHERE student_id = u.id) as highest_score'),
                DB::raw('(SELECT MIN(score) FROM student_section WHERE student_id = u.id) as lowest_score')
            )
            ->join('users as u', 'ss.student_id', '=', 'u.id')
            ->joinSub($averageScoreSubquery, 'avg_subquery', 'u.id', '=', 'avg_subquery.student_id')
            ->groupBy('ss.student_id', 'u.name', 'avg_subquery.average_score')
            ->orderByDesc('total_score');


        $topThreeQuery = array();
        $studentSectionCount = StudentSection::all()->count();
        if ($studentSectionCount > 0) {
            $topThreeQuery = DB::table(DB::raw('student_section ss'))
                ->select(
                    'u.name as student_name', 'u.profile_url',
                    DB::raw('SUM(ss.score) as total_score'),
                    DB::raw('(SELECT MAX(score) FROM student_section WHERE student_id = u.id) as lowest_score'),
                    DB::raw('(SELECT MIN(score) FROM student_section WHERE student_id = u.id) as highest_score')
                )
                ->join('users as u', 'ss.student_id', '=', 'u.id')
                ->groupBy('ss.student_id', 'u.name')
                ->orderByDesc('total_score')->get();
        }

        // Check if the authenticated user's role is "mentor"
        if (Auth::user()->role != "mentor") {
            $leaderboardQuery->limit(5);
        }

        $topThree = $topThreeQuery;

        $leaderboard = array();
        if ($studentSectionCount > 0) {
            $leaderboard = $leaderboardQuery->get();
        }

        if (Auth::check() && Auth::user()->role == 'mentor') {
            $userId = Auth::user()->id;
            $user_name = Auth::user()->name;
            $blog = DB::select("select * from view_blog where user_id = $userId ");
            $classes = DB::select("select * from view_course where mentor_id = $userId");
            $classRegistered = DB::select("SELECT * from view_student_lesson where student_id=$userId");
            $projectCreatedCount = DB::table('view_project')
                ->where('user_id', $userId)
                ->count();

            $classRegisteredCount = DB::table('view_course')
                ->where('mentor_id', $userId)
                ->count();

            $studentCount = DB::table('view_student_lesson')
                ->where('mentor_name', $user_name)
                ->count();

            // Hitung jumlah student yang telah menyelesaikan setiap course
            $courseCompleteCount = DB::table('student_lesson')
                ->select('lesson_id', DB::raw('COUNT(*) AS completed_students'))
                ->where('learn_status', [0, 1])
                ->groupBy('lesson_id')
                ->get();

            // Hitung jumlah total student dalam setiap course
            $totalStudentsCount = DB::table('student_lesson')
                ->select('lesson_id', DB::raw('COUNT(*) AS total_students'))
                ->groupBy('lesson_id')
                ->get();

            // Inisialisasi variabel untuk menghitung jumlah kelas yang sedang dalam status "On Progress Course"
            $onProgressCount = 0;
            $completedCourseCount = 0;
            // Gabungkan kedua hasil perhitungan sebelumnya untuk menentukan apakah suatu course telah selesai
            $courseStatus = [];
            foreach ($courseCompleteCount as $course) {
                $lessonId = $course->lesson_id;
                $completedStudents = $course->completed_students;
                $totalStudents = $totalStudentsCount->where('lesson_id', $lessonId)->first()->total_students;

                // Simpan dalam variabel status masing-masing kelas
                $courseStatus[$lessonId] = ($completedStudents == $totalStudents) ? 'Completed Course' : 'On Progress Course';

                // Hitung berapa banyak kelas yang sedang dalam status on progress course
                if ($courseStatus[$lessonId] === 'On Progress Course') {
                    $onProgressCount++;
                }else{
                    $completedCourseCount++;
                }
            }

            // Retrieve all records of exams taken by students
            $takenExamCourseSection = ExamTaker::all();

            // Initialize an empty array to store unique exam sessions
            $takenExamId = [];
            $eligibleSessionId = [];

            // Loop through each taken exam
            foreach ($takenExamCourseSection as $mExamSection){

                // Find the exam session related to the taken exam
                $examSession = ExamSession::find($mExamSection->session_id);

                // If the exam session exists
                if($examSession!=null){

                    // Find the exam related to the session
                    $exam = Exam::find($examSession->exam_id);
                    // If the exam exists
                    if($exam!=null){
                        // Add the exam ID to the list of taken exam sessions
                        array_push($takenExamId, $exam->id);
                    }
                }
            }

            // Filter out duplicate exam sessions and convert the array to numerical indexed array
            $availableExamToFilter = (array_values(array_unique($takenExamId)));

            // Retrieve the latest three exams based on their IDs
            $latestExams = Exam::whereIn('id', $availableExamToFilter)->latest()->take(3)->get();

            // Initialize an empty array to store average scores
            $averageScoreArray = [];

            // Retrieve the average scores of exams for the latest three exam sessions
            $averageScores  = DB::table('exam_sessions')
                ->rightJoin('exam_takers', 'exam_sessions.id', '=', 'exam_takers.session_id')
                ->leftJoin('exams', 'exam_sessions.exam_id', '=', 'exams.id')
                ->select('exams.id', 'exams.title', DB::raw('ROUND(AVG(exam_takers.current_score)) as average_score'))
                ->where('exam_sessions.exam_type', 'Post Test')
                ->groupBy('exam_sessions.id')
                ->take(3)
                ->get();

            $arraySomething = [];



            $results = DB::select("
                        SELECT
                            sub.exam_title,
                            sub.exam_session_id,
                            sub.avg_score
                        FROM
                            (SELECT
                                e.title as exam_title,
                                es.id AS exam_session_id,
                                AVG(et.current_score) AS avg_score
                            FROM
                                exam_takers et
                            LEFT JOIN
                                exam_sessions es ON et.session_id = es.id
                            LEFT JOIN
                                course_section cs ON cs.id = et.course_section_flag
                            LEFT JOIN
                                exams e ON e.id = es.exam_id
                            LEFT JOIN
                                users u ON et.user_id = u.id
                            GROUP BY
                                es.id
                            ORDER BY
                                es.created_at DESC
                            LIMIT 3)
                        AS sub
                    ");

            $averageScoreArray = [];

            foreach ($results as $score) {
                $averageScoreArray[] = [
                    'exam_session_id' => $score->exam_session_id,
                    'title_exam' => $score->exam_title,
                    'average_score' => $score->avg_score
                ];
            }

            // return $averageScoreArray;

            $myStudent = DB::select("select * from view_student_lesson where mentor_name = '$user_name' ");


            $blogCreatedCount = DB::table('view_blog')
                ->where('user_id', $userId)
                ->count();

            if (!Auth::check()) {
                return Redirect::away('/'); // Replace '/login' with the URL of your login page
            }

            MyHelper::addAnalyticEvent(
                "Buka Dashboard Mentor", "Dashboard"
            );
            return view('main.dashboard')
                ->with(compact(
                    'classes',
                    'classRegistered',
                    'blog',
                    'blogCreatedCount',
                    'leaderboard',
                    'studentCount',
                    'myStudent',
                    'topThree',
                    'projectCreatedCount',
                    'classRegisteredCount',
                    'onProgressCount',
                    'completedCourseCount',
                    'latestExams',
                    'averageScoreArray'
                ));
        } 
        else if (Auth::check() && Auth::user()->role == 'student') {
            $userId = Auth::id();
            $blog = DB::select("select * from view_blog where user_id = $userId ");

            $userScores = DB::table('student_section')
                ->join('course_section', 'student_section.section_id', '=', 'course_section.id')
                ->select('course_section.section_title', DB::raw('COALESCE(student_section.score, 0) as score'))
                ->where('student_section.student_id', $userId)
                ->orderBy('course_section.section_order')
                ->get();


            $classes = DB::select("select * from view_course");
            $classRegistered = DB::select("SELECT * from view_student_lesson where student_id=$userId");
            $projectCreatedCount = DB::table('view_project')
                ->where('user_id', $userId)
                ->count();

            $classRegisteredCount = DB::table('view_student_lesson')
                ->where('student_id', $userId)
                ->count();

            $activeCourse = DB::table('student_lesson')
                ->leftJoin('lessons', 'student_lesson.lesson_id', '=', 'lessons.id')
                ->leftJoin('users', 'student_lesson.student_id', '=', 'users.id')
                ->select('student_lesson.student_id', 'users.name', 'student_lesson.lesson_id', 'student_lesson.learn_status', 'lessons.course_title')
                ->where('student_id', $userId)
                ->where('learn_status', 0)
                ->count();

            $completedCourse = DB::table('student_lesson')
                ->leftJoin('lessons', 'student_lesson.lesson_id', '=', 'lessons.id')
                ->leftJoin('users', 'student_lesson.student_id', '=', 'users.id')
                ->select('student_lesson.student_id', 'users.name', 'student_lesson.lesson_id', 'student_lesson.learn_status', 'lessons.course_title')
                ->where('student_id', $userId)
                ->where('learn_status', 1)
                ->count();

            $monthly_CompletedCourse = DB::table('student_lesson')
                ->leftJoin('lessons', 'student_lesson.lesson_id', '=', 'lessons.id')
                ->leftJoin('users', 'student_lesson.student_id', '=', 'users.id')
                ->select(DB::raw('YEAR(student_lesson.finished_at) as year, MONTH(student_lesson.finished_at) as month, COUNT(*) as completed_count'))
                ->where('student_lesson.student_id', $userId)
                ->where('student_lesson.learn_status', 1)
                ->groupBy(DB::raw('YEAR(student_lesson.finished_at), MONTH(student_lesson.finished_at)'))
                ->get();
                        
                $completedCourse_monthly = [];

                // Loop untuk mengisi data per bulan
                for ($i = 1; $i <= 12; $i++) {
                    $found = false;
                    foreach ($monthly_CompletedCourse as $course) {
                        if ($course->month == $i) {
                            // Simpan data per bulan sebagai objek
                            $completedCourse_monthly[$i] = (object) [
                                "year" => $course->year,
                                "month" => $course->month,
                                "completed_count" => $course->completed_count
                            ];
                            $found = true;
                            break;
                        }
                    }
                    // Jika tidak ada data untuk bulan tersebut, definisikan sebagai 0
                    if (!$found) {
                        $completedCourse_monthly[$i] = (object) [
                            "year" => date("Y"),
                            "month" => $i,
                            "completed_count" => 0
                        ];
                    }
                }
                
                // Sortir array berdasarkan bulan
                ksort($completedCourse_monthly);
                
                // Setelah data disiapkan, Anda dapat mengakses properti month dengan benar
                $jan = $completedCourse_monthly[1];
                $feb = $completedCourse_monthly[2];
                $mar = $completedCourse_monthly[3];
                $apr = $completedCourse_monthly[4];
                $mei = $completedCourse_monthly[5];
                $jun = $completedCourse_monthly[6];
                $jul = $completedCourse_monthly[7];
                $agt = $completedCourse_monthly[8];
                $sep = $completedCourse_monthly[9];
                $okt = $completedCourse_monthly[10];
                $nov = $completedCourse_monthly[11];
                $des = $completedCourse_monthly[12];
                
                
            
            $blogCreatedCount = DB::table('view_blog')
                ->where('user_id', $userId)
                ->count();


            $userID = Auth::id();
            $myClasses = DB::select("
                SELECT *,
                       'new_attribute_value' AS new_attribute  -- Add your new attribute here
                FROM (
                    SELECT
                        RANK() OVER (PARTITION BY a.id ORDER BY cs.id ASC) AS ranking,
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
                    LEFT JOIN
                        course_section cs ON a.id = cs.course_id
                    WHERE EXISTS (
                            SELECT 1
                            FROM student_lesson sl
                            WHERE sl.lesson_id = a.id
                            AND sl.student_id = $userID
                        )
                    GROUP BY
                        a.id, b.name, b.profile_url, cs.id
                ) AS main_table
                WHERE main_table.ranking = 1;
            ");

            // Append new attribute to each row
            foreach ($myClasses as &$class) {
                $firstSection = DB::table('course_section')
                    ->where('course_id', '=', $class->id)
                    ->orderByRaw("CAST(section_order AS UNSIGNED), section_order ASC")
                    ->first();
                // Tambahkan pengecekan untuk memastikan $firstSection tidak null
                if ($firstSection !== null) {
                    $class->first_section = $firstSection->id;
                } else {
                    // Handle jika $firstSection null
                    $class->first_section = null; // Atau nilai default lainnya sesuai kebutuhan
                }
            }

            $lessonCategories = DB::table('lesson_categories')->get()->keyBy('name');

            // return $myClasses;

            MyHelper::addAnalyticEvent(
                "Buka Dashboard Student", "Dashboard"
            );
            return view('main.dashboard')
                ->with(compact(
                    'classes',
                    'myClasses',
                    'userID',
                    'classRegistered',
                    'blog',
                    'userScores',
                    'topThree',
                    'leaderboard',
                    'blogCreatedCount',
                    'projectCreatedCount',
                    'classRegisteredCount',
                    'activeCourse',
                    'completedCourse', 
                    'lessonCategories',
                    'jan', 'feb', 'mar', 'apr', 'mei', 'jun',
                    'jul', 'agt', 'sep', 'okt', 'nov', 'des'
                ));
        }
    }
}
