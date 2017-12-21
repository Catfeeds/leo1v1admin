<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTeacherChristmas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_teacher_christmas', function(Blueprint $table) {
            t_field($table->increments("id"), "老师圣诞节|元旦节活动");
            t_field($table->integer("teacherid"), "分享人");
            t_field($table->string("next_openid"), "下级openid");
            t_field($table->integer("add_time"), "添加时间");
            t_field($table->integer("score"), "积分");

            $table->index('teacherid');
            $table->index('add_time');
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
