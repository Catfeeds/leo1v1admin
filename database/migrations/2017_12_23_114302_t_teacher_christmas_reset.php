<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherChristmasReset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_teacher_christmas', function( Blueprint $table)
        {

            $table->dropColumn('teacherid');
            $table->dropColumn('next_openid');

            t_field($table->string("shareId"), "分享人openid");
            t_field($table->string("currentId"), "下级openid");
            $table->index('shareId');
            $table->index('currentId');

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
