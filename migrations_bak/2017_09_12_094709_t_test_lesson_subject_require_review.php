<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonSubjectRequireReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_test_lesson_subject_require_review', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("adminid"),"排课申请人");
            t_field($table->integer("require_id"),"申请id");
            t_field($table->integer("group_adminid"),"组长id");
            t_field($table->integer("group_suc_flag"),"组长审核标志0未通过,1通过");
            t_field($table->integer("group_time"),"组长审核时间");
            t_field($table->integer("master_adminid"),"主管id");
            t_field($table->integer("master_suc_flag"),"主管审核标志0未通过,1通过");
            t_field($table->integer("master_time"),"主管审核时间");
            t_field($table->integer("create_time"),"申请时间");
            $table->index("adminid");
            $table->index("require_id");
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
