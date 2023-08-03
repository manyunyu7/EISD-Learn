<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Portfolio extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('portfolio', function (Blueprint $table) {
          $table->id();
          $table->string('image');
          $table->unsignedBigInteger('user_id');
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->string('title');
          $table->string('category');
          $table->text('content');
          $table->text('link');
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
        Schema::dropIfExists('portfolio');
    }
}
