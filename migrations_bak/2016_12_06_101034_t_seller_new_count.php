<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}

class TSellerNewCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //
        Schema::create('db_weiyi_admin.t_seller_new_count', function (Blueprint $table)
        {
            $table->integer("new_count_id",true);
            add_field($table->integer("seller_new_count_type" ), "类型");
            add_field($table->integer("adminid" ), "销售id");
            add_field($table->integer("add_time" ), "加入时间");
            add_field($table->integer("start_time" ), "有效开始时间");
            add_field($table->integer("end_time" ), "有效终止时间");
            add_field($table->integer("count" ), "个数");
            add_field($table->integer("value_ex" ), "扩展说明value");
            $table->index("end_time");
            $table->index("add_time");
            $table->index("start_time");
            $table->index(["adminid","value_ex"]);
        });
        Schema::create('db_weiyi_admin.t_seller_new_count_get_detail', function (Blueprint $table)
        {
            $table->integer("detail_id",true);
            add_field($table->integer("new_count_id" ), "from t_seller_new_count ");
            add_field($table->integer("get_time" ), "获取时间");
            add_field($table->string("get_desc"), "获取说明" );
            $table->index("new_count_id");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('db_weiyi_admin.t_seller_new_count' );
        Schema::drop('db_weiyi_admin.t_seller_new_count_get_detail' );
        //
    }
}
