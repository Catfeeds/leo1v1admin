<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TSellerStudentOriginAddIsExistCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('db_weiyi.t_seller_student_origin', function( Blueprint $table)
        {
            t_field($table->integer("is_exist_count"),"第几次重复进入");
            $table->index("is_exist_count");
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
