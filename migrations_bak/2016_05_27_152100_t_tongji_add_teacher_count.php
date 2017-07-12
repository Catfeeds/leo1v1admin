<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTongjiAddTeacherCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi_admin.t_tongji', function( Blueprint $table)
        {
            $table->integer("teacher_count");
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
        Schema::table('db_weiyi_admin.t_tongji', function( Blueprint $table)
        {
            $table->dropColumn("teacher_count");
        });
        //
    }
}
