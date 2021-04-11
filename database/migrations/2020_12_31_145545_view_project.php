<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ViewProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::statement("CREATE OR REPLACE VIEW `view_project` as
         SELECT a.* ,b.name,b.id as `owner_id`, b.name as `owner_name`,b.role as `role`,
         b.profile_url as `owner_profile` from portfolio a left join users b on a.user_id=b.id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
    */
    public function down()
    {
        DB::statement("DROP VIEW view_project");
    }
}
