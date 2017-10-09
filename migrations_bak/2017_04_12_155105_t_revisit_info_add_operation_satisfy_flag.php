<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TRevisitInfoAddOperationSatisfyFlag extends Migration
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
            t_field($table->integer("operation_satisfy_flag"),"家长对于我们的软件操作和体验是否满意");
            t_field($table->integer("operation_satisfy_type"),"家长对于我们的软件操作和体验不满意的类型");
            t_field($table->string("operation_satisfy_info",1000),"家长对于我们的软件操作和体验不满意的具体描述");
            t_field($table->integer("record_tea_class_flag"),"反馈老师对于近期课程的评价和不足是否完成");
            t_field($table->string("child_performance",1000),"学生近期表现");
            t_field($table->integer("tea_content_satisfy_flag"),"家长对于老师教学内容和水平是否满意");
            t_field($table->integer("tea_content_satisfy_type"),"家长对于老师教学内容和水平不满意的类型");
            t_field($table->string("tea_content_satisfy_info",1000),"家长对于老师教学内容和水平不满意的具体描述");
            t_field($table->string("other_parent_info",1000),"家长其他意见与建议");
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
