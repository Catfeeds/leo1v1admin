<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTestLessonOptLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi.t_test_lesson_opt_log', function( Blueprint $table)
        {
            t_field($table->bigInteger("roomid"),"测试课程的id");
            t_field($table->integer("opt_time"),"进入或退出测试课程的时间");
            t_field($table->integer("userid"),"");
            t_field($table->tinyInteger("role"),"角色");
            t_field($table->integer("opt_type"),"课堂操作类型 1 login 2 logout");
            t_field($table->integer("server_ip"),"登陆退出时用户ip");
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
