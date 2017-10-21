<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TLessonTimeModifyAddBackstageType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('db_weiyi.t_lesson_time_modify', function( Blueprint $table)
        {
            t_field($table->tinyInteger("backstage_type"),"后台类型 0:理优后台 1:微信端");
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
