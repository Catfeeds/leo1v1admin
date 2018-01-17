<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class TCreateSubGradeBookTag extends Migration
{
    /**
     * Run the migrations.
     *OrderActivityConfigChangeValue
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('db_weiyi.t_sub_grade_book_tag');
        Schema::create('db_weiyi.t_sub_grade_book_tag', function (Blueprint $table){
            $table->increments('id');
                t_field($table->integer('subject'),"科目id");
                t_field($table->integer('grade'),"年级");
                t_field($table->integer('bookid'),"教材版本id");
                t_field($table->string('tag'),"学科化标签名字");
                t_field($table->integer('del_flag'),"是否删除默认为0");
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