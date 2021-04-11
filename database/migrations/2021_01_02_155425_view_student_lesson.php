<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ViewStudentLesson extends Migration
{



    public function up()
    {
        DB::statement("CREATE OR REPLACE VIEW `view_student_lesson` as
         SELECT b.id,b.course_title,b.course_cover_image,b.course_trailer,b.course_category,
         b.course_description,b.mentor_name,b.id as `mentor_id` ,b.profile_url as `mentor_profile_url`,
         b.created_at as `course_created_at`,b.updated_at as `course_updated_at`,
         
         c.id as student_id,c.name as `student_name`,c.profile_url as `student_profile_url`,
         a.created_at,a.updated_at as `register_course_at` from student_lesson a
         LEFT JOIN view_course b on a.lesson_id=b.id 
         LEFT JOIN users c on a.student_id=c.id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW view_student_lesson");
    }
}
