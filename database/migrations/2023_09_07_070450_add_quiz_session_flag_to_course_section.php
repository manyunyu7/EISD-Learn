<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuizSessionFlagToCourseSection extends Migration
{
    public function up()
    {
        Schema::table('course_section', function (Blueprint $table) {
            $table->unsignedBigInteger('quiz_session_id')->nullable();
            $table->foreign('quiz_session_id')->references('id')->on('exam_sessions')->onDelete('restrict');
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
