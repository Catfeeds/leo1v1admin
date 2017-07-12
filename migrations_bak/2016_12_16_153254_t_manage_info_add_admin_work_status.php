<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}

class TManageInfoAddAdminWorkStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //       
        Schema::table('db_weiyi_admin.t_manager_info', function( Blueprint $table)
        {
            add_field($table->integer("admin_work_status"),"教务老师工作状态" );
        });

        Schema::table('t_test_lesson_subject', function( Blueprint $table)
        {
            add_field($table->integer("history_accept_adminid"),"操作过排课的教务老师" );
        });
        Schema::table('t_test_lesson_subject_require', function( Blueprint $table)
        {
            add_field($table->integer("jw_test_lesson_status"),"教务排课状态 0 未设置 1 完成 2 挂起 3 退回" );
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
