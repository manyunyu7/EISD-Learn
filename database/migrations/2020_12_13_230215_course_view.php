<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CourseView extends Migration
{
        /**
         * Run the migrations.
         *
         * @return void
        */
        public function up() {
            DB::statement("CREATE OR REPLACE VIEW `view_course` AS SELECT
            a.* , b.name as `mentor_name`, b.profile_url from lessons a 
            LEFT JOIN users b on a.mentor_id=b.id ");
        }
    
        /**
         * Reverse the migrations.
         *
         * @return void
        */
        public function down()
        {
            DB::statement("DROP VIEW view_course");
        }
}
