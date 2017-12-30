<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2018010101 extends  activity_config_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2018010101 ;

    static $not_period_lesson_count_price_config = [
        // 小学 奥赛 201 202 203 301 302 303
        0 =>[300,390,330,350,390,450,470,500],
        30=>[270,350,295,315,370,425,445,475],
        60=>[260,335,285,305,355,410,425,455],
        90=>[245,320,270,290,350,405,420,450],
    ];

    static $period_lesson_count_price_config = [
        0 =>[315,410,345,365,410,470,490,520],
        30=>[285,365,310,330,385,445,465,495],
        60=>[275,350,300,320,370,430,445,475],
        90=>[255,335,285,305,365,425,440,470],
    ];

    static function get_lesson_price( $can_period_flag, $lesson_times, $grade  ) {


        $def_value=[0, [ 10000,10000, 10000, 10000, 10000, 100000, 100000, 10000  ]  ];

        if ($grade ==99 ) { //奥赛
            $index=1;
        } else if ( $grade>100 && $grade<=106) {
            $index=0;
        } else if ($grade==201) {
            $index=2;
        } else if ($grade==202) {
            $index=3;
        } else if ($grade==203) {
            $index=4;
        } else if ($grade==301) {
            $index=5;
        } else if ($grade==302) {
            $index=6;
        } else if ($grade==303) {
            $index=7;
        }else { //年级不对
            return  [0,10000];
        }

        $config= static::$not_period_lesson_count_price_config;
        if ($can_period_flag ) {
            $config= static::$period_lesson_count_price_config;
        }
        $price_config=static::get_value_from_config_ex($config, $lesson_times, $def_value );
        return array($price_config[0], $price_config[1][$index]  )  ;
    }


    public function __construct(  $args   ) {
        parent::__construct($args);
        $this->date_range=[ "2017-01-02"  , "2018-06-30"];
        $this->period_flag_list= [ E\Eperiod_flag::V_0 ,E\Eperiod_flag::V_1  ];
        $this->contract_type_list = [E\Econtract_type::V_0 ,  E\Econtract_type::V_3];
        $this->lesson_times_range = [1 ,  10000];

    }

    protected function do_exec (  &$out_args,&$can_period_flag , &$price,  &$present_lesson_count,  &$desc_list )   {

        if ( !parent::do_exec($out_args,$can_period_flag,$price,$present_lesson_count,$desc_list)){
            return ;
        };

        \App\Helper\Utils::logger(" do price: $price");


        list($find_count_level , $find_pre_price)= static::get_lesson_price( $can_period_flag, $this->lesson_times, $this->grade  );
        $find_price= $find_pre_price* $this->lesson_times;
        $off_value=  ($find_price /$price)*100;
        $price=$find_price;
        if ($off_value<100) {
            //2018-0101 满课时打折(常规) 
            $off_value_str = sprintf("%.02f",  $off_value );
            $desc_list[]=static::gen_activity_item(1,  "$find_count_level 次课 $off_value_str 折, 单价: $find_pre_price " , $price,  $present_lesson_count, $can_period_flag );
        }else{
            $desc_list[]=static::gen_activity_item(0,  "" , $price,  $present_lesson_count , $can_period_flag );
        }

    }

}