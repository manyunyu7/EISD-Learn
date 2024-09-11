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
    public function myClass(Request $request)
    {
        $userID = Auth::id();
        $sortParam = $request->input('sort', 'Latest');
        $catParam = $request->input('category', 'All Category');

        $lessonCategories = LessonCategory::all();

        $sortBy = ($sortParam == 'Latest') ? 'desc' : 'asc';

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
            ->whereNull('a.deleted_at')
            ->whereExists(function ($query) use ($userID) {
                $query->select(DB::raw(1))
                    ->from('student_lesson as sl')
                    ->whereRaw('sl.lesson_id = a.id')
                    ->where('sl.student_id', $userID);
            })
            ->groupBy('a.id', 'b.name', 'b.profile_url', 'cs.id');

        $myClassesQuery = DB::table(DB::raw("({$subQuery->toSql()}) as main_table"))
            ->mergeBindings($subQuery)
            ->where('main_table.ranking', 1)
            ->when($sortParam == 'Latest', function ($query) use ($sortBy) {
                return $query->orderBy('main_table.created_at', $sortBy);
            }, function ($query) use ($sortBy) {
                return $query->orderBy('main_table.num_students_registered', $sortBy);
            })
            ->when($catParam != 'All Category', function ($query) use ($catParam) {
                return $query->where('main_table.course_category_name', $catParam);
            });

        $myClasses = $myClassesQuery->get();

        foreach ($myClasses as &$class) {
            $sections = CourseSection::select(
                'lessons.id as lesson_id',
                'lessons.course_title as lessons_title',
                'lessons.mentor_id',
                'users.name as mentor_name',
                'course_section.id as section_id',
                'course_section.section_order',
                'course_section.section_title',
                'course_section.quiz_session_id',
                'course_section.section_content',
                'course_section.section_video',
                'course_section.created_at',
                'course_section.updated_at',
                'course_section.can_be_accessed'
            )
            ->leftJoin('lessons', 'lessons.id', '=', 'course_section.course_id')
            ->leftJoin('users', 'users.id', '=', 'lessons.mentor_id')
            ->where('course_section.course_id', $class->id)
            // ->where('users.is_testing', '=', 'n')
            ->orderBy(DB::raw('CAST(course_section.section_order AS UNSIGNED)'), 'ASC')
            ->first();

            $class->first_section = $sections ? $sections->section_id : null;
        }

        
        $compact = compact('myClasses', 'userID', 'lessonCategories');

        if ($request->dump == true) {
            return $compact;
        }

        return view('student.myclass')->with($compact);
    }



}
