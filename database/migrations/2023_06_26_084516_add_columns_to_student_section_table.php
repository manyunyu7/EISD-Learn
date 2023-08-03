<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToStudentSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_section', function (Blueprint $table) {
            $table->string('minutes_watch')->after('section_id')->nullable();
            $table->string('is_finished')->after('section_id')->nullable();
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
            $table->dropColumn('minutes_watch');
            $table->dropColumn('is_finished');
        });
    }
}
