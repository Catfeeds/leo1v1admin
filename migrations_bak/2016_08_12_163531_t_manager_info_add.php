<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TManagerInfoAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_seller_student_info', function( Blueprint $table)
        {
            $table->index("cancel_time");
            $table->index("cancel_lesson_start");
        });
        Schema::table('db_weiyi_admin.t_manager_info', function( Blueprint $table)
        {
            $table->integer("creater_adminid");
        });


        //
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
