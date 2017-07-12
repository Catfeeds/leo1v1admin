<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TStudentInfoAddOrgionid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_student_info', function( Blueprint $table)
        {
            $table->integer("originid"); 
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
        Schema::table('t_student_info', function( Blueprint $table)
        {
            //是不是测试用户
            $table->dropColumn("originid");
        });
        //
    }
}
