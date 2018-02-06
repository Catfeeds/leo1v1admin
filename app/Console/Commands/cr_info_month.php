<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class cr_info_month extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cr_info_month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '助教助长月报信息';

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


        $end_time   = strtotime(date('Y-m', $timestamp));
        //        $end_time = strtotime(date('Y-m-d'),time()); //
        $start_time = strtotime(date('Y-m',$end_time-86400));

        $start_month = date("Y-m",$start_time);
        $end_month   = date("Y-m",$end_time);

        $cur_start   = strtotime(date('Y-m-01',$start_time));
        $last_month  = strtotime(date('Y-m-01',$cur_start-100));
        if(date('d',$start_time) == '1' && date('d',$end_time) == '1'){//月报
            $type = 1;
            $create_time = $end_time;
            $create_time_range = date('Y-m-d H:i:s',$start_time).'~'.date('Y-m-d H:i:s',$end_time);
        }else if($start_month == $end_month){ //周报
            $type = 2;
            $create_time = $end_time;//
            $create_time_range = date('Y-m-d H:i:s',$start_time).'~'.date('Y-m-d H:i:s',$end_time);
        }else{//跨月报
            $type = 3;
            $create_time = $end_time;
            $create_time_range = date('Y-m-d H:i:s',$start_time).'~'.date('Y-m-d H:i:s',$end_time);
        }
        //节点
        //概况
        $ret_total         = $task->t_order_info->get_total_price($start_time,$end_time);
        if($type == 3){
            $month_ret_total   = $task->t_order_info->get_total_price(strtotime($end_month),$end_time);
        }elseif($type == 1 || $type == 2){
            $month_ret_total   = $task->t_order_info->get_total_price(strtotime($start_month),$end_time);
        }//月初至今
        $ret_total_thirty  = $task->t_order_info->get_total_price_thirty($start_time,$end_time);
        $ret_cr = $task->t_manager_info->get_cr_num($start_time,$end_time);
        $ret_refund = $task->t_order_refund->get_assistant_num($start_time,$end_time);  //退费总人数
        $target = $task->t_manager_info->get_cr_target($last_month);//月度目标
        $arr['target']             = $target * 100;                          //1-续费目标
        $arr['total_price']        = $ret_total['total_price'] ;             //2-现金总收入
        $arr['total_income']       = $ret_total['total_price'] ;             //A1-现金总收入
        $arr['person_num']         = $ret_total['person_num'];               //A2-下单总人数
        $arr['contract_num']       = $ret_total['order_num']; //合同数
        $arr['total_price_thirty'] = round($ret_total_thirty['total_price'] / 100,2); //A3-入职完整月人员签单额
        $arr['person_num_thirty']  = $ret_total_thirty['person_num'];        //A4-入职完整月人员人数
        $arr['cr_num']             = $ret_cr;                                //A8-CR总人数
        $arr['refund_num']         = $ret_refund;                            //A10-退费总人数
        if(($arr['target']-$arr['total_price']) > 0){
            $arr['gap_money'] = $arr['target'] - $arr['total_price'];        //4-缺口金额
        }else{
            $arr['gap_money'] = 0;
        }
        if($arr['target']){
            $arr['kpi_per'] = round(100*$arr['total_price']/$arr['target'],2);//3-完成率
            $arr['month_kpi_per'] = round($month_ret_total['total_price']/$arr['target']*100,2);
        }else{
            $arr['kpi_per'] = 0;
            $arr['month_kpi_per'] = 0;
        }
        if($arr['total_price']){
            $arr['contract_per']   = round($arr['total_price']/$arr['contract_num']);//A6-平均单笔
        }else{
            $arr['contract_per']   = 0;
        }
        if($arr['person_num_thirty'] > 0){
            $arr['person_num_thirty_per'] = round($arr['total_price_thirty'] / $arr['person_num_thirty'],2);//A5-平均人效
        }else{
            $arr['person_num_thirty_per'] = 0;
        }
        //课时消耗
        $lesson_consume    = $task->t_lesson_info->get_total_consume($start_time,$end_time); //课时消耗实际数量,上课学生数
        $leave_num         = $task->t_lesson_info->get_leave_num($start_time,$end_time); //老师,学生请假课时
        $arr['lesson_consume'] = round($lesson_consume['total_consume']/100,2);          //B5-课时消耗实际数量
        $arr['teacher_leave'] = 0;                                                       //B6-老师请假课时
        $arr['student_leave'] = 0;                                                       //B7-学生请假课时
        $arr['other_leave'] = 0;                                                         //B8-其他原因未上课时
        foreach($leave_num as $key => $value){
            if($value['lesson_cancel_reason_type'] == 11){ //学生请假11
                $arr['student_leave'] = round($value['num']/100,2);
            }
            if($value['lesson_cancel_reason_type'] == 12){ //老师请假
                $arr['teacher_leave'] = round($value['num']/100,2);
            }
            if($value['lesson_cancel_reason_type'] == 3 || $value['lesson_cancel_reason_type'] == 4){ //网络设备
                $arr['other_leave'] += round($value['num']/100,2);
            }
        }
        //续费
        $arr['total_renew'] = round($ret_total['total_renew']/100,2); //续费金额
        $arr['renew_num']   = $ret_total['renew_num'];       //总笔数
        if($arr['renew_num']>0){
            $arr['renew_num_per'] = round($arr['total_renew']/$arr['renew_num'],2); //平均单笔
        }else{
            $arr['renew_num_per'] = 0;
        }

        //转介绍
        $tranfer = $task->t_seller_student_new->get_tranfer_phone_num_new($start_time,$end_time);
        $tranfer_data = $task->t_order_info->get_cr_to_cc_order_num($start_time,$end_time);
        $arr['tranfer_phone_num'] = $tranfer;                                         //D1-转介绍至CC例子量
        $arr['tranfer_total_price'] = round($tranfer_data['total_price'] /100,2);     //D2-转介绍至CC例子签单金额
        $arr['tranfer_total_num']   = $tranfer_data['total_num'];                     //D3-转介绍至CC例子签单量

        $arr['tranfer_num']   = $ret_total['tranfer_num']/1;                          //D5-转介绍成单数量
        $arr['total_tranfer'] = $ret_total['total_tranfer']/100;                      //D6-转介绍总金额

        if($arr['tranfer_num'] > 0){
            $arr['tranfer_num_per'] = round($arr['total_tranfer']/$arr['tranfer_num'],2);//D7- 转介绍平均单笔
        }else{
            $arr['tranfer_num_per'] = 0;
        }

        //扩科
        $kk          = $task->t_test_lesson_subject_sub_list->tongji_kk_data($start_time,$end_time) ;
        $success_num = $task->t_test_lesson_subject_sub_list->tongji_success_order($start_time,$end_time);
        $arr['total_test_lesson_num'] = $kk['total_test_lesson_num'];                 //E1-扩课试听数量
        $arr['success_num'] = $success_num;                                           //E2-扩课成单数量
        $arr['fail_num'] = $kk['fail_num'];                                           //E3-扩科待跟进数量
        $arr['wait_num'] = $kk['wait_num'];                                           //E4-扩科未成单数量
        //存档------------------------------------------------
        //概况
        //$finish_num = $task->t_student_info->get_finish_num($start_time,$end_time);//A9-结课学员数
        $finish_num = $task->t_student_info->get_finish_num_new_list($start_time,$end_time);//A9-结课学员数
        $arr['finish_num'] = $finish_num;
        //课时消耗
        $read_num       = $task->t_student_info->get_read_num($start_time,$end_time);//在读学员数量
        $lesson_plan    = $task->t_lesson_info->get_total_lesson($start_time,$end_time); //实际有效课时/排课量
        $lesson_income  = $task->t_lesson_info->get_total_income($start_time,$end_time);//课时有效收入
        $arr['read_num']       = $read_num;                                                       //B2-在读学生数量
        $arr['total_student']  = $lesson_consume['total_student'];                                //B3-上课学生数量
        $arr['lesson_plan']    = $lesson_plan['total_plan']; //计划排课数量
        $arr['student_arrive'] = $lesson_plan['student_arrive']; //学生有效课程数量
        $arr['lesson_income'] = round($lesson_income/100,2);                                      //B11-课时收入
        if($arr['lesson_plan']>0){
            $arr['student_arrive_per'] = round(100*$arr['student_arrive']/$arr['lesson_plan'],2); //B10-学生到课率
        }else{
            $arr['student_arrive_per'] = 0;
        }
        //续费 6
        $warning_list_new = $task->t_student_info->get_warning_stu_list_new();
        $userlist = '';
        foreach ($warning_list_new as $key => $value) {
          $userlist .= ','.$value['userid'];
        }
        $userlist = trim($userlist,',');


        //转介绍
        /*
        $tranfer_cr = $task->t_seller_student_new->get_tranfer_phone_num($start_time,$end_time);
        $month_tranfer_data = $task->t_order_info->get_cr_to_cc_order_num($start_time,$end_time); //月初至今
        $arr['month_tranfer_total_num']   = $month_tranfer_data['total_num'];
        if($arr['month_tranfer_total_num']){
          $arr['tranfer_success_per'] = round(100*$arr['month_tranfer_total_num']/$tranfer_cr,2); //D4-月转介绍至CC签单率
        }else{
          $arr['tranfer_success_per'] = 0;
        }*/
        $month_tranfer_data = $task->t_order_info->get_cr_to_cc_order_num($start_time,$end_time); //月初至今
        $month_tranfer = $task->t_seller_student_new->get_tranfer_phone_num_new($start_time,$end_time);
        //$tranfer_total_month = $task->t_seller_student_new->get_tranfer_phone_num_month(strtotime($end_month),$end_time);
        $tranfer_total_month['total_orderid'] = $month_tranfer_data['total_num'];
        $tranfer_total_month['total_num']     = $month_tranfer;

        //$tranfer_total_month = $task->t_seller_student_new->get_tranfer_phone_num_month($start_time,$end_time);
        if($tranfer_total_month['total_orderid']){
          $arr['tranfer_success_per'] = $tranfer_total_month['total_num']==0 ? 0:round(100*$tranfer_total_month['total_orderid']/$tranfer_total_month['total_num'],2); //D4-月转介绍至CC签单率
        }else{
          $arr['tranfer_success_per'] = 0;
        }
        //扩科
        $month_kk          = $task->t_test_lesson_subject_sub_list->tongji_kk_data($start_time,$end_time) ;
        $month_success_num = $task->t_test_lesson_subject_sub_list->tongji_success_order($start_time,$end_time);
        $arr['month_total_test_lesson_num'] = $month_kk['total_test_lesson_num'];                 //E1-扩课试听数量
        $arr['month_success_num'] = $month_success_num;                                           //E2-扩课成单数量
        if($arr['month_total_test_lesson_num']){
          $arr['kk_success_per'] = round(100*$arr['month_success_num']/$arr['month_total_test_lesson_num'],2);//E5-月扩课成功率
        }else{
          $arr['kk_success_per'] = 0;
        }

        if($type==2 ||$type ==3){//周报,跨月周报
            $warning_list_new = $task->t_student_info->get_warning_stu_list_new();
        }else if($type == 1){//月报
            $warning_list_new = $task->t_student_info->get_warning_stu_list_month_new();
        }
        $student_list = '';
        foreach($warning_list_new as $key => $value){
            $student_list .= ','.$value['userid'];
        }
        $student_list = trim($student_list,',');
        $arr['student_list'] = $student_list;
        //续费
        $warning_list = $task->t_cr_week_month_info->get_student_list_new(1,$start_time);
        $renew_student_list = $task->t_order_info->get_renew_student_list_new($start_time,$end_time);

        $warning_num = 0;
        if($warning_list != 0){
            $warning_list = explode(",",$warning_list);
            $warning_num = empty($warning_list) ? 0 : count($warning_list);
        }
        $arr['real_renew_num'] = empty($renew_student_list)?0: count($renew_student_list); //   实际续费学生数量
        if($arr['real_renew_num'] == 0){
            $arr['plan_renew_num'] = 0; //计划内续费学生数量
            $arr['other_renew_num'] = 0;//计划外续费学生数量
        }else{
            $arr['plan_renew_num'] = 0;
            if(!empty($warning_list)){
                foreach($warning_list as $key => $value){
                    $userid = $value;
                    if(!empty($renew_student_list[$userid])){
                        ++$arr['plan_renew_num'];
                    }
                }
            }
            $arr['other_renew_num'] = $arr['real_renew_num'] - $arr['plan_renew_num'];
        }
        $arr['expect_finish_num'] = $warning_num; //预计结课学生数量


        //月初至今
        $month_warning_list = $task->t_cr_week_month_info->get_student_list_new(1,$start_time); //月初拉上个月数据
        $month_renew_student_list = $task->t_order_info->get_renew_student_list(strtotime($end_month),$end_time);

        $month_warning_num = 0;
        if($month_warning_list != 0){
            $month_warning_list = explode(",",$month_warning_list);
            $month_warning_num = empty($month_warning_list) ? 0 : count($month_warning_list);
        }
        $month_real_renew_num = empty($month_renew_student_list)?0: count($month_renew_student_list); //   实际续费学生数量
        if($month_real_renew_num == 0){
            $month_plan_renew_num = 0; //计划内续费学生数量
        }else{
           $month_plan_renew_num = 0;
            if(!empty($month_warning_list)){
                foreach($month_warning_list as $key => $value){
                    if(!empty($month_renew_student_list[$value])){
                         ++$month_plan_renew_num;
                    }
                }
            }
            $arr['other_renew_num'] = $arr['real_renew_num'] - $arr['plan_renew_num'];
        }

        $month_real_renew_num = empty($month_renew_student_list)?0: count($month_renew_student_list); //  实际续费学生数量


        $arr['renew_per'] = $month_warning_num == 0 ? 0:round(100*$arr['real_renew_num']/$month_warning_num,2);//  月续费率
        $arr['finish_renew_per'] = $month_warning_num == 0 ? 0:round(100*$arr['plan_renew_num']/$month_warning_num,2);//  月续费率

        /*新增数据*/
        $cr_order_info = $task->t_order_info->get_all_cr_order_info($start_time,$end_time);
        $arr["average_person_effect"] = !empty(@$cr_order_info["ass_num"])?round($cr_order_info["all_money"]/$cr_order_info["ass_num"]):0; //平均人效(非入职完整月)

        $all_pay = $task->t_student_info->get_student_list_for_finance_count();//所有有效合同数
        $refund_info = $task->t_order_refund->get_refund_userid_by_month(-1,$end_time);//所有退费信息
        $arr["cumulative_refund_rate"] = $all_pay["orderid_count"] == 0 ? 0:round($refund_info["orderid_count"]/$all_pay["orderid_count"]*100,2)*100;//合同累计退费率

        // 获取停课,休学,假期数
        $ret_info_stu = $task->t_student_info->get_student_count_archive();

        foreach($ret_info_stu as $item) {
            if ($item['type'] == 2) {
                @$arr['stop_student']++;
            } else if ($item['type'] == 3) {
                @$arr['drop_student']++;
            } else if ($item['type'] == 4) {
                @$arr['summer_winter_stop_student']++;
            }
        }

        //新签合同未排量(已分配/未分配)/新签学生数
        $user_order_list = $task->t_order_info->get_order_user_list_by_month($end_time);
        $new_user = [];//上月新签
        $arr['new_order_assign_num'] = 0;
        $arr['new_order_unassign_num'] = 0;
        foreach ( $user_order_list as $item ) {
            if ($item['order_time'] >= $start_time ){
                $new_user[] = $item['userid'];
                if (!$item['start_time'] && $item['assistantid'] > 0) {//新签订单,未排课,已分配助教
                    @$arr['new_order_assign_num']++;
                } else if (!$item['start_time'] && !$item['assistantid']) {//新签订单,未排课,未分配助教
                    @$arr['new_order_unassign_num']++;
                }
            }

        }

        $new_user = array_unique($new_user);
        $arr['new_student_num'] = count($new_user);//新签学生数

        //结课率
        $arr["all_registered_student"] = $arr['finish_num']+$arr["read_num"]+$arr["stop_student"]+$arr["drop_student"]+$arr["summer_winter_stop_student"];
        $arr["student_end_per"] = $arr["all_registered_student"] == 0 ? 0:round($arr["finish_num"]/$arr["all_registered_student"]*100,2)*100;

        //各年级在读学生统计
        $grade_list = $task->t_student_info->get_read_num_by_grade();
        $arrr=[];
        foreach($grade_list as $k=>$val){
            $arrr[$k]=$val["num"];
        }
        $grade_str = json_encode($arrr);


        //课时消耗目标数量
        $last_year_start = strtotime("-1 years",$start_time); 
        $last_year_end = strtotime("+1 months",$last_year_start); 

        $month_start_grade_info = $task->t_cr_week_month_info->get_data_by_type($start_time,$type);
        $month_start_grade_str = @$month_start_grade_info["grade_stu_list"];
        $grade_arr = json_decode($month_start_grade_str,true); //月初各年级在读人数
        
        $lesson_consume    = $task->t_lesson_info->get_total_consume_by_grade( $last_year_start,$last_year_end);
        $lesson_consume_target = 0;
        foreach($lesson_consume as $kk=>$vv){
            if($vv["total_student"]>0){
                $lesson_consume_target += @$grade_arr[$kk]*$vv["total_consume"]/$vv["total_student"];
            }
        }
        $new_student_num_last = @$month_start_grade_info["new_student_num"];
        $read_num_last = @$month_start_grade_info["read_num"];
        $lesson_consume_target += $new_student_num_last*600;
        $lesson_target_total = $read_num_last+ $new_student_num_last;
        $lesson_target  = $lesson_target_total == 0 ?0 :$lesson_consume_target/ $lesson_target_total ;


        $insert_data = [
          "create_time"             => $create_time,            //存档时间
          "create_time_range"       => $create_time_range,      //存档时间范围
          "type"                    => $type,                   //存档类型
          "target"                  => intval($arr['target']),  //1-月度目标收入
          "total_price"             => $arr['total_price'],     //2-完成金额
          "kpi_per"                 => intval($arr['kpi_per']*100), //3-完成率
          "gap_money"               => $arr['gap_money'],       //4-缺口金额

          "total_income"            => $arr['total_income'],    //A1-现金总收入
          "person_num"              => $arr['person_num'],      //A2-下单总人数

          "total_price_thirty"      => intval($arr["total_price_thirty"]*100),//A3-入职完整月人员签单额
          "person_num_thirty"       => $arr['person_num_thirty'],//A4-入职完整月人员人数
          "person_num_thirty_per"   => intval($arr['person_num_thirty_per']*100),//A5-平均人效
          "contract_per"            => intval($arr['contract_per']),//A6-平均单笔
          "month_kpi_per"           => intval($arr['month_kpi_per']*100),//A7-月KPI完整率(月初至今)
          "cr_num"                  => $arr['cr_num'],           //A8-CR总人数
          "finish_num"              => $arr['finish_num'],       //A9-结课学员数///存档
          "refund_num"              => $arr['refund_num'],       //A10-退费总人数

          "read_num"                => $arr['read_num'],         //B2-在读学生数量
          "total_student"           => $arr['total_student'],    //B3-上课学生数量

          "lesson_consume"          => intval($arr['lesson_consume']*100),//B5-课时消耗实际数量
          "teacher_leave"           => intval($arr['teacher_leave']*100),//B6-老师请假课时
          "student_leave"           => intval($arr['student_leave']*100),//B7-学生请假课时
          "other_leave"             => intval($arr['other_leave']*100),  //B8-其他原因未上课时

          "student_arrive"          => $arr['student_arrive'],   //学生到课数量
          "lesson_plan"             => $arr['lesson_plan'],      //排课数量
          "student_arrive_per"      => intval($arr['student_arrive_per']*100),//B10-学生到课率
          "lesson_income"           => intval($arr['lesson_income']*100),//B11-课时收入

          "expect_finish_num"       => $arr['expect_finish_num'],//C1-预计结课学生数量
          "plan_renew_num"          => $arr['plan_renew_num'],   //C2-计划内续费学生数量
          "other_renew_num"         => $arr['other_renew_num'],  //C3-计划外续费学生数量
          "real_renew_num"          => $arr['real_renew_num'],   //C4-实际续费学生数量
          "total_renew"             => intval($arr['total_renew']*100),  //C5-续费金额
          "renew_num_per"           => intval($arr['renew_num_per']*100),//C6-续费平均单笔
          "renew_per"               => intval($arr['renew_per']*100),    //C7-月续费率
          "finish_renew_per"        => intval($arr['finish_renew_per']*100),//C8-月预警续费率

          "tranfer_phone_num"       => $arr['tranfer_phone_num'],//D1-转介绍至CC例子量
          "tranfer_total_price"     => intval($arr['tranfer_total_price']*100), //D2-转介绍至CC例子签单金额
          "tranfer_total_num"       => $arr['tranfer_total_num'], //D3-转介绍至CC例子签单量
          "tranfer_success_per"     => intval($arr['tranfer_success_per']*100),//D4-月转介绍至CC签单率
          "tranfer_num"             => $arr['tranfer_num'],       //D5-转介绍成单数量
          "total_tranfer"           => intval($arr['total_tranfer']*100), //D6-转介绍总金额
          "tranfer_num_per"         => intval($arr['tranfer_num_per']*100),//D7- 转介绍平均单笔

          "total_test_lesson_num"   => $arr['total_test_lesson_num'],//E1-扩课试听数量
          "success_num"             => $arr['success_num'],       //E2-扩课成单数量
          "wait_num"                => $arr['wait_num'],          //E3-扩科待跟进数量
          "fail_num"                => $arr['fail_num'],          //E4-扩科未成单数量
          "kk_success_per"          => intval($arr['kk_success_per']*100),    //E5-月扩课成功率

          "student_list"            => $arr['student_list'],      //预警学员列表

          "average_person_effect"   => $arr["average_person_effect"],  //平均人效(非入职完整月)
          "cumulative_refund_rate"  => $arr["cumulative_refund_rate"], //合同累计退费率
          "stop_student"            => $arr["stop_student"],      //停课学生
          "drop_student"            => $arr["drop_student"],    //休学学员
          "summer_winter_stop_student" =>$arr["summer_winter_stop_student"],  //寒暑假停课学生
          "new_order_assign_num"    => $arr["new_order_assign_num"],  //新签合同未排量(已分配)
          "new_order_unassign_num"  => $arr["new_order_unassign_num"], //新签合同未排量(未分配)
          "student_end_per"         => $arr["student_end_per"],   //结课率
          "new_student_num"         => $arr["new_student_num"],   //本月新签学生数
          "grade_stu_list"          => $grade_str ,        //各年级在读学生数,json格式
          "lesson_target"           => $lesson_target  ,        //课时系数目标量
          "lesson_consume_target"   => $lesson_consume_target ,        //课时消耗目标数量
        ];

        
        $ret_id = $task->t_cr_week_month_info->get_info_by_type_and_time($type,$create_time);
        if($ret_id>0){
            $task->t_cr_week_month_info->field_update_list($ret_id,$insert_data);
        }else{
            $task->t_cr_week_month_info->row_insert($insert_data);
        }
    }
}
