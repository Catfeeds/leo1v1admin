<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddNewColum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_lesson_info', function(Blueprint $table) {
            t_field($table->string("uuid_stu"), "学生PPT讲义的uuid");
            t_field($table->string("zip_url_stu"), "学生讲义压缩包链接");
            t_field($table->tinyInteger("ppt_status_stu"), "学生ppt转化状态 0:未处理 1:已成功 2:失败");
            t_field($table->tinyInteger("use_ppt_stu"), "学生讲义是否是ppt 0:ppf 1:ppt");
            $table->index('uuid_stu', 'uuid_stu_ppt');
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
