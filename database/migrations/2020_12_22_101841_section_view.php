<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SectionView extends Migration
{
    public function up() {
        DB::statement("CREATE OR REPLACE VIEW `view_course_section` 
        AS SELECT a.id as `lesson_id`,a.course_title as lessons_title, a.mentor_id ,
         b.name as `mentor_name` , c.id as `section_id` ,
         c.section_order, c.section_title, c.section_content ,
          c.section_video, c.created_at,c.updated_at 
          from course_section c 
          LEFT JOIN lessons a on a.id=c.course_id LEFT JOIN users b on a.mentor_id=b.id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
    */
    public function down()
    {
        DB::statement("DROP VIEW view_course_section");
    }
}
