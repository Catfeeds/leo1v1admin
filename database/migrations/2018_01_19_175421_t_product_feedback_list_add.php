<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TProductFeedbackListAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_product_feedback_list', function(Blueprint $table) {
            t_field($table->integer("lesson_problem"), "问题类型");
            t_field($table->string("img_url"), "图片链接");
            t_field($table->string("video_url"), "音频/视频链接");
            t_field($table->string("zip_url"), "压缩包文件链接");
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
