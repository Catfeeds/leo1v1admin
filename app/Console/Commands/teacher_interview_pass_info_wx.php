<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class teacher_interview_pass_info_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:teacher_interview_pass_info_wx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '教师一周面试信息发送微信';

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
        $start_time = time()-7*86400;
        $end_time = time();
        $start = date("Y-m-d H:i:s",$start_time);
        $end = date("Y-m-d H:i:s",$end_time);
        $res = $task->t_teacher_lecture_info->get_lecture_info_by_subject_new($start_time,$end_time);
        $video_succ = $task->t_teacher_lecture_info->get_lecture_info_by_subject_new($start_time,$end_time,1);
        $one = $task->t_teacher_record_list->get_all_interview_count_by_subject($start_time,$end_time,-1);
        $one_succ = $task->t_teacher_record_list->get_all_interview_count_by_subject($start_time,$end_time,1);        
        $all=0;$suc=0;$all_time=0;$video_num=0;       
        foreach($res as $k=>&$v){
            $all +=$v["all_count"];
            $all_time +=$v["time_count"];
            $video_num +=$v["all_count"];
            $v["video_num"] = $v["all_count"];
        }
        foreach($video_succ as $item){
            $suc += $item["all_count"];
        }
        foreach($one as $k=>&$vv){
            $all +=$vv["all_count"];
            if(isset($res[$k])){
                $res[$k]["all_count"] += $vv["all_count"];
            }else{
                $vv["video_num"]=0;
                $vv["time_count"]=0;
                $res[$k] = $vv;
            }
        }
        foreach($one_succ as $k=>&$vvv){
            $suc +=$vvv["all_count"];
            if(isset($video_succ[$k])){
                $video_succ[$k]["all_count"] += $vvv["all_count"];
            }else{
                $video_succ[$k] = $vvv;
            }

        }

        
        $ave_per = $all==0?0:(round($suc/$all,2)*100)."%";
        $ave_time = $all==0?0:round($all_time/$video_num/86400,1);
        //$tea_arr=["349"=>"Jack"];
        $subject_info ="";
        foreach($res as $p){
            $subject = $p["subject"];
            $subject_str = E\Esubject::get_desc($subject);
            $pass_per = (round(@$video_succ[$subject]["all_count"]/$p["all_count"],2)*100)."%";
            $sub_ave_time = !empty($p["video_num"])?round($p["time_count"]/$p["video_num"]/86400,1):0;
            $subject_info .= $subject_str."组面试".$p["all_count"]."位,入职".@$video_succ[$subject]["all_count"]."位,通过率".$pass_per.",平均审核时长".$sub_ave_time."天;";
            $tea_arr =$task->get_admin_group_subject_list($subject);
            //$tea_arr=[];
             $tea_arr[72]="Erick";
            $tea_arr[448]="rolon";
            $tea_arr[349]="Jack";
            foreach($tea_arr as $k=>$ttt){
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($k,"理优面试组","新老师入职情况汇总",$ttt."老师你好,".$subject_str."组面试".$p["all_count"]."位,入职".@$video_succ[$subject]["all_count"]."位,通过率".$pass_per.",平均审核时长".$sub_ave_time."天,所有学科平均面试通过率为".$ave_per.",所有学科平均审核时长".$ave_time."天
 请赶紧对新入职老师组织入职培训和学科培训,期待通过培训打造强大稳定的在线".$subject_str."教学Team","http://admin.yb1v1.com/tongji_ss/teacher_interview_info_tongji?order_by_str=pass_per%20asc&date_type=null&opt_date_type=0&start_time=".$start."&end_time=".$end."&subject=".$subject);
 }
        }

           
        $list = $task->t_teacher_lecture_info->get_lecture_info_by_subject_account($start_time,$end_time);
        $video_succ_list = $task->t_teacher_lecture_info->get_lecture_info_by_subject_account($start_time,$end_time,-1);
        $one_account = $task->t_teacher_record_list->get_all_interview_count_by_subject_account($start_time,$end_time,-1);
        $one_succ_account = $task->t_teacher_record_list->get_all_interview_count_by_subject_account($start_time,$end_time,1);        
        foreach($list as &$i){
            $i["video_num"] = $i["all_count"];
        }
        foreach($one_account as $k=>&$s){
            if(isset($list[$k])){
                $list[$k]["all_count"] +=$s["all_count"];
            }else{
                $s["video_num"]=0;
                $s["time_count"]=0;
                $list[$k]= $s;
            }
        }
        foreach($one_succ_account as $k=>&$t){
            if(isset($video_succ_list[$k])){
               $video_succ_list[$k]["all_count"] +=$s["all_count"]; 
            }else{
                $video_succ_list[$k]=$t;
            }
        }

        foreach($list as $k=>&$val){
            $pass_per_account = (round(@$video_succ_list[$k]["all_count"]/$val["all_count"],2)*100)."%";
            $account_ave_time = !empty($val["video_num"])?round($val["time_count"]/$val["all_count"]/86400,1):0;
            $task->t_manager_info->send_wx_todo_msg_by_adminid ($val["uid"],"理优面试组","新老师入职情况汇总",$val["account"]."老师你好,你上周共面试".$val["all_count"]."位老师,入职".@$video_succ_list[$k]["all_count"]."位,通过率".$pass_per_account.",平均审核时长".$account_ave_time."天,所有学科平均面试通过率为".$ave_per.",所有学科平均审核时长".$ave_time."天
              请赶紧对新入职老师组织入职培训和学科培训,期待通过培训打造强大稳定的在线教学Team","http://admin.yb1v1.com/tongji_ss/teacher_interview_info_tongji?order_by_str=pass_per%20asc&date_type=null&opt_date_type=0&start_time=".$start."&end_time=".$end."&teacher_account=".$val["teacherid"]);
            /*$task->t_manager_info->send_wx_todo_msg_by_adminid (72,"理优监课组","新老师入职情况汇总",$val["account"]."老师你好,你上周共面试".$val["all_count"]."位老师,入职".$val["suc_count"]."位,通过率".$pass_per_account.",平均审核时长".$account_ave_time."天,所有学科平均面试通过率为".$ave_per.",所有学科平均审核时长".$ave_time."天
              请赶紧对新入职老师组织入职培训和学科培训,期待通过培训打造强大稳定的在线教学Team","http://admin.yb1v1.com/tongji_ss/teacher_interview_info_tongji?order_by_str=pass_per%20asc&date_type=null&opt_date_type=0&start_time=".$start."&end_time=".$end."&teacher_account=".$val["teacherid"]);*/
            /* $task->t_manager_info->send_wx_todo_msg_by_adminid (349,"理优面试组","新老师入职情况汇总",$val["account"]."老师你好,你上周共面试".$val["all_count"]."位老师,入职".$val["suc_count"]."位,通过率".$pass_per_account.",平均审核时长".$account_ave_time."天,所有学科平均面试通过率为".$ave_per.",所有学科平均审核时长".$ave_time."天
               请赶紧对新入职老师组织入职培训和学科培训,期待通过培训打造强大稳定的在线教学Team","http://admin.yb1v1.com/tongji_ss/teacher_interview_info_tongji?order_by_str=pass_per%20asc&date_type=null&opt_date_type=0&start_time=".$start."&end_time=".$end."&teacher_account=".$val["teacherid"]);*/



        }
    }
}
