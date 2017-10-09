<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTTeacherComplaintsInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_teacher_complaints_info', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("add_time"),"申请时间");
            t_field($table->integer("adminid"),"申请者");
            t_field($table->integer("teacherid"),"老师");
            t_field($table->string("complaints_info",1000),"投诉内容");
            t_field($table->string("complaints_info_url"),"投诉内容对应图片地址");
            t_field($table->integer("subject"),"科目");
            t_field($table->integer("grade"),"年级段");
            t_field($table->string("record_scheme",500),"处理方案");
            t_field($table->string("record_scheme_url"),"处理方案图片地址");
            t_field($table->integer("accept_adminid"),"处理人");
            t_field($table->integer("accept_time"),"处理时间");
            t_field($table->integer("is_done"),"是否解决");
            t_field($table->integer("done_time"),"解决时间");           
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
