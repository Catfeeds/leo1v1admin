<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class teacher_limit_plan_lesson_info_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:teacher_limit_plan_lesson_info_wx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '教师限制排课或解除限制排课微信通知教务';

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
        $start_time = time()-86400;
        // $tea_arr=["343"=>"nina","418"=>"June","349"=>"Jack","434"=>"lee","436"=>"alina","454"=>"maze","492"=>"zoe","74"=>"王寅","72"=>"Erick"];
        $jw_teacher_list = $task->t_manager_info->get_jw_teacher_list_new();
        $tea_arr=[];
        foreach($jw_teacher_list as $lll){
            $tea_arr[$lll["uid"]]=$lll["account"];
        }
        $tea_arr[349]="jack";
        $tea_arr[72] ="Erick";
        $tea_arr[74] ="王寅";
        $tea_arr[448] ="rolon";

        //$tea_arr=["349"=>"Jack"];
        $ret_info = $task->t_teacher_info->get_limit_plan_lesson_info($start_time);
        if(!empty($ret_info)){
            foreach($ret_info as $item){
                $subject_str = E\Esubject::get_desc($item["subject"]);
                @$name .= ",".$item["nick"]."(".$subject_str.")";
                @$num++;
            }
            $teacher_name_list = trim($name,",");
            foreach($tea_arr as $k=>$v){
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($k,"理优监课组","各学科老师转化率低被限制排课的通知",$v."老师你好,系统检查到如下 ".$num."位 老师10次以上试听课未转化,教研老师进行了限制排课操作,并已反馈老师

具体名单是:".$teacher_name_list,"http://admin.yb1v1.com/tongji_ss/get_test_lesson_low_tra_teacher?subject=-1&is_record_flag=-1&is_do_sth=-1&limit_plan_lesson_type=-2&wx_type=1&start_time_ex=".$start_time."&end_time_ex=".time());

            }
        }
        $ret_info2 = $task->t_teacher_info->get_un_limit_plan_lesson_info($start_time);
        if(!empty($ret_info2)){
            foreach($ret_info2 as $item){
                $subject_str = E\Esubject::get_desc($item["subject"]);
                @$name2 .= ",".$item["nick"]."(".$subject_str.")";
                @$number++;
            }
            $teacher_name_list2 = trim($name2,",");
            
            foreach($tea_arr as $k=>$v){
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($k,"理优监课组","各学科老师解除限制排课的通知",$v."老师你好,系统检查到如下 ".$number."位 老师由教研老师进行了解除限制排课的操作,并已反馈老师

具体名单是:".$teacher_name_list2,"");

            }
        }
       
        

    }
}
