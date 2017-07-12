<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAdminCardLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('db_weiyi_admin.t_admin_card_log', function (Blueprint $table)
        {
            $table->integer('logtime');
            $table->integer('cardid');
            $table->primary(["logtime","cardid"]);
            $table->index("cardid");
        });
        Schema::create('db_weiyi_admin.t_admin_card_date_log', function (Blueprint $table)
        {
            $table->integer('logtime');
            $table->integer('cardid');
            $table->integer('start_logtime');
            $table->integer('end_logtime');
            $table->primary(["logtime","cardid"]);
            $table->index("cardid");
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
        Schema::drop('db_weiyi_admin.t_admin_card_date_log');
        Schema::drop('db_weiyi_admin.t_admin_card_log');
        //
    }
}
