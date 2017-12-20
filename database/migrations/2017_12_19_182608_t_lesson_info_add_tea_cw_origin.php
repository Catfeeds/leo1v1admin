<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddTeaCwOrigin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi.t_lesson_info', function( Blueprint $table)
        {
            t_field($table->integer("tea_cw_origin"),"文件来源 0:老师本地上传;1:老师的资源库(自己上传);2:老师的资源库(收藏);3:理优资源");
            t_field($table->integer("stu_cw_origin"),"文件来源 0:老师本地上传;1:老师的资源库(自己上传);2:老师的资源库(收藏);3:理优资源");
            t_field($table->integer("tea_cw_file_id"),"使用的文件file_id");
            t_field($table->integer("stu_cw_file_id"),"使用的文件file_id");
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
