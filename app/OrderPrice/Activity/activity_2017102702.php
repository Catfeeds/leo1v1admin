<?php
namespace App\OrderPrice\Activity;

use \App\Enums as E;
class activity_2017102702 extends  activity_base {

    public static $order_activity_type= E\Eorder_activity_type::V_2017102702;

    public function __construct(  $args   ) {
        parent::__construct($args);
    }

    protected function do_exec (&$can_period_flag,   &$price,  &$present_lesson_count,  &$desc_list )   {
        //2017-102702 27-31  15号前试听的 30,45次课 全款9折

        if (!$this->check_now("2017-10-27"  , "2017-11-01")) {
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
            $first_lesson_info=$task->t_lesson_info_b3->get_grade_first_test_lesson( $userid, $grade );
            $lesson_start = $first_lesson_info["lesson_start"];

            $lesson_start_desc=" 试听课时间:".\App\Helper\Utils::unixtime2date($lesson_start ).",";

            $adminid=$task->t_seller_student_new->get_admin_assignerid($userid);
            $account_role=$task->t_manager_info->get_account_role($adminid);
            if ( $lesson_start< strtotime("2017-10-16")
                && $this->lesson_times >=30 &&  $this->lesson_times <=45
            ) {
                $price*=0.9;
                $desc_list[]=static::gen_activity_item(1,  $lesson_start_desc. "折上折:9折" , $price,  $present_lesson_count, $can_period_flag );

            } else {
                $desc_list[]=static::gen_activity_item(0,  $lesson_start_desc , $price,  $present_lesson_count, $can_period_flag );
            }

        }

    }
}