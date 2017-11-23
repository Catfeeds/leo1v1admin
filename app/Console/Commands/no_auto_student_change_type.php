<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class no_auto_student_change_type extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:no_auto_student_change_type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '手动修改学生类型的学生,系统刷新学生类型';

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
        /**  @var   $task \App\Console\Tasks\TaskController */

        $task = new \App\Console\Tasks\TaskController ();

        // //临时处理数据
        // $start_time = strtotime("2017-08-01");        
        // $end_time = strtotime("2017-09-01");
        // // $last_month = strtotime("2017-10-01");        


        // $month_half = $start_time+15*86400;
        // $last_month = strtotime("-1 month",$start_time);
        // $ass_month= $task->t_month_ass_student_info->get_ass_month_info_payroll($start_time);
        // $last_ass_month= $task->t_month_ass_student_info->get_ass_month_info_payroll($last_month);
        

        // // //销售月拆解
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
        //     $lesson_count_list[] = $task->t_manager_info->get_assistant_lesson_count_info($week,$week_edate);
        // }


        // // $ppp=1;
        // foreach($ass_month as $k=>&$item){
        //     /*回访*/
        //     $revisit_reword_per = 0.2;
        //     //当前在读学员
        //     $read_student_list = $item["userid_list"];
        //     if($read_student_list){
        //         $read_student_arr = json_decode($read_student_list,true);
        //         foreach($read_student_arr as $val){
        //             //先检查是否是本月才开始上课的(获取各科目常规课最早上课时间)
        //             $regular_lesson_list = $task->t_lesson_info_b3->get_stu_first_lesson_time_by_subject($val);
        //             $assign_time = $task->t_student_info->get_ass_assign_time($val);
        //             $first_lesson_time = @$regular_lesson_list[0]["lesson_start"];
        //             foreach($regular_lesson_list as $t_item){
        //                 if($t_item["lesson_start"]>=$start_time && $t_item["lesson_start"]<=$end_time && $t_item["lesson_start"]>$assign_time){
        //                     $revisit_end = $t_item["lesson_start"]+86400;
                            
        //                     $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val,$t_item["lesson_start"],$revisit_end,$item["account"],5);
        //                     if($revisit_num <=0){
        //                         $revisit_reword_per -=0.05;
        //                     }

                            
        //                 }
        //                 if($t_item["lesson_start"]<$first_lesson_time){
        //                     $first_lesson_time = $t_item["lesson_start"];
        //                 }

        //                 if($revisit_reword_per <=0){
        //                     break;
        //                 }
        //             }
        //             if($revisit_reword_per <=0){
        //                 break;
        //             }

        //             if($first_lesson_time>0 && $first_lesson_time<$month_half){
        //                 if($assign_time < $month_half){
        //                     $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val,$start_time,$end_time,$item["account"],-2);
        //                     if($revisit_num <2){
        //                         $revisit_reword_per -=0.05;
        //                     }
        //                 }elseif($assign_time>=$month_half && $assign_time <$end_time){                            
        //                     $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val,$month_half,$end_time,$item["account"],-2);
        //                     if($revisit_num <1){
        //                         $revisit_reword_per -=0.05;
        //                     }

        //                 }
        //             }elseif($first_lesson_time>0 && $first_lesson_time>=$month_half &&  $first_lesson_time<=$end_time){                       
        //                 $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val,$month_half,$end_time,$item["account"],-2);
        //                 if($revisit_num <1){
        //                     $revisit_reword_per -=0.05;
        //                 }

        //             }
        //             if($revisit_reword_per <=0){
        //                 break;
        //             }



                    
        //         }
        //     }
        //     if($revisit_reword_per >0){
        //         //检查本月带过的历史学生 
        //         $history_list = $task->t_ass_stu_change_list->get_ass_history_list($k,$start_time,$end_time);
                       
        //         foreach($history_list as $val){
        //             $add_time = $val["add_time"];
        //             if($add_time<$month_half){
        //                 $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val["userid"],$start_time,$month_half,$item["account"],-2);
        //                 if($revisit_num <1){
        //                     $revisit_reword_per -=0.05;
        //                 }

        //             }else{
        //                 $assign_time = $val["assign_ass_time"];
        //                 if($assign_time <$month_half){
        //                     $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val["userid"],$start_time,$end_time,$item["account"],-2);
        //                     if($revisit_num <2){
        //                         $revisit_reword_per -=0.05;
        //                     }

        //                 }else{
        //                     $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val["userid"],$month_half,$end_time,$item["account"],-2);
        //                     if($revisit_num <1){
        //                         $revisit_reword_per -=0.05;
        //                     }

        //                 }
        //             }
        //             if($revisit_reword_per <=0){
        //                 break;
        //             }

            
        //         }

        //     }

        //     if($revisit_reword_per>0){
        //         //检查停课学生回访状态
        //         $stop_student_list = $item["stop_student_list"];
        //         if($stop_student_list){
        //             $stop_student_arr = json_decode($stop_student_list,true);
        //             foreach($stop_student_arr as $val){
        //                 //先检查是否是本月才开始上课的(获取各科目常规课最早上课时间)
        //                 $first_regular_lesson_time = $task->t_lesson_info_b3->get_stu_first_regular_lesson_time($val);
        //                 $assign_time = $task->t_student_info->get_ass_assign_time($val);                        

        //                 if($first_regular_lesson_time>0 && $first_regular_lesson_time<$month_half){
        //                     if($assign_time < $month_half){
        //                         $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val,$start_time,$end_time,$item["account"],-2);
        //                         if($revisit_num <2){
        //                             $revisit_reword_per -=0.05;
        //                         }
        //                     }elseif($assign_time>=$month_half && $assign_time <$end_time){                            
        //                         $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val,$month_half,$end_time,$item["account"],-2);
        //                         if($revisit_num <1){
        //                             $revisit_reword_per -=0.05;
        //                         }

        //                     }
        //                 }elseif($first_regular_lesson_time>0 && $first_regular_lesson_time>=$month_half &&  $first_regular_lesson_time<=$end_time){                       
        //                     $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val,$month_half,$end_time,$item["account"],-2);
        //                     if($revisit_num <1){
        //                         $revisit_reword_per -=0.05;
        //                     }

        //                 }
        //                 if($revisit_reword_per <=0){
        //                     break;
        //                 }



                    
        //             }
        //         }

        //     }
        //     if($revisit_reword_per <0){
        //         $revisit_reword_per=0;
        //     }
        //     $item["revisit_reword_per"] = $revisit_reword_per;
           

        //     //课时消耗达成率
        //     $read_student_list = @$last_ass_month[$k]["userid_list"];//改为在读人数
        //     // $registered_student_list = @$item["registered_student_list"];//先以10月份数据代替
        //     if( $read_student_list){
        //         $read_student_arr = json_decode( $read_student_list,true);
        //         $last_stu_num = count($read_student_arr);//月初在读人员数
        //         $last_lesson_total = $task->t_week_regular_course->get_lesson_count_all($read_student_arr);//月初周总课时消耗数
        //         $estimate_month_lesson_count =$n*$last_lesson_total/$last_stu_num;
        //     }else{
        //         $read_student_arr=[];      
        //         $estimate_month_lesson_count =100;
        //     }

        //     //得到单位学员平均课时数完成率
        //     $seller_lesson_count =$seller_stu_num=0;
        //     foreach($lesson_count_list as $p_item){
        //         $seller_lesson_count += @$p_item[$k]["lesson_count"]; 
        //         $seller_stu_num += @$p_item[$k]["user_count"]; 
        //     }
        //     $seller_stu_num = $seller_stu_num/$n;
        //     // $seller_stu_num = $item["seller_week_stu_num"];
        //     // $seller_lesson_count = $item["seller_month_lesson_count"];
        //     // $estimate_month_lesson_count = $item["estimate_month_lesson_count"];
        //     if(empty($seller_stu_num)){
        //         $lesson_count_finish_per=0;
        //     }else{
        //         $lesson_count_finish_per= round($seller_lesson_count/$seller_stu_num/$estimate_month_lesson_count*100,2);
        //     }

        //     //算出kpi中课时消耗达成率的情况
        //     if($lesson_count_finish_per>=70){
        //         $kpi_lesson_count_finish_per = 0.4;
        //     }else{
        //         $kpi_lesson_count_finish_per=0;
        //     }

        //     $item["kpi_lesson_count_finish_per"]=$kpi_lesson_count_finish_per;
        //     $task->t_month_ass_student_info->get_field_update_arr($k,$start_time,1,[
        //         "revisit_reword_per"  =>$revisit_reword_per*100,
        //         "kpi_lesson_count_finish_per" =>$kpi_lesson_count_finish_per*100,
        //         "estimate_month_lesson_count" =>$estimate_month_lesson_count,
        //         "seller_month_lesson_count"   =>$seller_lesson_count,
        //         "seller_week_stu_num"         =>$seller_stu_num
        //     ]);

        //     /*课程消耗奖金*/
        //     // if($lesson_count_finish_per>=120){
        //     //     $lesson_count_finish_reword=$seller_lesson_count*1.2;
        //     // }elseif($lesson_count_finish_per>=100 ){
        //     //     $lesson_count_finish_reword=$seller_lesson_count*1;
        //     // }elseif($lesson_count_finish_per>=75 ){
        //     //     $lesson_count_finish_reword=$seller_lesson_count*0.8;
        //     // }elseif($lesson_count_finish_per>=50 ){
        //     //     $lesson_count_finish_reword=$seller_lesson_count*0.5;
        //     // }else{
        //     //     $lesson_count_finish_reword=0;
        //     // }

        //     // $item["lesson_count_finish_reword"]=$lesson_count_finish_reword;

        //     /*续费提成奖金*/
        //     //计算助教相关退费

        //     // $renw_target = @$last_ass_month[$k]["warning_student"]*0.8*7000*100;
        //     // $renw_price = $item["renw_price"]+$item["tran_price"]-$item["ass_refund_money"];
        //     // $renw_per = $renw_target>0?( $renw_price/$renw_target*100):0;
        //     // $renw_reword = 0;
        //     // if($renw_per>=120){
        //     //     $renw_reword = $renw_target*0.04+($renw_price-$renw_target)*0.05;
        //     // }elseif($renw_per>=100){
        //     //     $renw_reword = $renw_target*0.04;
        //     // }elseif($renw_per>=75){
        //     //     $renw_reword = $renw_target*0.028;
        //     // }elseif($renw_per>=50){
        //     //     $renw_reword = $renw_target*0.02;
        //     // }elseif($renw_per>=30){
        //     //     $renw_reword = $renw_target*0.012;
        //     // }

        //     // $item["renw_reword"] =  $renw_reword;


        //     /*转介绍奖金*/
        //     //转介绍个数
        //     // $cc_tran_num = $item["cc_tran_num"];
        //     // $cc_tran_num_reword=0;
        //     // if($cc_tran_num>5){
        //     //     $cc_tran_num_reword = $cc_tran_num*50000;
        //     // }elseif($cc_tran_num>=3){
        //     //     $cc_tran_num_reword = $cc_tran_num*30000;
        //     // }else{
        //     //     $cc_tran_num_reword = $cc_tran_num*20000;
        //     // }
        //     // //转介绍金额提成
        //     // $cc_tran_price_reword = $item["cc_tran_money"]*0.02;

        //     // $cc_tran_reword = $cc_tran_num_reword+$cc_tran_price_reword;
        //     // $item["cc_tran_reword"] = $cc_tran_reword;


        //     // /*退费20%、停课15%、结课未续费5%*/
        //     // //退费
        //     // $ass_refund_money = $item["ass_refund_money"];            
        //     // $ass_renw_money = $item["renw_price"]+$item["tran_price"];
        //     // $refund_per = $ass_renw_money>0?$ass_refund_money/$ass_renw_money:0;
        //     // if($refund_per<=0.05){
        //     //     $refund_reword_per = 0.2;
        //     // }else{
        //     //     $refund_reword_per = 0;
        //     // }
        //     // $item["refund_reword_per"]=$refund_reword_per;

        //     // //停课
        //     // $all_stu_num = $item["all_ass_stu_num"];//所有学员
        //     // $all_stop_num = $item["stop_student"];//停课学员
        //     // $stop_per = $all_stu_num>0?($all_stop_num/$all_stu_num):0;
        //     // if($all_stu_num>=100 && $stop_per<=0.06){
        //     //     $stop_reword_per = 0.15;
        //     // }elseif($all_stu_num>=50 && $stop_per<=0.05 && $all_stu_num<100){
        //     //     $stop_reword_per = 0.15;
        //     // }elseif( $all_stu_num<50 && $stop_per<=0.04){
        //     //     $stop_reword_per = 0.15;
        //     // }else{
        //     //     $stop_reword_per = 0;
        //     // }
        //     // $item["stop_reword_per"]=$stop_reword_per;

        //     // //结课未续费
        //     // $end_no_renw_num = $item["end_no_renw_num"];
        //     // $end_no_renw_num = $item["end_stu_num"];//先以10月份当月结课学生数代替
        //     // $end_no_renw_per = $all_stu_num>0?($end_no_renw_num/$all_stu_num):0;
        //     // if($end_no_renw_per <=0.08){
        //     //     $end_no_renw_reword_per = 0.05;
        //     // }else{
        //     //     $end_no_renw_reword_per = 0;
        //     // }
        //     // $item["end_no_renw_reword_per"]=$end_no_renw_reword_per;
            

        //     // $item["revisit_reword"] = $item["revisit_reword_per"]*1500/100;
        //     // $item["kpi_lesson_count_finish_reword"] = $item["kpi_lesson_count_finish_per"]*1500/100;
        //     // $item["refund_reword"] = $item["refund_reword_per"]*1500;
        //     // $item["stop_reword"] = $item["stop_reword_per"]*1500;
        //     // $item["end_no_renw_reword"] = $item["end_no_renw_reword_per"]*1500;
        //     // $item["lesson_count_finish_reword"] = $item["lesson_count_finish_reword"]/100;
        //     // $item["renw_reword"] = $item["renw_reword"]/100;
        //     // $item["cc_tran_reword"] = $item["cc_tran_reword"]/100;
        //     // $item["all_reword"] =  $item["revisit_reword"]+$item["kpi_lesson_count_finish_reword"]+$item["refund_reword"]+$item["stop_reword"]+$item["end_no_renw_reword"]+ $item["lesson_count_finish_reword"]+$item["renw_reword"]+ $item["cc_tran_reword"];
            
        // }
        // dd(11111111);










        // //哈哈哈
      

        

        $time = strtotime(date("Y-m-d",time()));        
        $user_stop = $task->t_student_info->get_no_auto_stop_stu_list($time);
        foreach($user_stop as $item){
            $check_lesson = $task->t_lesson_info_b2->check_have_regular_lesson($time,time(),$item["userid"]);
            if($check_lesson==1){
                $task->t_student_info->get_student_type_update($item["userid"],0);
                $task->t_student_info->field_update_list($item["userid"],[
                   "is_auto_set_type_flag" =>0 
                ]);
                $task->t_student_type_change_list->row_insert([
                    "userid"    =>$item["userid"],
                    "add_time"  =>time(),
                    "type_before" =>$item["type"],
                    "type_cur"    =>0,
                    "change_type" =>1,
                    "adminid"     =>0,
                    "reason"      =>"系统更新"
                ]);

            }
        }

        $ret_student_end_info = $task->t_student_info->get_student_list_end_id(-1);
        foreach($ret_student_end_info as $val){
            $task->t_student_info->get_student_type_update($val["userid"],1);
            $task->t_student_type_change_list->row_insert([
                "userid"    =>$val["userid"],
                "add_time"  =>time(),
                "type_before" =>$val["type"],
                "type_cur"    =>1,
                "change_type" =>1,
                "adminid"     =>0,
                "reason"      =>"系统更新"
            ]);
            $task->delete_teacher_regular_lesson($val["userid"],1);
            $refund_time = $task->t_order_refund->get_last_apply_time($val["userid"]);
            $last_lesson_time = $task->t_student_info->get_last_lesson_time($val["userid"]);
            
            if(empty($refund_time) || $refund_time>$last_lesson_time){
                $assistantid = $task->t_student_info->get_assistantid($val["userid"]);
                $adminid = $task->t_assistant_info->get_adminid_by_assistand($assistantid);
                $month = strtotime(date("Y-m-01",time()));
                $ass_info = $task->t_month_ass_student_info->get_ass_month_info($month,$adminid,1);
                if($ass_info){
                    $num = @$ass_info[$adminid]["end_no_renw_num"]+1;
                    $task->t_month_ass_student_info->get_field_update_arr($adminid,$month,1,[
                        "end_no_renw_num" =>$num
                    ]);

                }else{
                    $task->t_month_ass_student_info->row_insert([
                        "adminid" =>$adminid,
                        "month"   =>$month,
                        "end_no_renw_num"=>1,
                        "kpi_type" =>1
                    ]);
                }

 
            }

        }

    }
}
