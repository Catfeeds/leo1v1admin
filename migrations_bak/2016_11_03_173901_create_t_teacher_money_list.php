<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
function add_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}

class CreateTTeacherMoneyList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('t_teacher_money_list', function (Blueprint $table)
        {
            add_field($table->integer("teacherid"),"老师id");
            add_field($table->tinyInteger("type")->default(0),"金额类型");
            add_field($table->integer("add_time"),"记录添加时间");
            add_field($table->integer("money"),"金额");
            add_field($table->string("money_info"),"类型注释/获奖原因");

            $table->primary(["teacherid","type","add_time"]);
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
