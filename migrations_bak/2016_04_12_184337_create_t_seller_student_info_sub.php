<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTSellerStudentInfoSub extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_seller_student_info_sub', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string( 'phone',16);
            $table->string( 'phone_location',64);
            $table->string( 'origin',32);
            $table->unsignedInteger("add_time");
            $table->integer("subject");
            $table->integer("grade");
            $table->integer("has_pad");
            $table->integer("trial_type");
            $table->string("nick",64);
            $table->string("qq",64);
            $table->integer("admin_revisiterid");

            $table->index("phone","phone" );
            $table->index(["admin_revisiterid","add_time"], "admin_revisiterid__add_time"  );
            $table->index(["add_time"], "add_time"  );
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
        Schema::drop('t_seller_student_info_sub');
        //
    }
}
