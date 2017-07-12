<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFieldModifiedListAddTeacherid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_field_modified_list', function (Blueprint $table) {
            t_field($table->integer("teacherid"),"teacherid");
            t_field($table->integer("userid"),"userid");
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
