<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TUserReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('db_weiyi_admin.t_user_report', function (Blueprint $table){
            t_field($table->integer("id",true),"id");
            t_field($table->integer("log_time"),"");
            t_field($table->integer("report_uid"),"");
            t_field($table->integer("report_account_type"),"");
            t_field($table->string("report_msg", 4096),"");
            t_field($table->integer("obj_account_type"),"account_type");
            $table->index("log_time");
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
