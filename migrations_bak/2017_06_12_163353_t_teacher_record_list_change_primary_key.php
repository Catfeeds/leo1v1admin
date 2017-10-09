<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherRecordListChangePrimaryKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('db_weiyi.t_teacher_record_list', function( Blueprint $table)
        {
            $table->dropPrimary();
        });

        Schema::table('db_weiyi.t_teacher_record_list',function( Blueprint $table)
        {
            $table->increments("id");
            $table->unique(['teacherid','type','add_time'],'unique_record');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
