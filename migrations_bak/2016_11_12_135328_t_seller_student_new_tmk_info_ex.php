<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}


class TSellerStudentNewTmkInfoEx extends Migration
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
            add_field($table->integer("tmk_next_revisit_time"),"tmk下次回访时间");
            add_field($table->string("tmk_desc",500),"tmk说明");
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
