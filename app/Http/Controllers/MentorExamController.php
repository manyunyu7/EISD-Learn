<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MentorExamController extends Controller
{
    public function viewCreateNew(){
        return view("exam.create_new_exam");
    }

    public function viewManageExam(Request $request){
        $dayta=Exam::where("created_by",'=',Auth::id())->get();
        $compact = compact('dayta');

        if($request->dump==true){
            return $compact;
        }
        return view("exam.manage_exam")->with($compact);
    }

    public function storeNewExam(Request $request){
        $exam = new Exam();
        $user_id  = Auth::id();
        $exam->title = $request->title;
        $exam->randomize = $request->randomize;
        $exam->can_access = $request->can_access;
        $exam->start_date = $request->startDate;
        $exam->end_date = $request->endDate;
        $exam->instruction = $request->instruction;
        $exam->description = $request->description;
        $exam->created_by = $user_id;

        if ($request->file('image') != "") {
            $image = $request->file('image');
            $name = $image->hashName();
            $image->storeAs('public/exam/cover/', $name);
            $exam->image = $name;
        }

        if ($exam->save()) {
            //redirect dengan pesan sukses
            return redirect('exam/manage')->with(['success' => 'Berhasil Menyimpan Exam, Tambah soal di detail exam!']);
        } else {
            //redirect dengan pesan error
            return redirect('exam/new')->with(['error' => 'Gagal Menyimpan Exam']);
        }
    }
}
