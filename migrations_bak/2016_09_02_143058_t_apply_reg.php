<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TApplyReg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_weiyi_admin.t_apply_reg', function (Blueprint $table)
        {
            $table->string("phone",20);
            $table->integer('add_time');

            $table->string("name",40);
            $table->string("education",20);
            $table->string("residence",20);
            $table->integer('gender');
            $table->integer('birth'); 
            $table->string('english',20);
            $table->string('polity',20);
            $table->string("carded",30);
            $table->string("marry",20);
            $table->string('child');
            $table->string("email",20);
            $table->string('post',100);
            $table->string('dept',30);
            $table->string('address',256);
            $table->string('strong',256);
            $table->string('interest',256);
            $table->integer('non_compete');
            $table->integer('is_labor');
            $table->string('deucation_info', 5000 );
            $table->string('work_info',5000);
            $table->string('family_info',5000);

            $table->primary("phone");
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
