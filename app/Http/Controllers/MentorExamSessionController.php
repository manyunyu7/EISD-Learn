<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamQuestionAnswers;
use App\Models\ExamSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentorExamSessionController extends Controller
{

    public function viewDetailSession(Request $request,$id){

        $data = ExamSession::findOrFail($id);
        $exam = Exam::findOrFail($data->exam_id);

        $students = User::where("role","=","student")->get();
        $examId = $exam->id;
        $examId = strval($examId);
        $showCompact=true;
        $compact = compact('showCompact','data','exam',"students",'examId');
        if($request->dump==true){
            return $compact;
        }
        return view("exam.session.session_detail")->with($compact);
    }

    public function fetchQuestions($id)
    {
        $exam = ExamSession::findOrFail($id);
        $questions = json_decode($exam->questions_answers);

        return response()->json($questions);
    }

    public function viewManageSession(Request $request,$id){
        $dayta= ExamSession::where('created_by', '=', Auth::id())
            ->where('exam_id', '=', $id)
            ->where(function ($query) {
                $query->whereNull('is_deleted')
                    ->orWhere('is_deleted', '<>', 'y');
            })
            ->get();
        $exam = Exam::findOrFail($id);

        $students = User::where("role","=","student")->get();
        $examId = $exam->id;
        $examId = strval($examId);
        $compact = compact('dayta','exam',"students",'examId');
        if($request->dump==true){
            return $compact;
        }
        return view("exam.manage_exam_session")->with($compact);
    }

    public function getExamSession(Request $request){
        $id = $request->id;
        $examSessions = ExamSession::where('created_by', '=', Auth::id())
            ->where('exam_id', '=', $id)
            ->where(function ($query) {
                $query->whereNull('is_deleted')
                    ->orWhere('is_deleted', '<>', 'y');
            })
            ->get();

        return $examSessions;
    }


    public function getSessionData(Request $request,$id){
        $session = ExamSession::findOrFail($id);
        return $session;
    }

    public function storeSession(Request $request){
        $examSession = new ExamSession();
        $examSession->start_date = $request->start_date;
        $examSession->end_date = $request->end_date;
        $examSession->instruction = $request->instruction ?? '';
        $examSession->description = $request->description ?? '';
        $examSession->can_access = $request->can_access;
        $examSession->public_access = $request->public_access;
        $examSession->show_result_on_end = $request->show_result_on_end;
        $examSession->title = $request->title;
        $examSession->allow_review = $request->allow_review;
        $examSession->show_score_on_review = $request->show_score_on_review;
        $examSession->time_limit_minute = $request->time_limit;
        $examSession->allow_multiple = $request->allow_multiple;
        $examSession->exam_id = $request->exam_id;
        $examSession->created_by = Auth::id();
        $examSession->is_deleted = "n";

        $questionsAnswers = ExamQuestionAnswers::where("exam_id","=",$examSession->exam_id)->get();
        $examSession->questions_answers=$questionsAnswers;

        if($examSession->save()){
            return response()->json(["message"=>"success"],200);
        }else{
            return response()->json(["message"=>"failed"],500);
        }
    }

    public function updateSessionData(Request $request){
        $examSession = ExamSession::findOrFail($request->id);
        $examSession->start_date = $request->start_date;
        $examSession->end_date = $request->end_date;
        $examSession->instruction = $request->instruction ?? '';
        $examSession->description = $request->description ?? '';
        $examSession->can_access = $request->can_access;
        $examSession->title = $request->title;
        $examSession->public_access = $request->public_access;
        $examSession->show_result_on_end = $request->show_result_on_end;
        $examSession->allow_review = $request->allow_review;
        $examSession->show_score_on_review = $request->show_score_on_review;
        $examSession->time_limit_minute = $request->time_limit;
        $examSession->allow_multiple = $request->allow_multiple;

        //exam question should be the same with existing
        //        $questionsAnswers = ExamQuestionAnswers::where("exam_id","=",$examSession->exam_id)->get();
        //        $examSession->questions_answers=$questionsAnswers;

        if($examSession->save()){
            return response()->json(["message"=>"success"],200);
        }else{
            return response()->json(["message"=>"failed"],500);
        }
    }

    public function destroyExamSession(Request $request){
        $examSession = ExamSession::findOrFail($request->id);
        $examSession->is_deleted="y";
        if($examSession->save()){
            return response()->json(["message"=>"success"],200);
        }else{
            return response()->json(["message"=>"failed"],500);
        }
    }

}
