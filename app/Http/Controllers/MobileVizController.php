<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Models\StudentLesson;
use App\Models\StudentSection;
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

    public function sectionCount(Request $request)
    {
        $userId = $request->lms_user_id; // Use the user id from the request

        // Get all lesson IDs the student is enrolled in
        $enrolledLessonIds = StudentLesson::where('student_id', $userId)
            ->join('lessons', 'student_lesson.lesson_id', '=', 'lessons.id')
            ->whereNull('lessons.deleted_at')
            ->pluck('lessons.id')
            ->toArray();

        // Get all section IDs related to the lessons the student is enrolled in
        $enrolledSectionIds = CourseSection::whereIn('course_id', $enrolledLessonIds)
            // ->whereNull('deleted_at') // Ensure sections are not deleted
            ->pluck('id')
            ->toArray();

        // Get all sections that the student has entries for
        $studentSectionIds = StudentSection::where('student_id', $userId)
            ->pluck('section_id')
            ->toArray();

        // Count of sections taken by the student (based on entries in student_section and enrollment in lessons)
        $finishedSections = count(array_intersect($studentSectionIds, $enrolledSectionIds));

        // Get the total number of sections related to the enrolled lessons
        $totalSections = count($enrolledSectionIds);

        // Count of sections unfinished by the student
        $unfinishedSections = $totalSections - $finishedSections;


        // If the student has not taken any classes, get the total count of available lessons
        if (empty($studentSectionIds)) {
            // Get all eligible section IDs for lessons the student can register in
            $unfinishedSections = CourseSection::join('lessons', 'lessons.id', '=', 'course_section.course_id')
                ->whereNull('lessons.deleted_at') // Ensure courses are not deleted
                ->count(); // Directly count the records

        }

        return MyHelper::responseSuccessWithData(
            200,
            200,
            2,
            "success",
            "success",
            [
                'finished_sections' => $finishedSections,
                'unfinished_sections' => $unfinishedSections,
            ]
        );
    }

    public function getCompletedClass(Request $request)
    {
        $userId = $request->lms_user_id; // Use the user id from the request

        // Get the IDs and finished status of all lessons for the user
        $studentLessons = StudentLesson::where("student_id", $userId)
            ->join('lessons', 'student_lesson.lesson_id', '=', 'lessons.id')
            ->whereNull('lessons.deleted_at')
            ->where(function ($query) {
                $query->where('lessons.is_visible', 'y')
                    ->orWhereNull('lessons.is_visible')
                    ->orWhere('lessons.is_visible', '');
            })
            ->select('student_lesson.lesson_id', 'student_lesson.learn_status')
            ->get();

        // Count finished and unfinished lessons
        $finishedClassCount = $studentLessons->where('learn_status', 1)->count();
        $unfinishedClassCount = $studentLessons->where('learn_status', 0)->count();

        // If the student has not taken any classes, get the total count of available lessons
        if ($studentLessons->isEmpty()) {
            $totalLessonsCount = Lesson::whereNull('deleted_at')
                ->where(function ($query) {
                    $query->where('is_visible', 'y')
                        ->orWhereNull('is_visible')
                        ->orWhere('is_visible', '');
                })
                ->count();

            $unfinishedClassCount = $totalLessonsCount; // All lessons are unfinished
        }


        return MyHelper::responseSuccessWithData(
            200,
            200,
            2,
            "success",
            "success",
            [
                'completed' => $finishedClassCount,
                'not_completed' => $unfinishedClassCount
            ]
        );
    }


    public function getEnrolledClass(Request $request)
    {
        $userId = $request->lms_user_id; // Assuming you'll use the user id from the request
        //$userId = 88; // Uncomment this line if you want to hardcode the user id

        // Get the IDs of all classes taken by the user
        $enrolledLessonIds = StudentLesson::where("student_id", $userId)
            ->join('lessons', 'student_lesson.lesson_id', '=', 'lessons.id')
            ->whereNull('lessons.deleted_at')
            ->where(function ($query) {
                $query->where('lessons.is_visible', 'y')
                    ->orWhereNull('lessons.is_visible')
                    ->orWhere('lessons.is_visible', '');
            })
            ->pluck('student_lesson.lesson_id')
            ->toArray();

        // Count the number of enrolled classes
        $enrolled = count($enrolledLessonIds);

        // Count the total number of all classes available/eligible for the student but not taken yet
        $notEnrolled = Lesson::whereNull('deleted_at')
            ->where(function ($query) {
                $query->where('is_visible', 'y')
                    ->orWhereNull('is_visible')
                    ->orWhere('is_visible', '');
            })
            ->whereNotIn('id', $enrolledLessonIds)
            ->count();

        return MyHelper::responseSuccessWithData(
            200,
            200,
            2,
            "success",
            "success",
            [
                'enrolled' => $enrolled,
                'not_enrolled' => $notEnrolled
            ]
        );
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
