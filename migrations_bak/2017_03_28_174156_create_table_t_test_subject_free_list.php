<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTTestSubjectFreeList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::drop('t_test_subject_free_list');
        Schema::create('t_test_subject_free_list', function (Blueprint $table){
            t_field($table->integer("adminid"),"销售adminid");
            t_field($table->integer("userid"),"");
            t_field($table->integer("add_time"),"废除时间");
            t_field($table->integer("test_subject_free_type"),"废除类型");
            t_field($table->integer("test_subject_free_reason"),"废除原因");
            $table->primary(["userid","adminid"]);
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
