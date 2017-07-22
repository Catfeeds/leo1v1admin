<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderInfoAddColumns extends Migration
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
            t_field($table->string("teacher_deal_time",50),"老师操作时间");
            t_field($table->string("parent_deal_time",50),"家长操作时间");
            t_field($table->string("teacher_modify_time",1024),"老师选择时间段");
            t_field($table->string("teacher_modify_remark",1024),"老师修改时间备注");
            t_field($table->string("parent_modify_time",1024),"家长选择时间段");
            t_field($table->string("parent_modify_remark",1024),"家长修改时间备注");
            t_field($table->integer("is_modify_time_flag"),"上课时间调整是否成功 0:未成功 1:已成功");
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
