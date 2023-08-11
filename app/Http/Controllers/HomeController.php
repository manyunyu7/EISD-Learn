<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Redirect;

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
                'u.name as student_name', 'u.profile_url',
                DB::raw('SUM(ss.score) as total_score'),
                'avg_subquery.average_score as average_score',
                DB::raw('(SELECT MAX(score) FROM student_section WHERE student_id = u.id) as highest_score'),
                DB::raw('(SELECT MIN(score) FROM student_section WHERE student_id = u.id) as lowest_score')
            )
            ->join('users as u', 'ss.student_id', '=', 'u.id')
            ->joinSub($averageScoreSubquery, 'avg_subquery', 'u.id', '=', 'avg_subquery.student_id')
            ->groupBy('ss.student_id', 'u.name', 'avg_subquery.average_score')
            ->orderByDesc('total_score');

        $topThreeQuery =  DB::table(DB::raw('student_section ss'))
            ->select(
                'u.name as student_name', 'u.profile_url',
                DB::raw('SUM(ss.score) as total_score'),
                DB::raw('(SELECT MAX(score) FROM student_section WHERE student_id = u.id) as lowest_score'),
                DB::raw('(SELECT MIN(score) FROM student_section WHERE student_id = u.id) as highest_score')
            )
            ->join('users as u', 'ss.student_id', '=', 'u.id')
            ->groupBy('ss.student_id', 'u.name')
            ->orderByDesc('total_score')->get();

        $topThree = $topThreeQuery;
        // Check if the authenticated user's role is "mentor"
        if (Auth::user()->role != "mentor") {
            $leaderboardQuery->limit(5);
        }

        $leaderboard = $leaderboardQuery->get();
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

            // $myStudent = DB::table('view_student_lesson')
            //   ->where('mentor_id', $user_id)
            //   ->get();

            $myStudent = DB::select("select * from view_student_lesson where mentor_name = '$user_name' ");


            $blogCreatedCount = DB::table('view_blog')
                ->where('user_id', $userId)
                ->count();

            if (!Auth::check()) {
                return Redirect::away('/'); // Replace '/login' with the URL of your login page
            }

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
                    'classRegisteredCount'
                ));
        } else if (Auth::check() && Auth::user()->role == 'student') {
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

            $blogCreatedCount = DB::table('view_blog')
                ->where('user_id', $userId)
                ->count();


            return view('main.dashboard')
                ->with(compact(
                    'classes',
                    'classRegistered',
                    'blog',
                    'userScores',
                    'topThree',
                    'leaderboard',
                    'blogCreatedCount',
                    'projectCreatedCount',
                    'classRegisteredCount'
                ));
        }
    }
}
