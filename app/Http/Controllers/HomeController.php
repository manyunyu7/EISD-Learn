<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

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

    if (Auth::check() && Auth::user()->role == 'mentor') {
      $user_id  = Auth::user()->id;
      $user_name  = Auth::user()->name;
      $blog = DB::select("select * from view_blog where user_id = $user_id ");
      $classes = DB::select("select * from view_course where mentor_id = $user_id");
      $classRegistered = DB::select("SELECT * from view_student_lesson where student_id=$user_id");
      $projectCreatedCount = DB::table('view_project')
        ->where('user_id', $user_id)
        ->count();

      $classRegisteredCount = DB::table('view_course')
        ->where('mentor_id', $user_id)
        ->count();

      $studentCount = DB::table('view_student_lesson')
      ->where('mentor_name', $user_name)
      ->count();

      // $myStudent = DB::table('view_student_lesson')
      //   ->where('mentor_id', $user_id)
      //   ->get();

      $myStudent = DB::select("select * from view_student_lesson where mentor_name = '$user_name' ");


      $blogCreatedCount = DB::table('view_blog')
        ->where('user_id', $user_id)
        ->count();

      return view('main.dashboard')
        ->with(compact(
          'classes',
          'classRegistered',
          'blog',
          'blogCreatedCount',
          'studentCount',
          'myStudent',
          'projectCreatedCount',
          'classRegisteredCount'
        ));
    } else if (Auth::check() && Auth::user()->role == 'student') {
      $user_id  = Auth::id();
      $blog = DB::select("select * from view_blog where user_id = $user_id ");
      $classes = DB::select("select * from view_course");
      $classRegistered = DB::select("SELECT * from view_student_lesson where student_id=$user_id");
      $projectCreatedCount = DB::table('view_project')
        ->where('user_id', $user_id)
        ->count();

      $classRegisteredCount = DB::table('view_student_lesson')
        ->where('student_id', $user_id)
        ->count();

      $blogCreatedCount = DB::table('view_blog')
        ->where('user_id', $user_id)
        ->count();

      return view('main.dashboard')
        ->with(compact(
          'classes',
          'classRegistered',
          'blog',
          'blogCreatedCount',
          'projectCreatedCount',
          'classRegisteredCount'
        ));
    }
  }
}
