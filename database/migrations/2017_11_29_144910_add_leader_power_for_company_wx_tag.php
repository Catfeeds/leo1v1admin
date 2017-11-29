<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeaderPowerForCompanyWxTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("db_weiyi_admin.t_company_wx_tag",function(Blueprint $table) {
            t_field($table->string('leader_power'), "领导权限");
            t_field($table->string("no_leader_power"), "非领导权限");
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
