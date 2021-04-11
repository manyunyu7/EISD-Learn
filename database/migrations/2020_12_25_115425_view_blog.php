<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ViewBlog extends Migration
{



    public function up() {
        DB::statement("CREATE OR REPLACE VIEW `view_blog` as
         SELECT a.* ,b.name,b.id as `writer_id`, b.name as `writer_name`,b.role as `writer_role`,
         b.profile_url as `writer_profile` from blogs a left join users b on a.user_id=b.id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
    */
    public function down()
    {
        DB::statement("DROP VIEW view_blog");
    }
}
