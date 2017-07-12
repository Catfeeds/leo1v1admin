<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTAssistantMonthTarget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('db_weiyi_admin.t_assistant_month_target', function (Blueprint $table)
        {
            
            $table->integer("adminid");
            t_field($table->integer("month"),"月份 格式:2017-01-01");
            t_field($table->integer("lesson_target"),"目标系数");
 
            $table->primary(["adminid", "month" ]);
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
