<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TTeacherRecordList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_teacher_record_list', function (Blueprint $table)
        {
            $table->integer("teacherid");
            t_field($table->integer("type"),"记录类型 1 反馈记录");
            t_field($table->string("record_info",5000),"记录信息");
            t_field($table->integer("add_time"),"评价时间");
            t_field($table->string("acc",32),"评价人");
 
            $table->primary(["teacherid","add_time","type"]);
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
