<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentSectionColumnInStudentSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_section', function (Blueprint $table) {
            //
            $table->string('student_section')->nullable()->after('student-section');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_section', function (Blueprint $table) {
            //
            $table->string('student_section')->nullable()->after('student-section');

        });
    }
}
