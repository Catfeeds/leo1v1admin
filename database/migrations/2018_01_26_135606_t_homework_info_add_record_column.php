<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class THomeworkInfoAddRecordColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi.t_homework_info', function( Blueprint $table)
        {
            // t_field($table->integer("download_time"),"学生第一次下载作业时间");
            // t_field($table->integer("stu_check_time"),"学生第一次查看批改后作业时间");
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
