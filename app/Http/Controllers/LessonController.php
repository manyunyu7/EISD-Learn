<?php

namespace App\Http\Controllers;

use App\Models\ExamTaker;
use App\Models\LessonCategory;
use Illuminate\Http\Request;

use App\Models\Blog;
use App\Models\Lesson;
use App\Models\User;
use App\Models\StudentLesson;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use App\Models\Exam;

use Alert;
use App\Models\StudentSection;
use App\Http\Controllers\ITHubController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{

    //Redirect to Create Lesson View
    public function create(Request $request)
    {
        $categories = LessonCategory::all();
        $compact = compact('categories');

        if ($request->dump == true) {
            return $compact;
        }
        return view('lessons.create_lesson')->with($compact);
    }
    public function createV2(Request $request)
    {
        $categories = LessonCategory::all();
        // return($m_departments_ithub);
        $compact = compact('categories');

        if ($request->dump == true) {
            return $compact;
        }
        return view('lessons.create_lesson_v2')->with($compact);
    }


    //use above function, this function seems not used.
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
    public function manageV2(Request $request)
    {

        $userID = Auth::id();
        $keyword = NULL;
        $myClasses_searchKeyword = NULL;
        $user_id = Auth::id();
        $lessonCategories = LessonCategory::all();

        $dayta = DB::select("SELECT
            a.* , b.name as `mentor_name`, b.profile_url from lessons a
            LEFT JOIN users b on a.mentor_id=b.id where mentor_id = $user_id");

        $sortParam = request('sort' , 'Latest') ;
        $catParam  = request('category', 'All Category');

        $sortBy = ($sortParam == 'Latest') ? 'desc' : 'asc';
        $catBy = ($catParam == 'All Category') ? '*' : $catParam;


        $myClasses = DB::table('lessons as a')
        ->select([
            'a.*',
            'b.name AS mentor_name',
            'b.profile_url',
            'lc.name as course_category_name',
            'lc.color_of_categories as course_category_color',
            DB::raw('COUNT(c.student_id) AS num_students_registered'),
            DB::raw('CASE WHEN COUNT(c.student_id) > 0 THEN 1 ELSE 0 END AS is_registered')
        ])
        ->leftJoin('lesson_categories as lc','lc.id','=','a.category_id')
        ->leftJoin('users as b', 'a.mentor_id', '=', 'b.id')
        ->leftJoin('student_lesson as c', 'a.id', '=', 'c.lesson_id')
        ->where('b.role', 'mentor')
        ->where('a.mentor_id', $user_id)
        ->whereNull('a.deleted_at');

        if ($request->q != '') {
            $keyword = '%' . $request->q . '%';
            $myClasses->where('a.course_title', 'LIKE', $keyword);
        }
        $myClasses = $myClasses
        ->groupBy('a.id', 'b.name', 'b.profile_url')
        ->orderBy('a.created_at', $sortBy)
        ->when($sortParam == 'Latest', function ($query) use ($sortBy) {
                return $query->orderBy('a.created_at', $sortBy);
            }, function ($query) use ($sortBy) {
                return $query->orderBy('num_students_registered', $sortBy);
            })
        ->when($catParam != 'All Category', function ($query) use ($catParam) {
                return $query->where('lc.name', $catParam);
            })
        ->get();



        // return $myClasses;
        Paginator::useBootstrap();
        return view('lessons.manage_lesson_v2', compact('dayta', 'myClasses', 'keyword', 'myClasses_searchKeyword', 'lessonCategories'));
    }

    public function search(Request $request)
    {
        $lessonCategories = LessonCategory::all();

        $keyword = $request->input('search_keyword');
        $user_id = Auth::id();


        $dayta = DB::select("SELECT
            a.* , b.name as `mentor_name`, b.profile_url from lessons a
            LEFT JOIN users b on a.mentor_id=b.id where mentor_id = $user_id");

        // Buat query dasar
        $query = "
            SELECT
                a.*,
                u.name AS mentor_name,
                lc.name as course_category_name,
                lc.color_of_categories as course_category_color
            FROM
                lessons a
            LEFT JOIN
                users u ON a.mentor_id = u.id AND u.role = 'mentor'
            LEFT JOIN
                lesson_categories lc on lc.id = a.category_id
            WHERE
                a.deleted_at IS NULL
        ";

        // Tambahkan kondisi pencarian jika ada kata kunci
        $bindings = [];
        if (!empty($keyword)) {
            $query .= " AND (a.course_title LIKE :keyword)";
            $bindings['keyword'] = '%' . $keyword . '%';
        }

        $query .= " ORDER BY a.id DESC";

        // Jalankan query dengan parameter pencarian
        $myClasses_searchKeyword = DB::select($query, $bindings);


        // return $myClasses;
        if(Auth::user()->role == 'mentor'){
            return redirect()->to('/lesson/manage_v2?q='.$keyword);
        }
        else if(Auth::user()->role == 'student'){
            return redirect()->to('/class/class-list?q='.$keyword);
        }
        
        Paginator::useBootstrap();
        return view('lessons.manage_lesson_v2', compact('dayta', 'myClasses_searchKeyword', 'keyword', 'lessonCategories'));

        // return $keyword;
    }

    public function editClassV2(Request $request,$lesson_id)
    {
        $categories = LessonCategory::all();
        $category_selected = Lesson::findOrFail($lesson_id);
        // Get Category ID selected
        $categoryID_selected = DB::table('lesson_categories')
            ->select('lesson_categories.id')
            ->where('lesson_categories.name', $category_selected->course_category)
            ->get();



        $myClass = DB::table('lessons')
            ->select('lessons.*', 'users.name AS mentor_name')
            ->leftJoin('users', 'lessons.mentor_id', '=', 'users.id')
            ->where('users.role', 'mentor')
            ->where('lessons.id', $lesson_id)
            ->first(); // Ambil baris pertama dari hasil query

        $deptId = $myClass->department_id;
        $postId = $myClass->position_id;

        $compact = compact('deptId', 'postId', 'categories', 'myClass', 'category_selected', 'categoryID_selected', 'lesson_id');

        if($request->dump==true){
            return $compact;
        }

        return view('lessons.edit_lesson_v2', $compact);
    }

    public function updateClassV2(Request $request, $lesson_id)
    {

        $this->validate($request, [
            // 'image' => 'required',
            'title' => 'required',
            'content' => 'required',
        ]);

        //upload image
        $cat = $request->input('category');

        // $video->storeAs('public/class/trailer', $video->hashName());
        $user_id = Auth::id();
        $update_data_lesson = Lesson::findOrFail($lesson_id);

        $image = $request->file('image');
        $old_image_name = $update_data_lesson->course_cover_image;
        $new_image_name = null;

        if ($image != null) {
            Storage::disk('s3')->delete("profile-s3/$old_image_name");
            $image = $request->file('image');
            $imagePath = "lesson-s3/" . $image->hashName();
            Storage::disk('s3')->put($imagePath, file_get_contents($image));
            $update_data_lesson->course_cover_image = $imagePath;
        }

        $update_data_lesson->course_title       = $request->title;
        $update_data_lesson->course_trailer     = 'Value';
        $update_data_lesson->category_id        = $request->category_id;
        $update_data_lesson->start_time         = $request->start_time;
        $update_data_lesson->end_time           = $request->end_time;
        $canBeAccessValueMapping = [
            'Aktif' => 'y',
            'Tidak Aktif' => 'n',
        ];
        $update_data_lesson->can_be_accessed = $canBeAccessValueMapping[$request->akses_kelas] ?? null;
        $update_data_lesson->mentor_id          = $user_id;
        $update_data_lesson->course_description = $request->content;
        $update_data_lesson->text_descriptions  = '';
        $update_data_lesson->pin                = $request->pass_class;
        $update_data_lesson->position           = $request->position;
        $update_data_lesson->target_employee    = '';
        $newLabelValueMapping = [
            'Aktif' => 'y',
            'Tidak Aktif' => 'n',
        ];

        $update_data_lesson->new_class = $newLabelValueMapping[$request->new_class] ?? null;
        $update_data_lesson->tipe               = $request->tipe;
        $update_data_lesson->department_id      = json_encode($request->department_id);
        $update_data_lesson->position_id        = json_encode($request->position_id);

        if($request->department_id==null || $request->department_id==""){
            $update_data_lesson->department_id = "[]";
        }

        if($request->position_id==null || $request->position_id==""){
            $update_data_lesson->position_id = "[]";
        }



        if ($update_data_lesson->save()) {
            //redirect dengan pesan sukses
            return redirect('lesson/manage_v2')->with(['success' => 'Kelas Berhasil Diperbaharui!']);
        } else {
            //redirect dengan pesan error
            return redirect('lesson/manage_v2')->with(['error' => 'Kelas Gagal Diperbaharui!']);
        }
    }

    public function delete_class_v2($lessonId)
    {
        $current_timestamp                              = Carbon::now();
        $update_data_lesson_for_deleted_at              = Lesson::findOrFail($lessonId);
        $update_data_lesson_for_deleted_at->deleted_at  = $current_timestamp;
        $update_data_lesson_for_deleted_at->save();

        return back()->with(['success' => 'Class Deleted Successfully']);
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


    public function edit(Request $request, Lesson $lesson)
    {

        $categories = LessonCategory::all();
        $compact = compact('lesson', 'categories');

        if ($request->dump == true) {
            return $compact;
        }
        return view('lessons.edit_lesson', compact('lesson', 'categories'));
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
        if (Auth::check()) {
            if (Auth::user()->role == "student") {
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
        if (!empty($section)) {
            $firstSectionId = $section[0]->section_id;
        }
        $nextUrl = "/course/$lesson_id/section/$firstSectionId";
        $abc = url("/") . $nextUrl;
        //$section = DB::select("select * from view_course_section where lesson_id = $lesson_id ORDER BY section_order ASC");
        $compact = compact('lesson', 'firstSectionId', 'nextUrl', 'abc', 'section', 'sectionTakenByStudent', 'lastSectionTaken', 'isRegistered');
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
        // Validate the request data
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
        ]);

        // Find the lesson by ID
        $lesson = Lesson::findOrFail($lesson->id);
        $cat = LessonCategory::findOrFail($request->category_id)->name;
        $image = $request->file('image');
        $video = $request->file('video');

        // Update image if it exists in the request
        if ($image) {
            // Delete old image
            Storage::disk('local')->delete('public/class/cover/' . $lesson->image);
            // Upload new image
            $image->storeAs('public/class/cover', $image->hashName());
        }

        // Update video if it exists in the request
        if ($video) {
            // Delete old video
            Storage::disk('local')->delete('public/class/trailer/' . $lesson->video);
            // Upload new video
            $video->storeAs('public/class/trailer', $video->hashName());
        }

        // Prepare data to be updated
        $lessonData = [
            'course_title' => $request->title,
            'course_category' => $cat,
            'course_description' => $request->content,
            'category_id' => $request->category_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'can_be_accessed' => $request->can_be_accessed,
        ];

        // If image exists, add it to the data
        if ($image) {
            $lessonData['course_cover_image'] = $image->hashName();
        }

        // If video exists, add video-related data to the data
        if ($video) {
            $lessonData = array_merge($lessonData, [
                'course_trailer' => $video->hashName(),
            ]);
        }

        // Update the lesson with the prepared data
        $lesson->update($lessonData);

        // Redirect with success or error message
        return $lesson
            ? redirect('lesson/manage')->with(['success' => 'Data Berhasil Diupdate!'])
            : redirect('lesson/manage')->with(['error' => 'Data Gagal Diupdate!']);
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
        ]);

        //upload image
        $image = $request->file('image');
        $video = $request->file('video');
        $cat = $request->input('category');
        $image->storeAs('public/class/cover', $image->hashName());
        $video->storeAs('public/class/trailer', $video->hashName());
        $user_id = Auth::id();

        $cat = LessonCategory::findOrFail($request->category_id);
        if ($cat != null) {
            $cat = $cat->name;
        }

        $inputDeyta = Lesson::create([
            'course_cover_image' => $image->hashName(),
            'course_title' => $request->title,
            'course_trailer' => $video->hashName(),
            'course_category' => $cat,
            'category_id' => $request->category_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'can_be_accessed' => $request->access,
            'mentor_id' => $user_id,
            'course_description' => $request->content,
            'text_descriptions' => $request->content
            // 'pin' => $request->pass_class,
            // 'position'=> $request->position,
            // 'target_employee'=> $request->target_employee,
            // 'new_class' => $request->new_class
        ]);

        if ($inputDeyta) {
            //redirect dengan pesan sukses
            return redirect('lesson/manage')->with(['success' => 'Kelas Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect('lesson/manage')->with(['error' => 'Kelas Gagal Disimpan!']);
        }
    }
    public function storeV2(Request $request)
    {
        $this->validate($request, [
            'image' => 'required',
            'title' => 'required',
            'content' => 'required',
        ]);

        //upload image
        $image = $request->file('image');
        $imagePath = "";
        if ($image != null) {
            $image = $request->file('image');
            $imagePath = "lesson-s3/" . $image->hashName();
            Storage::disk('s3')->put($imagePath, file_get_contents($image));
        }

        $user_id = Auth::id();

        // Setting Value Department
        $department = $request->department_id;
        $json_department = json_encode($department);
        // return $json_department;
        // Setting Value Position
        $position = $request->position_id;

        $insert_to_Lesson = new Lesson();
        $insert_to_Lesson->course_cover_image = $imagePath;
        $insert_to_Lesson->course_title = $request->title;


        $insert_to_Lesson->course_trailer = 'Value';
        $insert_to_Lesson->category_id = $request->category_id;
        $insert_to_Lesson->start_time = $request->start_time;
        $insert_to_Lesson->end_time = $request->end_time;
        $canBeAccessValueMapping = [
            'Aktif' => 'y',
            'Tidak Aktif' => 'n',
        ];
        $insert_to_Lesson->can_be_accessed = $canBeAccessValueMapping[$request->akses_kelas] ?? null;
        $insert_to_Lesson->mentor_id = $user_id;
        $insert_to_Lesson->course_description = $request->content;
        $insert_to_Lesson->text_descriptions = '';
        $insert_to_Lesson->pin = $request->pass_class;
        $insert_to_Lesson->position = $request->position;
        $insert_to_Lesson->target_employee = '';
        $newLabelValueMapping = [
            'Aktif' => 'y',
            'Tidak Aktif' => 'n',
        ];

        $insert_to_Lesson->new_class = $newLabelValueMapping[$request->new_class] ?? null;
        $insert_to_Lesson->tipe = $request->tipe;
        $insert_to_Lesson->department_id = json_encode($request->department_id);
        $insert_to_Lesson->position_id = json_encode($request->position_id);

        if($request->department_id==null || $request->department_id==""){
            $insert_to_Lesson->department_id = "[]";
        }

        if($request->position_id==null || $request->position_id==""){
            $insert_to_Lesson->position_id = "[]";
        }


        if ($insert_to_Lesson->save()) {
            //redirect dengan pesan sukses
            return redirect('lesson/manage_v2')->with(['success' => 'Kelas Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return "error gais";
            return redirect('lesson/manage_v2')->with(['error' => 'Kelas Gagal Disimpan!']);
        }
    }

    public function fetchDepartments()
    {
        $departments = DB::connection('ithub')
            ->table('m_departments')
            ->select('id', 'code', 'name')
            ->where('code', 'like', '%_NEW%')
            ->get();
        // return response()->json($departments);
        return response()->json($departments);
    }
    public function fetchPositions()
    {
        $positions = DB::connection('ithub')
            ->table('m_group_employees')
            ->select('id', 'code', 'name')
            ->get();
        // return response()->json($departments);
        return $positions;
    }
    public function fetchShowCourse(Request $request)
    {
        // $isVisible = Lesson::findOrFail($lesson->id);
        $lessonId = $request->input('lesson_id');
        $switchStatus = $request->input('switch_status');

        $isVisible = Lesson::findOrFail($lessonId);
        $isVisible->is_visible = $switchStatus;

        if ($isVisible->save()) {
            //redirect dengan pesan sukses
            return redirect('lesson/manage_v2')->with(['success' => 'Kelas Telah Diaktifkan!']);
            // return back();
        } else {
            //redirect dengan pesan error
            return redirect('lesson/manage_v2')->with(['error' => 'Kelas di Nonaktifkan!']);
            // return back();
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

    public function viewDashboard()
    {
        $user_id = Auth::id();
        $dayta = DB::select("select * from view_course where mentor_id = $user_id");
        $myClasses = DB::select("
                        SELECT
                            a.*,
                            u.name AS mentor_name,
                            lc.color_of_categories as course_category_color,
                            lc.name as course_category_name
                        FROM
                            lessons a
                        LEFT JOIN
                            users u ON a.mentor_id = u.id AND u.role = 'mentor'
                        LEFT JOIN
                            lesson_categories lc on a.category_id = lc.id
                        WHERE
                            a.deleted_at IS NULL
                            AND
                            a.is_visible = 'y'
                        ORDER BY
                            a.id ASC
                    ");



        // Hitung jumlah student yang telah menyelesaikan setiap course
        $courseCompleteCount = DB::table('student_lesson')
            ->select('lesson_id', DB::raw('COUNT(*) AS completed_students'))
            ->where('learn_status', [0, 1])
            ->groupBy('lesson_id')
            ->get();

        // Hitung jumlah total student dalam setiap course
        $totalStudentsCount = DB::table('student_lesson')
            ->select('lesson_id', DB::raw('COUNT(*) AS total_students'))
            // ->join('lessons', 'student_lesson.lesson_id', '=', 'lessons.id')
            // ->where('lessons.is_visible', 'y') // Perhatikan penggunaan tanda kutip pada 'y'
            ->groupBy('lesson_id')
            ->get();



        // Inisialisasi variabel untuk menghitung jumlah kelas yang sedang dalam status "On Progress Course"
        $onProgressCount = 0;
        $completedCourseCount = 0;
        // Gabungkan kedua hasil perhitungan sebelumnya untuk menentukan apakah suatu course telah selesai
        $courseStatus = [];
        foreach ($courseCompleteCount as $course) {
            $lessonId = $course->lesson_id;
            $completedStudents = $course->completed_students;
            $totalStudents = $totalStudentsCount->where('lesson_id', $lessonId)->first()->total_students;

            // Simpan dalam variabel status masing-masing kelas
            $courseStatus[$lessonId] = ($completedStudents == $totalStudents) ? 100 : round((($completedStudents / $totalStudents) * 100));
            $class = DB::select("
                SELECT
                    lsn.id AS lesson_id,
                    lsn.course_title AS lesson_title,
                    lsn.course_cover_image AS lesson_cover_img,
                    lsn.department_id AS lesson_dept_id,
                    lsn.position_id AS lesson_posit_id,
                    cs.quiz_session_id AS exam_session_id,
                    cs.section_title AS section_title,
                    cs.id AS section_id,
                    cs.created_at AS date_create,
                    es.exam_type AS exam_type,
                    exm.id AS exam_id,
                    COUNT(et.id) AS students_count
                FROM
                    lessons lsn
                LEFT JOIN
                    course_section cs ON lsn.id = cs.course_id
                LEFT JOIN
                    exam_sessions es ON cs.quiz_session_id = es.id
                LEFT JOIN
                    exams exm ON es.exam_id = exm.id
                LEFT JOIN
                    exam_takers et ON es.id = et.session_id
                WHERE
                    lsn.id = 18
                    AND
                    es.exam_id = exm.id
                    AND
                    es.exam_type = 'Post Test'
                    AND
                    et.session_id = 58
                GROUP BY
                    lsn.id,
                    lsn.course_title,
                    lsn.course_cover_image,
                    lsn.department_id,
                    lsn.position_id,
                    cs.quiz_session_id,
                    cs.section_title,
                    cs.id,
                    cs.created_at,
                    es.exam_type,
                    exm.id
                ORDER BY
                    cs.created_at DESC
            ");
        }

        // dd($class);
        Paginator::useBootstrap();
        return view(
            'main.mentorDashboard',
            compact(
                'dayta',
                'myClasses',
                'courseStatus',
                'completedStudents',
                'totalStudents'
            )
        );
    }

    public function view_courseDashboard($lesson_id)
    {
        $class = DB::select("
                        SELECT
                            lsn.id AS lesson_id,
                            lsn.course_title AS lesson_title,
                            lsn.course_cover_image AS lesson_cover_img,
                            lsn.department_id AS lesson_dept_id,
                            lsn.position_id AS lesson_posit_id,
                            cs.quiz_session_id AS exam_session_id,
                            cs.section_title AS section_title,
                            cs.id AS section_id,
                            cs.created_at AS date_create,
                            es.exam_type AS exam_type,
                            exm.id AS exam_id
                        FROM
                            lessons lsn
                        LEFT JOIN
                            course_section cs ON lsn.id = cs.course_id
                        LEFT JOIN
                            exam_sessions es ON cs.quiz_session_id = es.id
                        LEFT JOIN
                            exams exm ON es.exam_id = exm.id
                        WHERE
                            lsn.id = $lesson_id
                            AND
                            es.exam_id = exm.id
                            AND
                            es.exam_type = 'Post Test'
                        ORDER BY
                            cs.created_at DESC
        ");
        $totalStudentsCount = DB::table('student_lesson')
            ->select('lesson_id', DB::raw('COUNT(*) AS total_students'))
            ->groupBy('lesson_id')
            ->get();
        $totalStudents = $totalStudentsCount->where('lesson_id', $lesson_id)->first()->total_students;


        if (!empty($class)) {
            // QUERY POST TEST
            $examSessionId = $class[0]->exam_session_id;
            $examSectionId = $class[0]->section_id;

            $students_takePostTest = DB::select("
                                        SELECT
                                            u.name,
                                            u.id,
                                            u.profile_url,
                                            u.department,
                                            MAX(et.current_score) AS highest_score,
                                            es.exam_type AS exam_type,
                                            et.course_section_flag AS course_section_id,
                                            et.course_flag AS course_id
                                        FROM
                                            exam_takers et
                                        LEFT JOIN
                                            course_section cs ON et.session_id = cs.quiz_session_id
                                        LEFT JOIN
                                            exam_sessions es ON et.session_id = es.id
                                        LEFT JOIN
                                            users u ON et.user_id = u.id
                                        WHERE
                                            et.session_id = $examSessionId
                                            AND et.course_section_flag = $examSectionId
                                            AND es.exam_type = 'Post Test'
                                        GROUP BY
                                            et.user_id, et.course_flag
                                        ORDER BY
                                            highest_score ASC

            ");
            // dd($students_takePostTest);

            $students_notTakenPostTest = DB::select("
                SELECT
                    u.id,
                    u.name,
                    u.profile_url,
                    u.department
                FROM
                    users u
                INNER JOIN
                    student_lesson sl ON u.id = sl.student_id
                INNER JOIN
                    course_section cs ON sl.lesson_id = cs.course_id AND cs.id = $examSectionId
                WHERE
                    NOT EXISTS (
                        SELECT 1
                        FROM exam_takers et
                        WHERE et.user_id = u.id
                        AND et.session_id = $examSessionId
                        AND et.course_section_flag = $examSectionId
                    )
            ");

            $list_studentTaken = ExamTaker::join('users', 'exam_takers.user_id', '=', 'users.id')
                ->where('exam_takers.session_id', $examSessionId)
                ->where('exam_takers.course_section_flag', $examSectionId)
                ->select('exam_takers.current_score', 'users.name', 'users.profile_url', 'exam_takers.finished_at')
                ->get();
            // dd();

            // Mengurutkan hasil berdasarkan current_score secara menurun
            $list_studentTaken = $list_studentTaken->sortByDesc('current_score');

            // Mengambil top 3 siswa dengan highest score tertinggi
            $top_three_students = $list_studentTaken->take(3);
            // Memeriksa jumlah siswa dalam koleksi
            if ($top_three_students->count() >= 3) {
                // Memasukkan data dari masing-masing item ke dalam variabel rank1, rank2, dan rank3
                $rank1 = $top_three_students[0]; // Siswa dengan highest score tertinggi
                $rank2 = $top_three_students[1]; // Siswa dengan score tertinggi kedua
                $rank3 = $top_three_students[2]; // Siswa dengan score tertinggi ketiga
            } else {
                // Jika jumlah siswa kurang dari 3, berikan pesan kesalahan atau lakukan penanganan sesuai kebutuhan
                // Misalnya:
                $rank1 = $top_three_students[0]; // Siswa dengan highest score tertinggi
                $rank2 = $top_three_students[0]; // Siswa dengan score tertinggi kedua
                $rank3 = $top_three_students[0]; // Siswa dengan score tertinggi ketiga
            }

            $count_studentsTaken = count($students_takePostTest);
            $count_studentsUntaken = $totalStudents - $count_studentsTaken;

            // 3 Data Exam Terbaru
            $latestExams    = Exam::orderBy('created_at', 'desc')->take(3)->get(['title']);
            return  view(
                'main.course_dashboard',
                compact(
                    'class',
                    'totalStudents',
                    'count_studentsTaken',
                    'count_studentsUntaken',
                    'students_notTakenPostTest',
                    'list_studentTaken',
                    'rank1',
                    'rank2',
                    'rank3',
                    'students_takePostTest'
                )
            );
        } else {
            // Tampilkan pesan SweetAlert jika tidak ada post test dalam kelas
            // Alert::info('Tidak Ada Post Test', 'Tidak ada post test dalam kelas ini.');
            return view('main.course_dashboard');
        }
    }
}
