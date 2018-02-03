<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotesToTSellerStudentSystemReleaseLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi_admin.t_seller_student_system_release_log', function (Blueprint $table) {
            //
            t_field($table->integer("admin_assign_time"), "例子分配时间");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_seller_student_system_release_log', function (Blueprint $table) {
            //
        });
    }
}
