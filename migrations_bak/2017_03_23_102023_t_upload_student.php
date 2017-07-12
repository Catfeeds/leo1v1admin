<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TUploadStudent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('db_weiyi_admin.t_upload_info', function (Blueprint $table){
            t_field($table->integer("postid",true),"批次");
            t_field($table->integer("upload_adminid"),"上传者");
            t_field($table->integer("upload_time"),"上传时间");
            t_field($table->string("upload_desc"),"备注");
            t_field($table->integer("post_flag"),"提交标志");
            $table->index(["upload_adminid","upload_time"]);
        });
        //

        Schema::create('db_weiyi_admin.t_upload_student_info', function (Blueprint $table){
            t_field($table->integer("postid"),"批次");
            t_field($table->integer("add_time"),"加入时间");
            t_field($table->string("phone",20),"电话");
            t_field($table->string("phone_location",64),"电话地区");
            t_field($table->string("name"),"姓名");
            t_field($table->string("origin"),"来源");
            t_field($table->integer("subject"),"科目");
            t_field($table->integer("grade"),"年级");
            t_field($table->string("user_desc"),"备注");
            t_field($table->integer("has_pad"),"设备类型");
            t_field($table->integer("is_new_flag"),"是否新例子");
            $table->primary(["postid","phone" ]);
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
        Schema::drop( 'db_weiyi_admin.t_upload_info');
        Schema::drop( 'db_weiyi_admin.t_upload_student_info');
        //
    }
}
