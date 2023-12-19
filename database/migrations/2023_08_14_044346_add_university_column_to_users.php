<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniversityColumnToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('university')->nullable();
            $table->string('major')->nullable();
            $table->string('interest')->nullable();
            $table->string('cv')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('university')->nullable();
            $table->dropColumn('major')->nullable();
            $table->dropColumn('interest')->nullable();
            $table->dropColumn('cv')->nullable();
        });
    }
}
