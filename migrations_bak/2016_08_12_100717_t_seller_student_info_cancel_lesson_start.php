<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentInfoCancelLessonStart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //
        Schema::table('t_seller_student_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("cancel_lesson_start")
                ,"取消的课程上课时间" );
            \App\Helper\Utils::comment_field(
                $table->integer("cancel_flag")
                ,"取消标识,0:无,1:取消,2:换时间" );
            \App\Helper\Utils::comment_field(
                $table->integer("test_lesson_parentid")
                ,"这节试听课的上级节点是哪个" );
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
