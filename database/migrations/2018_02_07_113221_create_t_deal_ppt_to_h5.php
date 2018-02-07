<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTDealPptToH5 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_deal_ppt_to_h5', function(Blueprint $table) {
            t_comment($table, "ppt转h5日志表");
            t_field($table->increments("id"), "");
            t_field($table->integer("add_time"), "添加时间");
            t_field($table->integer("lessonid"), "课程ID");
            t_field($table->tinyInteger("is_tea"), "0:学生 1:老师");
            t_field($table->string("ppt_url"), "标记类别");
            t_field($table->tinyInteger("id_deal_falg"), "0:未设置 1:已成功 2:转化中");

            $table->index('lessonid');
            $table->index('add_time');
            $table->index('ppt_url');
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
