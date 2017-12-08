<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyWxDepartment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('db_weiyi_admin.t_company_wx_department', function(Blueprint $table) {
        //     t_field($table->increments("id"),"企业微信员工组织表");
        //     t_field($table->string("name",50),"部门名");
        //     t_field($table->integer("parentid"),"父部门id");
        //     t_field($table->integer("order"),"排序");
        // });
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
