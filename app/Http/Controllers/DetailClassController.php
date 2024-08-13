<?php

namespace App\Http\Controllers;
use Illuminate\Pagination\Paginator;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailClassController extends Controller
{
    //
    public function viewClass(Request $request,$id){
        // $lesson_id = $lesson->id;
        $data = Lesson::findOrFail($id);
        $dayta = DB::table('course_section as c')
            ->select(
                'a.id as lesson_id',
                'a.course_title as lessons_title',
                'a.mentor_id',
                'b.name as mentor_name',
                'c.id as section_id',
                'c.section_order',
                'c.section_title',
                'c.section_content',
                'c.section_video',
                'c.created_at',
                'c.updated_at',
                'c.can_be_accessed',
                'd.time_limit_minute'
            )
            ->leftJoin('lessons as a', 'a.id', '=', 'c.course_id')
            ->leftJoin('users as b', 'a.mentor_id', '=', 'b.id')
            ->leftJoin('exam_sessions as d', 'c.quiz_session_id', '=', 'd.exam_id')
            ->where('a.id', $id)
            ->orderByRaw("CAST(section_order AS UNSIGNED) ASC")
            ->get();


        $jumlahSection = $dayta->count();
        $jumlahDuration = $dayta->sum('time_limit_minute');


        $compact = compact("data","dayta", "jumlahSection", "jumlahDuration");
        if($request->dump==true){
            return $compact;
        }
        // return $dayta;
        return view("lessons.view_class")->with($compact);
    }

    public function mentor_viewClass($id){
        $data = Lesson::findOrFail($id);
        $dayta = DB::table('course_section as c')
        ->select(
            'a.id as lesson_id',
            'a.course_title as lessons_title',
            'a.mentor_id',
            'b.name as mentor_name',
            'c.id as section_id',
            'c.section_order',
            'c.section_title',
            'c.section_content',
            'c.section_video',
            'c.created_at',
            'c.updated_at',
            'c.can_be_accessed'
        )
        ->leftJoin('lessons as a', 'a.id', '=', 'c.course_id')
        ->leftJoin('users as b', 'a.mentor_id', '=', 'b.id')
        ->where('a.id', $id)
        ->orderByRaw("CAST(section_order AS UNSIGNED) ASC")
        ->get();
        $jumlahSection = $dayta->count();


        $first_section = '0';

        if ($jumlahSection > 0){
            $first_section = $dayta->first()->section_id;
        }
        $preview_url = url('/')."/course/$id/section/$first_section";

        return view("lessons.mentor_view_class")->with(compact("dayta", "data", "jumlahSection", "first_section", "preview_url"));
    }


    public function viewStudents(Request $request, $lessonId){
        Paginator::useBootstrap();
        $sortBy = $request->sortBy ?? 'asc';
        $lessonId = $request->lessonId;
        // $sortBy = $request->sortBy;
        // Mengambil data siswa yang memiliki student_id dan lesson_id yang sesuai
        $studentsInLesson = User::join('student_lesson', 'users.id', '=', 'student_lesson.student_id')
        ->where('student_lesson.lesson_id', $lessonId)
        ->select('users.name', 'users.department') // Pilih kolom yang ingin Anda ambil dari tabel users
        ->orderBy('users.name', $sortBy)
        ->paginate(10);

        // return $request->all();
        // return $sortBy;
        return view("lessons.view_students")->with(compact("studentsInLesson", "sortBy", "lessonId"));
    }

}
