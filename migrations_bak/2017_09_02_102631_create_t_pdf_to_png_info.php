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

            t_field($table->integer("lessonid"),'课程id');
            t_field($table->integer("id_do_flag"),"是否处理 0:未处理 1:已处理 2:处理中");
            t_field($table->integer("create_time"),"添加时间");
            t_field($table->integer("deal_time"),"处理时间");
            t_field($table->string("pdf_url"),"pdf课件链接");
            $table->index(["lessonid"]);
            $table->index(["create_time"]);
            $table->index(["deal_time"]);


            /**
               `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
               `lessonid` int(11) NOT NULL COMMENT 'e8afbee7a88b6964',
               `id_do_flag` int(11) NOT NULL COMMENT 'e698afe590a6e5a484e7908620303ae69caae5a484e7908620313ae5b7b2e5a484e79086',
               `create_time` int(11) NOT NULL COMMENT 'e6b7bbe58aa0e697b6e997b4',
               `deal_time` int(11) NOT NULL COMMENT 'e5a484e79086e697b6e997b4',
               `pdf_url` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '706466e8afbee4bbb6e993bee68ea5',
               PRIMARY KEY (`id`),
               KEY `db_weiyi_t_pdf_to_png_info_lessonid_index` (`lessonid`),
               KEY `db_weiyi_t_pdf_to_png_info_create_time_index` (`create_time`),
               KEY `db_weiyi_t_pdf_to_png_info_deal_time_index` (`deal_time`)

             */




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
