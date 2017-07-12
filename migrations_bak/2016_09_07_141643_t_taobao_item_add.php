<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTaobaoItemAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_taobao_item', function( Blueprint $table)
        {
            \App\Helper\Utils::comment_field($table->string("product_id",200),"产品id");
            \App\Helper\Utils::comment_field($table->integer("status")->default(0),"0 已下架 1 未下架");
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
