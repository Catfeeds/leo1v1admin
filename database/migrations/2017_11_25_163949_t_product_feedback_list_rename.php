<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TProductFeedbackListRename extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("db_weiyi.t_product_feedback_list", function(Blueprint $table) {
            $table->dropColumn('describe');
            t_field($table->string('describe_msg', 1024),"问题描述");
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
