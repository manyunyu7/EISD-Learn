<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CourseSection extends Migration
{
    public function up()
    {
        Schema::create('course_section', function (Blueprint $table) {
            $table->id();
            $table->string('section_title');
            $table->string('section_order');
            $table->string('section_video');
            $table->text('section_content');
            $table->unsignedBigInteger('course_id');
            $table->unique(['section_order']);
            $table->foreign('course_id')->references('id')->on('lessons')->onDelete('cascade');         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::dropIfExists('course_section');
    }
}
