<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisualizationDetailController extends Controller
{
    public function seeMainPieChartDetail(Request $request)
    {

        $locationId = $request->input('location') ?? "all";
        $departmentId = $request->input('department') ?? "all";
        $classId = $request->input('class') ?? "all";
        $month = $request->input('month') ?? "all";
        $learnStatus = request('learn_status');


        // department for pie chart
        $departments = DB::connection('ithub')
            ->table('m_departments')
            ->select('id', 'code', 'name')
            ->get();

        // Fetch positions
        $positions = DB::connection('ithub')
            ->table('m_group_employees')
            ->select('id', 'code', 'name')
            ->get();

        //Department for filter latest post test
        $departmentsForFilter = DB::connection('ithub')
            ->table('m_departments')
            ->select('id', 'code', 'name')
            ->where('code', 'like', '%_NEW%')
            ->get();

        $locations = DB::connection('ithub')
            ->table('m_sites')
            ->select('id', 'code', 'name')
            ->get();

        $classes = DB::table('lessons')
            ->where(function ($query) {
                $query->whereNull('deleted_at')
                    ->orWhere('deleted_at', '');
            })
            ->get();

        $userFilters = DB::connection('mysql')
            ->table('users')
            ->select('mdln_username', 'name', 'users.position_id', 'users.department_id', 'lessons.course_title')
            ->where('role', '=', 'student')
            ->where(function ($query) use ($locationId) {
                if (!empty($locationId)) {
                    if ($locationId !== 'all') {
                        $query->whereJsonContains('location', ['site_id' => $locationId]);
                    }
                }
            })
            ->where(function ($query) use ($departmentId) {
                if (!empty($departmentId)) {
                    if ($departmentId != "all") {
                        $query->where('users.department_id', '=', $departmentId);
                    }
                }
            })
            ->where(function ($query) use ($classId) {
                if (!empty($classId)) {
                    if ($classId != "all") {
                        $query->where('lessons.id', '=', $classId);
                    }
                }
            })
            ->leftJoin('student_lesson', 'users.id', '=', 'student_lesson.student_id') // Join with student_lesson table
            ->leftJoin('lessons', 'lessons.id', '=', 'student_lesson.lesson_id') // Join with student_lesson table
            ->where(function ($query) use ($learnStatus) {
                if (!empty($learnStatus) && $learnStatus !== 'all') {
                    if ($learnStatus === 'finished') {
                        $query->where('student_lesson.learn_status', '=', 1);
                    } elseif ($learnStatus === 'not_finished') {
                        $query->where('student_lesson.learn_status', '=', 0);
                    }
                }
            })
            ->where(function ($query) {
                // Check that the lesson is not deleted
                $query->whereNull('lessons.deleted_at');
            })
            ->get();

        $users_departments = $userFilters->map(function ($userLMS) use ($departments) {
            $userLMS->department = $departments->firstWhere('id', $userLMS->department_id);
            return $userLMS;
        });

        // Kelompokkan berdasarkan nama departemen
        $mainPieChartData = $users_departments->groupBy(function ($userLMS) {
            return $userLMS->department->name ?? 'Undefined Department';
        })->map(function ($group) {
            return $group->count();
        });


        // Create an associative array of departments for quick lookup
        $departmentMap = $departments->pluck('name', 'id')->toArray();


        // Create an associative array of positions for quick lookup
        $positionMap = $positions->pluck('name', 'id')->toArray();



        // Add department names to user filters
        // Add department names and position names to user filters
        foreach ($userFilters as $userFilterItem) {
            // Assign the department name based on the department_id
            $userFilterItem->department_name = $departmentMap[$userFilterItem->department_id] ?? '';

            // Assign the position name based on the position_id (assuming you have a position_id field)
            $userFilterItem->position_name = $positionMap[$userFilterItem->position_id] ?? '';
        }

        $compact = compact('userFilters', 'mainPieChartData', 'departmentsForFilter', 'locations', 'learnStatus', 'locationId', 'departmentId', 'classes');


        if ($request->dump == true) {

            return $compact;
        }

        return view('vis.main_pie_chart')->with($compact);
    }
}
