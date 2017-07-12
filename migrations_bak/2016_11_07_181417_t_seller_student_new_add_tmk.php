<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}


class TSellerStudentNewAddTmk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::table('t_seller_student_new', function( Blueprint $table)
        {
            /*
              tmk_flag
              tmk_adminid
              tmk_assign_time
              tmk_student_status  未设置, 待定 , 无效, 有效 
            */
            add_field($table->integer("tmk_join_time"),"加入tmk资源时间");
            add_field($table->integer("tmk_adminid"),"分配给谁负责");
            add_field($table->integer("tmk_assign_time"),"tmk分配时间");
            add_field($table->integer("tmk_student_status"),"tmp标识状态");

            $table->index("tmk_assign_time");
            $table->index("tmk_join_time");
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
