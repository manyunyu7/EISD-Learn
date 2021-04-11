<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Exception;

use App\Models\Blog;
use App\Models\Lesson;
use App\Models\FinalProject;
use App\Models\User;
use App\Models\CourseSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use DB;
use File;

class FinalProjectController extends Controller
{

    public function see_correction()
    {
        $mentor_id = Auth::user()->id;
        $dayta = DB::select("select * from view_submission_by_mentor where student_id = $mentor_id ");
        return view('project.project_see')->with(compact('dayta'));
    }

    public function correction()
    {

        $mentor_id = Auth::user()->id;
        $dayta = DB::select("select * from view_submission_by_mentor where mentor_id = $mentor_id ");
        return view('project.correct')->with(compact('dayta'));
    }

    public function submit_page(Lesson $lesson)
    {
        $user_id  = Auth::user()->id;
        $lesson_id = $lesson->id;

        $student_lesson = DB::table('student_lesson')
            ->where('student-lesson', "$user_id-$lesson_id")
            ->get()
            ->toArray();

        $submission = DB::table('student_submission')
            ->where('student_id', "$user_id")
            ->where('lesson_id', "$lesson_id")
            ->get();


        $isRegistered = false;
        if ($student_lesson == null) {
            abort(401, "Anda Belum Mendaftar ke Kelas " . $lesson->name);
        } else {
            $isRegistered = true;
        }

        $lesson_id = $lesson->id;
        $section = DB::select("select * from view_course_section where lesson_id = $lesson_id ORDER BY section_order ASC");

        $dayta = DB::select("select * from view_course where id = $lesson->id ");
        return view('project.submit', compact('lesson', 'dayta', 'isRegistered', 'section', 'submission'));
    }

    public function certificate(Lesson $lesson)
    {
        $name = Auth::User()->name;
        return response()
            ->view('project.print', ['data' => $lesson,"name"=>$name])
            ->header('Content-Type', 'image/jpeg');
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {

        $rules = [
            'note'     => 'required',
            'project_file'     => 'required',
        ];
        $customMessages = [
            'required' => 'Silakan Isi Kolom :attribute terlebih dahulu'
        ];
        $this->validate($request, $rules, $customMessages);
        $lesson_id = $request->course_id;
        $student_id = Auth::user()->id;

        $fileProject = $request->file('project_file');
        $fileProject->storeAs("public/class/submission/$lesson_id/", $fileProject->hashName());
        $inputDeyta = FinalProject::create([
            'student_id'     => $student_id,
            'lesson_id'     => $lesson_id,
            'status'     => 0,
            'note'     => $request->note,
            'teacher_note'     => "",
            'nilai'     => 0,
            'file'     => $fileProject->hashName(),
        ]);

        if ($inputDeyta) {
            //redirect dengan pesan sukses
            return redirect("course/$lesson_id/submission")->with(['success' => 'Kelas Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect("course/$lesson_id/submission")->with(['error' => 'Kelas Gagal Disimpan!']);
        }
    }

    /**
     * update
     *
     * @return void
     */
    public function score(Request $request)
    {
        // abort(401, "ID submission = ".$request->project_id);

        $updateDetails = [
            'teacher_note' => $request->get('teacher_note'),
            'status' => $request->get('status'),
            'nilai' => $request->get('score')
        ];
        $query =  DB::table('student_submission')
            ->where('id', $request->project_id)
            ->update($updateDetails);





        if ($query) {
            //redirect dengan pesan sukses
            return redirect("/lesson/correct")->with(['success' => 'Materi Berhasil Diupdate!']);
        } else {
            //redirect dengan pesan error
            return redirect("/lesson/correct")->with(['error' => 'Kelas Gagal Diupdate!']);
        }
    }
}
