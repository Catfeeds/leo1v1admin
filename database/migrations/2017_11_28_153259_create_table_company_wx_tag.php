<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCompanyWxTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("db_weiyi_admin.t_company_wx_tag", function(Blueprint $table) {
            t_field($table->increments("id"), "标签");
            t_field($table->string("name", 50), "标签名");
            t_field($table->string('department'), "部门");
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
