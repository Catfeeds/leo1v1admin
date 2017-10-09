<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTYxyxTestPicInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_yxyx_test_pic_info', function( Blueprint $table)
        {
            $table->increments("id");

            t_field($table->string("test_title"),"标题");
            t_field($table->integer("adminid"),"管理员id");
            t_field($table->string("test_des"),"内容描述");
            t_field($table->smallInteger("grade"),"年级");
            t_field($table->tinyInteger("subject"),"科目");
            t_field($table->smallInteger("test_type"),"考试类型");
            t_field($table->string("pic", 1024),"图片链接，最多10个，以|分开");
            t_field($table->string("poster"),"封面");
            t_field($table->integer("visit_num"),"访问次数,默认0");
            t_field($table->integer("share_num"),"分享次数,默认0");
            t_field($table->integer("create_time"),"添加时间");

            // $table->primary('id');
            $table->index(["grade"]);
            $table->index(["subject"]);
            $table->index(["create_time"]);
            $table->index(["test_type"]);
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
