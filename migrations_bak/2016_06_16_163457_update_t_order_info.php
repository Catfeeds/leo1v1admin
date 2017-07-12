<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTOrderInfo extends Migration
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
            $table->integer("lesson_count");
            $table->integer("lesson_count_gift");
            $table->integer("original_price");
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
        Schema::table('t_order_info', function( Blueprint $table)
        {
            $table->dropColumn("lesson_count");
            $table->dropColumn("lesson_count_gift");
            $table->dropColumn("original_price");
        });
    }
}
