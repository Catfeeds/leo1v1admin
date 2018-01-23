<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TProductFeedbackListResetFeedbackAdminid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_product_feedback_list', function(Blueprint $table) {
            $table->dropColumn('feedback_adminid');
                t_field($table->string("feedback_nick",100), "反馈人姓名");
                $table->index('feedback_nick');
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
