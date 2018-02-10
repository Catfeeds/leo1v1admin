<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;

class assistant_performance extends Controller
{
    use CacheNick;
    use TeaPower;
    public function ass_revisit_info_month() {
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);

        $account = $this->get_account();
        if($account=="jack"){
            $account="eros";
        }
        $assistantid = $this->t_assistant_info->get_assistantid( $account);
        $assistantid  = $this->get_in_int_val("assistantid",$assistantid);
        $ret_info = $this->t_student_info->get_assistant_read_stu_info($assistantid);
        $month_start = strtotime(date("Y-m-01",$start_time));
        $month_end = strtotime(date("Y-m-01",$month_start+40*86400));
        $cur_start = $month_start+15*86400;
        $cur_end =  $month_end;
        $last_start = $month_start;
        $last_end =  $month_start+15*86400;
        $cur_time_str  = date("m.d",$cur_start)."-".date("m.d",$cur_end-300);
        $last_time_str = date("m.d",$last_start)."-".date("m.d",$last_end-300);

        return $this->pageView(__METHOD__,$ret_info,[
            "last_time_str"=>$last_time_str,
            "cur_time_str" =>$cur_time_str
        ]);
    }

    public function get_ass_stu_lesson_month(){
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        $account = $this->get_account();
        if($account=="jack"){
            $account="eros";
        }
        $assistantid = $this->t_assistant_info->get_assistantid( $account);
        $assistantid  = $this->get_in_int_val("assistantid",$assistantid);
        $adminid = $this->t_manager_info->get_ass_adminid($assistantid);

        $start_info       = \App\Helper\Utils::get_week_range($start_time,1 );
        $first_week = $start_info["sdate"];
        $end_info = \App\Helper\Utils::get_week_range($end_time,1 );
        if($end_info["edate"] <= $end_time){
            $last_week =  $end_info["sdate"];
        }else{
            $last_week =  $end_info["sdate"]-7*86400;
        }
        $n = ($last_week-$first_week)/(7*86400)+1;
        $userid_list = $this->t_student_info->get_read_student_ass_info();
        $time_list=[];
        $time_arr=[];
        for($i=0;$i<$n;$i++){
           $week = $first_week+$i*7*86400;
            $id =$this->t_ass_weekly_info->get_id_by_unique_record($adminid,$week,1);
            if($id>0){
                $read_student_list = $this->t_ass_weekly_info->field_get_list($id,"read_student_list");
                $read_student_list= @$read_student_list["read_student_list"];
            }else{
                $read_student_list = @$userid_list[$adminid];
            }
            if($read_student_list){
                $read_student_list = json_decode($read_student_list,true);
            }else{
                $read_student_list=[];
            }
            $time_list[$week]=$read_student_list;
            $time_arr[$week]=date("Y.m.d",$week)."-".date("Y.m.d",$week+7*86400-100);

        }
        $list=[];
        foreach($time_list as $k=>$val){
            foreach($val as $v){
                $list[$v][$k]=1; 
            }
        }
        foreach($list as $key=>&$item){
            foreach($time_list as $k=>$v){
                if(!isset($item[$k])){
                    $item[$k]="否";
                }else{
                     $item[$k]="是";
                }
            }
            $item["nick"] = $this->cache_get_student_nick($key);

        }

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($list),[
            "time_arr" =>$time_arr
        ]);

    }


    /*
      助教薪资版本
      2017年11-12月使用第一版本
      2018年1月开始第二版本
    */
    public function performance_info_show(){
        return $this->performance_info_second();
    }

    /*
      助教薪资第一版本
     */
    public function performance_info(){
              
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        if($start_time <strtotime("2017-08-01") ){
            return $this->pageView(__METHOD__,null);
        }
        $test_time = strtotime("2017-10-01");        
        $test_month= $this->t_month_ass_student_info->get_ass_month_info_payroll($test_time);
        // $end_time = strtotime("2017-11-01");


        $month_half = $start_time+15*86400;
        $last_month = strtotime("-1 month",$start_time);
        $ass_month= $this->t_month_ass_student_info->get_ass_month_info_payroll($start_time);
        
        $last_ass_month= $this->t_month_ass_student_info->get_ass_month_info_payroll($last_month);
        
        $target_info = $this->t_ass_group_target->field_get_list($start_time,"rate_target,renew_target");
        

        // //销售月拆解
        // $start_info       = \App\Helper\Utils::get_week_range($start_time,1 );
        // $first_week = $start_info["sdate"];
        // $end_info = \App\Helper\Utils::get_week_range($end_time,1 );
        // if($end_info["edate"] <= $end_time){
        //     $last_week =  $end_info["sdate"];
        // }else{
        //     $last_week =  $end_info["sdate"]-7*86400;
        // }
        // $n = ($last_week-$first_week)/(7*86400)+1;

        // //每周课时/学生数
        // $lesson_count_list=[];
        // for($i=0;$i<$n;$i++){
        //     $week = $first_week+$i*7*86400;
        //     $week_edate = $week+7*86400;
        //     $lesson_count_list[] = $this->t_manager_info->get_assistant_lesson_count_info($week,$week_edate);
        // }


        foreach($ass_month as $k=>&$item){
            if(empty($item["become_member_time"])){
                $item["become_member_time"]=$item["create_time"];
            }
            \App\Helper\Utils::unixtime2date_for_item($item, 'become_member_time','_str',"Y-m-d");  
            \App\Helper\Utils::unixtime2date_for_item($item, 'become_full_member_time','_str',"Y-m-d");  
            if($item["del_flag"]==0){
                $item["leave_member_time"]=0;
            }
            \App\Helper\Utils::unixtime2date_for_item($item, 'leave_member_time','_str',"Y-m-d");

            $item["del_flag_str"] = $item["del_flag"]?"离职":"在职";
            E\Eaccount_role::set_item_value_str($item);


            /*回访*/
            $revisit_reword_per = 0.2;
            // //当前在读学员
            // $read_student_list = $item["userid_list"];
            // if($read_student_list){
            //     $read_student_arr = json_decode($read_student_list,true);
            //     foreach($read_student_arr as $val){
            //         //先检查是否是本月才开始上课的(获取各科目常规课最早上课时间)
            //         $regular_lesson_list = $this->t_lesson_info_b3->get_stu_first_lesson_time_by_subject($val);
            //         $assign_time = $this->t_student_info->get_ass_assign_time($val);
            //         $first_lesson_time = @$regular_lesson_list[0]["lesson_start"];
            //         foreach($regular_lesson_list as $t_item){
            //             if($t_item["lesson_start"]>=$start_time && $t_item["lesson_start"]<=$end_time && $t_item["lesson_start"]>$assign_time){
            //                 $revisit_end = $t_item["lesson_start"]+86400;
                            
            //                 $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val,$t_item["lesson_start"],$revisit_end,$item["account"],5);
            //                 if($revisit_num <=0){
            //                     $revisit_reword_per -=0.05;
            //                 }

                            
            //             }
            //             if($t_item["lesson_start"]<$first_lesson_time){
            //                 $first_lesson_time = $t_item["lesson_start"];
            //             }

            //             if($revisit_reword_per <=0){
            //                 break;
            //             }
            //         }
            //         if($revisit_reword_per <=0){
            //             break;
            //         }

            //         if($first_lesson_time>0 && $first_lesson_time<$month_half){
            //             if($assign_time < $month_half){
            //                 $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val,$start_time,$end_time,$item["account"],-2);
            //                 if($revisit_num <2){
            //                     $revisit_reword_per -=0.05;
            //                 }
            //             }elseif($assign_time>=$month_half && $assign_time <$end_time){                            
            //                 $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val,$month_half,$end_time,$item["account"],-2);
            //                 if($revisit_num <1){
            //                     $revisit_reword_per -=0.05;
            //                 }

            //             }
            //         }elseif($first_lesson_time>0 && $first_lesson_time>=$month_half &&  $first_lesson_time<=$end_time){                       
            //             $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val,$month_half,$end_time,$item["account"],-2);
            //             if($revisit_num <1){
            //                 $revisit_reword_per -=0.05;
            //             }

            //         }
            //         if($revisit_reword_per <=0){
            //             break;
            //         }



                    
            //     }
            // }
            // if($revisit_reword_per >0){
            //     //检查本月带过的历史学生 
            //     $history_list = $this->t_ass_stu_change_list->get_ass_history_list($k,$start_time,$end_time);
                       
            //     foreach($history_list as $val){
            //         $add_time = $val["add_time"];
            //         if($add_time<$month_half){
            //             $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val["userid"],$start_time,$month_half,$item["account"],-2);
            //             if($revisit_num <1){
            //                 $revisit_reword_per -=0.05;
            //             }

            //         }else{
            //             $assign_time = $val["assign_ass_time"];
            //             if($assign_time <$month_half){
            //                 $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val["userid"],$start_time,$end_time,$item["account"],-2);
            //                 if($revisit_num <2){
            //                     $revisit_reword_per -=0.05;
            //                 }

            //             }else{
            //                 $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val["userid"],$month_half,$end_time,$item["account"],-2);
            //                 if($revisit_num <1){
            //                     $revisit_reword_per -=0.05;
            //                 }

            //             }
            //         }
            //         if($revisit_reword_per <=0){
            //             break;
            //         }

            
            //     }

            // }

            // if($revisit_reword_per>0){
            //     //检查停课学生回访状态
            //     $stop_student_list = $item["stop_student_list"];
            //     if($stop_student_list){
            //         $stop_student_arr = json_decode($stop_student_list,true);
            //         foreach($stop_student_arr as $val){
            //             //先检查是否是本月才开始上课的(获取各科目常规课最早上课时间)
            //             $first_regular_lesson_time = $this->t_lesson_info_b3->get_stu_first_regular_lesson_time($val);
            //             $assign_time = $this->t_student_info->get_ass_assign_time($val);                        

            //             if($first_regular_lesson_time>0 && $first_regular_lesson_time<$month_half){
            //                 if($assign_time < $month_half){
            //                     $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val,$start_time,$end_time,$item["account"],-2);
            //                     if($revisit_num <2){
            //                         $revisit_reword_per -=0.05;
            //                     }
            //                 }elseif($assign_time>=$month_half && $assign_time <$end_time){                            
            //                     $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val,$month_half,$end_time,$item["account"],-2);
            //                     if($revisit_num <1){
            //                         $revisit_reword_per -=0.05;
            //                     }

            //                 }
            //             }elseif($first_regular_lesson_time>0 && $first_regular_lesson_time>=$month_half &&  $first_regular_lesson_time<=$end_time){                       
            //                 $revisit_num = $this->t_revisit_info->get_ass_revisit_info_personal($val,$month_half,$end_time,$item["account"],-2);
            //                 if($revisit_num <1){
            //                     $revisit_reword_per -=0.05;
            //                 }

            //             }
            //             if($revisit_reword_per <=0){
            //                 break;
            //             }



                    
            //         }
            //     }

            // }
            // if($revisit_reword_per <0){
            //     $revisit_reword_per=0;
            // }
            // $item["revisit_reword_per"] = $revisit_reword_per;
           

            /*课时消耗达成率*/
            // $registered_student_list = @$last_ass_month[$k]["registered_student_list"];
            // $registered_student_list = @$item["registered_student_list"];//先以10月份数据代替
            // if($registered_student_list){
            //     $registered_student_arr = json_decode($registered_student_list,true);
            //     $last_stu_num = count($registered_student_arr);//月初在册人员数
            //     $last_lesson_total = $this->t_week_regular_course->get_lesson_count_all($registered_student_arr);//月初周总课时消耗数
            //     $estimate_month_lesson_count =$n*$last_lesson_total/$last_stu_num;
            // }else{
            //     $registered_student_arr=[];      
            //     $estimate_month_lesson_count =100;
            // }

            // //得到单位学员平均课时数完成率
            // $seller_lesson_count =$seller_stu_num=0;
            // foreach($lesson_count_list as $p_item){
            //     $seller_lesson_count += @$p_item[$k]["lesson_count"]; 
            //     $seller_stu_num += @$p_item[$k]["user_count"]; 
            // }
            // $seller_stu_num = $seller_stu_num/$n;
            $seller_stu_num = $item["seller_week_stu_num"];
            $seller_lesson_count = $item["seller_month_lesson_count"];
            $estimate_month_lesson_count = $item["estimate_month_lesson_count"];
            if(empty($seller_stu_num)){
                $lesson_count_finish_per=0;
            }else{
                $lesson_count_finish_per= round($seller_lesson_count/$seller_stu_num/$estimate_month_lesson_count*100,2);
            }

            //算出kpi中课时消耗达成率的情况
            // if($lesson_count_finish_per>=70){
            //     $kpi_lesson_count_finish_per = 0.4;
            // }else{
            //     $kpi_lesson_count_finish_per=0;
            // }

            // $item["kpi_lesson_count_finish_per"]=$kpi_lesson_count_finish_per;

            /*课程消耗奖金*/
            if($lesson_count_finish_per>=120){
                $lesson_count_finish_reword=$seller_lesson_count*1.2;
            }elseif($lesson_count_finish_per>=100 ){
                $lesson_count_finish_reword=$seller_lesson_count*1;
            }elseif($lesson_count_finish_per>=75 ){
                $lesson_count_finish_reword=$seller_lesson_count*0.8;
            }elseif($lesson_count_finish_per>=50 ){
                $lesson_count_finish_reword=$seller_lesson_count*0.5;
            }else{
                $lesson_count_finish_reword=0;
            }

            $item["lesson_count_finish_reword"]=$lesson_count_finish_reword;

            /*续费提成奖金*/
            //计算助教相关退费

            $renw_target = @$last_ass_month[$k]["warning_student"]*0.8*7000*100;
            if($start_time>=strtotime("2017-11-01")){
                $renw_target = @$target_info["renew_target"]; 
            }
            $item["renw_target"] =  $renw_target;
            // $renw_price = $item["renw_price"]+$item["tran_price"]-$item["ass_refund_money"];
            $renw_price = $item["renw_price"]+$item["tran_price"];
            $renw_per = $renw_target>0?( $renw_price/$renw_target*100):0;
            $renw_reword = 0;
            if($renw_per>=120){
                $renw_reword = $renw_target*1.2*0.04+($renw_price-$renw_target*1.2)*0.05;
            }elseif($renw_per>=100){
                $renw_reword = $renw_price*0.04;
            }elseif($renw_per>=75){
                $renw_reword = $renw_price*0.028;
            }elseif($renw_per>=50){
                $renw_reword = $renw_price*0.02;
            }elseif($renw_per>=30){
                $renw_reword = $renw_price*0.012;
            }

            $item["renw_reword"] =  $renw_reword;
            $item["renw_price"] =  $renw_price;


            /*转介绍奖金*/
            //转介绍个数
            $cc_tran_num = $item["cc_tran_num"]+$item["tran_num"]+$item["hand_tran_num"];
            $cc_tran_num_reword=0;
            if($cc_tran_num>5){
                $cc_tran_num_reword = $cc_tran_num*50000;
            }elseif($cc_tran_num>=3){
                $cc_tran_num_reword = $cc_tran_num*30000;
            }else{
                $cc_tran_num_reword = $cc_tran_num*20000;
            }
            //转介绍金额提成
            $cc_tran_price_reword = $item["cc_tran_money"]*0.02;

            $cc_tran_reword = $cc_tran_num_reword+$cc_tran_price_reword;
            $item["cc_tran_reword"] = $cc_tran_reword;

            $item["cc_tran_num"] = $cc_tran_num;


            /*扩课20%、停课15%、结课未续费5%*/
            //退费
            // $ass_refund_money = $item["ass_refund_money"];            
            // $ass_renw_money = $item["renw_price"]+$item["tran_price"];
            // $refund_per = $ass_renw_money>0?$ass_refund_money/$ass_renw_money:0;
            // if($refund_per<=0.05){
            //     $refund_reword_per = 0.2;
            // }else{
            //     $refund_reword_per = 0;
            // }
            // $item["refund_reword_per"]=$refund_reword_per;

            //扩课
            $kk_num = $item["kk_num"]+$item["hand_kk_num"];
            $kk_per = $item["read_student"]>0?($kk_num/$item["read_student"]):0;
            if($kk_per>=0.06){
                $kk_reword_per = 0.2;
            }else{
                $kk_reword_per = 0;
            }
            $item["kk_reword_per"] = $kk_reword_per;

            //停课
            if($start_time <strtotime("2017-11-01")){
                $all_stu_num =  @$test_month[$k]["all_ass_stu_num"];
                $all_stop_num =  @$test_month[$k]["stop_student"];

            }else{
                $all_stu_num = $item["all_ass_stu_num"];//所有学员
                $all_stop_num = $item["stop_student"];//停课学员               
            }

            $stop_per = $all_stu_num>0?($all_stop_num/$all_stu_num):0;
            if($all_stu_num>=100 && $stop_per<=0.06){
                $stop_reword_per = 0.15;
            }elseif($all_stu_num>=50 && $stop_per<=0.05 && $all_stu_num<100){
                $stop_reword_per = 0.15;
            }elseif( $all_stu_num<50 && $stop_per<=0.04){
                $stop_reword_per = 0.15;
            }else{
                 $stop_reword_per = 0;
            }
            $item["stop_reword_per"]=$stop_reword_per;

            //结课未续费
            if($start_time <strtotime("2017-11-01")){
                $end_no_renw_num = $item["end_stu_num"];//先以10月份当月结课学生数代替 
            }else{
                $end_no_renw_num = $item["end_no_renw_num"]; 
            }
            $end_no_renw_per = $all_stu_num>0?($end_no_renw_num/$all_stu_num):0;
            if($end_no_renw_per <=0.08){
                $end_no_renw_reword_per = 0.05;
            }else{
                $end_no_renw_reword_per = 0;
            }
            $item["end_no_renw_reword_per"]=$end_no_renw_reword_per;
            

            $item["revisit_reword"] = $item["revisit_reword_per"]*1500/100;
            $item["kpi_lesson_count_finish_reword"] = $item["kpi_lesson_count_finish_per"]*1500/100;
            $item["kk_reword"] = $item["kk_reword_per"]*1500;
            $item["stop_reword"] = $item["stop_reword_per"]*1500;
            $item["end_no_renw_reword"] = $item["end_no_renw_reword_per"]*1500;
            $item["lesson_count_finish_reword"] = $item["lesson_count_finish_reword"]/100;
            $item["renw_reword"] = $item["renw_reword"]/100;
            $item["cc_tran_reword"] = $item["cc_tran_reword"]/100;
            $item["all_reword"] =  $item["revisit_reword"]+$item["kpi_lesson_count_finish_reword"]+$item["kk_reword"]+$item["stop_reword"]+$item["end_no_renw_reword"]+ $item["lesson_count_finish_reword"]+$item["renw_reword"]+ $item["cc_tran_reword"];
            
        }
        // dd($ass_month);
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ass_month),[
            "start"=>date("Y-m-d H:i",$start_time),
            "end"=>date("Y-m-d H:i",$end_time),
        ]);

        //dd($ass_month);
        
               
        
        
    }

    /*
      助教薪资第二版本
    */
    public function performance_info_second(){
        #分页信息
        $page_info= $this->get_in_page_info();
        #排序信息
        list($order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str([],"adminid desc");

        #输入参数 
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        if($start_time <strtotime("2017-08-01") ){
            return $this->pageView(__METHOD__,null);
        }
        $test_time = strtotime("2017-10-01");        
        $test_month= $this->t_month_ass_student_info->get_ass_month_info_payroll($test_time);
        // $end_time = strtotime("2017-11-01");


        $month_half = $start_time+15*86400;
        $last_month = strtotime("-1 month",$start_time);
        $ass_month= $this->t_month_ass_student_info->get_ass_month_info_payroll($start_time);
        
        $last_ass_month= $this->t_month_ass_student_info->get_ass_month_info_payroll($last_month);
        
        $target_info = $this->t_ass_group_target->field_get_list($start_time,"rate_target,renew_target");

        $old_twl_info = $this->t_month_ass_student_info->get_ass_month_info(1512057500);//12月份旧版数据        

        //销售月拆解
        $start_info       = \App\Helper\Utils::get_week_range($start_time,1 );
        $first_week = $start_info["sdate"];
        $end_info = \App\Helper\Utils::get_week_range($end_time,1 );
        if($end_info["edate"] <= $end_time){
            $last_week =  $end_info["sdate"];
        }else{
            $last_week =  $end_info["sdate"]-7*86400;
        }
        $n = ($last_week-$first_week)/(7*86400)+1;

        // //每周助教在册学生数量获取
        // $registered_student_num=[];
        // for($i=0;$i<$n;$i++){
        //     $week = $first_week+$i*7*86400;
        //     $week_edate = $week+7*86400;
        //     $week_info = $this->t_ass_weekly_info->get_all_info($week);
        //     foreach($week_info as $val){
        //         @$registered_student_num[$val["adminid"]] +=@$week_info[$val["adminid"]]["registered_student_num"];
        //     } 
        // }


        foreach($ass_month as $k=>&$item){
            if(empty($item["become_member_time"])){
                $item["become_member_time"]=$item["create_time"];
            }
            \App\Helper\Utils::unixtime2date_for_item($item, 'become_member_time','_str',"Y-m-d");  
            \App\Helper\Utils::unixtime2date_for_item($item, 'become_full_member_time','_str',"Y-m-d");  
            if($item["del_flag"]==0){
                $item["leave_member_time"]=0;
            }
            \App\Helper\Utils::unixtime2date_for_item($item, 'leave_member_time','_str',"Y-m-d");

            $item["del_flag_str"] = $item["del_flag"]?"离职":"在职";
            E\Eaccount_role::set_item_value_str($item);

            $registered_student_list = @$last_ass_month[$k]["registered_student_list"];
            if($registered_student_list){
                $registered_student_arr = json_decode($registered_student_list,true);
                $last_registered_num = count($registered_student_arr);//月初在册人员数
            }else{
                $last_registered_num=0;
            }
            //  $item["last_registered_num"] = $last_registered_num;
            $item["all_student_last"] = @$last_ass_month[$k]["all_student"];
            if(!empty($item["all_student_last"])){
                $item["last_registered_num"] = $item["all_student_last"];
            }else{
                $item["last_registered_num"] =0;
            }
            $last_registered_num =  $item["last_registered_num"];


            /*回访*/
            $revisit_reword_per = 0.2;
                     

            /*课时消耗达成率*/
            // $registered_student_list = @$last_ass_month[$k]["registered_student_list"];
            // if($registered_student_list){
            //     $registered_student_arr = json_decode($registered_student_list,true);
            //     $last_stu_num = count($registered_student_arr);//月初在册人员数
            //     $last_lesson_total = $this->t_week_regular_course->get_lesson_count_all($registered_student_arr);//月初周总课时消耗数
            //     $estimate_month_lesson_count =$n*$last_lesson_total/$last_stu_num;  //预估月课时消耗总量
            // }else{
            //     $registered_student_arr=[];      
            //     $estimate_month_lesson_count =100;
            // }

            //平均学员数(销售周)
            // $seller_stu_num = @$registered_student_num[$k]/$n;


            //得到单位学员
            $seller_stu_num = $item["seller_week_stu_num"];
            $seller_lesson_count = $item["seller_month_lesson_count"];
            $estimate_month_lesson_count = $item["estimate_month_lesson_count"];
            if(empty($estimate_month_lesson_count)){
                $estimate_month_lesson_count=100;
            }
            if(empty($seller_stu_num)){
                $lesson_count_finish_per=0;
            }else{
                $lesson_count_finish_per= round($seller_lesson_count/$seller_stu_num/$estimate_month_lesson_count*100,2);
            }

            //算出kpi中课时消耗达成率的情况
            // if($lesson_count_finish_per>=70){
            //     $kpi_lesson_count_finish_per = 0.4*100;
            // }else{
            //     $kpi_lesson_count_finish_per=0;
            // }

            // $item["kpi_lesson_count_finish_per"]=$kpi_lesson_count_finish_per;

            /*课程消耗奖金*/
            if($lesson_count_finish_per>=120){
                $lesson_count_finish_reword=$seller_lesson_count*1.2;
            }elseif($lesson_count_finish_per>=100 ){
                $lesson_count_finish_reword=$seller_lesson_count*1;
            }elseif($lesson_count_finish_per>=75 ){
                $lesson_count_finish_reword=$seller_lesson_count*0.8;
            }elseif($lesson_count_finish_per>=50 ){
                $lesson_count_finish_reword=$seller_lesson_count*0.5;
            }else{
                $lesson_count_finish_reword=0;
            }

            $item["lesson_count_finish_reword"]=$lesson_count_finish_reword;

            /*续费提成奖金*/
            //计算助教相关退费

            $renw_target = @$last_ass_month[$k]["warning_student"]*0.8*7000*100;
            if($start_time>=strtotime("2017-11-01")){
                $renw_target = @$target_info["renew_target"]; 
            }
            $item["renw_target"] =  $renw_target;
            // $renw_price = $item["renw_price"]+$item["tran_price"]-$item["ass_refund_money"];
            $renw_price = $item["performance_cr_renew_money"]+$item["performance_cr_new_money"];
            $renw_per = $renw_target>0?( $renw_price/$renw_target*100):0;
            $renw_reword = 0;
            if($renw_per>=120){
                $renw_reword = $renw_target*1.2*0.04+($renw_price-$renw_target*1.2)*0.05;
            }elseif($renw_per>=100){
                $renw_reword = $renw_price*0.04;
            }elseif($renw_per>=75){
                $renw_reword = $renw_price*0.028;
            }elseif($renw_per>=50){
                $renw_reword = $renw_price*0.02;
            }elseif($renw_per>=30){
                $renw_reword = $renw_price*0.012;
            }

            $item["renw_reword"] =  $renw_reword;
            $item["renw_price"] =  $renw_price;


            /*转介绍奖金*/
            //转介绍个数
            $cc_tran_num = $item["performance_cc_tran_num"]+$item["performance_cr_new_num"]+$item["hand_tran_num"];
            $cc_tran_num_reword=0;
            if($cc_tran_num>5){
                $cc_tran_num_reword = ($cc_tran_num-5)*50000+130000;
            }elseif($cc_tran_num>=3){
                $cc_tran_num_reword = ($cc_tran_num-2)*30000+40000;
            }else{
                $cc_tran_num_reword = $cc_tran_num*20000;
            }
            //转介绍金额提成
            $cc_tran_price_reword = $item["performance_cc_tran_money"]*0.02;

            $cc_tran_reword = $cc_tran_num_reword+$cc_tran_price_reword;
            $item["cc_tran_reword"] = $cc_tran_reword;
            $item["cc_tran_price_reword"] = $cc_tran_price_reword;

            $item["cc_tran_num"] = $cc_tran_num;


            /*扩课20%、停课15%、结课未续费5%*/
            //退费
            // $ass_refund_money = $item["ass_refund_money"];            
            // $ass_renw_money = $item["renw_price"]+$item["tran_price"];
            // $refund_per = $ass_renw_money>0?$ass_refund_money/$ass_renw_money:0;
            // if($refund_per<=0.05){
            //     $refund_reword_per = 0.2;
            // }else{
            //     $refund_reword_per = 0;
            // }
            // $item["refund_reword_per"]=$refund_reword_per;

            //扩课
            $item["kk_num_old"] = @$old_twl_info[$k]["read_student"]+$item["hand_kk_num"];
            if($k==948){
                $item["kk_num_old"]=3;
            }
            $kk_num = $item["kk_num"]+$item["hand_kk_num"];
            $kk_per = $last_registered_num>0?($kk_num/$last_registered_num):0;
            //1月份以后新入职助教,当月有扩课的,扩课部分绩效拿满
            if($start_time>=strtotime("2018-01-01")){
                if( $item["become_member_time"]>=$start_time &&  $item["become_member_time"]< $end_time && $kk_num>0){
                    $kk_per=1;//>=0.06就可以
                }
            }
            if($kk_per>=0.06){
                $kk_reword_per = 0.2;
            }else{
                $kk_reword_per = 0;
            }
            $item["kk_all"]        = $kk_num;
            $item["kk_reword_per"] = $kk_reword_per;
            $kk_per_old = $last_registered_num>0?( $item["kk_num_old"]/$last_registered_num):0;
            if($kk_per_old>=0.06){
                $kk_reword_per_old = 0.2;
            }else{
                $kk_reword_per_old = 0;
            }
            $item["kk_reword_per_old"] = $kk_reword_per_old;
            $item["kk_reword_old"] = $item["kk_reword_per_old"]*1500;


            //停课
            if($start_time <strtotime("2017-11-01")){
                $all_stu_num =  @$test_month[$k]["all_ass_stu_num"];
                $all_stop_num =  @$test_month[$k]["stop_student"];

            }else{
                $all_stu_num = $last_registered_num;//月初在册学员
                $all_stop_num = $item["stop_student"];//停课学员               
            }

            $stop_per = $all_stu_num>0?($all_stop_num/$all_stu_num):0;
            if($all_stu_num>=100 && $stop_per<=0.06){
                $stop_reword_per = 0.15;
            }elseif($all_stu_num>=50 && $stop_per<=0.05 && $all_stu_num<100){
                $stop_reword_per = 0.15;
            }elseif( $all_stu_num<50 && $stop_per<=0.04){
                $stop_reword_per = 0.15;
            }else{
                $stop_reword_per = 0;
            }
            $item["stop_reword_per"]=$stop_reword_per;

            //结课未续费
            $end_no_renw_num = $item["end_stu_num"];//月结课学员 
            $end_no_renw_per = $last_registered_num>0?($end_no_renw_num/$last_registered_num):0;
            if($end_no_renw_per <=0.08){
                $end_no_renw_reword_per = 0.05;
            }else{
                $end_no_renw_reword_per = 0;
            }
            $item["end_no_renw_reword_per"]=$end_no_renw_reword_per;

            //临时处理
            if($lesson_count_finish_per>=70){
                $item["kpi_lesson_count_finish_per"]=40;
            }else{
                $item["kpi_lesson_count_finish_per"]=0;
            }

            $item["revisit_reword"] = $item["revisit_reword_per"]*1500/100;
            $item["kpi_lesson_count_finish_reword"] = $item["kpi_lesson_count_finish_per"]*1500/100;
            $item["kk_reword"] = $item["kk_reword_per"]*1500;
            $item["stop_reword"] = $item["stop_reword_per"]*1500;
            $item["end_no_renw_reword"] = $item["end_no_renw_reword_per"]*1500;
            $item["lesson_count_finish_reword"] = $item["lesson_count_finish_reword"]/100;
            $item["renw_reword"] = $item["renw_reword"]/100;
            $item["cc_tran_reword"] = $item["cc_tran_reword"]/100;
            $item["all_reword"] =  $item["revisit_reword"]+$item["kpi_lesson_count_finish_reword"]+$item["kk_reword"]+$item["stop_reword"]+$item["end_no_renw_reword"]+ $item["lesson_count_finish_reword"]+$item["renw_reword"]+ $item["cc_tran_reword"];

            if($start_time==strtotime("2017-12-01")){            
                $item["old_ewnew_money"] = @$old_twl_info[$k]["performance_cr_renew_money"]+@$old_twl_info[$k]["performance_cr_new_money"];
                $item["old_ewnew_money"] =$item["old_ewnew_money"]/100;
            }else{
                $item["old_ewnew_money"]="暂无数据";  
            }
            
        }


        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ass_month),[
            "start"=>date("Y-m-d H:i",$start_time),
            "end"=>date("Y-m-d H:i",$end_time),
            "week_start"=>date("Y-m-d",$first_week),
            "week_end"=>date("Y-m-d",$last_week+7*86400-100),
        ]);

        //dd($ass_month);
        
               
        
        
    }




    public function get_assistant_origin_order_losson_info(){
        list($start_time,$end_time,$opt_date_type)=$this->get_in_date_range(date("Y-m-01"),0,1,[
            1 => array("o.order_time","下单日期"),
            2 => array("o.pay_time", "生效日期"),
        ],3);

        $studentid         = $this->get_in_studentid(-1);
        $page_info          = $this->get_in_page_info();
        $sys_operator      = $this->get_in_str_val("sys_operator","");       
        $teacherid         = $this->get_in_teacherid(-1);
        $origin_userid     = $this->get_in_int_val("origin_userid", -1);          
        $order_adminid          = $this->get_in_adminid(-1);
        $assistantid       = $this->get_in_assistantid(-1);
        $ret_info = $this->t_order_info->get_assistant_origin_order_losson_list($start_time,$end_time,$opt_date_type, $studentid, $page_info , $sys_operator , $teacherid, $origin_userid ,$order_adminid,$assistantid );
        foreach($ret_info["list"] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item, 'order_time','_str'); 
            \App\Helper\Utils::unixtime2date_for_item($item, 'pay_time','_str'); 
            E\Egrade::set_item_value_str($item,"grade");
            E\Esubject::set_item_value_str($item,"subject");
          
            $item["phone_ex"] = preg_replace('/(1[3456789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item['phone']);

        }
        return $this->pageView(__METHOD__,$ret_info);


 
    }

    public function get_assistant_origin_order_losson_info_all(){
        list($start_time,$end_time,$opt_date_type)=$this->get_in_date_range(date("Y-m-01"),0,1,[
            1 => array("n.add_time","例子添加日期"),
            2 => array("o.order_time","下单日期"),
            3 => array("o.pay_time", "生效日期"),
        ],3);

        $studentid         = $this->get_in_studentid(-1);
        $page_info          = $this->get_in_page_info();
        $sys_operator      = $this->get_in_str_val("sys_operator","");       
        $teacherid         = $this->get_in_teacherid(-1);
        $origin_userid     = $this->get_in_int_val("origin_userid", -1);          
        $order_adminid          = $this->get_in_adminid(-1);
        $assistantid       = $this->get_in_assistantid(-1);
        $sys_operator_type  = $this->get_in_int_val("sys_operator_type", 1);
        $ret_info = $this->t_seller_student_new->get_assistant_origin_order_losson_list_all($start_time,$end_time,$opt_date_type, $studentid, $page_info , $sys_operator , $teacherid, $origin_userid ,$order_adminid,$assistantid,$sys_operator_type );

        foreach($ret_info["list"] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item, 'order_time','_str'); 
            \App\Helper\Utils::unixtime2date_for_item($item, 'pay_time','_str'); 
            \App\Helper\Utils::unixtime2date_for_item($item, 'add_time','_str'); 
            E\Egrade::set_item_value_str($item,"grade");
            E\Esubject::set_item_value_str($item,"subject");
          
            $item["phone_ex"] = preg_replace('/(1[3456789]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$item['phone']);

        }
        return $this->pageView(__METHOD__,$ret_info);


 
    }


    //助教每月回访信息回查列表
    public function  get_ass_revisit_history_detail_info(){
        $adminid = $this->get_in_int_val("adminid",324);
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        $ass_month= $this->t_month_ass_student_info->get_ass_month_info_payroll($start_time);
        $list = @$ass_month[$adminid];        
        $ret_info=[];

        //月末在读学员
        $read_student_list = $list["userid_list"];
        if($read_student_list){
            $read_student_arr = json_decode($read_student_list,true);
            foreach($read_student_arr as $val){
                $ret_info[$val]=[
                    "userid" =>$val,
                    "type_str"=>"在读学员",
                    "type_flag"=>1
                ];
            }
        }

        //历史学生
        $history_list = $this->t_ass_stu_change_list->get_ass_history_list($adminid,$start_time,$end_time);
        foreach($history_list as $val){
            $userid = $val["userid"];
            if(!isset($ret_info[$userid])){
                $ret_info[$userid]=[
                    "userid" =>$userid,
                    "type_str"=>"历史学员",
                    "type_flag"=>2
                ];

            }
        }

        //其他未结课学员
        $stop_student_list = $list["registered_student_list"];
        if($stop_student_list){
            $stop_student_arr = json_decode($stop_student_list,true);
            foreach($stop_student_arr as $val){
                if(!isset($ret_info[$val])){
                    $ret_info[$val]=[
                        "userid" =>$val,
                        "type_str"=>"其他未结课学员",
                        "type_flag"=>3
                    ];

                }
            }
        }

        $first_lesson_stu_list = $list["first_lesson_stu_list"];
        if($first_lesson_stu_list){
            $first_lesson_stu_arr = json_decode($first_lesson_stu_list,true);
            foreach($first_lesson_stu_arr as $val){
                $userid = $val["userid"];
                if(!isset($ret_info[$userid])){
                    $ret_info[$userid]=[
                        "userid" =>$userid,
                        "type_str"=>"第一次课学员",
                        "type_flag"=>4
                    ];

                }

            }
        }
        foreach($ret_info as &$item){
            $item["stu_nick"]= $this->cache_get_student_nick($item["userid"]);
        }

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info));

    }

    //助教每月在册学员常规课时展示
    public function show_ass_regular_lesson_info(){
        $adminid = $this->get_in_int_val("adminid",324);
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        $last_month = strtotime("-1 months",$start_time);
        $ass_month= $this->t_month_ass_student_info->get_ass_month_info_payroll($last_month);        
        $list = @$ass_month[$adminid];
        $registered_student_list = @$list["registered_student_list"];

        $start_info       = \App\Helper\Utils::get_week_range($start_time,1 );
        $first_week = $start_info["sdate"];
        $end_info = \App\Helper\Utils::get_week_range($end_time,1 );
        if($end_info["edate"] <= $end_time){
            $last_week =  $end_info["sdate"];
        }else{
            $last_week =  $end_info["sdate"]-7*86400;
        }
        $n = ($last_week-$first_week)/(7*86400)+1;

        $all=$num=0;

        if($registered_student_list){
            $registered_student_arr = json_decode($registered_student_list,true);
           
            $ass_userid="";
            foreach($registered_student_arr as $val){
                $ass_userid .=$val.",";
            }
            $ass_userid = "(".trim($ass_userid,",").")";
            $ret_info = $this->t_week_regular_course->get_stu_count_total_new($ass_userid);
            foreach($ret_info as &$item){
                $item["stu_nick"]= $this->cache_get_student_nick($item["userid"]);
                $all+=$item["regular_total"];
            }
            foreach($registered_student_arr as $val){
                if(!isset($ret_info[$val])){
                    $ret_info[$val]=[
                        "userid"  =>$val,
                        "regular_total"=>0,
                        "stu_nick"  =>$this->cache_get_student_nick($val)
                    ];
                }
            }
            $num = count($registered_student_arr);
            $all=round($all/$num*$n);
            
            
        }else{
            $ret_info=[];
        }
        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ret_info),[
            "total"  => $all
        ]);
 
    }

    //助教新签/续费合同详情(包含退费,下个月10前付款)
    public function get_ass_self_order_info(){
        $adminid = $this->get_in_int_val("adminid",324);
        $contract_type = $this->get_in_int_val("contract_type",-1);
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        $ass_order_info = $this->t_order_info->get_assistant_performance_order_info($start_time,$end_time,$adminid,$contract_type);
        $ass_order_period_list = $this->t_order_info->get_ass_self_order_period_money($start_time,$end_time);//助教自签合同金额(分期80%计算)

        $new_list= $renew_list=[];
        foreach($ass_order_info as $val){
            $contract_type = $val["contract_type"];
            $orderid = $val["orderid"];
            $userid = $val["userid"];
            $price = $val["price"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            if($contract_type==0){
                $new_list[$orderid]["uid"] = $uid;
                $new_list[$orderid]["userid"] = $userid;
                $new_list[$orderid]["price"] = $price;
                $new_list[$orderid]["orderid"] = $orderid;
                @$new_list[$orderid]["real_refund"] += $real_refund;
            }elseif($contract_type==3){
                $renew_list[$orderid]["uid"] = $uid;
                $renew_list[$orderid]["userid"] = $userid;
                $renew_list[$orderid]["price"] = $price;
                $renew_list[$orderid]["orderid"] = $orderid;
                @$renew_list[$orderid]["real_refund"] += $real_refund;
            }
        }
        $ass_renew_info = $ass_new_info=[];
        foreach($renew_list as $val){
            $orderid = $val["orderid"];
            $userid = $val["userid"];
            // $price = $val["price"];
            $price = @$ass_order_period_list[$orderid]["reset_money"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            if(!isset($ass_renew_info["user_list"][$userid])){
                $ass_renew_info["user_list"][$userid]=$userid;
                @$ass_renew_info["num"] +=1;
            }
            @$ass_renew_info["money"] += $price-$real_refund;
            @$ass_renew_info["refund_money"] += $real_refund;
            @$ass_renew_info["order_money"] += $price;

        }
        foreach($new_list as $val){
            $orderid = $val["orderid"];
            $userid = $val["userid"];
            // $price = $val["price"];
            $price = @$ass_order_period_list[$orderid]["reset_money"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            if(!isset($ass_new_info["user_list"][$userid])){
                $ass_new_info["user_list"][$userid]=$userid;
                @$ass_new_info["num"] +=1;
            }
            @$ass_new_info["money"] += $price-$real_refund;
            @$ass_new_info["refund_money"] += $real_refund;
            @$ass_new_info["order_money"] += $price;

        }

        $all_money = @$ass_renew_info["money"]+@$ass_new_info["money"];

        foreach($ass_order_info as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item, 'order_time','_str'); 
            \App\Helper\Utils::unixtime2date_for_item($item, 'apply_time','_str'); 
            \App\Helper\Utils::unixtime2date_for_item($item, 'pay_time','_str'); 
            $item["stu_nick"]= $this->cache_get_student_nick($item["userid"]);
        }

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($ass_order_info),[
            "all_money"  => $all_money,
            "renew_list" => $ass_renew_info,
            "new_list" => $ass_new_info
        ]);



        //dd([$ass_new_info,$ass_renew_info,$all_money]);


    }

    //销售转介绍合同详情(包含退费,下个月10前付款)
    public function get_seller_tran_order_info(){
        $adminid = $this->get_in_int_val("adminid",324);
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],3);
        $cc_order_list = $this->t_order_info->get_seller_tran_order_info($start_time,$end_time,$adminid);
        $cc_order_period_list = $this->t_order_info->get_seller_tran_order_period_money($start_time,$end_time);//CC合同金额(分期80%计算)

        $new_tran_list=[];
        foreach($cc_order_list as $val){
            $orderid = $val["orderid"];
            $userid = $val["userid"];
            $price = $val["price"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            $new_tran_list[$orderid]["uid"] = $uid;
            $new_tran_list[$orderid]["userid"] = $userid;
            $new_tran_list[$orderid]["price"] = $price;
            $new_tran_list[$orderid]["orderid"] = $orderid;
            @$new_tran_list[$orderid]["real_refund"] += $real_refund;
            
        }
        $ass_tran_info =[];
        foreach($new_tran_list as $val){
            $orderid = $val["orderid"];
            $userid = $val["userid"];
            // $price = $val["price"];
            $price = @$cc_order_period_list[$orderid]["reset_money"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            if(!isset($ass_tran_info["user_list"][$userid])){
                $ass_tran_info["user_list"][$userid]=$userid;
                @$ass_tran_info["num"] +=1;
            }
            @$ass_tran_info["money"] += $price-$real_refund;
            @$ass_tran_info_info["refund_money"] += $real_refund;
            @$ass_tran_info["order_money"] += $price;


        }

        foreach($cc_order_list as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item, 'order_time','_str'); 
            \App\Helper\Utils::unixtime2date_for_item($item, 'apply_time','_str'); 
            \App\Helper\Utils::unixtime2date_for_item($item, 'pay_time','_str'); 
            $item["stu_nick"]= $this->cache_get_student_nick($item["userid"]);
        }

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info($cc_order_list),[
            "ass_tran_info"  => @$ass_tran_info,
        ]);



    }




}
