<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TCsIntendedUserInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi.t_cs_intended_user_info', function (Blueprint $table){
              t_field($table->integer("id",true),"id");
              t_field($table->integer("create_time"),"创建时间");
              t_field($table->integer("create_adminid"),"添加客服ID");
              t_field($table->string("phone"),"联系电话");
              t_field($table->string("child_realname"),"孩子姓名");
              t_field($table->string("parent_realname"),"家长姓名");
              t_field($table->integer("relation_ship"),"关系");
              t_field($table->string("region"),"地区");
              t_field($table->integer("grade"),"年级");
              t_field($table->integer("cash"),"提现金额");
              t_field($table->integer("free_subject"),"试听科目");
              t_field($table->integer("region_version"),"教材版本");
              t_field($table->string("notes"),"备注");
              $table->index("create_adminid");
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
