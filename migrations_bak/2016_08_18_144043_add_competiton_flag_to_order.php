<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompetitonFlagToOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_order_info', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field(
                $table->integer('competition_flag')->default(0)
                ,"竞赛标志 0 常规课,1竞赛课"
            );
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
