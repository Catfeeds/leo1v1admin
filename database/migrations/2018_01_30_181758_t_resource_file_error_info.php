<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TResourceFileErrorInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create("db_weiyi.t_resource_file_error_info", function(Blueprint $table) {
            t_comment($table, "备课系统文件报错表");
            t_field($table->increments("id"), "id");
            t_field($table->integer("file_id"), "文件id");
            t_field($table->integer("teacherid"),"报错老师id");
            t_field($table->integer("add_time"),"报错时间");
            t_field($table->integer("resource_type"), "资料类型");
            t_field($table->integer("error_type"), "错误类型(资料库)|提问类型(培训库)");
            t_field($table->integer("sub_error_type"), "错误子类型(资料库)");
            t_field($table->string("detail_error",1024), "错误描述(资料库)|提问描述(培训库)");
            t_field($table->string("error_url",255), "错误文件链接(资料库)|文件链接(培训库)");
            t_field($table->string("detail_question",255), "提问简述(培训库)");
            
            $table->index(["file_id"]);
            $table->index(["teacherid"]);
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
