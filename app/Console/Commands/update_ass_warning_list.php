<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class update_ass_warning_list extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_ass_warning_list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '新版预警学员信息更新';

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
        //更新未设置续费状态,但是课时小于1的人的完成状态
        $ret = $task->t_month_ass_warning_student_info->get_end_stu_warning_info(2);
        foreach($ret as $v){
            $task->t_month_ass_warning_student_info->field_update_list($v["id"],[
                "done_flag"   =>2,
                "done_time"   =>time()
            ]);
        }

        //助教确认续费或者待定的,到期组长未确认成功的,有系统判断是否续费,不成功的改成结束状态
        $time = strtotime(date("Y-m-d",time()-86400));
        $list = $task->t_month_ass_warning_student_info->get_no_renw_end_time_list($time);
        foreach($list as $val){
            $flag = $task->t_order_info->get_stu_renw_order($val["userid"],$val["month"]);
            if(empty($flag)){
                $task->t_month_ass_warning_student_info->field_update_list($val["id"],[
                    "done_flag"   =>2,
                    "done_time"   =>time()
                ]);
            }
        }

        //更新信息
        $invalid_time = time()-28*86400;
        $list = $task->t_month_ass_warning_student_info->get_stu_warning_info(2);
        $warning_list = $task->t_student_info->get_warning_stu_list();
        foreach($warning_list as $item){
            $userid= $item["userid"];
            $last_time = $task->t_month_ass_warning_student_info->get_last_time_by_userid($userid);
            if($last_time < $invalid_time){
                $task->t_month_ass_warning_student_info->row_insert([
                    "adminid"        =>$item["uid"],
                    "userid"         =>$userid,
                    "groupid"        =>$item["groupid"],
                    "group_name"     =>$item["group_name"],
                    "warning_type"   =>2,
                    "month"  =>time()
                ]);
                $nick = $task->t_student_info->get_nick($userid);
                $id = $task->t_month_ass_warning_student_info->get_last_insertid();
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($item["uid"],"新增预警学员","新增预警学员","学生:".$nick,"http://admin.yb1v1.com/user_manage_new/ass_warning_stu_info_new?id=".$id);

 
            }
        }


        //周预警信息同步更新
        $month = time()-30*86400;        
        $time = time()+86400;
        $date_week = \App\Helper\Utils::get_week_range($time,1);
        $lstart = $date_week["sdate"];
        $lend = $date_week["edate"];
        $ret_info = $task->t_month_ass_warning_student_info->get_week_warning_info($lstart);
        foreach($ret_info as $v){
            $userid = $v["userid"];
            $info = $task->t_month_ass_warning_student_info->get_warning_info_by_userid($userid,$month);
            if(!empty($info)  && $info["ass_renw_flag"] >0){
                $task->t_month_ass_warning_student_info->field_update_list($v["id"],[
                    "ass_renw_flag"  =>$info["ass_renw_flag"],
                    "no_renw_reason" =>$info["no_renw_reason"],
                    "renw_price"     =>$info["renw_price"],
                    "renw_week"      =>$info["renw_week"],
                    "master_renw_flag" =>$info["master_renw_flag"],
                    "master_no_renw_reason"=>$info["master_no_renw_reason"]
                ]);
            }

        }


       
              
    }
}
