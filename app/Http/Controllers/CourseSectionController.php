<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\ExamSession;
use Illuminate\Http\Request;
use Exception;

use App\Models\Blog;
use App\Models\Lesson;
use App\Models\User;
use App\Models\CourseSection;
use App\Models\StudentSection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseSectionController extends Controller
{
    public function manage_section(Request $request, Lesson $lesson)
    {
        $user_id = Auth::id();
        $lesson_id = $lesson->id;
        $examSessions = ExamSession::where(function ($query) {
            $query->whereNull('is_deleted')
                ->orWhere('is_deleted', '<>', 'y');
        })->get();

        if ($user_id != $lesson->mentor_id) {
            abort(401, 'Unauthorized');
        }
        $dayta = DB::select("select * from view_course_section where lesson_id = $lesson_id ORDER BY section_order ASC");
        // $dayta = DB::select("select * from view_course_section  where mentor_id = $user_id");
        $compact = compact('dayta', 'examSessions', 'lesson');

        if ($request->dump == true) {
            return $compact;
        }
        return view('lessons.section.manage_section', $compact);
    }

    public function updateScores(Request $request)
    {
        $scoresData = $request->input('scores');

        foreach ($scoresData as $studentSectionId => $score) {
            // Assuming you have a StudentSection model
            $studentSection = StudentSection::find($studentSectionId);
            if ($studentSection) {
                $studentSection->score = $score;
                $studentSection->save();
            }
        }

        return response()->json(['message' => 'Scores updated successfully']);
    }

    public function viewInputScore(Request $request, $lessonId, $sectionId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $section = CourseSection::findOrFail($sectionId);


        $students = DB::table('student_section')
            ->select([
                'student_section.id as student_section_id',
                'student_section.student_id',
                'student_section.score',
                'lessons.course_title',
                'lessons.id as course_id',
                'users.name as student_name',
                'users.email as student_email',
                'users.profile_url as student_profile_url',
                'course_section.section_title',
                'course_section.section_order',
                'course_section.id as section_id',
                'student_section.created_at as taken_at',
            ])
            ->leftJoin('users', 'student_section.student_id', '=', 'users.id')
            ->leftJoin('course_section', 'student_section.section_id', '=', 'course_section.id')
            ->leftJoin('lessons', 'course_section.course_id', '=', 'lessons.id')
            ->where('course_section.id', $sectionId)
            ->get();


        $compact = compact('students', 'lesson');
        if ($request->dump == true) {
            return $compact;
        }
        return view('lessons.section.input_score', $compact);
    }


    public function goToNextSection(Lesson $lesson, CourseSection $lesson_id)
    {
    }

    // SEE SECTION
    public function see_section(Request $request, Lesson $lesson, CourseSection $section)
    {
        // Find the next and previous sections
        $nextSectionId = null;
        $prevSectionId = null;
        $currentSectionId = $section->id;

        if (!Auth::check()) {
            MyHelper::addAnalyticEvent(
                "Belum Login Buka Section", "Course Section"
            );
            abort(401, "Anda Harus Login Untuk Melanjutkan " . $lesson->name);
        }

        $user_id = Auth::user()->id;
        $lesson_id = $lesson->id;
        $isRegistered = false;
        if (Auth::user()->role == "student") {
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

        $section_id = $section->id;
        $lesson_id = $lesson->id;

        $lessonObject = Lesson::findOrFail($lesson_id);
        if (Auth::user()->role == "student") {
            if ($lessonObject->can_be_accessed == "n") {
                MyHelper::addAnalyticEvent(
                    "Reject Section Diluar Jadwal", "Course Section"
                );
                abort(401, "Kelas ini hanya bisa diakses pada jadwal yang telah ditentukan ");
            }
        }


        // Get the preceding sections
        $precedingSections = DB::select("
         SELECT * FROM course_section WHERE course_id = :course_id ORDER BY section_order ASC", [
            'course_id' => $lesson_id,
        ]);

        $precedingSectionIds = array_map(function ($section) {
            return $section->id;
        }, $precedingSections);

        $studentTakenSections = DB::select("
        SELECT
            ss.student_id,
            users.name,
            lessons.course_title,
            lessons.id AS lessons_id,
            ss.section_id,
            ss.`student-section`
        FROM
            student_section AS ss
        LEFT JOIN users ON users.id = ss.student_id
        LEFT JOIN course_section ON ss.section_id = course_section.id
        LEFT JOIN lessons ON course_section.course_id = lessons.id
        WHERE users.id = :user_id AND lessons.id = :lessons_id
      ", [
            'user_id' => Auth::id(),
            'lessons_id' => $lesson_id,
        ]);
        $studentTakenSectionIds = array_map(function ($section) {
            return $section->section_id;
        }, $studentTakenSections);

        $currentSectionIndex = array_search($section_id, $precedingSectionIds);
        if ($currentSectionIndex !== false) {
            if ($currentSectionIndex < count($precedingSectionIds) - 1) {
                $nextSectionId = $precedingSectionIds[$currentSectionIndex + 1];
            }

            if ($currentSectionIndex > 0) {
                $prevSectionId = $precedingSectionIds[$currentSectionIndex - 1];
            }
        }

        $sectionTakenByStudent = null;
        $lastSectionTaken = null;

        if (Auth::check()) {
            if (Auth::user()->role == "student") {

                if ($section->can_be_accessed == "n") {
                    abort(401, "Materi baru dapat diakses pada jadwal yang telah ditentukan");
                }
                $sectionTakenByStudent = FacadesDB::table('student_section as ss')
                    ->select('section_id')
                    ->leftJoin('users', 'users.id', '=', 'ss.student_id')
                    ->leftJoin('course_section', 'ss.section_id', '=', 'course_section.id')
                    ->leftJoin('lessons', 'course_section.course_id', '=', 'lessons.id')
                    ->where('ss.student_id', \Illuminate\Support\Facades\Auth::id())
                    ->where('lessons.id', $lesson_id) // Add the condition lessons.id = 5
                    ->pluck('ss.section_id')
                    ->toArray();

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

        //return $precedingSectionIds;
        // Check if the student has taken all the preceding sections
        $isPrecedingTaken = StudentSection::whereIn('section_id', $precedingSectionIds)
            ->where('student_id', $user_id)
            ->exists();

        $sectionTakenOnCourseCount = DB::table('student_section as ss')
            ->leftJoin('users', 'users.id', '=', 'ss.student_id')
            ->leftJoin('course_section', 'ss.section_id', '=', 'course_section.id')
            ->leftJoin('lessons', 'course_section.course_id', '=', 'lessons.id')
            ->where('ss.student_id', Auth::id())
            ->where('lessons.id', $lesson_id)
            ->count();

        // $section = DB::select("select * from view_course_section where lesson_id = $lesson_id ORDER BY section_order ASC");
        // Fetch all sections for the lesson
        $student_sections = DB::select("select * from student_section ");
        $sections = DB::select("select * from view_course_section where lesson_id = $lesson_id ORDER BY section_order ASC");
        $section_spec = DB::select("select * from view_course_section where section_id = '$section_id' ");

        // Iterate over the sections and check if each one is already added to the student-section
        foreach ($sections as $key => $section) {
            // Check if the section is already added to the student-section
            $isTaken = StudentSection::where('section_id', $section->section_id)
                ->where('student_id', Auth::id())
                ->exists();

            // Add the 'isTaken' attribute to the section object
            $section->isTaken = $isTaken;
            $section->isCurrent = $section_id;

            if ($section->section_id == $section_id) {
                $section->isCurrent = true;
            } else {
                $section->isCurrent = false;
            }
        }

        $section = $sections;
        $firstSectionId = null;
        $lastSectionId = null;

        $next_section = $nextSectionId;
        $prev_section = $prevSectionId;
        $sectionOrder = $precedingSectionIds;

        if (!empty($sectionOrder)) {
            $firstSectionId = $sectionOrder[0];
            $lastSectionId = end($sectionOrder);
        }

        $isFirstSection = false;
        if ($firstSectionId == $section_id) {
            $isFirstSection = true;
        }

        $courseId = $lesson_id;
        $isStudent = false;


        $isEligibleStudent = true; //eligible to open the section
        if (Auth::user()->role == "student") {
            $isStudent = true;
            $completedSections = $sectionTakenByStudent;

            // Get the index of the current section in the sectionOrder array
            $currentSectionIndex = array_search($currentSectionId, $sectionOrder);

            // Loop through the sectionOrder array from the beginning until the current section index
            for ($i = 0; $i < $currentSectionIndex; $i++) {
                // Check if the section from sectionOrder exists in completedSections
                if (!in_array($sectionOrder[$i], $completedSections)) {
                    $isEligibleStudent = false;
                    abort(401, "Anda Harus Menyelesaikan Bagian-bagian Sebelumnya Untuk Mengakses Bagian Ini");
                }
            }
            if ($isEligibleStudent) {
                $this->startSection($currentSectionId);
            }
        }

        $compact = compact('isEligibleStudent', 'currentSectionId', 'courseId', 'next_section', 'prev_section',
            'isStudent', 'sectionTakenByStudent', 'sectionTakenOnCourseCount', 'isFirstSection',
            'firstSectionId', 'lastSectionId', 'isPrecedingTaken',
            'sectionOrder', 'lesson', 'section', 'section_spec', 'isRegistered');


        if ($request->dump == true) {
            return $compact;
        }

        MyHelper::addAnalyticEvent(
            "Buka Section", "Course Section"
        );

        return view('lessons.course_play', $compact);
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
        MyHelper::addAnalyticEvent(
            "Buka Halaman Buat Section", "Create Section"
        );
        $user_id = Auth::id();
        $dayta = DB::select("select * from view_course where mentor_id = $user_id");
        return view('lessons.section.create_section', compact('lesson'), compact('dayta'));
    }

    /**
     * store
     *
     * @param mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        ini_set('memory_limit', '1024000M');
        $rules = [
            'title' => 'required',
//            'video' => 'required',
//            'content' => 'required',
            'section_order' => 'required|unique:course_section',
            'course_id' => 'required',
//            'course_name' => 'required',
        ];
        $customMessages = [
            'required' => 'Mohon Isi Kolom :attribute terlebih dahulu'
        ];

        $this->validate($request, $rules, $customMessages);
        $lesson_id = $request->course_id;

        if($request->video!=null){
            $video = $request->file('video');
            $video->storeAs("public/class/content/$lesson_id/", $video->hashName());
        }

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

        // Create an instance of CourseSection
        $inputDeyta = new CourseSection();

        // Set common attributes
        $inputDeyta->course_id = $lesson_id ?? '';
        $inputDeyta->section_content = $request->content ?? '';
        $inputDeyta->section_order = $section_order ?? '';
        $inputDeyta->can_be_accessed = $request->access ?? '';
        $inputDeyta->quiz_session_id = $request->quiz_session_id ?? '';
        $inputDeyta->section_title = $request->title ?? '';
        $inputDeyta->section_video = " ";

        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            // Handle the video file if it's present and valid
            $video = $request->file('video');
            $video->storeAs("public/class/content/$lesson_id/", $video->hashName());
            $inputDeyta->section_video = $video->hashName();
        }

        // Save the CourseSection object
        $inputDeyta->save();


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
                'can_be_accessed' => $request->access,
                'quiz_session_id' => $request->quiz_session_id,
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
                'can_be_accessed' => $request->access,
                'quiz_session_id' => $request->quiz_session_id,
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
