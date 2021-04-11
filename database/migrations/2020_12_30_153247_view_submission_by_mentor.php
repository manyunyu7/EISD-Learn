<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ViewSubmissionByMentor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //
    public function up()
    {
        DB::statement("CREATE OR REPLACE VIEW `view_submission_by_mentor` as
        select a.*,b.mentor_id , b.course_title,c.name from student_submission a 
        left join lessons b on a.lesson_id=b.id 
        left join users c on a.student_id=c.id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW view_submission_by_mentor");
    }
}
