<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAdminGroupName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //
        Schema::create('db_weiyi_admin.t_admin_group_name', function (Blueprint $table)
        {
            $table->Integer('groupid',true);
            $table->Integer('main_type');
            $table->string('group_name');
            $table->index(["main_type","groupid"]);
        });

        Schema::create('db_weiyi_admin.t_admin_group_user', function (Blueprint $table)
        {
             $table->Integer('groupid');
             $table->Integer('adminid');
             $table->primary(['groupid','adminid']);
             $table->index('adminid');
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
