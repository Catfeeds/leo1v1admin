<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTeacherResource extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_teacher_resource', function (Blueprint $table){
            $table->increments('tea_res_id');
                t_field($table->integer("teacherid"),"老师ｉｄ");
                t_field($table->integer("resource_type"),"资料类型");
                t_field($table->integer("subject"),"科目");
                t_field($table->integer("grade"),"年级");
                t_field($table->integer("tag_one"),"标签1，与resource_type相关，对应类型可变");
                t_field($table->integer("tag_two"),"标签2，与resource_type相关，对应类型可变");
                t_field($table->integer("tag_three"),"标签3，与resource_type相关，对应类型可变");
                t_field($table->integer("tag_four"),"标签4，与resource_type相关，对应类型可变");
                t_field($table->string("file_title"),"文件名称");
                t_field($table->integer("file_size"),"文件大小");
                t_field($table->string("file_type",10),"文件类型");
                t_field($table->string("file_link"),"文件链接");
                t_field($table->integer("file_id"),"后台上传资源id,若不为0，则是收藏的");
                t_field($table->integer("is_del"),"0否　１删除");
                t_field($table->integer("create_time"),"时间");

                $table->index("teacherid");
                $table->index("resource_type");
                $table->index("subject");
                $table->index("file_id");
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
