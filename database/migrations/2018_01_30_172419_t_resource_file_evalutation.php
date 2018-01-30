<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TResourceFileEvalutation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create("db_weiyi.t_resource_file_evalutation", function(Blueprint $table) {
            t_comment($table, "备课系统文件评价表");
            t_field($table->increments("id"), "id");
            t_field($table->integer("file_id"), "文件id");
            t_field($table->integer("teacherid"),"评价老师id");
            t_field($table->integer("add_time"),"评价时间");
            t_field($table->integer("resource_type"), "资料类型");
            
            t_field($table->tinyInteger("option_one"), "我认为这份培训资料对我(培训库使用)");
            t_field($table->integer("quality_score"), "质量总评(资料库使用)");
            t_field($table->integer("help_score"), "帮助指数(资料库使用)");
            t_field($table->integer("overall_score"), "全面指数");
            t_field($table->integer("detail_score"), "详细指数");
            t_field($table->integer("size"), "文字大小");
            t_field($table->integer("gap"), "间距大小");
            t_field($table->integer("bg_picture"), "背景图案");
            t_field($table->integer("text_type"), "讲义类型");
            t_field($table->integer("answer"), "答案程度");
            t_field($table->integer("suit_student"), "适宜学生");
            t_field($table->string("time_length"), "时长");
            $table->index(["file_id"]);
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
