<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTLectureRevisitInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_lecture_revisit_info', function (Blueprint $table)
        {
            t_field($table->string("phone"),"电话");
            t_field($table->integer("revisit_time"),"回访时间");
            t_field($table->string("sys_operator"),"进行回访的人");
            t_field($table->integer("revisit_origin"),"回访渠道 1 微信 ,2 电话,3 其他");
            t_field($table->string("revisit_note",1000),"回访内容");
            $table->primary(["phone","revisit_time"]);
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
