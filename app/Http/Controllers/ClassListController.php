<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use Illuminate\Http\Request;
use Exception;

use App\Models\Blog;
use App\Models\Lesson;
use App\Models\User;
use App\Models\CourseSection;
use App\Models\LessonCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LDAP\Result;

class ClassListController extends Controller
{
    public function landing()
    {
        return view('landing');
    }

    public function classes()
    {
        $dayta = DB::select("select * from view_course");
        MyHelper::addAnalyticEvent(
            "Buka Halaman Kelas","Kelas"
        );
        return view('classes')->with('dayta', $dayta);
    }

    public function classList(Request $request)
    {
        if (!Auth::check()) {
            abort(401, "Anda Harus Login Untuk Melanjutkan ");
        }

        $userID = Auth::id();
        $lessonCategories = LessonCategory::all();

        $sortParam = request('sort' , 'Latest') ;
        $catParam  = request('category', 'All Category');

        $sortBy = ($sortParam == 'Latest') ? 'desc' : 'asc';
        $catBy = ($catParam == 'All Category') ? '*' : $catParam;

        $classesQuery = DB::table('lessons as a')
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
            ->whereNotExists(function ($query) use ($userID) {
                $query->select(DB::raw(1))
                    ->from('student_lesson as sl')
                    ->whereRaw('sl.lesson_id = a.id')
                    ->where('sl.student_id', $userID);
            })
            ->where('a.is_visible', 'y');
            if ($request->q != '') {
                $keyword = '%' . $request->q . '%';
                $classesQuery->where('a.course_title', 'LIKE', $keyword);
            }
            $classesQuery = $classesQuery
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

        $classes = [];
        foreach ($classesQuery as $classItem) {
            if ($classItem->deleted_at === null || $classItem->deleted_at === '') {
                $classes[] = $classItem;
            }
        }


        $view_course = DB::select("select * from view_course");

        return view('student.all_class_new')->with(compact('classes', 'view_course','lessonCategories'));
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

    public function blogs()
    {
        $dayta = DB::select("select * from view_blog");
        return view('blogs')->with('dayta',$dayta);
    }

    public function validatePIN(Request $request)
    {
        $pin = $request->input('pin');
        $idClass = $request->input('idClass');
        $userID = Auth::id();
        $classes = DB::select("SELECT
                    a.*,
                    b.name AS mentor_name,
                    b.profile_url,
                    COUNT(c.student_id) AS num_students_registered,
                    CASE WHEN COUNT(c.student_id) > 0 THEN 1 ELSE 0 END AS is_registered
                FROM
                    lessons a
                LEFT JOIN
                    users b ON a.mentor_id = b.id
                LEFT JOIN
                    student_lesson c ON a.id = c.lesson_id
                WHERE
                    NOT EXISTS (
                        SELECT 1
                        FROM student_lesson sl
                        WHERE sl.lesson_id = a.id
                        AND sl.student_id = $userID -- Replace :user_id with the actual user ID you're interested in
                    )
                GROUP BY
                    a.id, b.name, b.profile_url");
        // Melakukan kueri ke database menggunakan Query Builder
        $course = DB::table('lessons')
        ->where('id', $idClass)
        ->where('pin', $pin)
        ->first();

        // return $course;

        // Jika kelas yang sesuai ditemukan, mengaitkan pengguna dengan kelas tersebut
        if ($course) {
            // Jika kelas ditemukan, Anda dapat menambahkan data ke tabel lain
            $dataToInsert = [
                'student_id' => $userID,
                'lesson_id' => $idClass,
                'student-lesson' => "$userID-$idClass",
                'learn_status' => 0,
                'certificate_file' => "",
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
                // ...
            ];


            // Melakukan penambahan data ke tabel lain (contoh: lessons)
            DB::table('student_lesson')->insert($dataToInsert);

            return redirect('/class/my-class')->with('success', 'Berhasil bergabung dalam kelas!');
        } else {
            // Jika PIN atau ID Kelas tidak valid, memberikan respons dengan status 422 (Unprocessable Entity)
            // return response()->json(['message' => 'PIN atau ID Kelas tidak valid'], 422);
            return redirect('/class/class-list')->with('error', 'PIN kelas tidak valid');
        }

    }

}
