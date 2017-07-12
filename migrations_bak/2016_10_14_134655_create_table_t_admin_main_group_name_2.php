<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTAdminMainGroupName2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_admin_main_group_name', function (Blueprint $table)
        {
            $table->Integer('groupid',true);
            $table->Integer('main_type');
            $table->string('group_name');
            $table->Integer('master_adminid');
            $table->index(["main_type","groupid"]);
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
