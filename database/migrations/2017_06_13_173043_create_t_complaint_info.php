<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTComplaintInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('db_weiyi.t_complaint_info', function (Blueprint $table){
            t_field($table->increments("complaint_id"),"");
            t_field($table->integer("add_time"),"投诉生成时间");
            t_field($table->integer("complaint_type"),"投诉来源类型");
            t_field($table->integer("userid"),"投诉人");
            t_field($table->integer("account_type"),"投诉人类别");
            t_field($table->text("complaint_info"),"投诉内容");
            t_field($table->string("complaint_img_url",2048),"投诉附件链接");
            t_field($table->string ("suggest_info",4096),"家长建议或其他建议");
            t_field($table->integer("current_adminid"),"当前负责人");
            t_field($table->integer("current_admin_assign_time"),"当前负责人 分配时间");
            t_field($table->integer("complaint_state"),"投诉处理状态 0:未处理 1:已分配 ,2 :   ");

            $table->index("add_time");
            $table->index("current_adminid" );
            $table->index(["userid","add_time"]);
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
