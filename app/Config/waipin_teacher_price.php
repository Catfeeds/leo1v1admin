<?php
namespace App\Config;


class waipin_teacher_price  extends teacher_price_base  {
    static public $level_config = [
        1 => [  1000 ],
        2 => [ 6000  ],
        3 => [ 12000  ],
        4 => [ 0xFFFFFFFF],
    ];


    static public function  get_1to6_price($level) {
        //小学	45	50	55	65
        if ($level==0) {
            return [
                1 => 45,
                2 => 50,
                3 => 55,
                4 => 65,
            ];
        }
        return null;
    }
    static public function  get_7to9_price($level) {
        //初中	55	60	65	75
        if ($level==0) {
            return [
                1 => 55,
                2 => 60,
                3 => 65,
                4 => 75,
            ];
        }
        return null;
    }


    static public function  get_10to12_price($level) {
        //高中	65	70	75	85

        if ($level==0) {
            return [
                1 => 65,
                2 => 70,
                3 => 75,
                4 => 85,
            ];
        }
        return null;

    }


    //teacher level -> grade   -> lesson_count
    static public $config=null;

    static public function get_config() {
        if (!static::$config ) {
            $l1_6_0=static::get_1to6_price(0);
            $l7_9_0=static::get_7to9_price(0);
            $l10_12_0=static::get_10to12_price(0);
            static::$config= [
                0 => [
                    101 => $l1_6_0,
                    102 => $l1_6_0,
                    103 => $l1_6_0,
                    104 => $l1_6_0,
                    105 => $l1_6_0,
                    106 => $l7_9_0,
                    201 => $l7_9_0,
                    202 => $l7_9_0,
                    203 => $l7_9_0,
                    301 => $l10_12_0,
                    302 => $l10_12_0,
                    303 => $l10_12_0,
                ],
            ];
        }

        return static::$config;
    }

    static public function get_price($level,$grade,$lesson_count_level ) {
        $config=self::get_config();
        $ret=@$config[$level][$grade];
        if (!$ret) {
            return  0;
        }else{
            return  $ret[$lesson_count_level];
        }
    }

}
