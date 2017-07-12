<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoLessonLoginStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('t_lesson_info', function( Blueprint $table)
        {
            t_field($table->integer("lesson_login_status"),"0:未设置,1:老师学生都登录过,2: 老师学生有一个没登录");
        });
        //
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
