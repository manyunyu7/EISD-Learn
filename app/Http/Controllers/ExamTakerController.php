<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\Exam;
use App\Models\ExamSession;
use Illuminate\Http\Request;

class ExamTakerController extends Controller
{
    public function viewInitialTakeSession(Request $request, $id)
    {

        $session = ExamSession::findOrFail($id);
        $exam = Exam::findOrFail($session->exam_id);
        $data = $session;
        $showCompact = true;
        MyHelper::addAnalyticEvent(
            "Take Quiz on exam ".$exam->id."-".$exam->title,"Exam Taker"
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

        $question_count =  count($questions);
        $compact = compact('session','totalScore', 'exam', 'data', 'showCompact','questions','question_count');
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

    public function submitQuiz(Request $request)
    {
        // Decode the JSON payload
        $payload = json_decode($request->getContent(), true);

        if (!$payload) {
            // Handle invalid JSON payload
            return response()->json(['error' => 'Invalid JSON payload'], 400);
        }

        // Extract relevant data from the payload
        $examId = $payload['examId'];
        $sessionId = $payload['sessionId'];
        $answers = $payload['answers'];

        // Load the exam session
        $session = ExamSession::findOrFail($sessionId);

        // Initialize a variable to store the user's score
        $userScore = 0;
        $originalQuestions = json_decode($session->questions_answers);

        // Loop through the user's answers
        foreach ($answers as $answer) {
            // Find the corresponding question in the original questions array by ID
            $question = collect($originalQuestions)->firstWhere('id', $answer['id']);

            // Ensure the question exists
            if ($question) {
                // Decode the choices from the question
                $choices = json_decode($question->choices, true);

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
            }
        }

        MyHelper::addAnalyticEvent(
            "Submit Exam Session : " . $session->id . " with score :" . $userScore, "Exam Taker"
        );

        return response()->json([
            "scores" => $userScore,
            "answer" => $answers
        ]);
    }
}
