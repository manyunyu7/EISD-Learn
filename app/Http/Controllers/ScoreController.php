<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScoreController extends Controller
{
    public function seeByStudent(Request $request,$id){
        $student = User::findOrFail($id);
        $scores = DB::table('course_section')
            ->select('course_section.section_title', DB::raw('COALESCE(student_section.score, 0) as score'), 'users.name','users.profile_url')
            ->join('student_section', 'course_section.id', '=', 'student_section.section_id')
            ->join('users', 'student_section.student_id', '=', 'users.id')
            ->where('student_section.student_id', $id)
            ->orderBy('course_section.section_order')
            ->get();


        $totalScore = DB::table('student_section')
            ->where('student_id', $id)
            ->sum('score');

        $compact = compact('student', 'scores', 'totalScore');

        if($request->dump==true){
            return $compact;
        }
        return view('lessons.score.see_score_by_student')->with($compact);
    }
}
