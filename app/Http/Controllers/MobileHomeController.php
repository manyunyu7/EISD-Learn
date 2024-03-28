<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\LessonCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MobileHomeController extends Controller
{

    public function classCategories(Request $request)
    {
        $userID = Auth::id();

        $data = LessonCategory::whereNull('deleted_at')
            ->orWhere('deleted_at', '') // Assuming empty string represents empty value
            ->get();

        $data =  DB::connection('ithub')->table('m_departments')->get();
        $newData = [];

        foreach ($data as $item) {
            $newItem = [
                "id" => 1,
                "name" => $item->name,
                "img_path" => "qYJgIKFdohPsLTdGYhOuJcGgdABZllJpVZbh1NzV.png",
                "created_by" => null,
                "deleted_at" => null,
                "created_at" => "2024-03-04T06:42:14.000000Z",
                "updated_at" => "2024-03-25T02:09:30.000000Z",
                "color_of_categories" => "#20cf2c",
                "full_img_path" => "http://0.0.0.0:5253/storage/class/category/qYJgIKFdohPsLTdGYhOuJcGgdABZllJpVZbh1NzV.png"
            ];

            $newData[] = $newItem;
        }

        return MyHelper::responseSuccessWithData(
            200,
            200,
            2,
            "success",
            "success",
            $newData);
    }


    public function classList(Request $request)
    {
        $userID = Auth::id();

        $classes = DB::table('lessons AS a')
            ->select(
                'a.*',
                'b.name AS mentor_name',
                'b.profile_url',
                DB::raw('COUNT(c.student_id) AS num_students_registered'),
                DB::raw('COUNT(DISTINCT d.student_id) AS num_students'),
                DB::raw('CASE WHEN COUNT(c.student_id) > 0 THEN 1 ELSE 0 END AS is_registered')
            )
            ->leftJoin('users AS b', 'a.mentor_id', '=', 'b.id')
            ->leftJoin('student_lesson AS c', function ($join) use ($userID) {
                $join->on('a.id', '=', 'c.lesson_id')
                    ->where('c.student_id', '=', $userID);
            })
            ->leftJoin('student_lesson AS d', 'a.id', '=', 'd.lesson_id')
            ->groupBy('a.id', 'b.name', 'b.profile_url')
            ->get();

        foreach ($classes as $data) {
            // Ambil warna kategori jika kategori ada dalam $lessonCategories
            $color = $lessonCategories[$data->course_category]->color_of_categories ?? '#007bff';
            $numStudents = DB::table('student_lesson')
                ->where('lesson_id', $data->id)
                ->get();
            $numStudentsCount = $numStudents->count();

            $data->color = $color;
            $data->full_img_path =   url("/").Storage::url('public/class/cover/') . $data->course_cover_image;
            $data->num_students = $numStudentsCount;
        }


        return MyHelper::responseSuccessWithData(
            200,
            200,
            2,
            "success",
            "success",
            $classes);
    }
}
