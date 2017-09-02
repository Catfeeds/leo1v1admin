<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTPdfToPngInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('db_weiyi.t_pdf_to_png_info', function( Blueprint $table)
        {
            $table->increments("id");

            t_field($table->integer("lessonid"),"课程id");
            t_field($table->integer("id_do_flag"),"是否处理 0:未处理 1:已处理");
            t_field($table->integer("create_time"),"添加时间");
            t_field($table->integer("deal_time"),"处理时间");
            t_field($table->string("pdf_url"),'pdf课件链接');

            $table->index(["lessonid"]);
            $table->index(["create_time"]);
            $table->index(["deal_time"]);
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
