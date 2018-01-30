<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyWxApprovalDataAddPageUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("db_weiyi_admin.t_company_wx_approval_data", function(Blueprint $table) {
            t_field($table->string("page_url"), "页面路由 /controller/action");
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
