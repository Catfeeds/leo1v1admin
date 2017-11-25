<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTResourceInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_resource', function (Blueprint $table){
            $table->increments('resource_id');
                t_field($table->string("file_title"),"上传的文件名称");
                t_field($table->string("file_type",10),"文件类型");
                t_field($table->integer("file_size"),"文件大小（kb）");
                t_field($table->char("file_hash", 32),"上传返回的32位hash值");
                t_field($table->integer("adminid"),"上传者id");
                t_field($table->integer("is_del"),"删除标识　0否 1删除（回收站可见,可恢复）2永久删除（回收站不显示,不可恢复）");
                t_field($table->integer("down_num"),"下载次数");
                t_field($table->integer("visit_num"),"访问次数");
                t_field($table->integer("error_num"),"纠错次数");
                t_field($table->integer("use_num"),"使用次数");
                t_field($table->integer("resource_type"),"资料类型");
                t_field($table->integer("subject"),"科目");
                t_field($table->integer("grade"),"年级");
                t_field($table->integer("tag_one"),"标签1，与resource_type相关，对应类型可变");
                t_field($table->integer("tag_two"),"标签2，与resource_type相关，对应类型可变");
                t_field($table->integer("tag_three"),"标签3，与resource_type相关，对应类型可变");
                t_field($table->integer("create_time"),"上传时间");
                t_field($table->integer("edit_adminid"),"最后一次修改人");
                t_field($table->integer("update_time"),"最后一次修改时间");



                $table->index("file_title");
                $table->index("file_hash");
                $table->index("resource_type");
                $table->index(["subject","grade"],"fixd");
                $table->index("create_time");
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
