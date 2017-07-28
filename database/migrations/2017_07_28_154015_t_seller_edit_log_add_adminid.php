<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerEditLogAddAdminid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_seller_edit_log', function($table){
            $table->dropColumn('old'); //删除表的字段
                $table->dropColumn('new'); //删除表的字段
        });

        Schema::table('db_weiyi_admin.t_seller_edit_log', function( Blueprint $table)
        {
            t_field($table->string("old"),"修改前的值");
            t_field($table->string("new"),"修改后的值");
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
