<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017111705 extends  activity_config_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017111705;

    public function __construct(  $args   ) {
        parent::__construct($args);
        $this->date_range=[ "2017-11-18"  , "2017-11-20"];
        $this->period_flag_list= [ E\Eperiod_flag::V_0 ,E\Eperiod_flag::V_1  ];
        $this->contract_type_list = [E\Econtract_type::V_3 ];
        $this->lesson_times_range = [30 ,  10000];
        $this->max_count=18;
        self::$max_count_activity_type_list=[
            E\Eorder_activity_type::V_2017111702,
            E\Eorder_activity_type::V_2017111703,
            E\Eorder_activity_type::V_2017111704,
            E\Eorder_activity_type::V_2017111705,
        ];

        $this->check_grade_list=[
            E\Egrade::V_303,
        ];


    }

    protected function do_exec (  &$out_args,&$can_period_flag , &$price,  &$present_lesson_count,  &$desc_list )   {

        if ( !parent::do_exec($out_args,$can_period_flag,$price,$present_lesson_count,$desc_list)){
            return ;
        };

        $new_discount_config= $this->args["new_discount_config"] ;
        \App\Helper\Utils::logger(" do price: $price");

        list( $count_check_ok_flag,$now_count, $activity_desc_cur_count)= static::check_use_count($this->max_count );


        list($find_count_level ,$off_value)=static::get_value_from_config_ex($new_discount_config, $this->lesson_times, [1,100] );
        $old_price= $price;
        $price=$price*$off_value/100;
        $price=intval($price*90/100);

        $desc_list[]=static::gen_activity_item(1,  "常规: $find_count_level 次课 $off_value 折, 额外: 折上折9折 $activity_desc_cur_count " , $price,  $present_lesson_count, $can_period_flag ,$old_price-$price);

    }

}
