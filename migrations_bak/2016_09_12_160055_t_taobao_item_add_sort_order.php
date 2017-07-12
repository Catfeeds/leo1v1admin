<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTaobaoItemAddSortOrder extends Migration
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
            \App\Helper\Utils::comment_field(
                $table->integer("sort_order")
                ,"淘宝商品的排序"
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
