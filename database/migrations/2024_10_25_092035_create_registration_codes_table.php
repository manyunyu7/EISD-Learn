<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registration_codes', function (Blueprint $table) {
            $table->id();
            //table for unique registration code
            $table->string('registration_code')->unique();
            $table->string('notes')->nullable();
            $table->string('department_id')->nullable();
            $table->string('location')->nullable();
            $table->string('position_id')->nullable();
            $table->string('is_active')->default("y")->nullable();
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
        Schema::dropIfExists('registration_codes');
    }
}
