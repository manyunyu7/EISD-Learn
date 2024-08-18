<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamQuestionAnswers;
use App\Models\ExamSession;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MentorExamController extends Controller
{
    public function viewCreateNew()
    {
        return view("exam.create_new_exam");
    }

    public function viewEditExam(Request $request,$id)
    {
        $data = Exam::findOrFail($id);
        $exam=$data;
        $compact = compact('data','exam');
        if($request->dump==true){
            return $compact;
        }
        return view("exam.edit_exam")->with($compact);
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
    public function viewEditQuestion_v2(Request $request, $id)
    {
        $question = ExamQuestionAnswers::findOrFail($id);
        $exam = Exam::findOrFail($question->exam_id);
        $choices = json_decode($question->choices, true);
        $showCompact = true;
        $compact = compact('question', 'exam', 'showCompact', 'choices', 'id');
        if ($request->dump == true) {
            return $compact;
        }
        return view("exam.edit_question_v2")->with($compact);
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
        if ($request->questionType != "essay") {
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

    public function storeQuestion_v2(Request $request)
    {

        $validatedData = $request->validate([
                            //            'question' => 'required',
                            //            'correct_answer' => 'required',
                            //            'choices' => 'required|json', // Validate that 'choices' is a valid JSON string
                                        // Add other validation rules as needed
        ]);



        // Create a new ExamQuestionAnswer instance and fill it with the validated data
        $questionAnswer = new ExamQuestionAnswers();

        $questionAnswer->question = $request->question;
        if ($request->file('question_images') != "") {
            $image = $request->file('question_images');
            $name = $image->hashName();
            $image->storeAs('public/exam/question/', $name);
            $questionAnswer->image = $name;
        }
        $questionAnswer->question_type = $request->type_questions;
        $questionAnswer->correct_answer = null;

        // Cari entri terakhir untuk exam_id yang diberikan
        $lastOrder = ExamQuestionAnswers::where('exam_id', $request->exam_id)
        ->orderBy('order', 'desc')
        ->value('order');
        $questionAnswer->order = $lastOrder === null ? 1 : $lastOrder + 1;

        $choices = [];
        // Iterasi melalui request untuk mengambil nilai pilihan jawaban dan skornya
        for ($i = 1; $i <= 4; $i++) {
            // Cek apakah input dengan nama "stm_$i" dan "scr_$i" ada dalam request
            if ($request->has("stm_$i") && $request->has("scr_$i")) {
                // Tambahkan data pilihan jawaban dan skor ke dalam array
                $choices[] = [
                    'text' => $request->input("stm_$i"),
                    'score' => $request->input("scr_$i"),
                ];
            }
        }

        // Konversi array pilihan jawaban menjadi JSON
        $questionAnswer->choices    = json_encode($choices);
        $questionAnswer->exam_id    = $request->exam_id;
        $questionAnswer->created_by = auth()->user()->id;

        $formattedDate = Carbon::now()->format('Y-m-d H:i:s');
        $questionAnswer->created_at = $formattedDate;
        $questionAnswer->updated_at = $formattedDate;



        $compact = compact('questionAnswer');
        // Save the question
        if ($questionAnswer->save()){
            // Fetch sessions and question answers
            $sessions = ExamSession::where("exam_id", "=", $request->exam_id)->get();
            $questionsAnswers = ExamQuestionAnswers::where("exam_id", "=", $request->exam_id)->get();

            // Loop through each session
            foreach ($sessions as $session) {
                // Filter question answers for this session
                $sessionQuestionAnswers = $questionsAnswers->where('session_id', $session->id);

                $session->questions_answers = $questionsAnswers;
                $session->save();
            }

            return back()->with($compact)->with('success', 'Berhasil Menambah Soal!');
        }
        else{
            return back()>with($compact)->with(['error' => 'Gagal Menambah Soal !']);

        }
        // $examId = $request->exam_id;
        // $compact = compact('examId');
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
        if ($request->questionType != "essay") {
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
    public function updateQuestion_v2(Request $request)
    {
        // Create a new ExamQuestionAnswer instance and fill it with the validated data
        $questionAnswer = ExamQuestionAnswers::findOrFail($request->question_id);
        $questionAnswer->exam_id = $request->exam_id;
        $questionAnswer->question_type = $request->type_questions;
        $questionAnswer->question = $request->question;
        $questionAnswer->created_by = auth()->user()->id;

        if ($request->file('question_images') != "") {
            $image = $request->file('question_images');
            $name = $image->hashName();
            $image->storeAs('public/exam/question/', $name);
            $questionAnswer->image = $name;
        }

        $choices = [];
        // Iterasi melalui request untuk mengambil nilai pilihan jawaban dan skornya
        for ($i = 1; $i <= 4; $i++) {
            // Cek apakah input dengan nama "stm_$i" dan "scr_$i" ada dalam request
            if ($request->has("stm_$i") && $request->has("scr_$i")) {
                // Tambahkan data pilihan jawaban dan skor ke dalam array
                $choices[] = [
                    'text' => $request->input("stm_$i"),
                    'score' => $request->input("scr_$i"),
                ];
            }
        }

        // Konversi array pilihan jawaban menjadi JSON
        $questionAnswer->choices    = json_encode($choices);
        $questionAnswer->correct_answer = null;


        $compact = compact('questionAnswer');
        // Save the question
        if ($questionAnswer->save()) {

            // Fetch sessions and question answers
            $sessions = ExamSession::where("exam_id", "=", $request->exam_id)->get();
            $questionsAnswers = ExamQuestionAnswers::where("exam_id", "=", $request->exam_id)->get();

            // Loop through each session
            foreach ($sessions as $session) {
                // Filter question answers for this session
                $sessionQuestionAnswers = $questionsAnswers->where('session_id', $session->id);

                $session->questions_answers = $questionsAnswers;
                $session->save();
            }

            return redirect()->back()->with('success', 'Berhasil Update Soal!');
        } else {
            return redirect()->back()->with('error', 'Gagal Update Soal!');
        }
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

    public function deleteQuestion($id)
    {
        try {
            $question = ExamQuestionAnswers::findOrFail($id);
            // Delete the question
            $question->delete();
            return back()->with('success', 'Berhasil Menghapus Soal!');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Gagal Menghapus Soal !');
        }
    }


    // MANAGE EXAM
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
    public function viewManageExam_v2(Request $request)
    {
        $timezone = config('app.timezone'); // Misalnya 'Asia/Jakarta'
        $currentDateTime = Carbon::now($timezone)->toDateTimeString();
        $dayta = DB::table('exams as e')
                ->select(
                    'e.*',
                    'es.exam_type',
                    'es.start_date',
                    'es.end_date',
                    'cs.quiz_session_id as examSessionID_onCourseSection',
                    DB::raw('CASE
                                WHEN cs.quiz_session_id = es.id THEN "Exam Used"
                                ELSE "Exam Not Used"
                            END as is_examUsed'),
                    DB::raw('CASE
                                WHEN es.start_date > "' . $currentDateTime . '" THEN "Waiting to Start"
                                WHEN es.start_date <= "' . $currentDateTime . '" AND es.end_date >= "' . $currentDateTime . '" THEN "Ongoing"
                                ELSE "Finish"
                            END as status')
                )
                ->leftJoin('exam_sessions as es', 'e.id', '=', 'es.exam_id')
                ->leftJoin('course_section as cs', 'es.id', '=', 'cs.quiz_session_id')
                ->where("e.created_by", Auth::id())
                ->where(function ($query) {
                    $query->where("is_deleted", "<>", "y")
                        ->orWhereNull("is_deleted");
                })
                ->get();

        $compact = compact('dayta');

        if ($request->dump == true) {
            return $compact;
        }
        return view("exam.manage_exam_versi_2")->with($compact);
    }

    public function viewCreateExam_v2(Request $request)
    {
        $dayta =Exam::where("created_by",Auth::id())
        ->where(function ($query) {
                $query->where("is_deleted","<>","y")
                    ->orWhereNull("is_deleted");
            })
        ->get();
        $compact = compact('dayta');

        if ($request->dump == true) {
            return $compact;
        }
        // dd($dayta);
        return view("exam.create_exam_versi_2")->with($compact);
    }
    public function viewCreateQuest_v2(Request $request, $examId)
    {
        $dayta = DB::table('exams as e')->select(
            'e.*',
            'es.start_date',
            'es.end_date',
            DB::raw('CASE
                        WHEN es.start_date > NOW() AND es.end_date <= NOW() THEN "Waiting to Start"
                        WHEN es.start_date <= NOW() AND es.end_date >= NOW() THEN "Ongoing"
                        ELSE "Finish" END as status'),
            DB::raw('CASE WHEN et.current_score IS NOT NULL THEN "Scored" ELSE "Not Scored" END as score_status'))

        ->leftJoin('exam_sessions as es', 'e.id', '=', 'es.exam_id')
        ->leftJoin('exam_takers as et', 'es.id', '=', 'et.session_id')
        ->where("e.created_by",Auth::id())
        ->where(function ($query) {
            $query->where("is_deleted","<>","y")
                ->orWhereNull("is_deleted");
        })
        ->get();

        // return $dayta;
        $examSession_data = ExamSession::where("exam_id", $examId)->get();
        $questionAnswer = ExamQuestionAnswers::all();
        $compact = compact('dayta', 'examId', 'questionAnswer', 'examSession_data');

        // return $examSession_data;
        if ($request->dump == true) {
            return $compact;
        }
        return view("exam.create_exam_v2_next_step")->with($compact);
    }

    public function viewEditQuest_v2(Request $request, $examId)
    {

        $timezone = config('app.timezone'); // Misalnya 'Asia/Jakarta'
        $currentDateTime = Carbon::now($timezone)->toDateTimeString();
        $data_examSession = DB::table('exam_sessions as es')->select(
                                'es.*',
                                DB::raw('CASE
                                            WHEN es.start_date > "' . $currentDateTime . '" THEN "Waiting to Start"
                                            WHEN es.start_date <= "' . $currentDateTime . '" AND es.end_date >= "' . $currentDateTime . '" THEN "Ongoing"
                                            ELSE "Finish"
                                        END as status'),
                                DB::raw('CASE WHEN et.current_score IS NOT NULL THEN "Scored" ELSE "Not Scored" END as score_status')
                            )
                            ->where("es.exam_id", $examId)
                            ->leftJoin("exam_takers as et","es.id","=","et.session_id")
                            ->first();

        $status_exam =  $data_examSession->status;
        $examScore_status=  $data_examSession->score_status;

        $questionAnswer = ExamQuestionAnswers::all();
        // return $status_exam;


        $compact = compact('examId', 'questionAnswer', 'data_examSession', 'status_exam', 'examScore_status');

        if ($request->dump == true) {
            return $compact;
        }
        return view("exam.edit_exam_v2")->with($compact);
    }
    public function downloadExam($examId){
        $data_exam = DB::select("
                        SELECT
                            e.id as exam_id,
                            e.title as exam_title,
                            l.course_title as course,
                            l.id as course_id,
                            et.current_score as exam_score,
                            u.name as takers_name,
                            es.exam_type as type,
                            cs.section_title as course_section_title,
                            es.id as exam_session_id,
                            cs.id as course_section_id
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
                        LEFT JOIN
                            lessons l ON l.id = cs.course_id
                        WHERE
                            e.id = $examId
                    ");

        // return $data_exam;
        return view("exam.download_exam_pages", compact('data_exam'));
    }

    public function updateExam(Request $request)
    {
        $exam = Exam::findOrFail($request->id);
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
            Storage::disk('local')->delete('public/exam/cover/' . $exam->image);
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

    public function storeNewExam(Request $request)
    {
        $user_id = Auth::id();


        // Insert to Table Exam
        $exam = new Exam();
        $exam->title = $request->title;
        if ($request->file('image') != "") {
            $image = $request->file('image');
            $name = $image->hashName();
            $image->storeAs('public/exam/cover/', $name);
            $exam->image = $name;
        }
        $exam->start_date = $request->startDate;
        $exam->end_date = $request->endDate;
        $exam->instruction = $request->instruction;
        $exam->description = $request->description;
        $exam->randomize = $request->randomize;
        $exam->can_access = $request->can_access;
        $exam->created_by = $user_id;


        // SAVE INPUT
        if ($exam->save()) {
            //redirect dengan pesan sukses

            $examId = $exam->id;
            $user_id = Auth::id();

            // Insert to Table Exam Session
            $examSession = new ExamSession();
            $examSession->start_date = $request->start_date;
            $examSession->end_date = $request->end_date;
            $examSession->instruction = $request->instruction;
            $examSession->description = 'n/a';
            $examSession->can_access = 'n/a';
            $examSession->time_limit_minute = $request->times_limit;

            //translate user input to y and n for positive and negative case
            $mapping = [
                'Aktif' => 'y',
                'Tidak Aktif' => 'n',
            ];

            $examSession->public_access = $mapping[$request->public_access] ?? '';
            $examSession->allow_review = $mapping[$request->allow_review] ?? '';
            $examSession->show_result_on_end = $mapping[$request->show_result_on_end] ?? '';
            $examSession->allow_multiple = $mapping[$request->allow_multiple] ?? '';

            $examSession->show_score_on_review = 'y/n';

            $examSession->exam_id = $exam->id;
            $examSession->created_by = $user_id;
            $examSession->questions_answers = null;
            $examSession->exam_type = $request->exam_type;


            // Save to Table
            // dd($examSession);
            $examSession->save();


            // return redirect('exam/manage')->with(['success' => 'Berhasil Menyimpan Exam, Tambah soal di detail exam!']);
            return redirect(url("/exam/manage-exam-v2/$examId/create-question"))->with(['success' => 'Berhasil Menyimpan Exam, Tambah soal di detail exam!']);

        } else {
            //redirect dengan pesan error
            return redirect('exam/new')->with(['error' => 'Gagal Menyimpan Exam']);
        }
    }

}
