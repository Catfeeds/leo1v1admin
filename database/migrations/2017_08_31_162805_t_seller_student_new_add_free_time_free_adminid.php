<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentNewAddFreeTimeFreeAdminid extends Migration
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
            t_field($table->integer("free_adminid"),"回流人");
            t_field($table->integer("free_time"),"回流时间");
            $table->index("free_adminid");
            $table->index("free_time");
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
