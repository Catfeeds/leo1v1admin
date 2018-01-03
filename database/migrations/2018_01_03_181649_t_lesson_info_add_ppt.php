<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddPpt extends Migration
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
            t_field($table->integer("tea_cw_type"), "老师上传讲义的类型 0:pdf 1:ppt");
            t_field($table->string("uuid"), "老师PPT讲义的uuid");
            t_field($table->integer("ppt_status"), "ppt转化状态 0:未处理 1:已成功 2:失败");
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
