<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentOriginAddLastSucLessonid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_seller_student_origin', function( Blueprint $table)
        {
            t_field($table->integer("last_suc_lessonid"),"当前渠道进入后最近一次试听成功");
            t_field($table->integer("last_orderid"),"当前渠道进入后最近一次签单");
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
