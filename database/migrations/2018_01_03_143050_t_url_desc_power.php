<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TUrlDescPower extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_url_desc_power', function( Blueprint $table)
        {
            t_comment($table, "角色的每个页面的详细权限" );
            t_field($table->integer("id",true) ,"自增id");
            t_field($table->integer("role_groupid") ,"角色组id");
            t_field($table->string("url") ,"页面地址:/test/get_user_list1");
            t_field($table->string("opt_key") ,"权限识别:grade,opt_grade,input_grade..");
            t_field($table->integer("open_flag") ,"是否开放权限");
            $table->unique(["role_groupid", "url","opt_key"],"role_url_opt_key");
        });

        //
        Schema::create('db_weiyi_admin.t_url_input_define', function( Blueprint $table)
        {
            t_comment($table, "角色的每个页面的输入数据设置" );
            t_field($table->integer("id",true) ,"自增id");
            t_field($table->integer("role_groupid") ,"角色组id");
            t_field($table->string("url") ,"页面地址:/test/get_user_list1");
            t_field($table->string("field_name") ,"参数名:grade");
            t_field($table->integer("field_value") ,"值");
            $table->unique(["role_groupid", "url","field_name"],"role_url_field_name");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('db_weiyi_admin.t_url_desc_power');
        //
    }
}
