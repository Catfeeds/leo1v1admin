<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class fulltime_teacher_kaoqin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fulltime_teacher_kaoqin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '全职/教研老师考勤';

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

        $time = time();
        
        $w = date("w");

        //全职老师提前下班
        if($w !=1 && $w != 2){
            $day_time = strtotime(date("Y-m-d",$time));
            $lesson_end = strtotime(date("Y-m-d",$time)." 19:30:00");
            $lesson_start = $lesson_end+1800;
            $lesson_list = $task->t_lesson_info_b2->get_off_time_lesson_info($lesson_start,$lesson_end);
            foreach($lesson_list as $item){
                $teacher_info = $task->t_manager_info->get_teacher_info_by_adminid($item["uid"]);
                $teacherid = $teacher_info["teacherid"];
                $check_exist = $task->t_fulltime_teacher_attendance_list->check_is_exist($teacherid,$day_time);
                if($check_exist != 1){
                    $start = $task->get_first_lesson_start($teacherid,$item["lesson_start"]);
                    //$lesson_end = $item["lesson_start"]-5400;
                    // $start = $task->t_lesson_info_b2->check_off_time_lesson_start($teacherid,$lesson_end,$item["lesson_start"]);
                    $off_time = $start-5400;
                    $task->t_fulltime_teacher_attendance_list->row_insert([
                        "teacherid"  =>$teacherid,
                        "add_time"   =>$time,
                        "attendance_type" =>2,
                        "attendance_time"  =>$day_time,
                        "off_time"         =>$off_time,
                        "adminid"          =>$item["uid"]
                    ]);
                }
            }
        }

        //第二天满8课时,在家办公(教研/全职老师)
        // if($w >=2){
        $start_time = strtotime(date("Y-m-d",$time))+86400;
        $end_time = $start_time + 86400;
        $lesson_info = $task->t_lesson_info_b2->get_qz_tea_lesson_info($start_time,$end_time);
        $list=[];
        foreach($lesson_info as $val){
            if($val["lesson_type"]==1100 && $val["train_type"]==5){
                @$list[$val["uid"]] += 0.8;
            }elseif($val["lesson_type"]==2){
                @$list[$val["uid"]] += 1.5;
            }else{
                @$list[$val["uid"]] += $val["lesson_count"]/100;
            }
        }
        $name_list ="";
        $num=0;
        $name_list_research="";
        $num_research=0;
        foreach($list as $k=>$item){
            if($item>=8){
                $account_role = $task->t_manager_info->get_account_role($k);
                if($account_role==5 && $w >=2){
                    $task->t_manager_info->send_wx_todo_msg_by_adminid ($k,"在家办公通知","明天课时满8课时可在家办公","老师您好,您明天的课时满8小时,可以在家办公","");
              
                    $teacher_info = $task->t_manager_info->get_teacher_info_by_adminid($k);                   
                    $teacherid = $teacher_info["teacherid"];
                    $realname = $task->t_teacher_info->get_realname($teacherid);
                    $task->t_fulltime_teacher_attendance_list->row_insert([
                        "teacherid"  =>$teacherid,
                        "add_time"   =>$time,
                        "attendance_type" =>1,
                        "attendance_time"  =>strtotime(date("Y-m-d",$time+86400)),
                        "day_num"           =>1,
                        "adminid"           =>$k,
                        "lesson_count"      =>$item*100
                    ]);
 
                    $name_list .= $realname.",";
                    $num++;
                }elseif($account_role==4){
                    $task->t_manager_info->send_wx_todo_msg_by_adminid ($k,"在家办公通知","明天课时满8课时可在家办公","老师您好,您明天的课时满8小时,可以在家办公","");
              
                    $teacher_info = $task->t_manager_info->get_teacher_info_by_adminid($k);                   
                    $teacherid = $teacher_info["teacherid"];
                    $realname = $task->t_teacher_info->get_realname($teacherid);
                    $task->t_fulltime_teacher_attendance_list->row_insert([
                        "teacherid"  =>$teacherid,
                        "add_time"   =>$time,
                        "attendance_type" =>1,
                        "attendance_time"  =>strtotime(date("Y-m-d",$time+86400)),
                        "day_num"           =>1,
                        "adminid"           =>$k,
                        "lesson_count"      =>$item*100
                    ]);

                    $name_list_research .= $realname.",";
                    $num_research++;
                }
 
            }
        }
        $name_list = trim($name_list,",");
        $name_list_research = trim($name_list_research,",");
        if($num>0){
            $task->t_manager_info->send_wx_todo_msg_by_adminid (349,"在家办公通知","明天在家办公老师名单","明天有如下".$num."位老师满8课时,可在家办公,具体名单如下:".$name_list,"");
            $task->t_manager_info->send_wx_todo_msg_by_adminid (480,"在家办公通知","明天在家办公老师名单","明天有如下".$num."位老师满8课时,可在家办公,具体名单如下:".$name_list,"");                               
            $task->t_manager_info->send_wx_todo_msg_by_adminid (986,"在家办公通知","明天在家办公老师名单","明天有如下".$num."位老师满8课时,可在家办公,具体名单如下:".$name_list,"");

        }
        if($num_research>0){
            $task->t_manager_info->send_wx_todo_msg_by_adminid (349,"在家办公通知","明天在家办公教研老师名单","明天有如下".$num_research."位老师满8课时,可在家办公,具体名单如下:".$name_list_research,"");
            $task->t_manager_info->send_wx_todo_msg_by_adminid (72,"在家办公通知","明天在家办公教研老师名单","明天有如下".$num_research."位老师满8课时,可在家办公,具体名单如下:".$name_list_research,"");                               

        }



        
        $list_all = $task->t_order_info->get_no_pay_order_list();
        foreach($list_all as $item11){
            $orderid = $item11["orderid"];
            $data = $task->t_child_order_info->get_all_child_order_info($orderid);
            if(empty($data)){
                
                if($item11["from_orderno"]){
                    $task->t_child_order_info->row_insert([
                        "child_order_type" =>0,
                        "pay_status"       =>1,
                        "add_time"         =>time(),
                        "parent_orderid"   =>$orderid,
                        "price"            => $item11["price"],
                        "channel"          => $item11["channel"],
                        "from_orderno"     => $item11["from_orderno"],
                        "pay_time"         => $item11["pay_time"]
                    ]);
                }else{
                    $task->t_child_order_info->row_insert([
                        "child_order_type" =>0,
                        "pay_status"       =>1,
                        "add_time"         =>time(),
                        "parent_orderid"   =>$orderid,
                        "price"            => $item11["price"],
                        "channel"          => $item11["channel"],
                        "pay_time"         => $item11["pay_time"]
                    ]);

                }

            }

        }


                     

            
            //}
       
              
    }
}
