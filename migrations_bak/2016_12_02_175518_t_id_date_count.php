<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}

class TIdDateCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_id_opt_log', function (Blueprint $table)
        {
            $table->integer("id",true);
            $table->integer("log_type");
            $table->integer("log_time");
            $table->integer("opt_id");
            $table->integer("value");
            $table->index(["log_type","log_time"]);
            $table->index(["log_type","opt_id"]);

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
