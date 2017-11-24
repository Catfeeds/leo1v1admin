<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTCompanyWxRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("db_weiyi_admin.t_company_wx_role", function(Blueprint $table) {
            t_field($table->increments("id"),"角色表");
            t_field($table->string("name", 50),"角色名");
            t_field($table->integer("a_id"),"权限id(t_company_wx_auth)");
            t_field($table->integer('u_id'),"部门id(t_company_wx_department)或员工id(t_company_wx_users)");
            t_field($table->tinyInteger("type"),"类型1.部门 2.员工");
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
