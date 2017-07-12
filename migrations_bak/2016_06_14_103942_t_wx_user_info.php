<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TWxUserInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
          array:9 [▼
          "openid" => "o97Q8vxpdbvMOslCsDA5jiTptSRo"
          "nickname" => "薛朝文"
          "sex" => 1
          "language" => "zh_CN"
          "city" => "Xuhui"
          " province " => "Shanghai"
          "country" => "CN"
          " headimgurl " => "http://wx.qlogo.cn/mmopen/8y1zr3H8ibkcm6cINZUIbWn28ujRBHTfcADWuTibiadxxDX9JOc5XjN2gNHW2d92eBkMDGNvh6SfSUE1NGnKPcx9cWLNlPL89x1/0"
          "privilege" => []
          ]
        */

	   Schema::create('db_weiyi_admin.t_wx_user_info', function (Blueprint $table)
       {
           $table->string("openid");
           $table->integer("update_time");
           $table->integer("sex");
           $table->string("language");
           $table->string("city");
           $table->string("country");
           $table->string("province");
           $table->string("headimgurl");

           $table->primary("openid");
           $table->index("update_time");

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

        Schema::drop('db_weiyi_admin.t_wx_user_info');
        //
    }
}
