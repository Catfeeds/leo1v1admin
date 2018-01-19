<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TChangeSubGradeBookTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_sub_grade_book_tag', function (Blueprint $table){
            t_field($table->integer("resource_type"),"资源类型");
            t_field($table->integer("season_id"),"课程类型");
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
