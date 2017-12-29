<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddTeaCwType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_lesson_info', function( Blueprint $table)
        {
            t_field($table->string("tea_cw_type",100) ,"老师上传讲义的格式");
            t_field($table->string("tea_cw_ppt_str",2048) ,"老师上传讲义的格式");
            t_field($table->integer("change_status",100) ,"老师上传PPT讲义转化进度 0:未处理 1:已成功 2:失败");
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
