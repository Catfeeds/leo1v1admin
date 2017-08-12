<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTYxyxTestPicVisitInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi.t_yxyx_test_pic_visit_info', function( Blueprint $table)
        {

            t_field($table->integer("test_pic_info_id"),"图片id");
            t_field($table->integer("parentid"),"家长id");

            $table->primary('test_pic_info_id');
            $table->index(["parentid"]);
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
