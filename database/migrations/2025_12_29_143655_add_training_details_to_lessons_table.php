<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrainingDetailsToLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('course_description');
            $table->date('end_date')->nullable()->after('start_date');
            $table->integer('target_audience')->nullable()->after('end_date');
            $table->string('duration')->nullable()->after('target_audience');
            $table->string('training_type')->nullable()->after('duration');
            $table->string('location')->nullable()->after('training_type');
            $table->string('vendor')->nullable()->after('location');
            $table->bigInteger('proposed_budget')->default(0)->after('vendor');
            $table->bigInteger('actual_budget')->default(0)->after('proposed_budget');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            //
        });
    }
}
