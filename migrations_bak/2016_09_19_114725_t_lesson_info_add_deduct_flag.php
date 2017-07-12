<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonInfoAddDeductFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("t_lesson_info",function(Blueprint $table)
        {
            \App\Helper\Utils::comment_field($table->integer("deduct_check_homework"),"学生提交作业后,48小时未批改作业");
            \App\Helper\Utils::comment_field($table->integer("deduct_change_class"),"换课未提前24小时");
            \App\Helper\Utils::comment_field($table->integer("deduct_rate_student"),"未对学生评价(试听 课后45分钟,常规 2天)");
            \App\Helper\Utils::comment_field($table->integer("deduct_upload_cw"),"课前未上传讲义");
            \App\Helper\Utils::comment_field($table->integer("deduct_come_late"),"上课迟到5分钟");
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
