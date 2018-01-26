<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentSystemAssignCountLogAddDefCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table("db_weiyi_admin.t_seller_student_system_assign_count_log", function( Blueprint $table)
        {
            t_field($table->integer("need_new_count"),"需要的新例子个数");
            t_field($table->integer("need_no_connected_count"),"需要的未拨通例子数");
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
