<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TMainMajorGroupNameMonthResstInstall extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('db_weiyi_admin.t_main_major_group_name_month');
        Schema::create('db_weiyi_admin.t_main_major_group_name_month', function( Blueprint $table)
        {
            t_field($table->integer("groupid"),"分组id");
            t_field($table->integer("month"),"月度时间,以每月一日");
            t_field($table->integer("main_type"),"部门类型");
            t_field($table->string("group_name"),"组名");
            t_field($table->integer("master_adminid"),"总监id");
            t_field($table->integer("main_assign_percent"),"分配比率");

            $table->primary(["groupid","month"],'g_month');
            $table->index("main_type");
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
