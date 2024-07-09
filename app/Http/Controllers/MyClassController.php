<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use Illuminate\Http\Request;
use Exception;

use App\Models\Blog;
use App\Models\Lesson;
use App\Models\User;
use App\Models\CourseSection;
use App\Models\LessonCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MyClassController extends Controller
{
    public function myClass()
    {
        $userID = Auth::id();
        $sortParam = request('sort' , 'Latest') ;
        $catParam  = request('category', 'All Category');

        $lessonCategories = LessonCategory::all();

        $sortBy = ($sortParam == 'Latest') ? 'desc' : 'asc';
        $catBy = ($catParam == 'All Category') ? '*' : $catParam;

        // Membuat subquery menggunakan Query Builder
        $subQuery = DB::table('lessons as a')
        ->select([
            DB::raw('RANK() OVER (PARTITION BY a.id ORDER BY cs.id ASC) AS ranking'),
            'a.*',
            'lc.color_of_categories as course_category_color',
            'lc.name as course_category_name',
            'b.name as mentor_name',
            'b.profile_url',
            DB::raw('COUNT(c.student_id) AS num_students_registered'),
            DB::raw('CASE WHEN COUNT(c.student_id) > 0 THEN 1 ELSE 0 END AS is_registered')
        ])
        ->leftJoin('users as b', 'a.mentor_id', '=', 'b.id')
        ->leftJoin('student_lesson as c', 'a.id', '=', 'c.lesson_id')
        ->leftJoin('course_section as cs', 'a.id', '=', 'cs.course_id')
        ->leftJoin('lesson_categories as lc', 'a.category_id', '=', 'lc.id')
        ->whereExists(function ($query) use ($userID) {
            $query->select(DB::raw(1))
                ->from('student_lesson as sl')
                ->whereRaw('sl.lesson_id = a.id')
                ->where('sl.student_id', $userID);
        })
        ->groupBy('a.id', 'b.name', 'b.profile_url', 'cs.id');


        $myClasses = DB::table(DB::raw("({$subQuery->toSql()}) as main_table"))
        ->mergeBindings($subQuery) // Menggabungkan bindings dari subquery
        ->where('main_table.ranking', 1)
        ->when($sortParam == 'Latest', function ($query) use ($sortBy) {
            return $query->orderBy('main_table.created_at', $sortBy);
        }, function ($query) use ($sortBy) {
            return $query->orderBy('main_table.num_students_registered', $sortBy);
        })
        ->when($catParam != 'All Category', function ($query) use ($catParam) {
            return $query->where('main_table.course_category_name', $catParam);
        })
        ->get();
        // Append new attribute to each row

        foreach ($myClasses as &$class) {
            $firstSection = DB::table('course_section')
                ->where('course_id', '=', $class->id)
                ->orderByRaw("CAST(section_order AS UNSIGNED), section_order ASC")
                ->first();

            // Tambahkan pengecekan untuk memastikan $firstSection tidak null
            if ($firstSection !== null) {
                $class->first_section = $firstSection->id;
            } else {
                // Handle jika $firstSection null
                $class->first_section = null; // Atau nilai default lainnya sesuai kebutuhan
            }
        }


        // return $class->first_section;
        // return $myClasses;

        return view('student.myclass')->with(compact('myClasses', 'userID', 'lessonCategories'));
    }
}
