<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertTManageInfoAddAccountRole extends Migration
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
            $table->integer("account_role");
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
	Schema::table('db_weiyi_admin.t_manager_info', function( Blueprint $table)
        {
            $table->dropColumn("account_role");
        });
	
    }
}
