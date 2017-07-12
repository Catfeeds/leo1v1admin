<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class regular_course_un_plan_send_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:regular_course_un_plan_send_wx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '未来三周常规课未排课信息发送助教';

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

        $ret = $task->t_week_regular_course->get_user_tea_num();
        $user_list=[];
        foreach($ret as $val){
            $user_list[]= $val["userid"]; 
        }
        $date_week = \App\Helper\Utils::get_week_range(time(),1);
        $lstart    = $date_week["sdate"];
        $lend      = $date_week["edate"];
        $lesson_num = $task->t_lesson_info->get_stu_normal_lesson_num($lstart,$lend,$user_list);
        $lesson_num2 = $task->t_lesson_info->get_stu_normal_lesson_num($lstart+7*86400,$lend+7*86400,$user_list);
        $lesson_num3 = $task->t_lesson_info->get_stu_normal_lesson_num($lstart+7*86400,$lend+7*86400,$user_list);
        $list=[];
        foreach($ret as $item){
            $num = $item["num"];
            $num1 = isset($lesson_num[$item["userid"]])?$lesson_num[$item["userid"]]["num"]:0; 
            $num2 = isset($lesson_num2[$item["userid"]])?$lesson_num2[$item["userid"]]["num"]:0; 
            $num3 = isset($lesson_num3[$item["userid"]])?$lesson_num3[$item["userid"]]["num"]:0;
            if($num > $num1 || $num > $num2 || $num > $num3){
                @$list[$item["uid"]]["name"] .= $item["nick"].","; 
                @$list[$item["uid"]]["userid"][$item["userid"]]=$item["userid"];
            }
        }
        foreach($list as $gg=>$tt){
            $ss = trim($tt["name"],",");
            $num = count($tt["userid"]);
            $account = $task->t_manager_info->get_account($gg);
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($gg,"三周常规课未排学生信息","三周常规课未排学生信息通知",$account."老师你好,系统检查到如下 ".$num."位 学生未来三周有常规课未排

具体名单是:".$ss,"http://admin.yb1v1.com/user_manage_new/user_regular_course_check_info?ass_adminid=".$gg);
 
        }

       
      

       
              
    }
}
