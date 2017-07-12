<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTSendWxTemplateRecordList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_send_wx_template_record_list', function (Blueprint $table)
        {
            t_field($table->string("template_id"),"模板id");
            t_field($table->integer("send_time"),"推送时间");
            t_field($table->integer("template_type"),"类型");
            t_field($table->string("title"),"标题");
            t_field($table->string("first_sentence"),"开头语");
            t_field($table->string("end_sentence"),"结束语");
            t_field($table->string("keyword1"),"");
            t_field($table->string("keyword2"),"");
            t_field($table->string("keyword3"),"");
            t_field($table->string("keyword4"),"");
            t_field($table->string("url"),"");
            $table->primary(["template_id","send_time"]);
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
