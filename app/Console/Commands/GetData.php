<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;
class GetData extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GetData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '拉数据';

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
        //学员的教材版本情况
        $ret_list = $this->task->t_student_info->get_stu_textbook_list();
        echo "userid|学生姓名|助教姓名|教材版本|年级|助教组别|试听申请教材版本";
        echo PHP_EOL;
        foreach($ret_list as $ret_val){
            $edition_str = E\Eregion_version::get_desc($ret_val['editionid']);
            $grade_str   = E\Egrade::get_desc($ret_val['grade']);

            echo $ret_val['userid']."|".$ret_val['stu_nick']."|".$ret_val['ass_nick']."|".$edition_str."|".$grade_str
                                   ."|".$ret_val['group_name']."|".$ret_val['stu_textbook'];
            echo PHP_EOL;
        }







        //停课学员数据，将合同过期的数据标红
        // $ret_list = $this->task->t_student_info->get_student_for_stop_study();
        // foreach($ret_list as &$val){
        //     $order_start = \App\Helper\Utils::unixtime2date($val['contract_starttime']);
        //     $order_end   = \App\Helper\Utils::unixtime2date($val['contract_endtime']);
        //     if($val['contract_endtime']<time()){
        //         $val['useful_flag'] = "过期";
        //     }else{
        //         $val['useful_flag'] = "未过期";
        //     }
        //     echo $val['userid']."|".$val['nick']."|".$val['ass_nick']."|".$val['group_name']."|".$val['useful_flag']
        //                        ."|".$order_start."|".$order_end;
        //     echo PHP_EOL;
        // }
        //

        //拉取1月和2月，23点半下课的学生量
        // $start_time = strtotime("2018-1-1");
        // $end_time   = strtotime("2018-2-6");
        // $lesson_list = $this->task->t_lesson_info_b3->get_lesson_list_by_time($start_time,$end_time);
        // $count = 0;
        // $stu_list = [];
        // foreach($lesson_list as $lesson_val){
        //     $lesson_hour = date("H",$lesson_val['lesson_end']);
        //     $lesson_minute = date("i",$lesson_val['lesson_end']);
        //     if($lesson_hour>=23 && $lesson_minute>=30){
        //         if(!isset($stu_list[$lesson_val['userid']])){
        //             $count++;
        //             echo $lesson_val['lessonid'];
        //             echo PHP_EOL;
        //             $stu_list[$lesson_val['userid']]=1;
        //         }
        //     }
        // }
        // echo $count;
        // echo PHP_EOL;
    }



}
