<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\ExamTaker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamTakerController extends Controller
{
    public function viewInitialTakeSession(Request $request, $id)
    {

        $session = ExamSession::findOrFail($id);
        $exam = Exam::findOrFail($session->exam_id);
        $data = $session;
        $showCompact = true;
        MyHelper::addAnalyticEvent(
            "Take Quiz on exam " . $exam->id . "-" . $exam->title, "Exam Taker"
        );
        $questions = json_decode($session->questions_answers);
        $totalScore = 0;

        foreach ($questions as $question) {
            if (isset($question->choices)) {
                $choices = json_decode($question->choices, true);

                foreach ($choices as $choice) {
                    if (isset($choice['score']) && $choice['score'] !== null && $choice['score'] >= 0) {
                        $totalScore += (int)$choice['score'];
                    }
                }
            }
        }

        $question_count = count($questions);
        $compact = compact('session', 'totalScore', 'exam', 'data', 'showCompact', 'questions', 'question_count');
        if ($request->dump == true) return $compact;

        return view("exam.student.take_exam")->with($compact);
    }

    public function fetchQuestions($id)
    {
        $exam = ExamSession::findOrFail($id);
        $questions = json_decode($exam->questions_answers);

        // Loop through the questions and move the "score" to each question
        $totalScore = 0;
        foreach ($questions as &$question) {
            $choices = json_decode($question->choices, true);

            // Calculate the total score for this question
            $questionScore = 0;

            foreach ($choices as $choice) {
                if (isset($choice['score'])) {
                    $questionScore += intval($choice['score']);
                    unset($choice['score']); // Remove score from the choice
                }
            }

            // Add the total score to the question
            $question->total_score = $questionScore;
            $totalScore += $questionScore;

            // Convert the modified choices back to JSON
            $question->choices = json_encode($choices);
        }

        // Add the total score for the exam
        $exam->total_score = $totalScore;

        return response()->json($questions);
        return response()->json(['exam' => $exam, 'questions' => $questions]);
    }

    public function fetchResultByStudentOnSection(Request $request){
        $courseId = $request->course_id;
        $sectionId = $request->section_id;

        $examResults = ExamTaker::where(
            "course_flag", "=", $courseId
        )->where(
            "course_section_flag", "=", $sectionId
        )->where(
            "user_id", '=', Auth::id()
        )->get();


        if (count($examResults) > 0) {
            $hasTakenAnyExam = true;
        }
    }

    public function submitQuiz(Request $request)
    {
        // Decode the JSON payload
        $payload = $request->userAnswers;
        // Extract relevant data from the payload
        $examId = $payload['examId'];
        $sessionId = $payload['sessionId'];
        $answers = $payload['answers'];
        $name = $request->fullName;
        $courseId = $request->courseId;
        $sectionId = $request->sectionId;

        $examTokenKey = 'exam_token_' . $examId;

        if (!session()->has($examTokenKey)) {
            session([$examTokenKey => Str::uuid()->toString()]);
        }
        $examToken = session($examTokenKey);

        // Load the exam session
        $session = ExamSession::findOrFail($sessionId);

        // Initialize a variable to store the user's score
        $userScore = 0;
        $originalQuestions = json_decode($session->questions_answers);

        // Loop through the user's answers
        foreach ($answers as &$answer) { // Note the "&" to make $answer mutable
            // Find the corresponding question in the original questions array by ID
            $question = collect($originalQuestions)->firstWhere('id', $answer['id']);

            // Ensure the question exists
            if ($question) {
                // Decode the choices from the question
                $choices = json_decode($question->choices, true);

                // Variable to store the correct answer and its score
                $correctAnswer = null;
                $correctScore = 0;

                // Loop through the choices to find the correct answer and its score
                foreach ($choices as $choice) {
                    if ($choice['score'] > $correctScore) {
                        $correctAnswer = $choice['text'];
                        $correctScore = $choice['score'];
                    }
                }

                // Variable to store descriptive score
                $descriptiveScore = ($correctAnswer === implode(", ", $answer['values'])) ? 'correct' : 'incorrect';

                // Loop through the user's selected choices
                foreach ($answer['values'] as $userChoice) {
                    // Find the selected choice in the question's choices
                    $selectedChoice = collect($choices)->firstWhere('text', $userChoice);

                    // If the selected choice exists and has a score, add it to the user's score
                    if ($selectedChoice && isset($selectedChoice['score'])) {
                        // Check the marker to determine if it's multiple or single select
                        if ($answer['isMultipleSelect']) {
                            // For multiple select, add the score for each selected choice
                            $userScore += intval($selectedChoice['score']);
                        } else {
                            // For single select, add the score only once
                            $userScore += intval($selectedChoice['score']);
                            // Break the inner loop since single select questions should have only one correct answer
                            break;
                        }
                    }
                }
                // Add the correct answer and its score to the answer
                $answer['correct_answer'] = $correctAnswer;
                $answer['correct_score'] = $correctScore;
                $answer['question_text'] = $question->question;
                // Add descriptive score
                $answer['isCorrect'] = $descriptiveScore;
            }
        }


        //check if the session allow multiple attempt
        $allowMultipleAttempt = false;
        if ($session->allow_multiple == "y" || $session->allow_multiple=="Aktif") {
            $allowMultipleAttempt = true;
        }

        $allowPublicAccess = false;
        if ($session->public_access == "y"|| $session->public_access == "Aktif") {
            $allowPublicAccess = true;
        }


        //if the session is already deleted;
        if ($session->is_deleted == "y") {
            return response()->json([
                "scores" => 0,
                "message" => "Quiz sudah tidak tersedia",
                "showError" => true
            ], 200);
        }

        if (Auth::user() == null && !$allowPublicAccess)
            return response()->json([
                "scores" => 0,
                "message" => "Anda harus login untuk mengerjakan quiz ini",
                "showError" => true
            ], 200);


        if ($session->can_access == "n") {
            return response()->json([
                "scores" => 0,
                "message" => "Quiz sedang tidak tersedia, hubungi Dept Training untuk memulai quiz",
                "showError" => true
            ], 200);
        }

        //check if user already have finished attempt
        $finishedAttemptCount = ExamTaker::where('user_id', '=', Auth::id())
            ->where('session_id', '=', $sessionId)
            ->where(function ($query) {
                $query->whereNotNull('finished_at');
            })
            ->count();

        //check if user take exam from course
        //if positive then we change the query to also check the session.
        if ($courseId != null && $sectionId != null) {
            $finishedAttemptCount = ExamTaker::where('user_id', '=', Auth::id())
                ->where('session_id', '=', $sessionId)
                ->where('course_section_flag', '=', $sectionId)
                ->where(function ($query) {
                    $query->whereNotNull('finished_at');
                })
                ->count();
        }

        if (!$allowMultipleAttempt) {
            if ($finishedAttemptCount > 0) {
                return response()->json([
                    "scores" => 0,
                    "message" => "Anda sudah mengambil sesi quiz ini, quiz hanya bisa diambil satu kali",
                    "showError" => true
                ], 200);
            }
        }


        //check if there is existing same session that unfinished
        //count the number of existing sessions that are unfinished
        $isFirstUnfinishedAttempt = false;
        $unfinishedAttemptCount = ExamTaker::where('user_id', '=', Auth::id())
            ->where('session_id', '=', $sessionId)
            ->where(function ($query) {
                $query->whereNull('finished_at');
            })
            ->count();

        if ($unfinishedAttemptCount == 0) {
            $isFirstUnfinishedAttempt = true;
        }

        $dimanaYa = "disini";

        $answers = json_encode($answers);
        //save exam result into examTaker table
        if ($isFirstUnfinishedAttempt) {
            $examResult = new ExamTaker();
            $examResult->user_id = Auth::id();
            $examResult->session_id = $sessionId;
            $examResult->user_answers = ($answers);
            $examResult->course_flag = $courseId;
            $examResult->course_section_flag = $sectionId;
            $examResult->current_score = $userScore;
            $examResult->guest_name = $name;
            $examResult->token_exam = $examToken;
            $examResult->save();
            $dimanaYa = "yessy";
        }


        //        if(!$allowMultipleAttempt && !$isFirstAttempt)

        //if not finished and not first attempt
        if ($request->isFinished != true && $isFirstUnfinishedAttempt != true) {
            $examResult = ExamTaker::where(
                "session_id", '=', $sessionId
            )->where(
                "user_id", '=', Auth::id()
            )->where(function ($query) {
                $query->whereNull('finished_at');
            })->first();
            $examResult->user_id = Auth::id();
            $examResult->session_id = $sessionId;
            $examResult->user_answers = ($answers);
            $examResult->course_flag = $courseId;
            $examResult->course_section_flag = $sectionId;
            $examResult->current_score = $userScore;
            $examResult->guest_name = $name;
            $examResult->save();
            $dimanaYa = "yossy";
        }

        if ($request->isFinished == true) {
            if ($isFirstUnfinishedAttempt) {
                $examResult = new ExamTaker();
                $examResult->user_id = Auth::id();
                $examResult->session_id = $sessionId;
                $examResult->user_answers = ($answers);
                $examResult->current_score = $userScore;
                $examResult->guest_name = $name;
                $examResult->course_flag = $courseId;
                $examResult->course_section_flag = $sectionId;
                $examResult->finished_at = Carbon::now();
                $examResult->is_finished = "y";
                $examResult->save();
                $dimanaYa = "priskilla";
            } else {
                $examResult = ExamTaker::where(
                    "session_id", '=', $sessionId
                )->where(
                    "user_id", '=', Auth::id()
                )->where(function ($query) {
                    $query->whereNull('finished_at');
                })->first();
                $examResult->user_id = Auth::id();
                $examResult->session_id = $sessionId;
                $examResult->user_answers = ($answers);
                $examResult->course_flag = $courseId;
                $examResult->course_section_flag = $sectionId;
                $examResult->current_score = $userScore;
                $examResult->guest_name = $name;
                $examResult->is_finished = "y";
                $examResult->finished_at = Carbon::now();
                $examResult->save();
                $dimanaYa = "yoxy";
            }
        }

        MyHelper::addAnalyticEvent(
            "Submit Exam Session : " . $session->id . " with score :" . $userScore, "Exam Taker"
        );

        //check if the session allowed user to see the result score
        $showScore = false;
        if ($session->show_result_on_end == "y") {
            $showScore = true;
        }

        if ($request->isFinished == true) {
            return response()->json([
                "d" => $dimanaYa,
                "is_first_attempt" => $isFirstUnfinishedAttempt,
                "is_finished" => $request->isFinished,
                "attempt_count" => $unfinishedAttemptCount,
                "scores" => $userScore,
                // "answer" => $answers,
                // "session" => $session,
                "message" => "Sukses",
                "showError" => false
            ]);
        } else {
            return response()->json([
                "d" => $dimanaYa,
                "is_finished" => $request->isFinished,
                "is_finished" => $request->isFinished,
                "is_first_attempt" => $isFirstUnfinishedAttempt,
                "scores" => 168,
                "attempt_count" => $unfinishedAttemptCount,
                // "answer" => $answers,
                // "session" => $session,
                "message" => "Sukses",
                "showError" => false
            ]);
        }

        return response()->json([
            "scores" => $userScore,
            "answer" => $answers
        ]);
    }
}
