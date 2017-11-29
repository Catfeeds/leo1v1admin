<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TResourceAddStuHash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi.t_resource', function( Blueprint $table)
        {
            t_field($table->char("sut_hash",32),"上传学生版返回的hash");
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
