<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class cr_info_funnel_month extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cr_info_funnel_month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '助教助长月报漏斗信息';

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
        //every week
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $timestamp = time(); 
        $now_time   = strtotime(date('Y-m', $timestamp));  
        
        //月报刷新
        $first_time = strtotime("-6 month", $now_time);
        for($i = $first_time; $i < $now_time;){
            $end_time = $i;
            $start_time = strtotime(date('Y-m',$end_time-86400));
            $start_month = date("Y-m",$start_time);
            $end_month   = date("Y-m",$end_time);
            $cur_start   = strtotime(date('Y-m-01',$start_time));
            $last_month  = strtotime(date('Y-m-01',$cur_start-100));
            if($task->t_cr_week_month_info->get_data_by_type($end_time,1)){
                if(date('d',$start_time) == '1' && date('d',$end_time) == '1'){//月报
                    $type = 1;
                    $create_time = $end_time;
                    $create_time_range = date('Y-m-d H:i:s',$start_time).'~'.date('Y-m-d H:i:s',$end_time);
                }
                $arr = '';
                //C2-计划内续费学生数量 //C4-实际续费学生数量 ////C7-月续费率//C8-月预警续费率
                $warning_list       = $task->t_cr_week_month_info->get_student_list_new(1,$start_time);//进入续费预警的学员
                $renew_student_list = $task->t_order_info->get_renew_student_list($start_time,$now_time);//往后6个月的合同学生数量
                $month_renew_student_list = $task->t_order_info->get_renew_student_list($start_time,$end_time);//往后1个月的合同学生数量
                $warning_num = 0;
                if($warning_list != 0){
                    $warning_list = explode(",",$warning_list);
                    $warning_num = empty($warning_list) ? 0 : count($warning_list);
                }
                $arr['real_renew_num'] = empty($renew_student_list)?0: count($renew_student_list); //   实际续费学生数量
                if($arr['real_renew_num'] == 0){
                    $arr['plan_renew_num'] = 0; //计划内续费学生数量
                    $month_plan_renew_num = 0; //计划内续费学生数量
                }else{
                    $month_plan_renew_num = 0;
                    $arr['plan_renew_num'] = 0;
                    if(!empty($waring_list)){
                        foreach($waring_list as $key => $value){
                            if(in_array($value,$renew_student_list)){
                                ++$arr['plan_renew_num'];
                            }
                        }
                        $month_plan_renew_num = $arr['plan_renew_num'];
                        foreach ($month_renew_student_list as $key => $value) {
                            if(!in_array($value, $warning_list)){
                                ++$month_plan_renew_num;
                            }
                        }
                    }
                }
                $arr['renew_per']        = $warning_num == 0 ? 0:round(100*$month_plan_renew_num/$warning_num,2);//  月续费率
                $arr['finish_renew_per'] = $warning_num == 0 ? 0:round(100*$arr['plan_renew_num']/$warning_num,2);//  月预警续费率
                ////D4-月转介绍至CC签单率
                $tranfer            = $task->t_seller_student_new->get_tranfer_phone_num($start_time,$end_time);
                $month_tranfer_data = $task->t_order_info->get_cr_to_cc_order_num($start_time,$now_time); //签单数量(分配例子当月1号到6个月)
                $arr['month_tranfer_total_num']   = $month_tranfer_data['total_num'];
                if($arr['month_tranfer_total_num']){
                  $arr['tranfer_success_per'] = round($arr['month_tranfer_total_num']/$tranfer,2); //D4-月转介绍至CC签单率
                }else{
                  $arr['tranfer_success_per'] = 0;
                }
                //E5-月扩课成功率
                $month_kk          = $task->t_test_lesson_subject_sub_list->tongji_kk_data($start_time,$end_time) ;
                $month_success_num = $task->t_test_lesson_subject_sub_list->tongji_success_order($start_time,$end_time);
                $arr['month_total_test_lesson_num'] = $month_kk['total_test_lesson_num'];                 //E1-扩课试听数量
                $arr['month_success_num'] = $month_success_num;                                           //E2-扩课成单数量
                if($arr['month_total_test_lesson_num']){
                  $arr['kk_success_per'] = round(100*$arr['month_success_num']/$arr['month_total_test_lesson_num'],2);//E5-月扩课成功率
                }else{
                  $arr['kk_success_per'] = 0;
                }
                $insert_data = [
                    "create_time"             => $create_time,            //存档时间
                    "create_time_range"       => $create_time_range,      //存档时间范围
                    "type"                    => 4,                   //存档类型
                    "plan_renew_num"          => $arr['plan_renew_num'],   //C2-计划内续费学生数量
                    "real_renew_num"          => $arr['real_renew_num'],   //C4-实际续费学生数量
                    "renew_per"               => intval($arr['renew_per']*100),    //C7-月续费率
                    "finish_renew_per"        => intval($arr['finish_renew_per']*100),//C8-月预警续费率
                    "tranfer_success_per"     => intval($arr['tranfer_success_per']*100),//D4-月转介绍至CC签单率
                    "kk_success_per"          => intval($arr['kk_success_per']*100),    //E5-月扩课成功率
                ];
                $ret_id = $task->t_cr_week_month_info->get_info_by_type_and_time(4,$create_time);
                if($ret_id>0){
                    $task->t_cr_week_month_info->field_update_list($ret_id,$insert_data);
                }else{
                    $task->t_cr_week_month_info->row_insert($insert_data);
                }
            }
            $i = strtotime("+1 month",$i);
        }
    }
}
