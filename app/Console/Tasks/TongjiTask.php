<?php

namespace App\Console\Tasks;

use \App\Enums as E;
use \App\Models\Zgen as Z;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis ;


class TongjiTask extends TaskController
{
    public function get_user_list($list) {
        $ret_list=[];
        foreach( $list as $item ) {
            $ret_list[$item["userid"] ]=true;
        }
        return $ret_list;
    }
    public function tongji()
    {//得到
        $now=time(NULL)-86400*2;

        $r_date_arr = \App\Helper\Common::get_month_range($now);
        $start_time = $r_date_arr["sdate"];
        $end_time   = $r_date_arr["edate"];
        $end_time   = time(NULL);
        
        //本月上课列表
        $lesson_list=$this->t_lesson_info->tongji_get_1v1_lesson_list($start_time,$end_time);
        $lesson_user_list= $this->get_user_list( $lesson_list);
        echo "\nlesson_user_list:";
        print_r($lesson_user_list);

         
        //得到正式学员列表
        $lesson_left_user_list=$this->t_course_order->tongji_get_active_1v1_list();
        
        echo "\nlesson_left_user_list:";
        print_r($lesson_left_user_list);


        //all_user
        $all_user=$lesson_user_list;
        foreach ( $lesson_left_user_list as $l_key=> $l_item) {
            $all_user[$l_key]=true;
        }
        $tmp_all_user=[];
        //过滤测试用户
        foreach ($all_user as $userid=>$v) {
            $is_test_user= $this->t_student_info->get_is_test_user($userid);
            if (!$is_test_user) {
                $tmp_all_user[$userid]=true;
            }else{
                unset($lesson_left_user_list[$userid ] );
                unset($lesson_user_list[$userid ] );
            }
        }
        $all_user=$tmp_all_user;

        
        //得到停课学生列表  是正式学员，但没有上课
        $stop_user_list=[];
        foreach ( $lesson_left_user_list as $l_key=> $l_item) {
            if (!isset($lesson_user_list[$l_key])){
                $stop_user_list[$l_key]=true;
            }
        }

        //得到结课学员  有上课，但已不是正式学员
        $finish_user_list=[];
        foreach ( $lesson_user_list as $l_key=> $l_item) {
            if (!isset($lesson_left_user_list[$l_key])){
                $finish_user_list[$l_key]=true;
            }
        }
        $new_stu_map=[];
        $old_stu_map=[];
        foreach ($all_user as $userid=>$v) {
            //得到用户状态
            $is_new_stu=$this->t_order_info->tongji_get_user_is_new_stu($userid,$start_time);
            if ($is_new_stu==-1)  {
                echo "error user  $userid \n";
            }else{
                if ($is_new_stu==1) {
                    $new_stu_map[$userid]=true;
                }else{
                    $old_stu_map[$userid]=true;
                }
            }
        }
        //处理 试听课人数
        $test_user_count = $this->t_lesson_info->tongji_get_test_count($start_time,$end_time);

        //消耗课时数
        $deal_lesson_count_list = $this->t_lesson_info->tongji_get_lesson_count_list($start_time,$end_time);
        $test_lesson_count=0;
        $new_lesson_count=0;
        $old_lesson_count=0;
        foreach($deal_lesson_count_list as $item ) {
            $lesson_type=$item["lesson_type"];
            $userid=$item["userid"];
            if ($lesson_type==2) { //试听
                $test_lesson_count++;
            }else{
                if (isset($new_stu_map[$userid] )){
                    $new_lesson_count++;
                }else if  (isset($old_stu_map[$userid] )) {
                    $old_lesson_count++;
                }
            }
        }

        //总金额,总课次
        $order_info=$this->t_order_info->tongji_get_money_all($start_time,$end_time);
        $money=$order_info["price_all"];

        //新增,续费的总人数
        $order_user_list=$this->t_order_info-> tongji_get_order_user_list($start_time,$end_time);

        $next_pay_count=0;
        $new_pay_count=0;
        foreach($order_user_list as $item ) {
            $userid=$item["userid"];
            if (isset($new_stu_map[$userid])) {
                echo "check order new userid=$userid\n";
                $new_pay_count++;
            }else if (isset( $old_stu_map[$userid]) ){
                echo "check order old userid=$userid\n";
                $next_pay_count++;
            }else{
                echo "check order test_user userid=$userid \n";
            }
        }
        echo ("xxxx:$new_pay_count\n");
        //得到报科目数
        $subject_user_list=$this->t_order_info->tongji_get_suject_list($start_time,$end_time);

        echo "\nsubject_user_list:\n";
        print_r($subject_user_list) ;
        $new_course_count=0;
        $old_course_count=0;
        foreach ($subject_user_list as $userid=>$count) {
            if (isset($new_stu_map[$userid])) {
                $new_course_count+=$count; // 
            }else if (isset($old_stu_map[$userid]) ){
                $old_course_count+=$count; // 
            }
        }
        $log_date=date ("Ymd",$start_time);
        
        echo "stop_user_list:";
        print_r( $stop_user_list );
        //得到老师个数
        $teacher_count=$this->t_lesson_info->tongji_get_teacher_count($start_time,$end_time);


        $this->t_tongji->row_insert  ([
            'log_date'         => $log_date,
            'new_course_count' => $new_course_count,
            'old_course_count' => $old_course_count,

            //消耗课时
            'new_lesson_count'  => $new_lesson_count,
            'old_lesson_count'  => $old_lesson_count,
            'test_lesson_count' => $test_lesson_count,

            "teacher_count"  =>$teacher_count,
            'money' => $money,
            //'real_money'    => $real_money,
            'test_free_count' => $test_user_count ,
            //'test_money_count' => $test_money_count,
            //'test_money'=>$test_money,
            'new_count'=>$new_pay_count,
            'next_count'=>$next_pay_count,
            'old_count'    => count($old_stu_map),
            'stop_count'   => count($stop_user_list),
            'finish_count' => count($finish_user_list),
        ]);
    }
}
