<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColorOfCategoryToLessonCtegories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_categories', function (Blueprint $table) {
            //
            $table->string('color_of_categories')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_categories', function (Blueprint $table) {
            //
            $table->dropColumn('color_of_categories');

        });
    }
}
