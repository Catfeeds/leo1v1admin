<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class teacher_first_test_lesson_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:teacher_first_test_lesson_wx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '教师第一次试听课发送微信';

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
        //$start_time = strtotime(date("Y-m-d",time()));
        $end_time = time();
        $date_week_pre = \App\Helper\Utils::get_week_range(time()-7*86400,1);
        $start_time = strtotime(date("Y-m-d",$date_week_pre["sdate"] + 2*86400));
        $start=date("Y-m-d H:i:s",$start_time);
        $end = date("Y-m-d H:i:s",$end_time);
        $lessonid_list = $task->t_lesson_info->get_all_first_lesson_teacher($start_time,$end_time);
        //$lessonid_list = $task->t_test_lesson_subject_sub_list->get_test_lessonid_list_by_set_time($start_time,$end_time);
        $teacher_name = [];
        foreach($lessonid_list as &$item){
            $check_have_record = $task->t_teacher_record_list->check_have_record($item["teacherid"],1);
            @$teacher_name[$item["subject"]]["num"]++;
            if($check_have_record !=1 ){
                @$teacher_name[$item["subject"]]["no_record_num"]++;
                @$teacher_name[$item["subject"]]["name"] .= ",".$item["realname"];
            }
        }
        // $tea_arr=["349"=>"Jack"];
        // $tea_arr=["72"=>"Erick","349"=>"Jack","478"=>"CoCo"];
        foreach($teacher_name as $k=>&$item){
            if(isset($item["name"])){
                $teacher_name_list = trim($item["name"],",");
            }else{
                $teacher_name_list="";
            }
            $num = $item["num"];
            if(isset($item["no_record_num"])){
                $un_num = $item["no_record_num"];
            }else{
                $un_num=0;
            }
            $tea_arr =$task->get_admin_group_subject_list($k);
            $subject_str = E\Esubject::get_desc($k);
            //$tea_arr[72]="Erick";
            $tea_arr[448]="rolon";
            // $tea_arr[349]="Jack";
            foreach($tea_arr as $kk=>$vv){
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($kk,"理优监课组","老师第一次试听课监课一周汇总",$vv."老师你好,系统检查到本周未".$subject_str."学科如下 ".$num."位 老师新入职老师,其中".$un_num."位老师还未反馈

具体名单是:".$teacher_name_list."

请赶紧监听该老师课程,期待通过监课和培训打造强大稳定的在线教学Team","http://admin.yb1v1.com/tongji_ss/teacher_first_test_lesson_week?date_type=null&opt_date_type=0&start_time=".$start."&end_time=".$end."&subject=".$k);

            }


                      
        }

        //dd($teacher_name);
    }
}
