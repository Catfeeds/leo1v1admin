<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TYxyxTestPicInfoAddCustomType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_yxyx_test_pic_info', function( Blueprint $table)
        {
            t_field($table->string("custom_type",128),"自定义标签id，用逗号分割");
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
