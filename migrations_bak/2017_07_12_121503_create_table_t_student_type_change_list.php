<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTStudentTypeChangeList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_student_type_change_list', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("userid"),"学生id");
            t_field($table->integer("add_time"),"添加时间");
            t_field($table->tinyInteger("type_before"),"修改前类型");
            t_field($table->tinyInteger("type_cur"),"当前类型");
            t_field($table->tinyInteger("change_type"),"修改方式 1,系统;2,手动");
            t_field($table->integer("adminid"),"操作人");
            t_field($table->string("reason"),"操作原因");
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
