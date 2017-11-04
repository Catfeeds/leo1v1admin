<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_yxyx extends  activity_base {

    public static $order_activity_type= E\Eorder_activity_type::V_YXYX;

    public function __construct(  $args   ) {
        parent::__construct($args);
    }

    protected function do_exec (&$out_args ,&$can_period_flag,   &$price,  &$present_lesson_count,  &$desc_list )   {
        if ($this->lesson_times  <10  ) {
            return ;
        }
        //优学优享活动
        $userid=$this->userid;
        if ($userid) {
            $parentid=$this->task->t_student_info->get_parentid($userid);
            if ($parentid) {
                $prize_row=$this->task->t_ruffian_activity->get_order_max_prize_type($parentid);
                if ($prize_row) {

                    $prize_type= $prize_row["prize_type"];
                    $id=$prize_row["id"];
                    $off_str=E\Eruffian_prize_type::v2s($prize_type);
                    /*
                      2=>  "off_10",
                      3=>  "off_50",
                      4=>  "off_100",
                      5=>  "off_300",
                      6=>  "off_500",
                    */
                    $free_money=0;
                    if ( preg_match("/off_(.*)/",$off_str ,$matches)  ) {
                        $free_money= $matches[1];
                    }
                    if ($free_money){
                        $price-=$free_money;
                        $out_args["ruffian_activity_use_id"] = $id;
                        $activity_desc = E\Eruffian_prize_type::get_desc($prize_type ). ", id=$id ";
                        $desc_list[]=static::gen_activity_item(1,  $activity_desc , $price,  $present_lesson_count, $can_period_flag );
                    }
                }
            }
        }
    }

}