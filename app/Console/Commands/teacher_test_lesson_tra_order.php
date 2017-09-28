<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class teacher_test_lesson_tra_order extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:teacher_test_lesson_tra_order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '教师试听转化率过低发送微信';

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
        //
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $start_time_ave = time()-30*86400;
        $res = $task->t_lesson_info->get_all_test_order_info_by_time($start_time_ave);
        $num =0;$arr=0;
        foreach($res as $item){
            if($item["orderid"]>0 && $item["order_time"]>0 && $item["lesson_start"]>0){
                $num++;
                $arr += ($item["order_time"]-$item["lesson_start"]);
            }
        }
        $day_num = ceil($arr/$num/86400);
        $start_time=time()-60*86400;
        $end_time = time()-$day_num*86400;

        $teacherid_list = $task->t_lesson_info->get_have_ten_test_lesson_teacher_list($start_time,-1,"",$end_time,0);
        $teacher_name=[];
        foreach($teacherid_list as $item){
            $teacherid = $item["teacherid"];
            $limit_plan_lesson_type  =$item["limit_plan_lesson_type"];     
            $test_lesson_num = $item["suc_count"]>=20?20: $item["suc_count"];
            $subject = $item["subject"];
            $subject_str = E\Esubject::get_desc($subject);
            $identity = E\Eidentity::get_desc($item["identity"]);
            $realname = $item["realname"];
            $day = ceil((time()-$item["create_time"])/86400);
            $ss=0;
            $ret = $task->t_lesson_info->get_test_lesson_order_info_by_teacherid($teacherid,$start_time,$end_time,$test_lesson_num);
            $start = date("Y-m-d H:i:s",$ret[9]["lesson_start"]-20);
            $end = date("Y-m-d",time());
            $tt =0;
            foreach($ret as $v){
                /* $userid = $v["userid"];
                $subject = $v['subject'];
                $uu = $task->t_course_order->get_have_order_info($teacherid,$userid,$subject);
                if($uu){
                    $tt ++;
                    }*/
                if($v["course_teacherid"] >0){
                     $tt ++;
                }
                if($v["orderid"]>0){
                    $ss++;
                }
            }
            /* if($ss <$tt){
                $ss = $tt;
                }*/

            if(($tt/$test_lesson_num) < 0.05){
                if($limit_plan_lesson_type > 0){             
                    $date_week = \App\Helper\Utils::get_week_range(time(),1);
                    $lstart = $date_week["sdate"];
                    $lend = $date_week["edate"];           
                    $test_lesson_num_week_end = $task->t_lesson_info->get_limit_type_teacher_end_lesson_num($teacherid,$lstart,$lend);            
                    if($limit_plan_lesson_type <= $test_lesson_num_week_end){
                        @$teacher_name[$subject]["name"] .= ",".$realname;
                        @$teacher_name[$subject]["num"]++;    
                    }
                }else{
                    @$teacher_name[$subject]["name"] .= ",".$realname;
                    @$teacher_name[$subject]["num"]++;
                }

            }
            
        }
        // $tea_arr=["349"=>"Jack"];
        foreach($teacher_name as $k=>&$item){
            $teacher_name_list = trim($item["name"],",");
            $num = $item["num"];
            $tea_arr =$task->get_admin_group_subject_list($k);
            $subject_str = E\Esubject::get_desc($k);
            // $tea_arr[72]="Erick";
            $tea_arr[448]="rolon";
            // $tea_arr[349]="Jack";
            foreach($tea_arr as $kk=>$vv){
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($kk,"理优监课组","老师试听课转化率过低报警通知",$vv."老师你好,系统检查到".$subject_str."学科如下 ".$num."位 老师20次试听课转化率低于5%

具体名单是:".$teacher_name_list."

请赶紧监听该老师课程并进行冻结或者课程反馈处理,期待通过监课和培训打造强大稳定的在线".$subject_str."教学Team","http://admin.yb1v1.com/tongji_ss/get_test_lesson_low_tra_teacher?subject=".$k);


            }
        }

    }
}
