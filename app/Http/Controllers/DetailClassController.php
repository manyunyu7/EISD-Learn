<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
// use CourseSection;
use App\Models\CourseSection;
use Illuminate\Pagination\Paginator;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
                'c.quiz_session_id',
                'c.section_content',
                'c.section_video',
                'c.created_at',
                'c.updated_at',
                'c.can_be_accessed',
            )
            ->leftJoin('lessons as a', 'a.id', '=', 'c.course_id')
            ->leftJoin('users as b', 'a.mentor_id', '=', 'b.id')
            ->leftJoin('exam_sessions as d', 'c.quiz_session_id', '=', 'd.exam_id')
            ->where('a.id', $id)
            ->orderByRaw("CAST(section_order AS UNSIGNED) ASC")
            ->get();


        $jumlahSection = $dayta->count();
        $jumlahDuration = $dayta->sum('time_limit_minute');

        // return $dayta;

        $time_limit_minute = null;
        foreach ($dayta as $section) {
            $examSession = ExamSession::where('id', $section->quiz_session_id)->first();
            if ($examSession != null) {
                $section->time_limit_minute = $examSession->time_limit_minute;
            } else {
                $section->time_limit_minute = null; // Optional: set to null if exam session is not found
            }
        }

        $jumlahDuration = $dayta->sum('time_limit_minute');

        $compact = compact("data","dayta", "jumlahSection", "jumlahDuration");
        if($request->dump==true){
            return $compact;
        }
        // return $dayta;
        return view("lessons.view_class")->with($compact);
    }

    public function mentor_viewClass(Request $request,$id){
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
            'c.quiz_session_id',
            'c.section_content',
            'c.section_video',
            'c.created_at',
            'c.updated_at',
            'c.can_be_accessed',
        )
        ->leftJoin('lessons as a', 'a.id', '=', 'c.course_id')
        ->leftJoin('users as b', 'a.mentor_id', '=', 'b.id')
        ->leftJoin('exam_sessions as d', 'c.quiz_session_id', '=', 'd.exam_id')
        ->where('a.id', $id)
        ->orderByRaw("CAST(section_order AS UNSIGNED) ASC")
        ->get();


        $jumlahSection = $dayta->count();
        $jumlahDuration = $dayta->sum('time_limit_minute');


        $first_section = '0';

        if ($jumlahSection > 0){
            $first_section = $dayta->first()->section_id;
        }
        $preview_url = url('/')."/course/$id/section/$first_section";

        $time_limit_minute = null;
        foreach ($dayta as $section) {
            $examSession = ExamSession::where('id', $section->quiz_session_id)->first();
            if ($examSession != null) {
                $section->time_limit_minute = $examSession->time_limit_minute;
            } else {
                $section->time_limit_minute = null; // Optional: set to null if exam session is not found
            }
        }

        $jumlahDuration = $dayta->sum('time_limit_minute');

        $compact = compact("dayta", "data", "jumlahSection", "first_section", "preview_url", "jumlahDuration");
        if($request->dump==true){
            return $compact;
        }

        // return $dayta;

        return view("lessons.mentor_view_class")->with($compact);
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

    public function mentor_duplicateClass(Request $request, $id){
        // Original Class --> lessons
        $data = Lesson::findOrFail($id);
        // Create Duplicate Class
        $copy_new_class = new Lesson();
        $copy_new_class->course_title = $data->course_title."_COPY";
        $copy_new_class->course_cover_image = $data->course_cover_image;
        $copy_new_class->course_trailer = $data->course_trailer;
        $copy_new_class->mentor_id =  $data->mentor_id;
        $copy_new_class->course_description = $data->course_description;
        $copy_new_class->created_at = Carbon::now();
        $copy_new_class->updated_at = Carbon::now();
        $copy_new_class->can_be_accessed = $data->can_be_accessed;
        $copy_new_class->is_visible = $data->is_visible;
        $copy_new_class->category_id = $data->category_id;
        $copy_new_class->text_descriptions = $data->text_descriptions;
        $copy_new_class->pin = $data->pin;
        $copy_new_class->new_class = $data->new_class;
        $copy_new_class->department_id = $data->department_id;
        $copy_new_class->position_id = $data->position_id;
        $copy_new_class->tipe = $data->tipe;
        $copy_new_class->rating_course = 0;
        $copy_new_class->lesson_id_duplicate_by = $id;

        $data_course_section = CourseSection::where('course_id', $id)->orderBy('section_order')->get();

        // Table course_section (course_section.course_id == lessons.id)
        if($copy_new_class->save()){
            // $copy_new_course_section = new CourseSection();
            foreach ($data_course_section as $section) {
                $copy_new_course_section = new CourseSection();
                $copy_new_course_section->section_order = $section->section_order;
                $copy_new_course_section->section_title = $section->section_title;
                $copy_new_course_section->quiz_session_id = $section->quiz_session_id;
                $copy_new_course_section->course_id = $copy_new_class->id;
                $copy_new_course_section->section_content = $section->section_content;
                $copy_new_course_section->section_video = $section->section_video;
                $copy_new_course_section->created_at = Carbon::now();
                $copy_new_course_section->updated_at = Carbon::now();
                $copy_new_course_section->can_be_accessed = $section->can_be_accessed;
                $copy_new_course_section->enable_absensi = $section->enable_absensi;
                // Simpan data
                if (!$copy_new_course_section->save()) {
                    // Jika gagal menyimpan, kembalikan pesan error
                    return redirect('lesson/manage_v2')->with(['error' => 'Duplikat Kelas Gagal Dibuat!']);
                }
            }
            if($copy_new_course_section->save()){
                return redirect('lesson/manage_v2')->with(['success' => 'Duplikat Kelas Berhasil Dibuat!']);
            } else {
                return redirect('lesson/manage_v2')->with(['error' => 'Duplikat Kelas Gagal Dibuat!']);
            }
        }

        
        $compact = compact("data");
        if($request->dump==true){
            return $compact;
        }
        return view("lessons.duplicate_class")->with($compact);
    }

}
