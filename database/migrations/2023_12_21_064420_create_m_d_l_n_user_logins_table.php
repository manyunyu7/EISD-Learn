<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMDLNUserLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_d_l_n_user_logins', function (Blueprint $table) {
            $table->id();
            $table->string("user_id")->nullable();
            $table->string("firebase_token")->nullable();
            $table->string("device_type")->nullable();
            $table->string("user_name")->nullable();
            $table->string("user_dept")->nullable();
            $table->string("set_logged_out")->nullable();
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
        Schema::dropIfExists('m_d_l_n_user_logins');
    }
}
