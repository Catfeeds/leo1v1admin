<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTFulltimeTeacherPositiveRequireList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_fulltime_teacher_assessment_list', function( Blueprint $table)
        {
            t_field($table->integer("post"),"岗位");
            t_field($table->integer("main_department"),"部门");
            t_field($table->integer("positive_type"),"自评转正类型");
            $table->index("adminid","adminid");
        });

        Schema::create('db_weiyi.t_fulltime_teacher_positive_require_list', function (Blueprint $table){
            t_field($table->integer("id",true),"");
            t_field($table->integer("adminid"),"");
            t_field($table->integer("add_time"),"提交时间");
            t_field($table->integer("post"),"岗位");
            t_field($table->integer("main_department"),"部门");
            t_field($table->integer("positive_type"),"转正类型");

            t_field($table->integer("create_time"),"入职时间");
            t_field($table->integer("positive_time"),"转正时间");
            t_field($table->integer("level"),"等级");
            t_field($table->integer("positive_level"),"转正后等级");
            t_field($table->integer("rate_stars_master"),"考评星级");
            t_field($table->integer("assess_id"),"考评表id");
            t_field($table->text("self_assessment"),"自评内容");
            t_field($table->integer("mater_adminid"),"主管");
            t_field($table->integer("master_assess_time"),"主管处理时间");
            t_field($table->integer("master_deal_flag"),"主管处理选项 0未设置,1 同意,2 驳回");
            t_field($table->integer("main_mater_adminid"),"总监");
            t_field($table->integer("main_master_assess_time"),"总监处理时间");
            t_field($table->integer("main_master_deal_flag"),"总监处理选项 0未设置,1 同意,2 驳回");

                        
            $table->index("adminid","adminid");
            $table->index("mater_adminid","mater_adminid");
            $table->index("main_mater_adminid","main_mater_adminid");
            $table->index("add_time","add_time");
            $table->index("assess_id","assess_id");

           
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
