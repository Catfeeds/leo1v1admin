<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017080101 extends  activity_config_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017080101;

    public function __construct(  $args   ) {
        parent::__construct($args);
        $this->date_range=[ "2017-08-01"  , "2018-12-31"];
        $this->period_flag_list= [ E\Eperiod_flag::V_0 ,E\Eperiod_flag::V_1  ];
        $this->contract_type_list = [E\Econtract_type::V_0 ,  E\Econtract_type::V_3];
        $this->lesson_times_range = [1 ,  10000];


    }

    protected function do_exec (  &$out_args, &$can_period_flag, &$price,  &$present_lesson_count,  &$desc_list )   {
        if (!parent::do_exec($out_args,$can_period_flag,$price,$present_lesson_count,$desc_list)){
            return ;
        };

        $free_money=0;
        if($this->from_test_lesson_id ){
            $task= self::get_task_controler();
            //当配活动
            $lesson_info= $task->t_lesson_info_b2->field_get_list(
                $this->from_test_lesson_id,
                "lesson_start,userid,grade");
            $userid = $lesson_info["userid"];
            $grade  = $lesson_info["grade"];
            $lesson_start = $lesson_info["lesson_start"];
            $first_lesson_info=$task->t_lesson_info_b3->get_grade_first_test_lesson( $userid, $grade );
            $lesson_start = $first_lesson_info["lesson_start"];

            $lesson_start_desc=" 试听课时间:".\App\Helper\Utils::unixtime2date($lesson_start );

            $check_time= strtotime( date("Y-m-d", $lesson_start) )+86400*2;

            if ( $this->lesson_times>=30 && $lesson_start &&  time(NULL)<$check_time  ) {

                $free_money=300;
                $price-=$free_money;
                //2017-0801 当配活动(常规)
                $activity_desc="试听后一天内下单 立减 300元 ,$lesson_start_desc";
                $desc_list[]=static::gen_activity_item(1, $activity_desc ,  $price,  $present_lesson_count ,$can_period_flag );
            }else{

                $activity_desc="$lesson_start_desc";
                $desc_list[]=static::gen_activity_item(0, $activity_desc ,  $price,  $present_lesson_count ,$can_period_flag );
            }
        }else{
            $activity_desc="无试听课";

            $desc_list[]=static::gen_activity_item(0, $activity_desc ,  $price,  $present_lesson_count,$can_period_flag );
        }

    }
    function get_desc() {
        $ret_arr=parent::get_desc();

        $ret_arr[]=["", " 试听后一天内下单 立减 300元 " ];
        return $ret_arr;
    }

}