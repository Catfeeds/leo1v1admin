<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTLessonTimeModify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_lesson_time_modify', function (Blueprint $table){
            t_field($table->integer("lessonid"),"课时id");

            t_field($table->integer("parentid"),"家长id");
            t_field($table->integer("teacherid"),"老师id");
            t_field($table->string("teacher_deal_time",50),"老师发起申请时间");
            t_field($table->string("parent_deal_time",50),"家长发起申请时间");
            t_field($table->string("teacher_modify_time",1024),"老师选择时间段");
            t_field($table->string("teacher_modify_remark",1024),"老师修改时间备注");
            t_field($table->string("parent_modify_time",1024),"家长选择时间段");
            t_field($table->string("parent_modify_remark",1024),"家长修改时间备注");
            t_field($table->integer("is_modify_time_flag"),"上课时间调整是否成功 0:未成功 1:已成功");

            t_field($table->string("teacher_keep_original_remark",1024),"老师维持原有时间备注");
            t_field($table->string("parent_keep_original_remark",1024),"家长维持原有时间备注");
            t_field($table->string("teacher_change_time_remark",1024),"老师维持原有时间备注");

            t_field($table->string("original_time",100),"原始上课时间");

            $table->primary('lessonid');
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
