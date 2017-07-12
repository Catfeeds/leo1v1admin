<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTSellerStudentInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_seller_student_info', function (Blueprint $table)
        {
            $table->increments("id");
            $table->string("phone",20);
            $table->integer('userid');
            $table->integer('add_time');
            $table->string('origin');
            $table->integer('admin_assignerid');
            $table->integer('admin_assign_time');
            $table->integer('admin_revisiterid');
            $table->integer('grade');
            $table->integer('subject');
            $table->string('nick');
            $table->string('desc');
            $table->integer('status');
            $table->integer('revisit_count');
            $table->integer('last_revisit_time');
            $table->string('last_revisit_msg');
            $table->integer('has_pad');
            $table->unique("phone");
            $table->index("add_time");
            $table->index(["admin_assignerid"   ]);
            $table->index(["admin_revisiterid" , "admin_assign_time"  ]);
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
        Schema::drop('t_seller_student_info');
        //
    }
}
