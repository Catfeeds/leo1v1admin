<?php
namespace App\ClassMenu;
class menu{
   static  public  function get_config()  {
        return [
            ["power_id"=>80, "name"=>"小班课",  "list"=> [
                ["power_id"=>83, "name"=>"tt",   "url"=>"/main_page/admin"],
                ["power_id"=>80, "name"=>"xx", "url"=>"/tongji/online_def_user_count_list"],
                ["power_id"=>8, "name"=>"xxlsl",   "url"=>"/user_manage_new/record_audio_server_list"],
            ]],
            ["power_id"=>81, "name"=>"小班课2",  "list"=> [
                ["power_id"=>83, "name"=>"tt",   "url"=>"/main_page/admin2"],
                ["power_id"=>80, "name"=>"xx", "url"=>"/tongji/sonline_def_user_count_list2"],
                ["power_id"=>8, "name"=>"xxlsl",   "url"=>"/user_manage_new/record_audio_server_list2w"],
            ]],

        ];

    }

}
