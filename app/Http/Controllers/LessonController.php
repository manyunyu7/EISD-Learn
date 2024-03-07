<?php

namespace App\Http\Controllers;

use App\Models\ExamTaker;
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
use App\Models\StudentSection;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB as FacadesDB;

class LessonController extends Controller
{

    //Redirect to Create Lesson View
    public function create()
    {
        return view('lessons.create_lesson');
    }
    public function create_v2()
    {
        return view('lessons.create_lesson_v2');
    }

    public function add()
    {
        return view('lesson.create');
    }

    public function index()
    {
        return view('main.home');
    }


    public function seeStudent($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $user_id = Auth::id();
        $dayta = DB::select("SELECT * FROM view_course WHERE mentor_id = $user_id");
        $students = DB::select("SELECT * FROM student_lesson WHERE lesson_id = $lessonId");
        $sections = DB::select("SELECT * FROM course_section WHERE course_id = $lessonId");

        $student_sections = DB::select("
                                SELECT ss.*, cs.course_id
                                FROM student_section ss
                                INNER JOIN course_section cs ON ss.section_id = cs.id
                                WHERE cs.course_id = $lessonId
                            ");

        // Organize the sections data into an associative array for easier access
        $sectionsData = [];
        foreach ($sections as $section) {
            $sectionsData[$section->id] = $section;
        }

        // Create an associative array to hold student-wise course data
        $courseData = [];
        foreach ($student_sections as $student_section) {
            $studentId = $student_section->student_id;
            $sectionId = $student_section->section_id;
            $courseId = $student_section->course_id;

            // Get the student name from the User model
            $student = User::findOrFail($studentId);

            // Add student data if not already added
            if (!isset($courseData[$studentId])) {
                $courseData[$studentId] = [
                    'student_id' => $studentId,
                    'student_name' => $student->name, // Use the 'name' field from the User model
                    'student_profile_url' => $student->student_profile_url,
                    'course_id' => $courseId,
                    'section_taken_count' => 0,
                    'section_remaining_count' => count($sections),
                    'section_taken' => [],
                    'section_remaining' => $sections,
                    'completion_percentage' => '0%', // Initialize with 0% completion
                ];
            }

            // Add section data to the student's sections_taken array
            $courseData[$studentId]['section_taken'][] = [
                'section_id' => $sectionId,
                'section_title' => $sectionsData[$sectionId]->section_title,
            ];
            $courseData[$studentId]['section_taken_count']++;
            $courseData[$studentId]['section_remaining_count']--;
            unset($courseData[$studentId]['section_remaining'][$sectionId]);

            // Calculate the completion percentage
            $totalSections = count($sections);
            if ($totalSections > 0) {
                $completedSections = $courseData[$studentId]['section_taken_count'];
                $completionPercentage = ($completedSections / $totalSections) * 100;
                $courseData[$studentId]['completion_percentage'] = number_format($completionPercentage, 2) . '%';
            } else {
                $courseData[$studentId]['completion_percentage'] = '0%';
            }
        }

        // Create an array to hold the student data without the nested "courseData" structure
        $studentData = [];
        foreach ($courseData as $studentId => $data) {
            $studentData[] = $data;
        }

        Paginator::useBootstrap();
        $compact = compact('dayta', 'lesson', 'students', 'studentData');
        // Uncomment the following line to return the view (if you need to use it later)
        return view('lessons.manage_student', $compact);
        return $compact;
    }

    public function manage()
    {
        $user_id = Auth::id();
        $dayta = DB::select("select * from view_course where mentor_id = $user_id");
        Paginator::useBootstrap();
        // return $dayta;
        return view('lessons.manage_lesson', compact('dayta'));
    }
    public function manage_v2()
    {
        $user_id = Auth::id();
        $dayta = DB::select("select * from view_course where mentor_id = $user_id");
        Paginator::useBootstrap();
        // return $dayta;
        return view('lessons.manage_lesson_v2', compact('dayta'));
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

        // Delete related records in ExamTaker
        ExamTaker::where('course_flag', $id)->delete();

        if ($lesson) {
            //redirect dengan pesan sukses
            return redirect('lesson/manage')->with(['success' => 'Kelas Berhasil Dihapus!']);
        } else {
            //redirect dengan pesan error
            return redirect('lesson/manage')->with(['error' => 'Kelas Gagal Dihapus!']);
        }
    }


    public function edit(Request $request,Lesson $lesson)
    {
        if($request->dump==true){
            return $lesson;
        }
        return view('lessons.edit_lesson', compact('lesson'));
    }



    public function show(Lesson $lesson)
    {
        $user_id = FacadesAuth::id();
        $lesson_id = $lesson->id;
        $lessonz = FacadesDB::table('view_course')
            ->where('id', "$lesson_id")
            ->get()
            ->toArray();

        $lesson = $lessonz[0];

        $student_lesson = FacadesDB::table('student_lesson')
            ->where('student-lesson', "$user_id-$lesson_id")
            ->get()
            ->toArray();

        $isRegistered = true;
        if ($student_lesson == null) {
            $isRegistered = false;
        }

        $sections = FacadesDB::select("select * from view_course_section where lesson_id = $lesson_id ORDER BY section_order ASC");

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

        $sectionTakenByStudent = null;
        $lastSectionTaken = null;
        if(Auth::check()){
            if(Auth::user()->role=="student"){
                $sectionTakenByStudent = FacadesDB::table('student_section as ss')
                    ->leftJoin('users', 'users.id', '=', 'ss.student_id')
                    ->leftJoin('course_section', 'ss.section_id', '=', 'course_section.id')
                    ->leftJoin('lessons', 'course_section.course_id', '=', 'lessons.id')
                    ->where('ss.student_id', \Illuminate\Support\Facades\Auth::id())
                    ->where('lessons.id', $lesson_id) // Add the condition lessons.id = 5
                    ->count();

                $lastSectionTaken = FacadesDB::table('student_section as ss')
                    ->leftJoin('users', 'users.id', '=', 'ss.student_id')
                    ->leftJoin('course_section', 'ss.section_id', '=', 'course_section.id')
                    ->leftJoin('lessons', 'course_section.course_id', '=', 'lessons.id')
                    ->where('ss.student_id', \Illuminate\Support\Facades\Auth::id())
                    ->where('lessons.id', $lesson_id)
                    ->orderBy('ss.id', 'desc') // Assuming 'id' is the primary key column in 'student_section' table
                    ->first();

            }
        }


        $section = $sections;
        $firstSectionId = null;
        if(!empty($section)){
            $firstSectionId = $section[0]->section_id;
        }
        $nextUrl = "/course/$lesson_id/section/$firstSectionId";
        $abc = url("/").$nextUrl;
        //$section = DB::select("select * from view_course_section where lesson_id = $lesson_id ORDER BY section_order ASC");
        $compact = compact('lesson', 'firstSectionId','nextUrl','abc','section', 'sectionTakenByStudent','lastSectionTaken', 'isRegistered');
        return view('lessons.main_course', $compact);
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
            'title' => 'required',
            'content' => 'required'
        ]);
        $lesson = Lesson::findOrFail($lesson->id);
        $cat = $request->input('category');

        if ($request->file('image') == "" && $request->file('video') == "") {
            $lesson->update([
                'course_title' => $request->title,
                'course_description' => $request->content,
                'course_category' => $request->input('category')
            ]);
        } else if ($request->file('video') == "" && $request->file('image') != "") {
            //hapus old image
            Storage::disk('local')->delete('public/class/cover/' . $lesson->image);
            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/class/cover', $image->hashName());
            $lesson->update([
                'course_cover_image' => $image->hashName(),
                'course_title' => $request->title,
                'course_category' => $request->input('category'),
                'course_description' => $request->content
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
                'course_title' => $request->title,
                'course_trailer' => $video->hashName(),
                'course_category' => $request->input('category'),
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'can_be_accessed' => $request->can_be_accessed,
                'course_description' => $request->content
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
        // return $request->all();
        ini_set('upload_max_filesize', '500M');
        ini_set('post_max_size', '500M');
        // Alert::success('pesan yang ingin disampaikan', 'Judul Pesan');
        $this->validate($request, [
            'image' => 'required',
            'title' => 'required',
            'content' => 'required',
            'category' => 'required_without_all',
        ]);

        //upload image
        $image = $request->file('image');
        $video = $request->file('video');
        $cat = $request->input('category');
        $image->storeAs('public/class/cover', $image->hashName());
        $video->storeAs('public/class/trailer', $video->hashName());
        $user_id = Auth::id();

        $cats = $request->input('category');

        $inputDeyta = Lesson::create([
            'course_cover_image' => $image->hashName(),
            'course_title' => $request->title,
            'course_trailer' => $video->hashName(),
            'course_category' => $cat,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'can_be_accessed' => $request->access,
            'mentor_id' => $user_id,
            'course_description' => $request->content,
            'text_descriptions' => $request->content
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
            $user_id = Auth::user()->id;
            $registerLesson = StudentLesson::create([
                'student_id' => $user_id,
                'lesson_id' => $request->course_id,
                'learn_status' => 0,
                'certificate_file' => "",
                'student-lesson' => "$user_id-$request->course_id",
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
        $user_id = Auth::user()->id;
        $course_id = $request->course_id;
        $delete = DB::table('student_lesson')->where('student-lesson', '=', $user_id . "-" . $course_id)->delete();
        if ($delete) {
            return redirect('/home')->with(['success' => 'Berhasil Drop Kelas!']);
        } else {
            return redirect('/home')->with(['error' => 'Gagal Drop Kelas!']);
        }
    }
}
