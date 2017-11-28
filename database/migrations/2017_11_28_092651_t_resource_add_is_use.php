<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TResourceAddIsUse extends Migration
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
            t_field($table->integer("user_type"),"用途类型　0,老师 1,教研 2,咨询");
            t_field($table->integer("is_use"),"是否使用 0,否 1,是");
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
