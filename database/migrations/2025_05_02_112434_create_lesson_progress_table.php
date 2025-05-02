<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonProgressTable extends Migration
{
    public function up()
    {
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('lesson_id');
            $table->unsignedBigInteger('section_id');
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('course_section')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson_progress');
    }
}
