<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailClassController extends Controller
{
    //
    public function viewClass($id){
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
                'c.can_be_accessed'
            )
            ->leftJoin('lessons as a', 'a.id', '=', 'c.course_id')
            ->leftJoin('users as b', 'a.mentor_id', '=', 'b.id')
            ->where('a.id', $id)
            ->orderBy('c.section_order', 'ASC')
            ->get();

        // return $dayta;
        $jumlahSection = $dayta->count();
        return view("lessons.view_class")->with(compact("data","dayta", "jumlahSection"));
    }
    

    public function viewStudents($lessonId){
         // Mengambil data siswa yang memiliki student_id dan lesson_id yang sesuai
         $studentsInLesson = User::join('student_lesson', 'users.id', '=', 'student_lesson.student_id')
         ->where('student_lesson.lesson_id', $lessonId)
         ->select('users.name') // Pilih kolom yang ingin Anda ambil dari tabel users
         ->get();

        // Mengirim data ke tampilan (view)
        // return $studentsInLesson;
        return view("lessons.view_students")->with(compact("studentsInLesson"));
    }
    
}
