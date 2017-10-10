<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use \App\Libs;
use \App\Config as C;
use \App\Enums as E;
use App\Helper\Utils;

class test_sam  extends Controller
{
    use CacheNick;
    use TeaPower;
    
    public function lesson_list()
    {
      
    }
    
    private function get_lesson_quiz_cfg($lesson_quiz_status, $lesson_type)
    {
    }
    public function manager_list()
    {
    }
    public function test(){
        $timestamp = time(); 


        $end_time   = strtotime(date('Y-m-d', strtotime("this week Tuesday", $timestamp)));  
        //        $end_time = strtotime(date('Y-m-d'),time()); //
        $start_time = $end_time - 7 * 86400;

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
        $ret_total         = $this->t_order_info->get_total_price($start_time,$end_time);
        if($type == 3){
            $month_ret_total   = $this->t_order_info->get_total_price(strtotime($end_month),$end_time);
        }elseif($type == 1 || $type == 2){
            $month_ret_total   = $this->t_order_info->get_total_price(strtotime($start_month),$end_time);
        }//月初至今
        $ret_total_thirty  = $this->t_order_info->get_total_price_thirty($start_time,$end_time);
        $ret_cr = $this->t_manager_info->get_cr_num($start_time,$end_time);
        $ret_refund = $this->t_order_refund->get_assistant_num($start_time,$end_time);  //退费总人数
        $target = $this->t_manager_info->get_cr_target($last_month);//月度目标
        $arr['target']             = $target * 100;                          //1-续费目标
        $arr['total_price']        = $ret_total['total_price'] ;             //2-现金总收入
        $arr['total_income']       = $ret_total['total_price'] ;             //A1-现金总收入
        $arr['person_num']         = $ret_total['person_num'];               //A2-下单总人数
        $arr['contract_num']       = $ret_total['order_num']; //合同数
        $arr['total_price_thirty'] = $ret_total_thirty['total_price'] / 100; //A3-入职完整月人员签单额
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
        if($arr['person_num_thirty']){
            $arr['person_num_thirty_per'] = round($arr['total_price_thirty'] / $arr['person_num_thirty'],2);//A5-平均人效
        }else{
            $arr['person_num_thirty_per'] = 0;
        }
        //课时消耗
        $lesson_consume    = $this->t_lesson_info->get_total_consume($start_time,$end_time); //课时消耗实际数量,上课学生数
        $leave_num         = $this->t_lesson_info->get_leave_num($start_time,$end_time); //老师,学生请假课时
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
        if($arr['renew_num']){
            $arr['renew_num_per'] = round($arr['total_renew']/$arr['renew_num'],2); //平均单笔
        }else{
            $arr['renew_num_per'] = 0;
        }
        //转介绍
        $tranfer = $this->t_seller_student_new->get_tranfer_phone_num($start_time,$end_time);
        $tranfer_data = $this->t_order_info->get_cr_to_cc_order_num($start_time,$end_time);
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
        $kk          = $this->t_test_lesson_subject_sub_list->tongji_kk_data($start_time,$end_time) ;
        $success_num = $this->t_test_lesson_subject_sub_list->tongji_success_order($start_time,$end_time);
        $arr['total_test_lesson_num'] = $kk['total_test_lesson_num'];                 //E1-扩课试听数量
        $arr['success_num'] = $success_num;                                           //E2-扩课成单数量
        $arr['fail_num'] = $kk['fail_num'];                                           //E3-扩科待跟进数量
        $arr['wait_num'] = $kk['wait_num'];                                           //E4-扩科未成单数量
        //存档------------------------------------------------
        //概况 
        $finish_num = $this->t_student_info->get_finish_num($start_time,$end_time);//A9-结课学员数
        $arr['finish_num'] = $finish_num;
        //课时消耗
        $read_num   = $this->t_student_info->get_read_num($start_time,$end_time);//在读学员数量
        $lesson_plan    = $this->t_lesson_info->get_total_lesson($start_time,$end_time); //实际有效课时/排课量
        $lesson_income  = $this->t_lesson_info->get_total_income($start_time,$end_time);//课时有效收入
        $arr['read_num']       = $read_num;                                                       //B2-在读学生数量
        $arr['total_student']  = $lesson_consume['total_student'];                                //B3-上课学生数量
        $arr['lesson_plan']    = $lesson_plan['total_plan']; //计划排课数量
        $arr['student_arrive'] = $lesson_plan['student_arrive']; //学生有效课程数量
        $arr['lesson_income'] = round($lesson_income/100,2);                                      //B11-课时收入
        if($arr['lesson_plan']){
            $arr['student_arrive_per'] = round(100*$arr['student_arrive']/$arr['lesson_plan'],2); //B10-学生到课率
        }else{
            $arr['student_arrive_per'] = 0;
        }
        //续费 6
        $warning_list_new = $this->t_student_info->get_warning_stu_list_new();
        $userlist = '';
        foreach ($warning_list_new as $key => $value) {
          $userlist .= ','.$value['userid'];
        }
        $userlist = trim($userlist,',');



        //转介绍 
        $month_tranfer_data = $this->t_order_info->get_cr_to_cc_order_num(strtotime($end_month),$end_time); //月初至今
        $arr['month_tranfer_total_price'] = round($month_tranfer_data['total_price'] /100,2);
        $arr['month_tranfer_total_num']   = $month_tranfer_data['total_num'];
        if($arr['month_tranfer_total_num']){
          $arr['tranfer_success_per'] = round($arr['month_tranfer_total_price']/$arr['month_tranfer_total_num'],2); //D4-月转介绍至CC签单率
        }else{
          $arr['tranfer_success_per'] = 0;
        }
        //扩科
        $month_kk          = $this->t_test_lesson_subject_sub_list->tongji_kk_data(strtotime($end_month),$end_time) ;
        $month_success_num = $this->t_test_lesson_subject_sub_list->tongji_success_order(strtotime($end_month),$end_time);
        $arr['month_total_test_lesson_num'] = $month_kk['total_test_lesson_num'];                 //E1-扩课试听数量
        $arr['month_success_num'] = $month_success_num;                                           //E2-扩课成单数量
        if($arr['month_total_test_lesson_num']){
          $arr['kk_success_per'] = round(100*$arr['month_success_num']/$arr['month_total_test_lesson_num'],2);//E5-月扩课成功率
        }else{
          $arr['kk_success_per'] = 0;
        }

        if($type==2 ||$type ==3){//周报,跨月周报
            $warning_list_new = $this->t_student_info->get_warning_stu_list_new();
        }else if($type == 1){//月报
            $warning_list_new = $this->t_student_info->get_warning_stu_list_month_new();
        }
        $student_list = '';
        foreach($warning_list_new as $key => $value){
            $student_list .= ','.$value['userid'];
        }
        $student_list = trim($student_list,',');
        $arr['student_list'] = $student_list;
        //续费
        $warning_list = $this->t_cr_week_month_info->get_student_list_new($type,$start_time);
        $renew_student_list = $this->t_order_info->get_renew_student_list($start_time,$end_time);
        var_dump($warning_list);
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
            if(!empty($waring_list)){
                foreach($waring_list as $key => $value){
                    if(in_array($value,$renew_student_list)){
                        ++$arr['plan_renew_num'];
                    }
                }
            }
            $arr['other_renew_num'] = $arr['real_renew_num'] - $arr['plan_renew_num'];
        }
        $arr['expect_finish_num'] = $warning_num; //预计结课学生数量
        print_r($warning_num);
        print_r($warning_list);
        dd($arr);
        //月初至今
        $month_warning_list = $this->t_cr_week_month_info->get_student_list_new(1,$start_time); //月初拉上个月数据
        $month_renew_student_list = $this->t_order_info->get_renew_student_list(strtotime($end_month),$end_time);

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
                    if(in_array($value,$month_renew_student_list )){
                        ++$month_plan_renew_num;
                    }
                }
            }
            $arr['other_renew_num'] = $arr['real_renew_num'] - $arr['plan_renew_num'];
        }

        $month_real_renew_num = empty($month_renew_student_list)?0: count($month_renew_student_list); //  实际续费学生数量

        $arr['renew_per'] = $month_warning_num == 0 ? 0:round(100*$month_real_renew_num/$month_warning_num,2);//  月续费率
        $arr['finish_renew_per'] = $month_warning_num == 0 ? 0:round(100*$month_plan_renew_num/$month_warning_num,2);//  月续费率

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
          "total_price_thirty"      => $arr["total_price_thirty"],//A3-入职完整月人员签单额
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
        ];

        
        $ret_id = $this->t_cr_week_month_info->get_info_by_type_and_time($type,$create_time);
        if($ret_id>0){
            $this->t_cr_week_month_info->field_update_list($ret_id,$insert_data);
        }else{
            $this->t_cr_week_month_info->row_insert($insert_data);
        }
    }



    public function  tt(){
        //every month
        $timestamp = time();  
        $now_time   = strtotime(date('Y-m-d', strtotime("this week Tuesday", $timestamp)));  
        //        $end_time = strtotime(date('Y-m-d'),time()); //
        //周报刷新
        $first_time = strtotime("-6 month", $now_time);
        $first_time = strtotime(date('Y-m-d',strtotime("this week Tuesday",$first_time)));
        for($i = $first_time; $i < $now_time;){
            $end_time = $i;
            $start_time  = $end_time - 7 * 86400;;
            $start_month = date("Y-m",$start_time);
            $end_month   = date("Y-m",$end_time);
            $cur_start   = strtotime(date('Y-m-01',$start_time));
            $cur_month   = strtotime(date('Y-m-01',$end_time));
            $last_month  = strtotime(date('Y-m-01',$cur_start-100));
            $create_time_range = date('Y-m-d H:i:s',$cur_month).'~'.date('Y-m-d H:i:s',$end_time);  
            if($start_month == $end_month){ //周报
                $type = 2;
                $create_time = $end_time;//
                $create_time_range = date('Y-m-d H:i:s',$start_time).'~'.date('Y-m-d H:i:s',$end_time);
            }else{//跨月报
                $type = 3;
                $create_time = $end_time;
                $create_time_range = date('Y-m-d H:i:s',$start_time).'~'.date('Y-m-d H:i:s',$end_time);
            } 
            if($this->t_cr_week_month_info->get_data_by_type($end_time,$type)){
                $arr = '';
                //C2-计划内续费学生数量 //C4-实际续费学生数量 ////C7-月续费率//C8-月预警续费率
                $warning_list       = $this->t_cr_week_month_info->get_student_list_new($type,$start_time);//进入续费预警的学员
                $renew_student_list = $this->t_order_info->get_renew_student_list($cur_month,$now_time);//往后6个月的合同学生数量
                $month_renew_student_list = $this->t_order_info->get_renew_student_list($cur_month,$end_time);//往后1个月的合同学生数量
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
                $tranfer            = $this->t_seller_student_new->get_tranfer_phone_num($cur_month,$end_time);
                $month_tranfer_data = $this->t_order_info->get_cr_to_cc_order_num($cur_month,$now_time); //签单数量(分配例子当月1号到6个月)
                $arr['month_tranfer_total_num']   = $month_tranfer_data['total_num'];
                if($arr['month_tranfer_total_num']){
                  $arr['tranfer_success_per'] = round($arr['month_tranfer_total_num']/$tranfer,2); //D4-月转介绍至CC签单率
                }else{
                  $arr['tranfer_success_per'] = 0;
                }
                //E5-月扩课成功率
                $month_kk          = $this->t_test_lesson_subject_sub_list->tongji_kk_data($cur_month,$end_time) ;
                $month_success_num = $this->t_test_lesson_subject_sub_list->tongji_success_order($cur_month,$end_time);
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
                $ret_id = $this->t_cr_week_month_info->get_info_by_type_and_time(4,$create_time);
                if($ret_id>0){
                    $this->t_cr_week_month_info->field_update_list($ret_id,$insert_data);
                }else{
                    $this->t_cr_week_month_info->row_insert($insert_data);
                }
            }
            $i = strtotime("+7 days",$i);
        }
    }
}

