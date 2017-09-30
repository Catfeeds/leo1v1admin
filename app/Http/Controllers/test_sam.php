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
        
    }



    public function  tt(){
        //every week
        $end_time = strtotime(date('Y-m-d'),time()); //
        $start_time = $end_time - 7 * 86400;
        $start_month = date("Y-m",$start_time);
        $end_month   = date("Y-m",$end_time);
        if($start_month == $end_month){ //周报
            $type = 2;
            $create_time = $end_time;//
        }else{//跨月报
            $type = 3;
            $create_time = $end_time;
        }

        dd($start_time,$end_time);
        //节点

        //概况
        $ret_total   = $this->t_order_info->get_total_price($start_time,$end_time);
        $ret_total_thirty = $this->t_order_info->get_total_price_thirty($start_time,$end_time);
        $ret_cr = $this->t_manager_info->get_cr_num($start_time,$end_time);
        $ret_refund = $this->t_order_refund->get_assistant_num($start_time,$end_time);  //退费总人数
        $target = $this->t_manager_info->get_cr_target($last_month);//月度目标


        $arr['target']             = $target;                                //1-续费目标
        $arr['total_price']        = $ret_total['total_price'] ;             //2-现金总收入
        $arr['total_income']       = $ret_total['total_price'] ;             //A1-现金总收入
        $arr['person_num']         = $ret_total['person_num'];               //A2-下单总人数
        $arr['contract_num']       = $ret_total['order_num']; //合同数
        $arr['total_price_thirty'] = $ret_total_thirty['total_price'] / 100; //A3-入职完整月人员签单额
        $arr['person_num_thirty']  = $ret_total_thirty['person_num'];        //A4-入职完整月人员人数

        $arr['cr_num']             = $ret_cr;                                //A8-CR总人数
        $arr['refund_num']         = $ret_refund;//退费总人数

        if(($arr['target']-$arr['total_price']) > 0){
            $arr['gap_money'] = $arr['target'] - $arr['total_price'];   //4-缺口金额
        }else{
            $arr['gap_money'] = 0;  
        }

        if($arr['target']){
            $arr['kpi_per'] = round(100*$arr['total_price']/$arr['target'],2); //3-完成率
        }else{
            $arr['kpi_per'] = 0;
        }

        if($arr['total_price']){
            $arr['contract_per']   = round($arr['total_price']/$arr['contract_num'],2);   //A6-平均单笔
        }else{
            $arr['contract_per']   = 0;
        }
        if($arr['person_num_thirty']){
            $arr['person_num_thirty_per'] = round($arr['total_price_thirty'] / $arr['person_num_thirty'],2); //A5-平均人效
        }else{
            $arr['person_num_thirty_per'] = 0;
        }


        //课时消耗
        $lesson_consume    = $this->t_lesson_info->get_total_consume($start_time,$end_time); //课时消耗实际数量,上课学生数
        $leave_num         = $this->t_lesson_info->get_leave_num($start_time,$end_time); //老师,学生请假课时

        $arr['lesson_consume'] = round($lesson_consume['total_consume']/100,2);
        $arr['teacher_leave'] = 0;
        $arr['student_leave'] = 0;
        $arr['other_leave'] = 0;
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
        $arr['tranfer_num']   = $ret_total['tranfer_num']/1;  //转介绍成单数量
        $arr['total_tranfer'] = $ret_total['total_tranfer']/100; //转介绍总金额
        $arr['tranfer_phone_num'] = $tranfer; //转介绍至CC例子量
        
        $arr['tranfer_total_price'] = round($tranfer_data['total_price'] /100,2);
        $arr['tranfer_total_num']   = $tranfer_data['total_num'];
        if($arr['tranfer_num'] > 0){
            $arr['tranfer_num_per'] = round($arr['total_tranfer']/$arr['tranfer_num'],2);
        }else{
            $arr['tranfer_num_per'] = 0;
        }

        //扩科
        $kk          = $this->t_test_lesson_subject_sub_list->tongji_kk_data($start_time,$end_time) ;
        $success_num = $this->t_test_lesson_subject_sub_list->tongji_success_order($start_time,$end_time);
        $arr['total_test_lesson_num'] = $kk['total_test_lesson_num'];
        $arr['success_num'] = $success_num;
        $arr['fail_num'] = $kk['fail_num'];
        $arr['wait_num'] = $kk['wait_num'];


      //存档------------------------------------------------
        $finish_num = $this->t_student_info->get_finish_num($start_time,$end_time);//A9-结课学员数
        $read_num   = $this->t_student_info->get_read_num($start_time,$end_time);//在读学员数量
        $lesson_plan    = $this->t_lesson_info->get_total_lesson($start_time,$end_time); //实际有效课时/排课量
        $lesson_income  = $this->t_lesson_info->get_total_income($start_time,$end_time);//课时有效收入

        $tranfer = $this->t_seller_student_new->get_tranfer_phone_num($start_time,$end_time);
        $tranfer_data = $this->t_order_info->get_cr_to_cc_order_num($start_time,$end_time);

        $arr['tranfer_phone_num'] = $tranfer; //转介绍至CC例子量
        $arr['tranfer_total_price'] = round($tranfer_data['total_price'] /100,2);
        $arr['tranfer_total_num']   = $tranfer_data['total_num'];
        $arr['finish_num'] = $finish_num;
        $arr['read_num']   = $read_num;
        //$arr['total_student'] = $lesson_consume['total_student']; //实际有效课时
        $arr['lesson_plan'] = $lesson_plan['total_plan']; //计划排课数量
        $arr['student_arrive'] = $lesson_plan['student_arrive']; //学生有效课程数量
        $arr['lesson_income'] = round($lesson_income/100,2);
        if($arr['lesson_plan']){
            $arr['student_arrive_per'] = round(100*$arr['student_arrive']/$arr['lesson_plan'],2);
        }else{
            $arr['student_arrive_per'] = 0;
        }

        $warning_list_new = $this->t_student_info->get_warning_stu_list_new();

        dd($arr);
    }
}
