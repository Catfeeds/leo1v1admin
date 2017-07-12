<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TManageInfoAddCardid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_manager_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer("cardid") ->nullable(),
                "考勤卡id") ;
            $table->unique("cardid");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('db_weiyi_admin.t_manager_info', function( Blueprint $table)
        {
            $table->dropColumn("cardid");
        });
        //
    }
}
