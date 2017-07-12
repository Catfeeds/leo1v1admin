<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderInfoAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('db_weiyi.t_order_info', function( Blueprint $table)
        {
            t_field($table->string("remark",1024),"备注");
            t_field($table->integer("lesson_weeks"),"每周课时");
            t_field($table->integer("lesson_duration"),"每节课时长");
            t_field($table->string("addressee"),"收件人");
            t_field($table->string("receive_addr",255),"收件地址");
            t_field($table->string("pdf_url",255),"文件地址");
            t_field($table->string("receive_phone",100),"收件人电话");
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
