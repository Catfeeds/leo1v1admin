<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTestLessonSubjectRequireAddLimitTypeTeacherid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_test_lesson_subject_require', function( Blueprint $table)
        {
            t_field($table->integer("limit_require_flag"),"限课特殊申请标识");
            t_field($table->integer("limit_require_teacherid"),"限课特殊申请老师id");
            t_field($table->integer("limit_require_lesson_start"),"限课特殊申请上课时间");
            t_field($table->integer("limit_require_time"),"限课特殊申请时间");
            t_field($table->integer("limit_require_adminid"),"限课特殊申请人");
            t_field($table->integer("limit_require_send_adminid"),"限课特殊申请对象");
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
