<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentNewAddNewFields extends Migration
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
            t_field($table->integer("first_called_cc"),"首个拨通cc");
            t_field($table->integer("first_get_cc"),"首个获取cc");
            t_field($table->integer("test_lesson_flag"),"是否试听");
            t_field($table->integer("orderid"),"最近签单orderid");

            $table->index("orderid",'orderid');
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
