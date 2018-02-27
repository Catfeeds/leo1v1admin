<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerAutoFreeLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_seller_auto_free_log', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("userid"),"userid");
            t_field($table->integer("adminid"),"回流时例子负责人");
            t_field($table->integer("assign_type"),"分配类型0抢单1系统分配");
            t_field($table->integer("assign_time"),"分配时间");
            t_field($table->integer("last_revisit_time"),"回流时最后拨打时间");
            t_field($table->integer("last_edit_time"),"回流时最后编辑时间");
            t_field($table->integer("first_contact_time"),"回流时首次拨通时间");
            t_field($table->integer("left_time"),"回流时过期时间");
            t_field($table->integer("left_time_long"),"回流时过期时长");
            t_field($table->integer("create_time"),"回流时间");
            $table->index("userid");
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
