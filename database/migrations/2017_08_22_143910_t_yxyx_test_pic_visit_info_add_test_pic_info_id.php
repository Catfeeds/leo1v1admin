<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TYxyxTestPicVisitInfoAddTestPicInfoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_yxyx_test_pic_visit_info', function( Blueprint $table)
        {
            t_field($table->integer("test_pic_info_id"),"");

            $table->index(["test_pic_info_id"]);
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
