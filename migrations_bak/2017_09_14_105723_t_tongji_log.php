<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTongjiLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('db_weiyi_admin.t_tongji_log', function (Blueprint $table){
            t_field($table->integer("tongji_log_type"),"类型");
            t_field($table->integer("logtime"),"时间");
            t_field($table->integer("value"), "value" );
            $table->primary(["tongji_log_type","logtime"]);
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
