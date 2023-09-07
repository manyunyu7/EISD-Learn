<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamQuestionAnswers;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MentorExamController extends Controller
{
    public function viewCreateNew()
    {
        return view("exam.create_new_exam");
    }


    public function deleteExam(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $exam->is_deleted = "y";
        $exam->save();

        return back()->with(['success' => 'Berhasil Menghapus Exam !']);
        return response()->json(["message" => "success"]);
    }

    public function viewManageQuestion(Request $request, $id)
    {
        $dayta = Exam::where("created_by", '=', Auth::id())->get();
        $exam = Exam::findOrFail($id);
        $compact = compact('dayta', 'exam');

        if ($request->dump == true) {
            return $compact;
        }
        return view("exam.manage_question_answer")->with($compact);
    }

    public function viewEditQuestion(Request $request, $id)
    {
        $question = ExamQuestionAnswers::findOrFail($id);
        $exam = Exam::findOrFail($question->exam_id);
        $choices = json_decode($question->choices, true);
        $showCompact = true;
        $compact = compact('question', 'exam', 'showCompact', 'choices');
        if ($request->dump == true) {
            return $compact;
        }
        return view("exam.edit_question")->with($compact);
    }

    public function viewManageQuestionOrder(Request $request, $id)
    {
        $dayta = Exam::where("created_by", '=', Auth::id())->get();
        $exam = Exam::findOrFail($id);
        $compact = compact('dayta', 'exam');

        if ($request->dump == true) {
            return $compact;
        }
        return view("exam.manage_question_answer_order")->with($compact);
    }

    public function fetchQuestions(Request $request)
    {
        $id = $request->id;
        $questions = ExamQuestionAnswers::orderBy('order', 'asc')->where(
            "exam_id", "=", $id
        )->get();

        return response()->json($questions);
    }

    public function storeQuestion(Request $request)
    {

        $validatedData = $request->validate([
//            'question' => 'required',
//            'correct_answer' => 'required',
//            'choices' => 'required|json', // Validate that 'choices' is a valid JSON string
            // Add other validation rules as needed
        ]);

        // Create a new ExamQuestionAnswer instance and fill it with the validated data
        $questionAnswer = new ExamQuestionAnswers();
        $questionAnswer->exam_id = $request->exam_id;
        $questionAnswer->question_type = $request->questionType;
        $questionAnswer->question = $request->title;
        $questionAnswer->created_by = auth()->user()->id;

        if ($request->file('image') != "") {
            $image = $request->file('image');
            $name = $image->hashName();
            $image->storeAs('public/exam/question/', $name);
            $questionAnswer->image = $name;
        }

        // Handle different question types
        if ($request->questionType === 'multiple_choice') {
            // Create an array to hold choices and scores
            $choices = [];
            foreach ($request->choice as $index => $choiceText) {
                $choices[] = [
                    'text' => $choiceText,
                    'score' => $request->score[$index],
                ];
            }

            // Store the choices as JSON in the 'choices' column
            $questionAnswer->choices = json_encode($choices);
        } elseif ($request->questionType === 'essay') {
            $questionAnswer->correct_answer = $request->correct_answer;
        }

        // Save the question
        $questionAnswer->save();


        return response()->json(['message' => 'Question stored successfully'], 200);
    }

    public function updateQuestion(Request $request)
    {

        $validatedData = $request->validate([
//            'question' => 'required',
//            'correct_answer' => 'required',
//            'choices' => 'required|json', // Validate that 'choices' is a valid JSON string
            // Add other validation rules as needed
        ]);

        // Create a new ExamQuestionAnswer instance and fill it with the validated data
        $questionAnswer = ExamQuestionAnswers::findOrFail($request->question_id);
        $questionAnswer->exam_id = $request->exam_id;
        $questionAnswer->question_type = $request->questionType;
        $questionAnswer->question = $request->title;
        $questionAnswer->created_by = auth()->user()->id;

        if ($request->file('image') != "") {
            $image = $request->file('image');

            // Check if an old image exists
            if (!empty($questionAnswer->image) && Storage::exists('public/exam/question/' . $questionAnswer->image)) {
                // Delete the old image
                Storage::delete('public/exam/question/' . $questionAnswer->image);
            }

            $name = $image->hashName();

            // Store the new image
            $image->storeAs('public/exam/question/', $name);
            $questionAnswer->image = $name;
        }

        // Handle different question types
        if ($request->questionType === 'multiple_choice') {
            // Create an array to hold choices and scores
            $choices = [];
            foreach ($request->choice as $index => $choiceText) {
                $choices[] = [
                    'text' => $choiceText,
                    'score' => $request->score[$index],
                ];
            }

            // Store the choices as JSON in the 'choices' column
            $questionAnswer->choices = json_encode($choices);
        } elseif ($request->questionType === 'essay') {
            $questionAnswer->correct_answer = $request->correct_answer;
        }

        // Save the question
        $questionAnswer->save();


        return response()->json(['message' => 'Question stored successfully'], 200);
    }


    public function updateQuestionOrder(Request $request)
    {
        $newOrder = $request->input('newOrder');

        // Loop through the new order and update the 'order' field in the database
        foreach ($newOrder as $index => $questionId) {
            ExamQuestionAnswers::where('id', $questionId)->update(['order' => $index + 1]);
        }

        // You can return a success response if needed
        return response()->json(['message' => 'Question order updated successfully']);
    }


    public function viewManageExam(Request $request)
    {
        $dayta = Exam::where("created_by", '=', Auth::id())
            ->where("is_deleted", "<>", "y")
            ->orWhereNull("is_deleted")
            ->get();
        $compact = compact('dayta');

        if ($request->dump == true) {
            return $compact;
        }
        return view("exam.manage_exam")->with($compact);
    }

    public function storeNewExam(Request $request)
    {
        $exam = new Exam();
        $user_id = Auth::id();
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
