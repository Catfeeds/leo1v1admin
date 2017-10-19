<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TOrderActivityInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('t_order_activity_info', function (Blueprint $table) {
            t_field( $table->integer("orderid"), "订单号"  );
            t_field( $table->integer("subid"), "列id"  );
            t_field( $table->integer("order_activity_type"), "活动id"  );
            t_field( $table->integer("succ_flag"), "适用成功"  );
            t_field( $table->string ("activity_desc" ), "活动说明"  );
            t_field( $table->integer("cur_price"), "当前金额"  );
            t_field( $table->integer("cur_present_lesson_count"), "当前赠送课时"  );
            $table->primary(["orderid", "subid" ]);
            $table->unique(["orderid", "order_activity_type" ]);
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
