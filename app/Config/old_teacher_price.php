<?php
namespace App\Config;

class old_teacher_price  extends teacher_price_base  {
    static public $level_config = [
        1 => [  15000 ],
        2 => [ 22500  ],
        3 => [ 0xFFFFFFFF],
    ];

    static public function  get_1to5_price($level) {
        //小学1-5年级	50 		53 		60 	
        if ($level==3) {//A+
            return [
                1 => 50,  
                2 => 53,  
                3 => 60,  
            ];
        }
        return null;
    }
    static public function  get_6to8_price($level) {
        //6-8年级	57 		60 		67 	
        if ($level==3) {//A+
            return [
                1 => 57,  
                2 => 60,  
                3 => 67,  
            ];
        }
        return null;

    }
    static public function  get_9_price($level) {
        //9年级	67 		70 		77 	
        if ($level==3) {//A+
            return [
                1 => 67,  
                2 => 70,  
                3 => 77,  
            ];
        }
        return null;

    }

    static public function  get_10to11_price($level) {
        //高一-高二	73 		77 		83 	
        if ($level==3) {//A+
            return [
                1 => 73,  
                2 => 77,  
                3 => 83,  
            ];
        }
        return null;
    }

    static public function  get_12_price($level) {
        //`高三	83 		87 		93 	
        if ($level==3) {//A+
            return [
                1 => 83,  
                2 => 87,  
                3 => 93,  
            ];
        }
        return null;
    }



    //teacher level -> grade   -> lesson_count 
    static public $config=null;

    static public function get_config() {
        if (!static::$config ) {
            $l1_5_3=static::get_1to5_price(3);
            $l6_8_3=static::get_6to8_price(3);
            $l9_3=static::get_9_price(3);
            $l10_11_3=static::get_10to11_price(3);
            $l12_3=static::get_12_price(3);
            static::$config= [
                3 => [
                    101 => $l1_5_3, 
                    102 => $l1_5_3, 
                    103 => $l1_5_3, 
                    104 => $l1_5_3, 
                    105 => $l1_5_3, 
                    106 => $l6_8_3, 
                    201 => $l6_8_3, 
                    202 => $l6_8_3, 
                    203 => $l9_3, 
                    301 => $l10_11_3, 
                    302 => $l10_11_3, 
                    303 => $l12_3, 
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
