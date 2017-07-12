<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TQtCallInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_tq_call_info', function (Blueprint $table)
        {
            $table->integer("id");
            $table->integer('uid');
            $table->string('phone',16);
            $table->integer('start_time');
            $table->integer('end_time');
            $table->integer('duration');
            $table->integer('is_called_phone');
            $table->string('record_url');

            $table->primary("id");
            $table->index("start_time");
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
