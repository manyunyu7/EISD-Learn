<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

use App\Models\Blog;
use App\Models\Lesson;
use App\Models\User;
use App\Models\CourseSection;
use App\Models\StudentSection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseSectionController extends Controller
{
    public function manage_section(Lesson $lesson)
    {

        $user_id = Auth::id();
        $lesson_id = $lesson->id;
        if ($user_id != $lesson->mentor_id) {
            abort(401, 'Unauthorized');
        }
        $dayta = DB::select("select * from view_course_section where lesson_id = $lesson_id ORDER BY section_order ASC");
        // $dayta = DB::select("select * from view_course_section  where mentor_id = $user_id");
        return view('lessons.section.manage_section', compact('lesson'), compact('dayta'));
    }

    // SEE SECTION
    public function see_section(Lesson $lesson, CourseSection $section)
    {
        //i do auth check here, not in route middleware, because section is showed to non logged user 
        // in class preview
        if (!Auth::check()) {
            abort(401, "Anda Harus Login Untuk Melanjutkan " . $lesson->name);
        }
        $user_id = Auth::user()->id;
        $lesson_id = $lesson->id;

        if (Auth::user()->role == "student") {
            # code...

            $student_lesson = DB::table('student_lesson')
                ->where('student-lesson', "$user_id-$lesson_id")
                ->get()
                ->toArray();

            $isRegistered = false;
            if ($student_lesson == null) {
                abort(401, "Anda Belum Mendaftar ke Kelas " . $lesson->name);
            } else {
                $isRegistered = true;
            }
        }

        if (Auth::user()->role == "mentor") {
            $isRegistered = true;
        }

        $section_id = $section->id;
        $lesson_id = $lesson->id;
        // $section = DB::select("select * from view_course_section where lesson_id = $lesson_id ORDER BY section_order ASC");
        // Fetch all sections for the lesson
        $sections = DB::select("select * from view_course_section where lesson_id = $lesson_id ORDER BY section_order ASC");

        $this->startSection($section_id);
        $section_spec = DB::select("select * from view_course_section where section_id = '$section_id' ");

        // Iterate over the sections and check if each one is already added to the student-section
        foreach ($sections as $key => $section) {
            $section_id = $section->section_id;

            // Check if the section is already added to the student-section
            $isTaken = StudentSection::where('section_id', $section_id)
                ->where('student_id', Auth::id())
                ->exists();

            // Add the 'isTaken' attribute to the section object
            $section->isTaken = $isTaken;

            // Check if the current section is the last section
            if ($key === count($sections) - 1) {
                $section->isLastSection = true;
            } else {
                $section->isLastSection = false;
            }
        }
        $section = $sections;

        return view('lessons.course_play', compact('lesson', 'section', 'section_spec', 'isRegistered'));

        // // abort(401,"Lalalala");
        // $section=$section;
        // $user_id  = Auth::id();
        // $lesson_id = $lesson->id;
        // $dayta = DB::select("select * from view_course_section where lesson_id = $lesson_id ORDER BY section_order ASC");
        // // $dayta = DB::select("select * from view_course_section  where mentor_id = $user_id");
        // return view('lessons.course_play',compact('dayta','lesson','section'));
    }

    function startSection($sectionId)
    {
        $section = $sectionId;
        $student = Auth::id();

        $studentSectionValue = "$student" . "-" . "$section";

        // Check if the student-section already exists
        $existingRecord = StudentSection::where('student-section', $studentSectionValue)->first();

        if ($existingRecord) {
            // Handle the case when the record already exists
            // For example, you can return an error message or redirect back with an error
            // return back()->with('error', 'Student-section already exists.');
        } else {
            // Create a new instance of StudentSection
            $data = new StudentSection();
            $data->student_id = $student;
            $data->section_id = $section;
            $data->setAttribute('student-section', $studentSectionValue);
            // Save the data
            $data->save();

            // Perform any additional actions after saving
            // Redirect or return a success message
            // return redirect()->route('success')->with('success', 'Student-section saved successfully.');
        }
    }

    public function create_section(Lesson $lesson)
    {
        $user_id = Auth::id();
        $dayta = DB::select("select * from view_course where mentor_id = $user_id");
        return view('lessons.section.create_section', compact('lesson'), compact('dayta'));
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        ini_set('memory_limit', '1024000M');
        $rules = [
            'title' => 'required',
            'video' => 'required',
            'content' => 'required',
            'section_order' => 'required|unique:course_section',
            'course_id' => 'required',
            'course_name' => 'required',
        ];
        $customMessages = [
            'required' => 'Mohon Isi Kolom :attribute terlebih dahulu'
        ];

        $this->validate($request, $rules, $customMessages);
        $lesson_id = $request->course_id;

        $video = $request->file('video');
        $video->storeAs("public/class/content/$lesson_id/", $video->hashName());
        $section_order = $lesson_id . "-" . $request->section_order;
        // abort(404,$section_order);

        // Check for duplicate entry
        $existingSection = CourseSection::where('section_order', $section_order)
            ->where('course_id', $lesson_id)
            ->first();

        if ($existingSection) {
            $errorMessage = 'Urutan kelas sudah pernah digunakan, harap pilih nomor urutan yang lain.';
            return redirect("lesson/$lesson_id/section")->withErrors([$errorMessage])->withInput();
        }


        $inputDeyta = CourseSection::create([
            'section_video' => $video->hashName(),
            'section_content' => $request->content,
            'section_order' => $section_order,
            'course_id' => $lesson_id,
            'section_title' => $request->title,
        ]);

        if ($inputDeyta) {
            //redirect dengan pesan sukses
            return redirect("lesson/$lesson_id/section")->with(['success' => 'Kelas Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect("lesson/$lesson_id/section")->with(['error' => 'Kelas Gagal Disimpan!']);
        }
    }

    public function destroy($id)
    {
        $section = CourseSection::findOrFail($id);
        $section_video_file = "public/class/content/" . $section->course_id . "/" . $section->section_video;
        if ($section) {
            // abort('401','Pipipip Calon..........');
        } else {
            abort('401', 'Not Found');
        }
        Storage::disk('local')->delete($section_video_file);

        $section->delete();
        $lesson_id = $section->course_id;

        if ($section) {
            //redirect dengan pesan sukses
            return redirect("lesson/$lesson_id/section")->with(['success' => 'Kelas Berhasil Dihapus!']);
        } else {
            //redirect dengan pesan error
            return redirect("lesson/$lesson_id/section")->with(['error' => 'Kelas Gagal Dihapus!']);
        }
    }

    /**
     * update
     *
     * @return void
     */
    public function update(Request $request, CourseSection $section)
    {

        // try {
        //     //code causing exception to be thrown


        // $this->validate($request, [
        //     'section_u_title'     => 'required',
        //     'section_u_order'     => 'required|unique:course_section',
        // ]);


        CourseSection::findOrFail($section->id);


        $lesson_id = $section->course_id;
        // abort(401,"Lesson_id : ".$lesson_id);
        $section_order = $lesson_id . "-" . $request->section_u_order;
        if ($request->file('section_u_video') == "") {
            $section->update([
                'section_content' => $request->section_u_content,
                'section_order' => $section_order,
                'course_id' => $lesson_id,
                'section_title' => $request->section_u_title,
            ]);
        } else if ($request->file('section_u_video') != "") {
            //hapus old video
            Storage::disk('local')->delete("public/class/content/" . $section->course_id . "/" . $section->section_video);
            //upload new video
            $video = $request->file('section_u_video');
            $cat = $request->input('category');
            $video->storeAs('public/class/content/' . $section->course_id . "/", $video->hashName());
            $section->update([
                'section_video' => $video->hashName(),
                'section_content' => $request->section_u_content,
                'section_order' => $section_order,
                'course_id' => $lesson_id,
                'section_title' => $request->section_u_title,
            ]);
        } else {
        }
        if ($section) {
            //redirect dengan pesan sukses
            return redirect("lesson/$lesson_id/section")->with(['success' => 'Materi Berhasil Diupdate!']);
        } else {
            //redirect dengan pesan error
            return redirect("lesson/$lesson_id/section")->with(['error' => 'Kelas Gagal Diupdate!']);
        }
        // } catch (Exception $e) {
        //     return redirect("lesson/$lesson_id/section")->with(['error' => 'Ada Error Masbro!']);
        // }
    }
}
