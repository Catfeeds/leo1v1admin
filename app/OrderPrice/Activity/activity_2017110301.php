<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_2017110301 extends  activity_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017110301;

    public function __construct(  $args   ) {
        parent::__construct($args);
    }

    protected function do_exec (&$can_period_flag,   &$price,  &$present_lesson_count,  &$desc_list )   {

        //2017-1103 11.3-6 回流活动   每满10000减500 66个名额 
        if (!$this->check_now("2017-11-03","2017-11-07"))   {
            return ;
        }


        $from_test_lesson_id=$this->from_test_lesson_id;
        if($from_test_lesson_id ) {
            $task= self::get_task_controler();
            $lesson_info= $task->t_lesson_info_b2->field_get_list(
                $this->from_test_lesson_id,
                "lesson_start,userid,grade");
            $userid = $lesson_info["userid"];
            $grade  = $lesson_info["grade"];
            //$lesson_start = $lesson_info["lesson_start"];

            $max_count=66;
            list( $count_check_ok_flag,$now_count, $activity_desc_cur_count)= static::check_use_count($max_count ); 

            $last_lesson_info=$task->t_lesson_info_b3->get_grade_last_test_lesson( $userid, $grade );
            $lesson_start = $last_lesson_info["lesson_start"];

            $lesson_start_desc=" 试听课时间:".\App\Helper\Utils::unixtime2date($lesson_start ).",";

            $adminid=$task->t_seller_student_new->get_admin_assignerid($userid);
            $account_role=$task->t_manager_info->get_account_role($adminid);
            if ( $count_check_ok_flag &&  $lesson_start>0 && $lesson_start< strtotime("2017-10-18")
            ) {
                $free_money=floor($price /10000)*500;
                $price-=$free_money;
                $activity_desc=" $activity_desc_cur_count $lesson_start_desc 立减 $free_money 元";
                $desc_list[]=static::gen_activity_item(1,  $activity_desc , $price,  $present_lesson_count, $can_period_flag );

            } else {
                $activity_desc=" $activity_desc_cur_count $lesson_start_desc ";
                $desc_list[]=static::gen_activity_item(0,  $activity_desc , $price,  $present_lesson_count, $can_period_flag );
            }

        }

    }

}