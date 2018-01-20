<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAssGroupTargetAddGreoupRenewTarget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_ass_group_target', function( Blueprint $table)
        {
            t_field($table->integer("group_renew_target") ,"团队月续费目标");
            t_field($table->integer("all_renew_target") ,"总体月续费目标");
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
