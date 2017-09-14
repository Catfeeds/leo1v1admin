<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TMailGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('db_weiyi_admin.t_mail_group_name', function( Blueprint $table)
        {
            t_field($table->integer("groupid",true),"邮件组id");
            t_field($table->string("title"),"标题");
            t_field($table->string("email"),"邮箱");
            $table->unique("email");
        });

        Schema::create('db_weiyi_admin.t_mail_group_user_list', function( Blueprint $table)
        {
            t_field($table->integer("groupid"),"邮件组id");
            t_field($table->integer("adminid"),"用户id");
            $table->primary(["groupid", "adminid"]);
        });
        //
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
