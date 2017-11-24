<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCompanyWxUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi_admin.t_company_wx_users', function(Blueprint $table) {
            t_field($table->increments("id"),"企业微信员工表");
            t_field($table->string("userid", 20),"用户id");
            t_field($table->string("name", 50),"员工名");
            t_field($table->string("department"),"所属部门 1,2");
            t_field($table->string("position", 50),"职位");
            t_field($table->string("mobile",16),"手机号");
            t_field($table->tinyInteger("gender"),"性别1.男2.女");
            t_field($table->string("email", 50),"邮箱");
            t_field($table->string("avatar"),"头像地址");
            t_field($table->tinyInteger("isleader"),"是否为上级");
            t_field($table->string("english_name"),"英文名");
            t_field($table->string("telephone", 16),"座机");
            t_field($table->integer("order"),"排序");
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
