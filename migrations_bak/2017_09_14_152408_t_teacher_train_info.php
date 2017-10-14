<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherTrainInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_teacher_train_info', function (Blueprint $table){
              t_field($table->integer("id",true),"id");
              t_field($table->integer("create_time"),"创建时间");
              t_field($table->integer("create_adminid"),"培训课程添加人");
              t_field($table->integer("train_type"),"培训课程类型");
              t_field($table->integer("teacherid"),"教师id");
              t_field($table->integer("subject"),"科目");
              t_field($table->integer("status"),"培训状态");
              t_field($table->integer("lessionid"),"培训来源课程");
              t_field($table->integer("through_time"),"通过培训时间");
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
