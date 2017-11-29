<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyWxTagDepartment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("db_weiyi_admin.t_company_wx_tag_department", function(Blueprint $table){
            t_field($table->integer("id"), "t_company_wx_tag表id");
            t_field($table->integer("department"), "部门");
            $table->unique(['id','department']);
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
