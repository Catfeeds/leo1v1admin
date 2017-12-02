<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTResourceFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('db_weiyi.t_resource_file', function (Blueprint $table){
            $table->increments('file_id');
                 t_field($table->integer("resource_id"),"资源id");
                 t_field($table->string("file_title"),"上传的文件名称");
                 t_field($table->string("file_type",10),"文件类型");
                 t_field($table->integer("file_size"),"文件大小（kb）");
                 t_field($table->char("file_hash", 32),"上传返回的32位hash值");
                 t_field($table->string("file_link"),"文件链接");
                 t_field($table->integer("visit_num"),"访问次数");
                 t_field($table->integer("error_num"),"纠错次数");
                 t_field($table->integer("use_num"),"使用次数");
                 t_field($table->integer("file_use_type"),"文件使用对象 0默认 1老师 2学生");
                 t_field($table->integer("status"),"0使用中 1删除 2被替换(不再使用)");



                 $table->index("resource_id");
                 $table->index("file_title");
                 $table->index("file_hash");
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
        //
    }
}
