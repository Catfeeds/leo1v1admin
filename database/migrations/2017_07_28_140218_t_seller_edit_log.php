<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerEditLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_seller_edit_log', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("adminid"),"修改人id");
            t_field($table->integer("uid"),"被修改人id");
            t_field($table->integer("type"),"1更改权限组,2修改咨询老师等级");
            t_field($table->integer("old"),"修改前的值");
            t_field($table->integer("new"),"修改后的值");
            t_field($table->integer("create_time"),"创建时间");
            $table->primary("id");
            $table->index("adminid");
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
