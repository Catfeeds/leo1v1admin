<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class limit_require_deal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:limit_require_deal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '特殊申请排课';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $time = time()-86400;
        $list = $task->t_test_lesson_subject_require->get_limit_require_list($time);
        foreach($list as $val){
            $require_id   = $val["require_id"];
            $teacherid    = $val["limit_require_teacherid"];
            $grade        = $val["grade"];
            $subject      = $val["subject"];
            $userid      = $val["userid"];
            $test_lesson_subject_id        = $val["test_lesson_subject_id"];
            $lesson_start = $val["limit_require_lesson_start"];
            $lesson_end   = $lesson_start+2400;
            $orderid      = 1;
            $account = $task->t_manager_info->get_account($val["accept_adminid"]);
            $realname= $task->t_teacher_info->get_realname($teacherid);
            $lesson_time = date("Y-m-d H:i:s",$lesson_start);
            $db_lessonid = $task->t_test_lesson_subject_require->get_current_lessonid($require_id);

            $ret_row1 = $task->t_lesson_info->check_student_time_free($userid,0,$lesson_start,$lesson_end);
            //检查时间是否冲突
            if ($ret_row1) {
                $error_lessonid=$ret_row1["lessonid"];
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($val["limit_require_adminid"],$realname."老师限课特殊申请排课异常通知","排课异常通知","有现存的学生课程与".$val["nick"]."学生,".$realname."老师".$lesson_time."的课程冲突！现已驳回申请","http://admin.leo1v1.com/tea_manage/lesson_list?lessonid=".$error_lessonid);
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($val["limit_require_send_adminid"],$realname."老师限课特殊申请排课异常通知","排课异常通知","有现存的学生课程与".$val["nick"]."学生,".$realname."老师".$lesson_time."的课程冲突！现已驳回申请","http://admin.leo1v1.com/tea_manage/lesson_list?lessonid=".$error_lessonid);
                $task->t_test_lesson_subject_require->field_update_list($require_id,[
                    "limit_accept_flag"  =>2,
                    "limit_accept_time"  =>time()
                ]);
                return;

            }

            $ret_row2=$task->t_lesson_info->check_teacher_time_free(
                $teacherid,0,$lesson_start,$lesson_end);
            if($ret_row2){
                $error_lessonid = $ret_row2["lessonid"];
                 $task->t_manager_info->send_wx_todo_msg_by_adminid ($val["limit_require_adminid"],$realname."老师限课特殊申请排课异常通知","排课异常通知","有现存的老师课程与".$val["nick"]."学生,".$realname."老师".$lesson_time."的课程冲突！现已驳回申请","http://admin.leo1v1.com/tea_manage/lesson_list?lessonid=".$error_lessonid);
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($val["limit_require_send_adminid"],$realname."老师限课特殊申请排课异常通知","排课异常通知","有现存的老师课程与".$val["nick"]."学生,".$realname."老师".$lesson_time."的课程冲突！现已驳回申请","http://admin.leo1v1.com/tea_manage/lesson_list?lessonid=".$error_lessonid);
                $task->t_test_lesson_subject_require->field_update_list($require_id,[
                    "limit_accept_flag"  =>2,
                    "limit_accept_time"  =>time()
                ]);

                return;

            }

            $teacher_info = $task->t_teacher_info->field_get_list($teacherid,"teacher_money_type,level");
            $courseid     = $task->t_course_order->add_course_info_new($orderid,$userid,$grade,$subject,100,2,0,1,1,0,$teacherid);
            $lessonid     = $task->t_lesson_info->add_lesson(
                $courseid,0,
                $userid,
                0,
                2,
                $teacherid,
                0,
                $lesson_start,
                $lesson_end,
                $grade,
                $subject,
                100,
                $teacher_info["teacher_money_type"],
                $teacher_info["level"]
            );
            $task->t_homework_info->add(
                $courseid,
                0,
                $userid,
                $lessonid,
                $grade,
                $subject,
                $teacherid
            );
            $task->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
                "grade"  => $grade,
            ]);
            $task->t_test_lesson_subject_sub_list->row_insert([
                "lessonid"           => $lessonid,
                "require_id"         => $require_id,
                "set_lesson_adminid" => $val["accept_adminid"],
                "set_lesson_time"    => time(NULL) ,
                "seller_require_flag"=> 1
            ]);
            $task->t_test_lesson_subject_require->field_update_list($require_id , [
                'current_lessonid'      => $lessonid,
                'accept_flag'           => 1 ,
                'accept_time'           => time(NULL),
                'jw_test_lesson_status' => 1,
                'grab_status'           => 2,
            ]);
            $task->t_test_lesson_subject_require->set_test_lesson_status(
                $require_id,210,$account);

            $task->t_lesson_info->reset_lesson_list($courseid);
            $task->t_seller_student_new->field_update_list($userid,[
                "global_tq_called_flag" => 2,
                "tq_called_flag"        => 2,
            ]);

            $require_info = $task->t_test_lesson_subject_require->field_get_list($require_id,"test_lesson_subject_id,accept_adminid");
            $task->t_test_lesson_subject->field_update_list($require_info["test_lesson_subject_id"],[
                "history_accept_adminid" => $require_info["accept_adminid"]
            ]);

            if (\App\Helper\Utils::check_env_is_release()){
                $require_adminid = $task->t_test_lesson_subject->get_require_adminid($test_lesson_subject_id);
                $userid          = $task->t_test_lesson_subject->get_userid($test_lesson_subject_id);
                $phone           = $task->t_seller_student_new->get_phone($userid);
                $nick            = $task->t_student_info ->get_nick($userid);
                $teacher_nick    = $task->cache_get_teacher_nick($teacherid);

                $lesson_time_str    = \App\Helper\Utils::fmt_lesson_time($lesson_start,$lesson_end);
                $require_admin_nick = $task->cache_get_account_nick($require_adminid);
                $task->t_manager_info->send_wx_todo_msg(
                    $require_admin_nick,"来自:".$account
                    ,"排课[$phone][$nick] 老师[$teacher_nick] 上课时间[$lesson_time_str]","","");

                $parentid= $task->t_student_info->get_parentid($userid);
                $task->t_parent_info->send_wx_todo_msg($parentid,"课程反馈","您的试听课已预约成功!", "上课时间[$lesson_time_str]","http://wx-parent.leo1v1.com/wx_parent/index", "点击查看详情" );
            }            
            $require_month=["01"=>"18500","02"=>"18500","03"=>"18500","04"=>"18500","05"=>"18500","06"=>"35000","07"=>"18500","08"=>"18500","09"=>"18500","10"=>"18500","11"=>"18500","12"=>"19000"];

            $m = date("m",time());
            $start_time = strtotime(date("Y-m-01",time()));
            $end_time = strtotime(date("Y-m-01",$start_time+40*86400));
            $limit_num=150;
            if($val["limit_require_send_adminid"]==287){
                $limit_num= ceil($require_month[$m]*0.027);
            }elseif($val["limit_require_send_adminid"]==416){
                $limit_num= ceil($require_month[$m]*0.009);
            }elseif($val["limit_require_send_adminid"]==416){
                $limit_num= ceil($require_month[$m]*0.009);
            }else{
                $limit_num= ceil($require_month[$m]*0.026);
            }

            $num = $task->t_test_lesson_subject_require->get_month_limit_require_num($val["limit_require_send_adminid"],$start_time,$end_time);

            $task->t_manager_info->send_wx_todo_msg_by_adminid ($val["limit_require_adminid"],$realname."老师特殊申请排课已经排课成功","成功排课通知","学生:".$val["nick"].",老师:".$realname." ".$lesson_time."的课程已完成排课！","");
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($val["limit_require_send_adminid"],$realname."老师特殊申请排课已经排课成功","成功排课通知","学生:".$val["nick"].",老师:".$realname." ".$lesson_time."的课程已完成排课！本月限课特殊申请".$limit_num."次,目前已申请".$num."次","");
            $task->t_manager_info->send_wx_todo_msg_by_adminid (72,$realname."老师特殊申请排课已经排课成功","成功排课通知","学生:".$val["nick"].",老师:".$realname." ".$lesson_time."的课程已完成排课！","");
            $task->t_manager_info->send_wx_todo_msg_by_adminid (478,$realname."老师特殊申请排课已经排课成功","成功排课通知","学生:".$val["nick"].",老师:".$realname." ".$lesson_time."的课程已完成排课！","");




 
        }

                     
    }
}
