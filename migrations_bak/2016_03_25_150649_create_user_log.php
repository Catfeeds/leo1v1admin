<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_student_log', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('userid');
            $table->integer('log_time');
            $table->integer('type');
            $table->text("msg");
            $table->index("userid","userid" );
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
        Schema::drop('t_student_log');
        //
    }
}
