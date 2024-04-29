<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use Illuminate\Http\Request;
use Auth;
use Exception;

use App\Models\Blog;
use App\Models\Lesson;
use App\Models\User;
use App\Models\CourseSection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use DB;
use File;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class MyClassController extends Controller
{
    public function myClass()
    {
        $userID = Auth::id();
        $myClasses = DB::select("
                SELECT *,
                       'new_attribute_value' AS new_attribute  -- Add your new attribute here
                FROM (
                    SELECT
                        RANK() OVER (PARTITION BY a.id ORDER BY cs.id ASC) AS ranking,
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
                    LEFT JOIN
                        course_section cs ON a.id = cs.course_id
                    WHERE EXISTS (
                            SELECT 1
                            FROM student_lesson sl
                            WHERE sl.lesson_id = a.id
                            AND sl.student_id = $userID
                        )
                    GROUP BY
                        a.id, b.name, b.profile_url, cs.id
                ) AS main_table
                WHERE main_table.ranking = 1;
            ");

        // Append new attribute to each row
        foreach ($myClasses as &$class) {
            $firstSection = DB::table('course_section')
                ->where('course_id', '=', $class->id)
                ->orderByRaw("CAST(section_order AS UNSIGNED), section_order ASC")
                ->first();
                
             // Tambahkan pengecekan untuk memastikan $firstSection tidak null
            if ($firstSection !== null) {
                $class->first_section = $firstSection->id;
            } else {
                // Handle jika $firstSection null
                $class->first_section = null; // Atau nilai default lainnya sesuai kebutuhan
            }
        }

        // return $class->first_section;

        return view('student.myclass')->with(compact('myClasses', 'userID'));
    }
}
