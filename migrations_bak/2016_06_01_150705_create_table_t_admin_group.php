<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTAdminGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
	   Schema::create('db_weiyi_admin.t_admin_group', function (Blueprint $table) {
             $table->Integer('groupid');
             $table->Integer('adminid');
             $table->primary(['groupid','adminid']);
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
        Schema::drop('db_weiyi_admin.t_admin_group');
    }
}
