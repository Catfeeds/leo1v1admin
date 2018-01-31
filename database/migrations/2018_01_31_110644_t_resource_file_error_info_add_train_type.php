<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TResourceFileErrorInfoAddTrainType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_resource_file_error_info', function( Blueprint $table)
        {
            t_field($table->integer("train_error_type"),"提问类型(培训库)");
            t_field($table->string("phone"),"phone");
            t_field($table->string("nick"),"nick");
            t_field($table->string("answer",1024),"answer");
            t_field($table->integer("answer_time"),"answer_time");
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
