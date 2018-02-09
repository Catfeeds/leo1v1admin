<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TResourceChangeRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_resource_change_record', function(Blueprint $table) {
            t_comment($table, "老师负责人信息变更表");

            t_field($table->increments("id"), "id");

            t_field($table->integer("file_id"), "file id");
            t_field($table->integer("type"),"1 reload 2 kpi");
            t_field($table->integer("add_time"),"time");
            t_field($table->integer("apply_teacherid"),"apply");
            t_field($table->integer("pre_teacherid"),"变更前老师id");
            t_field($table->integer("teacherid"),"变更前老师id");
            t_field($table->integer("change_adminid"),"admin");
            t_field($table->string("action",255),"操作");
            t_field($table->string("result",32),"action result");
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
