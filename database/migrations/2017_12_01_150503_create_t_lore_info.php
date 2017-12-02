<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTLoreInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi.t_lore_info', function (Blueprint $table){
            $table->increments('lore_id');
                t_field($table->string("name"),"知识点名称");
                t_field($table->integer("pid"),"父id");
                t_field($table->integer("level"),"知识点等级 1,2,3");

                $table->index("pid");
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
