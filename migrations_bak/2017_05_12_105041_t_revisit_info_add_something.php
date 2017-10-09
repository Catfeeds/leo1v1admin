<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TRevisitInfoAddSomething extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_revisit_info', function( Blueprint $table)
        {
            t_field($table->integer("child_class_performance_flag"),"孩子课堂表现");
            t_field($table->integer("child_class_performance_type"),"孩子课堂表现不好的分类");
            t_field($table->string("child_class_performance_info",1000),"孩子课堂表现不好的具体表述");
            t_field($table->integer("school_score_change_flag"),"学校成绩变化");
            t_field($table->string("school_score_change_info",1000),"学校成绩变差的具体表述");
            t_field($table->integer("school_work_change_flag"),"学业变化");
            t_field($table->integer("school_work_change_type"),"学业变化的子分类");
            t_field($table->string("school_work_change_info",1000),"学业变化的具体表述");
            t_field($table->string("other_warning_info",1000),"其他预警问题");
            t_field($table->integer("is_warning_flag"),"是否预警中");
            t_field($table->string("warning_deal_url"),"预警解决相关图片地址");
            t_field($table->string("warning_deal_info",1000),"预警解决相关描述");
        });

        Schema::table('db_weiyi.t_test_lesson_subject_sub_list', function( Blueprint $table)
        {
            t_field($table->integer("seller_require_flag"),"是否CC要求");
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
