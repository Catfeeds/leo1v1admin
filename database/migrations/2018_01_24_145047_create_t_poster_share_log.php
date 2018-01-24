<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTPosterShareLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_tool.t_poster_share_log', function(Blueprint $table) {
            t_comment($table,"海报分享进入链接");
            t_field($table->integer("poster_id"), "海报id");
            t_field($table->integer("uid"), "分享人id");
            t_field($table->integer("parentId"), "家长id");
            t_field($table->string("par_openid"), "家长openid");
            t_field($table->string("phone",100), "学生号码");
            $table->index('poster_id', 'pid');
            $table->index('uid', 'uid');
            $table->index('par_openid', 'par_openid');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
