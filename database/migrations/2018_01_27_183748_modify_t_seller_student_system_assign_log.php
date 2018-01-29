<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTSellerStudentSystemAssignLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi_admin.t_seller_student_system_assign_log', function (Blueprint $table) {
            //
            t_field($table->tinyInteger('check_hold_flag'),"系统自动释放标识0:非系统释放1：系统释放");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_seller_student_system_assign_log', function (Blueprint $table) {
            //
        });
    }
}
