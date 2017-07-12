<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TFieldModifiedListDelField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t_field_modified_list', function ($table) {
            $table->dropColumn(["table_name","field"]);
        });
        Schema::table('t_field_modified_list', function (Blueprint $table) {
            t_field($table->string("t_name"),"表");
            t_field($table->string("f_name"),"字段");
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
