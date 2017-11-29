<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TResourceDropSutHash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_resource', function( Blueprint $table)
        {
            $table->dropColumn('sut_hash');
            $table->dropColumn('sut_link');

            t_field($table->char("stu_hash",32),"学生版hash");
            t_field($table->string("stu_link"),"学生版链接");
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
