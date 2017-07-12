<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TAudioRecordServer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	   Schema::create('t_audio_record_server', function (Blueprint $table)
       {
           $table->string("ip");
           $table->integer("last_active_time");

           \App\Helper\Utils::comment_field(
               $table->integer("priority")
               ,"优先权 ,越大越优先,一般 和机器的CPU个数成正比");
           $table->string("desc");
           $table->primary("ip");
           $table->index("priority");
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
