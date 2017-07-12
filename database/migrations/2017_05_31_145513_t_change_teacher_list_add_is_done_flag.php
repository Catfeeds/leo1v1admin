<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TChangeTeacherListAddIsDoneFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_change_teacher_list', function( Blueprint $table)
        {
             $table->dropColumn('id_done_flag');
        });

        Schema::table('db_weiyi.t_change_teacher_list', function( Blueprint $table)
        {
            t_field($table->integer("is_done_flag"),"完成标志 0 未设置,1已解决,2未解决");
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
