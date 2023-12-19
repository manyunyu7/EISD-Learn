<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuizSessionFlagToCourseSection extends Migration
{
    public function up()
    {
        Schema::table('course_section', function (Blueprint $table) {
            $table->string('quiz_session_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_section', function (Blueprint $table) {
            $table->dropColumn('quiz_session_id');
        });
    }
}
