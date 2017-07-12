<?php
namespace App\Config;


class teacher_price  extends teacher_price_base   {

    static public $level_config = [
        1 => [  1500 ],
        2 => [  4500 ],
        3 => [  7500 ],
        4 => [  13500 ],
        5 => [  18000 ],
        6 => [  0xFFFFFFFF],
    ];

    static public function  get_1to5_price($level) {
        if ($level==0) {
            return [
                1 => 33,  
                2 => 37,  
                3 => 40,  
                4 => 47,  
                5 => 50,  
                6 => 53,  
            ];
        }else if ( $level ==1) {
            return [
                1 => 40,  
                2 => 43,  
                3 => 47,  
                4 => 53,  
                5 => 57,  
                6 => 60,  
            ];
        }else if ( $level ==2) {
            return [
                1 => 47,  
                2 => 50,  
                3 => 53,  
                4 => 60,  
                5 => 63,  
                6 => 67,  
            ];
        }
        return null;
    }
    static public function  get_6to8_price($level) {
        if ($level==0) {
            //6-8年级	37 	40 	43 	50 	53 	57 

            return [
                1 => 37,  
                2 => 40,  
                3 => 43,  
                4 => 50,  
                5 => 53,  
                6 => 57,  
            ];
        }else if ( $level ==1) {
            //6-8年级	43 	47 	50 	57 	60 	63 
            return [
                1 => 43,  
                2 => 47,  
                3 => 50,  
                4 => 57,  
                5 => 60,  
                6 => 63,  
            ];
        }else if ( $level ==2) {
            //6-8年级	50 	53 	57 	63 	67 	70 
            return [
                1 => 50,  
                2 => 53,  
                3 => 57,  
                4 => 63,  
                5 => 67,  
                6 => 70,  
            ];
        }
        return null;
    }
    static public function  get_9_price($level) {
        if ($level==0) {
            //9年级	43 	47 	50 	57 	60 	63 
  
            return [
                1 => 43,  
                2 => 47,  
                3 => 50,  
                4 => 57,  
                5 => 60,  
                6 => 63,  
            ];
        }else if ( $level ==1) {
            //9年级	50 	53 	57 	63 	67 	70 

            return [
                1 => 50,  
                2 => 53,  
                3 => 57,  
                4 => 63,  
                5 => 67,  
                6 => 70,  
            ];
        }else if ( $level ==2) {
            //9年级	57 	60 	63 	70 	73 	77 

            return [
                1 => 57,  
                2 => 60,  
                3 => 63,  
                4 => 70,  
                5 => 73,  
                6 => 77,  
            ];
        }
        return null;
    }

    static public function  get_10to11_price($level) {
        if ($level==0) {
            //高一-高二	47 	50 	53 	60 	63 	67 
            return [
                1 => 47,  
                2 => 50,  
                3 => 53,  
                4 => 60,  
                5 => 63,  
                6 => 67,  
            ];
        }else if ( $level ==1) {
            //高一-高二	53 	57 	60 	67 	70 	73 
            return [
                1 => 53,  
                2 => 57,  
                3 => 60,  
                4 => 67,  
                5 => 70,  
                6 => 73,  
            ];
        }else if ( $level ==2) {
            //高一-高二	60 	63 	67 	73 	77 	80 
            return [
                1 => 60,  
                2 => 63,  
                3 => 67,  
                4 => 73,  
                5 => 77,  
                6 => 80,  
            ];
        }
        return null;
    }

    static public function  get_12_price($level) {
        if ($level==0) {
            //高三	53 	57 	60  	67 	70  73

            return [
                1 => 53,  
                2 => 57,  
                3 => 60,  
                4 => 67,  
                5 => 70,  
                6 => 73,  
            ];
        }else if ( $level ==1) {
            //高三	60 	63 	67 	73 	77 	80 
            return [
                1 => 60,  
                2 => 63,  
                3 => 67,  
                4 => 73,  
                5 => 77,  
                6 => 80,  
            ];
        }else if ( $level ==2) {
            //高三	67 	70 	73 	80 	83 	87 
            return [
                1 => 67,  
                2 => 70,  
                3 => 73,  
                4 => 80,  
                5 => 83,  
                6 => 87,  
            ];
        }
        return null;
    }



    //teacher level -> grade   -> lesson_count 
    static public $config=null;

    static public function get_config() {
        if (!static::$config ) {
            $l1_5_0=static::get_1to5_price(0);
            $l1_5_1=static::get_1to5_price(1);
            $l1_5_2=static::get_1to5_price(2);

            $l6_8_0=static::get_6to8_price(0);
            $l6_8_1=static::get_6to8_price(1);
            $l6_8_2=static::get_6to8_price(2);

            $l9_0=static::get_9_price(0);
            $l9_1=static::get_9_price(1);
            $l9_2=static::get_9_price(2);

            $l10_11_0=static::get_10to11_price(0);
            $l10_11_1=static::get_10to11_price(1);
            $l10_11_2=static::get_10to11_price(2);

            $l12_0=static::get_12_price(0);
            $l12_1=static::get_12_price(1);
            $l12_2=static::get_12_price(2);
            static::$config= [
                0 => [
                    101 => $l1_5_0, 
                    102 => $l1_5_0, 
                    103 => $l1_5_0, 
                    104 => $l1_5_0, 
                    105 => $l1_5_0, 
                    106 => $l6_8_0, 
                    201 => $l6_8_0, 
                    202 => $l6_8_0, 
                    203 => $l9_0, 
                    301 => $l10_11_0, 
                    302 => $l10_11_0, 
                    303 => $l12_0, 
                ],

                1 => [
                    101 => $l1_5_1, 
                    102 => $l1_5_1, 
                    103 => $l1_5_1, 
                    104 => $l1_5_1, 
                    105 => $l1_5_1, 
                    106 => $l6_8_1, 
                    201 => $l6_8_1, 
                    202 => $l6_8_1, 
                    203 => $l9_1, 
                    301 => $l10_11_1, 
                    302 => $l10_11_1, 
                    303 => $l12_1, 
                ],
                2 => [
                    101 => $l1_5_2, 
                    102 => $l1_5_2, 
                    103 => $l1_5_2, 
                    104 => $l1_5_2, 
                    105 => $l1_5_2, 
                    106 => $l6_8_2, 
                    201 => $l6_8_2, 
                    202 => $l6_8_2, 
                    203 => $l9_2, 
                    301 => $l10_11_2, 
                    302 => $l10_11_2, 
                    303 => $l12_2, 

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
