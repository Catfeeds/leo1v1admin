<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAdminWorkStartTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create("db_weiyi_admin.t_admin_work_start_time", function(Blueprint $table) {
            t_comment($table,"后台成员每天开始工作时间");
            t_field($table->integer("id",true), "");
            t_field($table->integer("log_date"), "哪一天");
            t_field($table->integer("adminid"), "后台成员");
            t_field($table->integer("work_start_time"), "工作开始时间");
            $table->unique(["adminid","log_date"]);
            $table->index(["log_date"]);
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
