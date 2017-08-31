<?php
namespace App\Helper;
class office_cmd{
    static $key="office_cmd";
    static function add_one(  $office_device_type ,$device_id,$device_opt_type , $office_device_sub_type, $value ) {

        $office_device_sub_type=1;//海尔
        if (in_array ($device_id , [1,2,6,7,8,11,13,14] )) {
            $office_device_sub_type=2;//美的
        }

        $item=[
            "create_time" => time(NULL),
            "office_device_type" => $office_device_type,
            "device_id" => $device_id ,
            "device_opt_type" => $device_opt_type ,
            "device_sub_type" => $office_device_sub_type,
            "value" => $value
        ];
        static::add_one_item($item);

    }
    static function get_list() {
        $sync_data_list=\App\Helper\Common::redis_get_json(static::$key);
        if (!$sync_data_list)  {
            $sync_data_list=[];
        }
        return $sync_data_list;
    }
    static function set_list($list ) {
        \App\Helper\Common::redis_set_json(static::$key,$list);
    }

    static function do_one() {
        $list= static::get_list();
        $item=array_shift($list);
        if ($item) {
            static::set_list($list);
        }
        return $item;
    }

    static function add_one_item($item) {
        $list= static::get_list();
        $list[]=$item;
        static::set_list($list);
    }

}