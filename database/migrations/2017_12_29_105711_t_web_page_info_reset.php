<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TWebPageInfoReset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_web_page_trace_log', function( Blueprint $table)
        {
            $table->dropColumn('act_usuall_id');
        });

        Schema::table('db_weiyi_admin.t_web_page_info', function( Blueprint $table)
        {
            t_field($table->integer("act_usuall_id") ,"关联t_activity_usually 的id");
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
