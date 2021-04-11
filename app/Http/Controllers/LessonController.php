<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Models\Blog;
use App\Models\Lesson;
use App\Models\User;
use App\Models\StudentLesson;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use DB;
use Alert;


class LessonController extends Controller
{

    //Redirect to Create Lesson View
    public function create()
    {
        return view('lessons.create_lesson');
    }

    public function add()
    {
        return view('lesson.create');
    }

    public function index()
    {
        return view('main.home');
    }



    public function manage()
    {
        $user_id  = Auth::id();
        $dayta = DB::select("select * from view_course where mentor_id = $user_id");
        Paginator::useBootstrap();
        return view('lessons.manage_lesson', compact('dayta'));
    }

    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        $course_image_file = "public/class/cover/$lesson->course_cover_image";
        $course_trailer_file = "public/class/trailer/$lesson->course_trailer";
        if ($lesson) {
            //Do Nothing
        } else {
            abort('401', 'Not Found');
        }
        Storage::disk('local')->delete($course_image_file);
        Storage::disk('local')->delete($course_trailer_file);

        $lesson->delete();

        if ($lesson) {
            //redirect dengan pesan sukses
            return redirect('lesson/manage')->with(['success' => 'Kelas Berhasil Dihapus!']);
        } else {
            //redirect dengan pesan error
            return redirect('lesson/manage')->with(['error' => 'Kelas Gagal Dihapus!']);
        }
    }


    public function edit(Lesson $lesson)
    {
        return view('lessons.edit_lesson', compact('lesson'));
    }



    public function show(Lesson $lesson)
    {
        $user_id  = Auth::id();
        $lesson_id = $lesson->id;
        $lessonz = DB::table('view_course')
            ->where('id', "$lesson_id")
            ->get()
            ->toArray();

        $lesson = $lessonz[0];

        $student_lesson = DB::table('student_lesson')
            ->where('student-lesson', "$user_id-$lesson_id")
            ->get()
            ->toArray();

        $isRegistered = true;
        if ($student_lesson == null) {
            $isRegistered = false;
        }

        $section = DB::select("select * from view_course_section where lesson_id = $lesson_id ORDER BY section_order ASC");
        return view('lessons.main_course', compact('lesson', 'section', 'isRegistered'));
    }



    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $blog
     * @return void
     */
    public function update(Request $request, Lesson $lesson)
    {
        $this->validate($request, [
            'title'     => 'required',
            'content'   => 'required'
        ]);

        $lesson = Lesson::findOrFail($lesson->id);
        $cat = $request->input('category');

        if ($request->file('image') == "" && $request->file('video') == "") {
            $lesson->update([
                'course_title'     => $request->title,
                'course_description'   => $request->content,
                'course_category'     => $request->input('category')
            ]);
        } else if ($request->file('video') == "" && $request->file('image') != "") {
            //hapus old image
            Storage::disk('local')->delete('public/class/cover/' . $lesson->image);
            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/class/cover', $image->hashName());
            $lesson->update([
                'course_cover_image'     => $image->hashName(),
                'course_title'     => $request->title,
                'course_category'     => $request->input('category'),
                'course_description'   => $request->content
            ]);
        } else if ($request->file('image') == "" && $request->file('video') != "") {
            //Kalau image tidak diubah tapi video diubah
            //hapus old video
            Storage::disk('local')->delete('public/class/trailer' . $lesson->video);
            //upload new video
            $video = $request->file('video');
            $cat = $request->input('category');
            $video->storeAs('public/class/trailer', $video->hashName());

            $lesson->update([
                'course_title'     => $request->title,
                'course_trailer'     => $video->hashName(),
                'course_category'     => $request->input('category'),
                'course_description'   => $request->content
            ]);
        } else {
        }
        if ($lesson) {
            //redirect dengan pesan sukses
            return redirect('lesson/manage')->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            //redirect dengan pesan error
            return redirect('lesson/manage')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }





    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        // Alert::success('pesan yang ingin disampaikan', 'Judul Pesan');
        $this->validate($request, [
            'image'     => 'required|image|mimes:png,jpg,jpeg',
            'title'     => 'required',
            'content'   => 'required',
            'category' => 'required_without_all',
        ]);

        //upload image
        $image = $request->file('image');
        $video = $request->file('video');
        $cat = $request->input('category');
        $image->storeAs('public/class/cover', $image->hashName());
        $video->storeAs('public/class/trailer', $video->hashName());
        $user_id  = Auth::id();

        $cats = $request->input('category');

        $inputDeyta = Lesson::create([
            'course_cover_image'     => $image->hashName(),
            'course_title'     => $request->title,
            'course_trailer'     => $video->hashName(),
            'course_category'     => $request->input('category'),
            'mentor_id'     => $user_id,
            'course_description'   => $request->content
        ]);

        if ($inputDeyta) {
            //redirect dengan pesan sukses
            return redirect('lesson/manage')->with(['success' => 'Kelas Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect('lesson/manage')->with(['error' => 'Kelas Gagal Disimpan!']);
        }
    }

    public function studentRegister(Request $request)
    {
        try {
            $user_id  = Auth::user()->id;
            $registerLesson = StudentLesson::create([
                'student_id'     => $user_id,
                'lesson_id'     => $request->course_id,
                'learn_status'     => 0,
                'certificate_file'     => "",
                'student-lesson'     => "$user_id-$request->course_id",
            ]);
            if ($registerLesson) {
                return redirect('/home')->with(['success' => 'Berhasil Mendaftar Kelas!']);
            } else {
                return redirect('/home')->with(['error' => 'Gagal Mendaftar Kelas!']);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with(['error' => 'Anda Sudah Terdaftar di Kelas Ini']);
        }
    }

    // Drop Kelas
    public function drop(Request $request)
    {
        $user_id  = Auth::user()->id;
        $course_id = $request->course_id;
       $delete =  DB::table('student_lesson')->where('student-lesson', '=', $user_id."-".$course_id)->delete();
        if ($delete) {
            return redirect('/home')->with(['success' => 'Berhasil Drop Kelas!']);
        } else {
            return redirect('/home')->with(['error' => 'Gagal Drop Kelas!']);
        }
    }

}
