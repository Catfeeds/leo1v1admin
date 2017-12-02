<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyTagUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("db_weiyi_admin.t_company_wx_tag_users", function(Blueprint $table) {
            t_field($table->integer("id"), "关联t_company_wx_tag表id");
            t_field($table->string("userid", 50), "关联t_company_wx_users表userid");
            $table->unique(['id','userid']);
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
