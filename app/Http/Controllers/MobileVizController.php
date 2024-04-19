<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\Lesson;
use App\Models\StudentLesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MobileVizController extends Controller
{

    public function mainChart(Request $request)
    {
        $userId = $request->lms_user_id; // Assuming you'll use the user id from the request
        // $userId = 88; // Uncomment this line if you want to hardcode the user id
        // Get all classes taken by the user
        $takenClasses = StudentLesson::where("student_id", "=", $userId)->get();

        // Count the total number of classes
        $totalClasses = count($takenClasses);


        // Count the number of completed classes where deleted_at is null
        $allClass = Lesson::whereNull('deleted_at')->count();

        // Count the number of incomplete classes
        $takenClass = $takenClasses->count();


        // Check if there are no classes
        if ($totalClasses == 0) {
            return MyHelper::responseSuccessWithData(
                200,
                200,
                2,
                "success",
                "success",
                [
                    'completed' => 0,
                    'incomplete' => $allClass
                ]
            );
        }

        // Calculate percentages and round to 2 decimal places
        $completedPercentage = round(($allClass / $totalClasses) * 100, 1);
        $incompletePercentage = round(($takenClass / $totalClasses) * 100, 1);


        return MyHelper::responseSuccessWithData(
            200,
            200,
            2,
            "success",
            "success",
            [
                'completed' => $takenClass,
                'incomplete' => $allClass
            ]
        );
        // Return the percentages
    }

    public function getCompletedClass(Request $request)
    {
        $userId = $request->lms_user_id; // Assuming you'll use the user id from the request
        //$userId = 88; // Uncomment this line if you want to hardcode the user id
        // Get all classes taken by the user
        $takenClasses = StudentLesson::where("student_id", "=", $userId)->get();

        // Count the total number of classes
        $totalClasses = count($takenClasses);

        // Check if there are no classes
        if ($totalClasses === 0) {
            return MyHelper::responseSuccessWithData(
                200,
                200,
                2,
                "success",
                "success",
                [
                    'completed' => 0,
                    'incomplete' => 100
                ]
            );
        }

        // Count the number of completed classes
        $completedClasses = $takenClasses->where('learn_status', 1)->count();

        // Count the number of incomplete classes
        $incompleteClasses = $takenClasses->where('learn_status', 0)->count();

        // Calculate percentages and round to 2 decimal places
        $completedPercentage = round(($completedClasses / $totalClasses) * 100, 1);
        $incompletePercentage = round(($incompleteClasses / $totalClasses) * 100, 1);


        return MyHelper::responseSuccessWithData(
            200,
            200,
            2,
            "success",
            "success",
            [
                'completed' => $completedClasses,
                'incomplete' => $incompleteClasses
            ]
        );
        // Return the percentages
    }

    public function getQuizResult(Request $request)
    {
        $userId = $request->lms_user_id;

        // Assuming you receive the month as a parameter named 'month' in your request
        $requestedMonth = request()->input('month');

        $currentYear = date('Y'); // Get the current year

        $subquery = DB::table('exam_takers as et')
            ->select([
                'u.name',
                'csf.section_title',
                DB::raw('CAST(COALESCE(et.current_score, 0) AS SIGNED) AS score'), // Convert to integer if possible, otherwise default to 0
                'et.finished_at',
                DB::raw('DATE_FORMAT(et.finished_at, "%e %M %Y") AS readable_date'),
                'csf.id AS course_section_id',
                DB::raw('(SELECT COUNT(*) FROM exam_takers WHERE user_id = et.user_id AND session_id = et.session_id AND finished_at >= et.finished_at) AS attempt_number'),
                'et.id AS exam_taker_id',
                'et.session_id',
                'et.user_id'
            ])
            ->join('course_section as csf', 'et.course_section_flag', '=', 'csf.id')
            ->leftJoin('users as u', 'u.id', '=', 'et.user_id')
            ->where('et.is_finished', 'Y');

        if (!empty($requestedMonth)) {
            $subquery->whereYear('et.finished_at', $currentYear) // Filter by current year
            ->whereMonth('et.finished_at', $requestedMonth);
        }

        $results = DB::table(DB::raw("({$subquery->toSql()}) AS subquery"))
            ->mergeBindings($subquery);
        //->where('attempt_number', 1);


        if ($userId != "") {
            $results->where("user_id", "=", $userId);
        }

        $results = $results->get();

        return MyHelper::responseSuccessWithData(
            200,
            200,
            2,
            "success",
            "success",
            $results
        );
    }
}
