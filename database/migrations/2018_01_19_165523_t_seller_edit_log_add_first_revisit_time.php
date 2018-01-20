<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerEditLogAddFirstRevisitTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi_admin.t_seller_edit_log', function( Blueprint $table)
        {
            t_field($table->integer("first_revisit_time"),"分配后首次拨打时间");
            t_field($table->integer("first_contact_time"),"分配后首次拨通时间");
        });

        Schema::table('db_weiyi.t_seller_student_new', function( Blueprint $table)
        {
            t_field($table->integer("distribution_count"),"被分配次数");
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
