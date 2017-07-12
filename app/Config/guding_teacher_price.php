<?php
namespace App\Config;

class guding_teacher_price  extends teacher_price_base {
    static public $level_config = [
        1 => [  0xFFFFFFFF],
    ];

    static public $config=null;
    static public function get_config() {
        static::$config= [
            0 => [
                101 => [1=> 50],
                102 => [1=> 50],
                103 => [1=> 50],
                104 => [1=> 50],
                105 => [1=> 50],
                106 => [1=> 50],
                201 => [1=> 55],
                202 => [1=> 60],
                203 => [1=> 75],
                301 => [1=> 75],
                302 => [1=> 80],
                303 => [1=> 90],
            ],
        ];
        return static::$config;
    }

    static public function get_price($level,$grade,$lesson_count_level ) {
        $config=self::get_config();
        $ret=@$config[$level][$grade];
        if (!$ret) {
            return  0;
        }else {
            return  $ret[$lesson_count_level];
        }
    }


}