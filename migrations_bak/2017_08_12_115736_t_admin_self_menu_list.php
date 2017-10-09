<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAdminSelfMenuList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('db_weiyi_admin.t_admin_self_menu', function( Blueprint $table)
        {
            $table->increments("id");
            t_field($table->integer("adminid"),"");
            t_field($table->integer("order_index"),"排序");
            t_field($table->string("title"),"标题");
            t_field($table->string("url"),"url");
            t_field($table->string("icon"),"");
            $table->index(["adminid", "order_index"] );
            $table->unique(["adminid", "url"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("db_weiyi_admin.t_admin_self_menu" );
        //
    }
}
