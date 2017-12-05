<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTResourceFileVisitInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_resource_file_visit_info', function (Blueprint $table){
            $table->increments('visit_id');
                t_field($table->integer("file_id"),"文件id");
                t_field($table->string("ip"),"访问者ip");
                t_field($table->integer("visit_type"),"类型　0 浏览 １重命名
2上传新版本　3删除　4还原  5 纠错　6彻底删除 7 使用");
                t_field($table->integer("visitor_type"),"访问者类型 0后台 1老师 2学生");
                t_field($table->integer("visitor_id"),"访问者id,adminid,teacherid,userid");
                t_field($table->integer("create_time"),"时间");

                $table->index("file_id");
                $table->index("visitor_type");
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
