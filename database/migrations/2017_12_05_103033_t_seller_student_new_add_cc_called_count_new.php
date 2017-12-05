<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentNewAddCcCalledCountNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_seller_student_new', function( Blueprint $table)
        {
            t_field($table->integer("cc_called_count_new"), "接通次数");
            t_field($table->integer("cc_no_called_count_new"), "未接通次数");
            t_field($table->integer("call_admin_count_new"), "例子经手CC数");
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
