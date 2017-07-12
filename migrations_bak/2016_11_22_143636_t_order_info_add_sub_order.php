<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}


class TOrderInfoAddSubOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_order_info', function( Blueprint $table)
        {
            add_field($table->integer("from_parent_order_type"),"父合同分类;0:课程包赠送, 1:转介绍赠送, 10:课程异常赠送" );
            add_field($table->integer("parent_order_id"),"父合同id");
            $table->index("parent_order_id");
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
