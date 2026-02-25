<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamQuestionAnswers;
use App\Models\ExamSession;
// use CourseSection;
use App\Models\CourseSection;
use Illuminate\Pagination\Paginator;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


class DetailClassController extends Controller
{
    //
    public function viewClass(Request $request,$id){
        // $lesson_id = $lesson->id;
        $data = Lesson::findOrFail($id);
        // --- GENERATE PRESIGNED URL UNTUK COVER IMAGE ---
        if ($data->course_cover_image) {
            $data->cover_signed_url = Storage::disk('s3')->temporaryUrl(
                $data->course_cover_image, now()->addMinutes(60)
            );
        } else {
            $data->cover_signed_url = null;
        }

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

        // Confirmation Duplicates
        $confirmation = $request->query('confirmation');

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
        $copy_new_class->is_visible = "t";
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


        // Find Exam Sessions Content
        $examSession_Ids = $data_course_section->pluck('quiz_session_id')->filter(fn($id) => $id != '-');
        $exam_id = null; // Inisialisasi biar tidak error jika tidak ada exam_id

        // Ambil semua exam_id yang sesuai
        $exam_ids = ExamSession::whereIn('id', $examSession_Ids)->get();


        $examData = [];
        foreach($exam_ids as $data) {
            // Karena $data sudah berupa nilai exam_id (bukan object), gunakan langsung
            $exam = Exam::where('id', $data->exam_id)->first();
            
            if ($exam) {
                $examData[] = $exam; 
            }
        }

        // return $examData;

        $examData_qna = [];
        foreach($exam_ids as $data_examSession){
            $query_examData_qna = ExamQuestionAnswers::where('exam_id', $data_examSession->exam_id)->get();
            if ($query_examData_qna) {
                $examData_qna[] = $query_examData_qna; 
            }
        }

        // return $examData_qna;

        // Table course_section (course_section.course_id == lessons.id)
        if($copy_new_class->save()){
            // $copy_new_course_section = new CourseSection();
            foreach ($data_course_section as $section) {
                if($section->quiz_session_id == '-'){
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
            }


            if($confirmation == "yes"){
                // Proses duplikasi Material Content to course_sections Table
                foreach ($data_course_section as $section) {
                    if($section->quiz_session_id != '-'){
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
                }
                // Proses duplikasi to Exams Table
                $newExamId = [];
                foreach($examData as $data_exam){
                    $copy_new_exam = new Exam();
                    $copy_new_exam->title        = $data_exam->title . "_COPY";
                    $copy_new_exam->image        = $data_exam->image;
                    $copy_new_exam->start_date   = $data_exam->start_date;
                    $copy_new_exam->end_date     = $data_exam->end_date;
                    $copy_new_exam->instruction  = $data_exam->instruction;
                    $copy_new_exam->description  = $data_exam->description;
                    $copy_new_exam->randomize    = $data_exam->random_sort_exam;
                    $copy_new_exam->can_access   = $data_exam->can_access;
                    $copy_new_exam->is_deleted   = $data_exam->is_deleted;
                    $copy_new_exam->created_by   = $data_exam->created_by;
                    $copy_new_exam->created_at   = Carbon::now(); 
                    $copy_new_exam->updated_at   = Carbon::now();
                    if ($copy_new_exam->save()) {
                        $newExamId[$data_exam->id] = $copy_new_exam->id; // Simpan ID baru
                    }

                }

                // Proses duplikasi to Exam Sessions Table
                foreach($exam_ids as $data_examSession){
                    $copy_new_exam_session = new ExamSession();
                    $copy_new_exam_session->start_date = Carbon::now()->addDays(1);
                    $copy_new_exam_session->end_date        = Carbon::now()->addDays(8);
                    $copy_new_exam_session->instruction   = $data_examSession->instruction;
                    $copy_new_exam_session->description     = $data_examSession->description;
                    $copy_new_exam_session->can_access  = $data_examSession->can_access;
                    $copy_new_exam_session->public_access  = $data_examSession->public_access;
                    $copy_new_exam_session->show_result_on_end    = $data_examSession->show_result_on_end;
                    $copy_new_exam_session->time_limit_minute   = $data_examSession->time_limit_minute;
                    $copy_new_exam_session->allow_review   = $data_examSession->allow_review;
                    $copy_new_exam_session->show_score_on_review   = $data_examSession->show_score_on_review;
                    $copy_new_exam_session->allow_multiple   = $data_examSession->allow_multiple;
                    // $copy_new_exam_session->exam_id   = $newExamId;
                    $copy_new_exam_session->created_by   = $data_examSession->created_by;
                    $copy_new_exam_session->questions_answers   = $data_examSession->questions_answers;
                    $copy_new_exam_session->created_at   = Carbon::now(); 
                    $copy_new_exam_session->updated_at   = Carbon::now();
                    $copy_new_exam_session->exam_type   = $data_examSession->exam_type;
                    $copy_new_exam_session->random_sort_exam   = $data_examSession->random_sort_exam;
                    // Pastikan exam_id baru yang digunakan
                    if (isset($newExamId[$data_examSession->exam_id])) {
                        $copy_new_exam_session->exam_id = $newExamId[$data_examSession->exam_id];
                    }
                    $copy_new_exam_session->save();
                }

                // Proses duplikasi to Exam Question Answers Table
                foreach($examData_qna as $qnaCollection){
                    // Looping untuk setiap soal di dalam Collection
                    foreach($qnaCollection as $data_exam_qna){
                        $copy_new_qna = new ExamQuestionAnswers();
                        $copy_new_qna->question       = $data_exam_qna->question;
                        $copy_new_qna->image          = $data_exam_qna->image;
                        $copy_new_qna->question_type  = $data_exam_qna->question_type;
                        $copy_new_qna->correct_answer = $data_exam_qna->correct_answer;
                        $copy_new_qna->order          = $data_exam_qna->order;
                        $copy_new_qna->choices        = $data_exam_qna->choices;
                        // $copy_new_qna->exam_id        = $newExamId;
                        $copy_new_qna->created_by     = $data_exam_qna->created_by;
                        $copy_new_qna->created_at     = Carbon::now();
                        $copy_new_qna->updated_at     = Carbon::now();
                        // Gunakan exam_id yang baru
                        if (isset($newExamId[$data_exam_qna->exam_id])) {
                            $copy_new_qna->exam_id = $newExamId[$data_exam_qna->exam_id];
                        }
                        $copy_new_qna->save();
                    }
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
