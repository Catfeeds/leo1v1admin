<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}


class TTestLessonSubjectRequireAddSellerRequireChangeType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_test_lesson_subject_require', function( Blueprint $table)
        {
            add_field($table->integer("seller_require_change_type"),"销售请求类型" );         
            add_field($table->integer("seller_require_change_time"),"销售请求更换的时间" );         
            add_field($table->integer("seller_require_change_flag"),"销售请求进展 0:未请求/已完成 1:请求中 " );         
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
