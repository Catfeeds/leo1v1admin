<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarkerPosterReset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // Schema::dropIfExists('t_personality_poster');
        // Schema::create('db_tool.t_personality_poster', function(Blueprint $table) {
        //     t_comment($table,"市场部个性海报");
        //     t_field($table->increments("id"), "");
        //     t_field($table->integer("uid"), "分享人id");
        //     t_field($table->integer("clickNum"), "家长点击次数");
        //     t_field($table->integer("stuNum"), "学生数量");
        // });

        // Schema::dropIfExists('t_poster_share_log');
        // Schema::create('db_tool.t_poster_share_log', function(Blueprint $table) {
        //     t_comment($table,"海报分享报名的学生链接");
        //     t_field($table->integer("poster_id"), "海报id");
        //     t_field($table->integer("uid"), "分享人id");
        //     t_field($table->string("phone",100), "学生号码");
        //     t_field($table->integer("studentid"), "学生id");
        //     t_field($table->integer("add_time"), "添加时间");
        //     $table->index('uid', 'uid');
        //     $table->index('phone', 'phone');
        // });

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
