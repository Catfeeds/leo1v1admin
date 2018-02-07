<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class get_ass_stu_info_update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_ass_stu_info_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '助教学生信息每日更新';

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
        $task=new \App\Console\Tasks\TaskController();      

        // $time = strtotime("2017-12-01");    
        // $list = $task->t_month_ass_student_info->get_ass_month_info($time);
        // foreach($list as &$val){
        //     $val["month"]=$val["month"]+100;
        //     unset($val["assistantid"]);
        //     $task->t_month_ass_student_info->row_insert($val);
        // }
        // dd($list);


        //更新助教信息
        $start_time = strtotime(date("Y-m-01",time()-86400));
        $end_time = strtotime(date("Y-m-01",$start_time+40*86400));
       
        //$start_time = strtotime(date("2017-08-01"));
        // $end_time = strtotime(date("2017-09-01"));

        $last_month = strtotime(date('Y-m-01',$start_time-100));
        $ass_last_month = $task->t_month_ass_student_info->get_ass_month_info($last_month);
        $ass_current_month = $task->t_month_ass_student_info->get_ass_month_info($start_time);
      
        $lesson_count_list_old=[];       

        foreach($ass_last_month as $ks=>&$vs){
            $userid_list = json_decode($vs["userid_list"],true);
            if(empty($userid_list)){
                $userid_list=[];
            }
            if(date("m",$start_time) == "06"){
                foreach($userid_list as $kq=>$qq){
                    $grade = $task->t_student_info->get_grade($qq);
                    if(in_array($grade,[203,303])){
                        unset($userid_list[$kq]);
                        $vs["read_student"]--;
                    }
                }
            }

            $lesson_count_list_old[$ks]=$task->t_manager_info->get_assistant_lesson_count_old($start_time,$end_time,$ks,$userid_list);
        }

      
        $ass_list = $task->t_manager_info->get_adminid_list_by_account_role_new(1,$start_time,0);

        $warning_list = $task->t_student_info->get_warning_stu_list();
        
        foreach($warning_list as $item){
            @$ass_list[$item["uid"]]["warning_student"]++;
        }

        $stu_info_all = $task->t_student_info->get_ass_stu_info_new();
        
        $userid_list = $task->t_student_info->get_read_student_ass_info();//在读学员名单

        $registered_userid_list = $task->t_student_info->get_read_student_ass_info(-2);//在册学员名单
        $stop_userid_list = $task->t_student_info->get_read_student_ass_info(2);//停课学员名单
       
        $month_stop_all =  $task->t_student_info->get_ass_month_stop_info_new($start_time,$end_time);
        $lesson_count_list = $task->t_manager_info->get_assistant_lesson_count_info($start_time,$end_time); 

        // $lesson_count_list_old = $task->t_manager_info->get_assistant_lesson_count_info_old($start_time,$end_time);        
                   
        $assistant_renew_list = $task->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time);

        //续费金额 分期按80%计算,按新方法获取
        $ass_renw_money = $task->t_manager_info->get_ass_renw_money_new($start_time,$end_time);

        //cc签单助教转介绍数据
        $cc_tran_order = $task->t_manager_info->get_cc_tran_origin_order_info($start_time,$end_time);

        //扩课成功数
        $kk_suc= $task->t_test_lesson_subject->get_ass_kk_tongji_info($start_time,$end_time);
        $refund_info = $task->t_order_refund->get_ass_refund_info($start_time,$end_time);

        $student_finish = $task->t_student_info->get_ass_first_revisit_info_finish($start_time,$end_time);//结课学生数
        $student_finish_detail = [];
        foreach ($student_finish as $key => $value) {  
            $student_finish_detail[$value['uid']] = $value['num']; 
        }

        $student_all = $task->t_student_info->get_ass_first_revisit_info();//在册学生数
        $student_all_detail = [];
        foreach ($student_all as $key => $value) {  
            $student_all_detail[$value['uid']] = $value['num']; 
        }


        $lesson_money_list = $task->t_manager_info->get_assistant_lesson_money_info($start_time,$end_time);
        $new_info          = $task->t_student_info->get_new_assign_stu_info($start_time,$end_time);
        $end_stu_info_new  = $task->t_student_info->get_end_class_stu_info($start_time,$end_time);
        $lesson_info       = $task->t_lesson_info_b2->get_ass_stu_lesson_list($start_time,$end_time);


        //主管2数据
        $month_middle = $start_time+15*86400;
        $lesson_list_first = $task->t_lesson_info_b2->get_all_ass_stu_lesson_info($start_time,$month_middle);
        $userid_list_first=[];
        $userid_list_first_all=[];
        foreach($lesson_list_first as $item1){
            $userid_list_first[$item1["uid"]][]=$item1["userid"];
            $userid_list_first_all[] = $item1["userid"];
        }
        $xq_revisit_first = $task->t_revisit_info->get_ass_xq_revisit_info_new($start_time,$month_middle,$userid_list_first_all,false);

        $lesson_list_second = $task->t_lesson_info_b2->get_all_ass_stu_lesson_info($month_middle,$end_time);
        $userid_list_second=[];
        $userid_list_second_all=[];
        foreach($lesson_list_second as $item2){
            $userid_list_second[$item2["uid"]][]=$item2["userid"];
            $userid_list_second_all[] = $item2["userid"];
        }

        $xq_revisit_second = $task->t_revisit_info->get_ass_xq_revisit_info_new($month_middle,$end_time,$userid_list_second_all,false);

        $new_info = $task->t_student_info->get_ass_new_stu_first_revisit_info($start_time,$end_time);
        $new_revisit=[];
        foreach($new_info as $vu){
            @$new_revisit[$vu["uid"]]["new_num"]++;
            if($vu["revisit_time"]>0){
                @$new_revisit[$vu["uid"]]["first_num"]++;
            }else{
                @$new_revisit[$vu["uid"]]["un_first_num"]++;
            }
        }

        $student_finish = $task->t_student_info->get_ass_first_revisit_info_finish($start_time,$end_time);//结课学生数
        $student_finish_detail = [];
        foreach ($student_finish as $key => $value) {  
            $student_finish_detail[$value['uid']] = $value['num']; 
        }
        $refund_score = $task->get_ass_refund_score($start_time,$end_time);

        $lesson_money_all = $task->t_manager_info->get_assistant_lesson_money_info_all($start_time,$end_time);
        $lesson_count_all = $task->t_manager_info->get_assistant_lesson_count_info_all($start_time,$end_time);
        $lesson_price_avg = !empty($lesson_count_all)?$lesson_money_all/$lesson_count_all:0;

        //第二版薪资新增
        list($performance_cr_new_list,$performance_cr_renew_list,$performance_cc_tran_list)= $this->get_ass_order_list_performance($start_time,$end_time);//新版薪资 助教续费新签合同/销售转介绍合同 金额/个数计算
        list($first_week,$last_week,$n) = $task->get_seller_week_info($start_time, $end_time);//销售月拆解       
        $registered_student_num=$this->get_register_student_list($first_week,$n);//销售月助教在册学生总数获取
        $seller_month_lesson_count = $task->t_manager_info->get_assistant_lesson_count_info($first_week,$last_week+7*86400);//销售月总课时
        $first_subject_list = $this->get_ass_stu_first_lesson_subject_info($start_time,$end_time);//生成助教学生第一次课信息(按科目)

        list($first_week_next,$last_week_next,$n_next) = $task->get_seller_week_info($end_time, strtotime("+1 months",$end_time));//销售月拆解     
        $seller_month_lesson_count_next = $task->t_manager_info->get_assistant_lesson_count_info($first_week_next,$last_week_next+7*86400);//销售月总课时

        foreach($ass_list as $k=>$item){
            if(!isset($item["warning_student"])){
                $item["warning_student"]=0;
            }
            $item["read_student"]          = @$stu_info_all[$k]["read_count"];
            $item["stop_student"]          = @$stu_info_all[$k]["stop_count"];
            $item["all_student"]           = @$stu_info_all[$k]["all_count"];
            $item["month_stop_student"]    = @$month_stop_student[$k]["num"];
            $item["lesson_total"]          = @$lesson_count_list[$k]["lesson_count"];
            $item["lesson_ratio"]          = !empty(@$ass_last_month[$k]["read_student"])?round(@$lesson_count_list_old[$k]/@$ass_last_month[$k]["read_student"]/100,2):0;

            // $item["renw_price"]            = @$assistant_renew_list[$k]["renw_price"];
            $item["renw_price"]            = @$ass_renw_money[$k]["money"];
            $item["all_price"]             = @$assistant_renew_list[$k]["all_price"];
            $item["tran_price"]            = @$assistant_renew_list[$k]["tran_price"];
            $item["renw_student"]          = @$assistant_renew_list[$k]["all_student"];

            $item["kk_num"]                = @$kk_suc[$k]["lesson_count"];
            $item["userid_list"]           = @$userid_list[$k];
            $item["refund_student"]        = @$refund_info[$k]["num"];
            $item["new_refund_money"]      = @$refund_info[$k]["new_price"];
            $item["renw_refund_money"]     = @$refund_info[$k]["renw_price"];
            $item["lesson_total_old"]      = @$lesson_count_list_old[$k];
            $item["read_student_new"]      = @$lesson_count_list[$k]["user_count"]; //上课学生-new
            $item["all_student_new"]       = @$student_all_detail[$k] + @$student_finish_detail[$k]; //在册学员-new

            //new add
            $item["lesson_money"]          = @$lesson_money_list[$k]["lesson_price"];//课耗收入
            $item["new_student"]           = isset($new_info[$k]["num"])?$new_info[$k]["num"]:0;//新签人数
            $item["new_lesson_count"]      = isset($new_info[$k]["lesson_count"])?$new_info[$k]["lesson_count"]:0;//购买课时
            $item["end_stu_num"]           = isset($end_stu_info_new[$k]["num"])?$end_stu_info_new[$k]["num"]:0;//结课学生
            $item["lesson_student"]        = isset($lesson_info[$k]["user_count"])?$lesson_info[$k]["user_count"]:0;//在读学生

            //cc签单助教转介绍数据

            $item["cc_tran_num"] =  @$cc_tran_order[$k]["stu_num"];
            $item["cc_tran_money"] =  @$cc_tran_order[$k]["all_price"];
            //主管2.0数据
            $item["userid_list_first"] = isset($userid_list_first[$k])?$userid_list_first[$k]:[];
            $item["userid_list_first_target"] = count($item["userid_list_first"]);
            $item["userid_list_first_count"] = @$xq_revisit_first[$k]["num"];
            $item["userid_list_second"] = isset($userid_list_second[$k])?$userid_list_second[$k]:[];
            $item["userid_list_second_target"] = count($item["userid_list_second"]);
            $item["userid_list_second_count"] = @$xq_revisit_second[$k]["num"];
            $item["revisit_target"] = $item["userid_list_first_target"]+$item["userid_list_second_target"];
            $item["revisit_real"] = $item["userid_list_first_count"]+$item["userid_list_second_count"];
            $item["tran_num"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["tran_num"]:0;
            // $item["new_num"] = isset($new_revisit[$k])?$new_revisit[$k]["new_num"]:0;
            $item["first_revisit_num"] = isset($new_revisit[$k]["first_num"])?$new_revisit[$k]["first_num"]:0;
            $item["un_first_revisit_num"] = isset($new_revisit[$k]["un_first_num"])?$new_revisit[$k]["un_first_num"]:0;
            // $item["refund_score"] = round((10-@$refund_score[$k])>=0?10-@$refund_score[$k]:0,2);
            $item["refund_score"] = (round(@$refund_score[$k],2))*100;
            $item["lesson_price_avg"] = (round(@$lesson_count_list[$k]["lesson_count"]*$lesson_price_avg/100,2))*100;
            $item["student_finish"] = isset($student_finish_detail[$k])?$student_finish_detail[$k]:0;
            
            $item["stop_student_list"] = @$stop_userid_list[$k];//月末停课学生名单
            
            $item["registered_student_list"] = @$registered_userid_list[$k];//月末在册学生名单
            $item["all_ass_stu_num"]         = @$stu_info_all[$k]["all_stu_num"];//所有学员数量

            $list_refund = $task->t_order_refund->get_ass_refund_info_new($start_time,$end_time,$k);
            $refund_money=0;
            foreach($list_refund as $vall){
                if($vall["value"]=="助教部" && $vall["score"]>0){
                    $refund_money +=$vall["real_refund"];
                }
                
            }

            $first_lesson_stu_list =  @$first_subject_list[$k]?$first_subject_list[$k]:[];//生成助教学生第一次课信息(按科目)
            if($first_lesson_stu_list){
                $item["first_lesson_stu_list"] = json_encode($first_lesson_stu_list);
            }else{
                $item["first_lesson_stu_list"]="";
            }               
            $read_student_list = $item["userid_list"];
            $registered_student_list = $item["registered_student_list"];

            $item["revisit_reword_per"] = $this->get_ass_revisit_reword_value($item["account"],$k,$start_time,$end_time,$item["first_lesson_stu_list"],$read_student_list,$registered_student_list);//回访绩效比例
            $item["seller_week_stu_num"] = round(@$registered_student_num[$k]/$n,1);//销售月周平均学生数
            $item["seller_month_lesson_count"] = @$seller_month_lesson_count[$k]["lesson_count"];//销售月总课时
            $registered_student_list_last = @$ass_last_month[$k]["registered_student_list"];
            list($item["kpi_lesson_count_finish_per"],$estimate_month_lesson_count)= $this->get_seller_month_lesson_count_use_info($registered_student_list_last,$item["seller_week_stu_num"],$n,$item["seller_month_lesson_count"]);
            $item["performance_cc_tran_num"] = @$performance_cc_tran_list[$k]["num"];
            $item["performance_cc_tran_money"] = @$performance_cc_tran_list[$k]["money"];
            $item["performance_cr_renew_num"] = @$performance_cr_renew_list[$k]["num"];
            $item["performance_cr_renew_money"] = @$performance_cr_renew_list[$k]["money"];
            $item["performance_cr_new_num"] = @$performance_cr_new_list[$k]["num"];
            $item["performance_cr_new_money"] = @$performance_cr_new_list[$k]["money"];

            //月初预估课时数据补充
            $item["estimate_month_lesson_count"] = @$ass_current_month[$k]["estimate_month_lesson_count"];
            if(empty($item["estimate_month_lesson_count"])){
                if($registered_student_list_last){
                    $item["estimate_month_lesson_count"]= $estimate_month_lesson_count;
                }else{
                    $item["estimate_month_lesson_count"]=100;
                }
            }

            $update_arr =  [
                "first_lesson_stu_list" =>$item["first_lesson_stu_list"],
                "revisit_reword_per"    =>$item["revisit_reword_per"]*100,
                "seller_week_stu_num"   =>$item["seller_week_stu_num"],
                "seller_month_lesson_count"=>$item["seller_month_lesson_count"],
                "kpi_lesson_count_finish_per"=>$item["kpi_lesson_count_finish_per"]*100,
                "estimate_month_lesson_count" =>$item["estimate_month_lesson_count"],//临时更新一次(月初生成)
                "performance_cc_tran_num"  =>$item["performance_cc_tran_num"],
                "performance_cc_tran_money"=>$item["performance_cc_tran_money"],
                "performance_cr_renew_num" =>$item["performance_cr_renew_num"],
                "performance_cr_renew_money" =>$item["performance_cr_renew_money"],
                "performance_cr_new_num" =>$item["performance_cr_new_num"],
                "performance_cr_new_money" =>$item["performance_cr_new_money"],
                "read_student"          =>$item["read_student"],
                "stop_student"          =>$item["stop_student"],
                "all_student"           =>$item["all_student"],
                "month_stop_student"    =>$item["month_stop_student"],
                "warning_student"       =>$item["warning_student"],
                "lesson_total"          =>$item["lesson_total"],
                "lesson_ratio"          =>$item["lesson_ratio"],
                "renw_price"            =>$item["renw_price"],
                "renw_student"          =>$item["renw_student"],
                "tran_price"            =>$item["tran_price"],
                "kk_num"                =>$item["kk_num"],
                "userid_list"           =>$item["userid_list"],
                "refund_student"        =>$item["refund_student"],
                "new_refund_money"      =>$item["new_refund_money"],
                "renw_refund_money"     =>$item["renw_refund_money"],
                "lesson_total_old"      =>$item["lesson_total_old"],
                "read_student_new"      =>$item["read_student_new"],
                "all_student_new"       =>$item["all_student_new"],

                "lesson_money"          =>$item["lesson_money"],
                "new_student"           =>$item["new_student"],
                "new_lesson_count"      =>$item["new_lesson_count"],
                "end_stu_num"           =>$item["end_stu_num"],
                "lesson_student"        =>$item["lesson_student"],
                "revisit_target"        =>$item["revisit_target"],
                "revisit_real"          => $item["revisit_real"],
                "first_revisit_num"     => $item["first_revisit_num"],
                "un_first_revisit_num"  => $item["un_first_revisit_num"],
                "refund_score"          => $item["refund_score"],
                "lesson_price_avg"      => $item["lesson_price_avg"],
                "student_finsh"         =>$item["student_finish"],
                "tran_num"              =>$item["tran_num"],
                "cc_tran_num"           =>$item["cc_tran_num"],
                "cc_tran_money"           =>$item["cc_tran_money"],

                "stop_student_list"       =>$item["stop_student_list"],
                "registered_student_list" =>$item["registered_student_list"],
                "all_ass_stu_num"         =>$item["all_ass_stu_num"],
                "ass_refund_money"        => $refund_money,
            ];
            $adminid_exist = $task->t_month_ass_student_info->get_ass_month_info($start_time,$k,1);
            if($adminid_exist){
                $task->t_month_ass_student_info->get_field_update_arr($k,$start_time,1,$update_arr);
            }else{
                $update_arr["adminid"] =$k;
                $update_arr["month"]   =$start_time;
                $update_arr["kpi_type"]   =1;
                $task->t_month_ass_student_info->row_insert($update_arr);
            }

            if(date("d",time())=="01"){
                $lesson_target     = $task->t_ass_group_target->get_rate_target($start_time);

                /*暂停使用*/
                // //add 课耗活动-------------------------------------------------------------------------------
                // $item["lesson_ratio_month"]          = !empty(@$item["read_student_new"])?round(@$item["lesson_total"]/@$item["read_student_new"]/100,3):0; //课程系数-新版 当月课耗/当月上课人数
                // $item["effective_student"] = @$item["read_student_new"]; //带学生人数(上课学生数)

                // //ca
                // $assign_lesson  = 0;
                // if($item['lesson_ratio_month'] < $lesson_target){
                //     $assign_lesson = 0;
                // }elseif($item['lesson_ratio_month'] < $lesson_target*1.1){
                //     $assign_lesson = 900;  //3
                // }elseif($item['lesson_ratio_month'] < $lesson_target*1.2){
                //     $assign_lesson = 1500; //5
                // }elseif($item['lesson_ratio_month'] < $lesson_target*1.3){
                //     $assign_lesson = 2100; //7
                // }else{
                //     $assign_lesson = 2700; //9
                // }
                // if($item['effective_student'] < 30){ 
                //     $assign_lesson = $assign_lesson * 0.2;
                // }elseif ($item['effective_student'] < 50) {
                //     $assign_lesson = $assign_lesson * 0.4;
                // }elseif ($item['effective_student'] < 70) {
                //     $assign_lesson = $assign_lesson * 0.6;
                // }elseif ($item['effective_student'] < 90) {
                //     $assign_lesson = $assign_lesson * 0.8;
                // }

                // //update assign_lesson in t_month_ass_student_info 
                // $update_arr =  [
                //     "assign_lesson"              =>$assign_lesson
                // ];
                // $task->t_month_ass_student_info->get_field_update_arr($k,$start_time,1,$update_arr);

                // //get assistantid
                // $ret_assistantid = $task->t_manager_info->get_assistant_id($k);
                // //get assign_lesson_count
                // $assign_lesson_count = $task->t_assistant_info->get_assign_lesson_count($ret_assistantid);
                // if($assign_lesson_count == ''){
                //     $assign_lesson_count = 0;
                // }

                // //update assign_lesson_count
                // $task->t_assistant_info->set_assign_lesson_count($ret_assistantid,$assign_lesson_count,$assign_lesson);
                // //end---------------------------------------------



                $month = strtotime(date("Y-m-01",time()));
                $userid_arr = @$userid_list[$k];
                $userid_list_last = json_decode($userid_arr,true);
                if(empty($userid_list_last)){
                    $userid_list_last=[];
                }

                $read_student_last = @$stu_info_all[$k]["read_count"];

                if(date("m",time()) == "06"){
                    foreach($userid_list_last as $kq=>$qq){
                        $grade = $task->t_student_info->get_grade($qq);
                        if(in_array($grade,[203,303])){
                            unset($userid_list_last[$kq]);
                            $read_student_last--;
                        }
                    }
                }
                $userid_list_last = json_encode($userid_list_last);


                //获取该月有几周
                $next_month = strtotime("+1 months",$month);
                list($first_week,$last_week, $number) = $task->get_seller_week_info($month,$next_month);//销售月拆解                 


                //月初周总课时消耗数
                $registered_student_list=[];
                if($item["registered_student_list"]){
                    $registered_student_list = json_decode($item["registered_student_list"],true);
                }
                if(!empty($registered_student_list)){
                    $last_stu_num = count($registered_student_list);//月初在读人员数
                    $last_lesson_total = $task->t_week_regular_course->get_lesson_count_all($registered_student_list);//月初周总课时消耗数
                    $estimate_month_lesson_count =$number*$last_lesson_total/$last_stu_num;
                }else{
                    $read_student_arr=[];      
                    $estimate_month_lesson_count =100;
                }

                $adminid_exist2 = $task->t_month_ass_student_info->get_ass_month_info($month,$k,1);
                if($adminid_exist2){                    
                    $month_arr = [
                        "read_student_last"     =>$read_student_last,
                        "userid_list_last"      =>$userid_list_last,
                        "estimate_month_lesson_count" =>$estimate_month_lesson_count
                    ];
                    $task->t_month_ass_student_info->get_field_update_arr($k,$month,1,$month_arr);                    
                }else{
                    $task->t_month_ass_student_info->row_insert([
                        "adminid"               =>$k,
                        "month"                 =>$month,
                        "read_student_last"     =>$read_student_last,
                        "userid_list_last"      =>$userid_list_last,
                        "kpi_type"              =>1,
                        "estimate_month_lesson_count" =>$estimate_month_lesson_count,
                    ]);

                }
            }             
        }

        if(date("d",time())=="01"){
            $warning_stu_list=[];
            foreach($warning_list as $ss){                   
                @$warning_stu_list[$ss["uid"]]["warning_student"]++;
                @$warning_stu_list[$ss["uid"]]["userid_list"][]=$ss["userid"];
            }

            $ass_list = $task->t_manager_info->get_adminid_list_by_account_role(1);
            foreach($ass_list as $ki=>$val){
                if(isset($warning_stu_list[$ki])){
                    $warning_student = @$warning_stu_list[$ki]["warning_student"];
                    $userid_list = json_encode($warning_stu_list[$ki]["userid_list"]);
                }else{
                    $warning_student =0;
                    $userid_list=[];
                    $userid_list = json_encode($userid_list);
                }
                $id =$task->t_ass_weekly_info->get_id_by_unique_record($ki,$end_time,2);
                if($id >0){
                        
                }else{
                    $task->t_ass_weekly_info->row_insert([
                        "adminid"   =>$ki,
                        "week"      =>$end_time,
                        "warning_student" =>$warning_student,
                        "warning_student_list" =>$userid_list,
                        "time_type"    =>2
                    ]);
                }
                    
            }

        }

        //每月1日,2日执行 回访以及销售月课时计算
        if(date("d",time()) <=10){
            $last_m = strtotime("-1 months",time());
            $start_time = strtotime(date("Y-m-01",$last_m));        
            $end_time = strtotime("+1 months",$start_time);        


            $month_half = $start_time+15*86400;
            $last_month = strtotime("-1 month",$start_time);
            $ass_month= $task->t_month_ass_student_info->get_ass_month_info_payroll($start_time);
            $last_ass_month= $task->t_month_ass_student_info->get_ass_month_info_payroll($last_month);

            list($performance_cr_new_list,$performance_cr_renew_list,$performance_cc_tran_list)= $this->get_ass_order_list_performance($start_time,$end_time);//新版薪资 助教续费新签合同/销售转介绍合同 金额/个数计算
            list($first_week,$last_week,$n) = $task->get_seller_week_info($start_time, $end_time);//销售月拆解       
          
            if(date("d",time())=="01" || date("d",time())=="02"){
                $registered_student_num=$this->get_register_student_list($first_week,$n);//销售月助教在册学生总数获取
                $seller_month_lesson_count = $task->t_manager_info->get_assistant_lesson_count_info($first_week,$last_week+7*86400);//销售月总课时
                $first_subject_list = $this->get_ass_stu_first_lesson_subject_info($start_time,$end_time);//生成助教学生第一次课信息(按科目)

                foreach($ass_month as $k=>$tt){
                    $first_lesson_stu_arr = @$first_subject_list[$k]?$first_subject_list[$k]:[];//生成助教学生第一次课信息(按科目)
                    $first_lesson_stu_list="";
                    if($first_lesson_stu_arr){
                        $first_lesson_stu_list = json_encode($first_lesson_stu_arr);
                    }               
                    $read_student_list = $tt["userid_list"];
                    $registered_student_list = $tt["registered_student_list"];
                    $revisit_reword_per = $this->get_ass_revisit_reword_value($tt["account"],$k,$start_time,$end_time,$first_lesson_stu_arr,$read_student_list,$registered_student_list);//回访绩效比例
                    $seller_week_stu_num = round(@$registered_student_num[$k]/$n,1);//销售月周平均学生数
                    $seller_month_lesson_count = @$seller_month_lesson_count[$k]["lesson_count"];//销售月总课时
                    $registered_student_list_last = @$last_ass_month[$k]["registered_student_list"];
                    list($kpi_lesson_count_finish_per,$estimate_month_lesson_count)= $this->get_seller_month_lesson_count_use_info($registered_student_list_last,$seller_week_stu_num,$n,$seller_month_lesson_count);
                   
                    $task->t_month_ass_student_info->get_field_update_arr($k,$start_time,1,[
                        "revisit_reword_per"          =>$revisit_reword_per*100,
                        "kpi_lesson_count_finish_per" =>$kpi_lesson_count_finish_per*100,
                        "seller_month_lesson_count"   =>$seller_month_lesson_count,
                        "seller_week_stu_num"         =>$seller_week_stu_num,
                        "first_lesson_stu_list"       => $first_lesson_stu_list
                    ]);
 
                }
               
                
            }

            foreach($ass_month as $k=>$tt){
               
                $performance_cc_tran_num = @$performance_cc_tran_list[$k]["num"];
                $performance_cc_tran_money= @$performance_cc_tran_list[$k]["money"];
                $performance_cr_renew_num = @$performance_cr_renew_list[$k]["num"];
                $performance_cr_renew_money = @$performance_cr_renew_list[$k]["money"];
                $performance_cr_new_num = @$performance_cr_new_list[$k]["num"];
                $performance_cr_new_money = @$performance_cr_new_list[$k]["money"];
 
                $task->t_month_ass_student_info->get_field_update_arr($k,$start_time,1,[
                    "performance_cc_tran_num"  =>$performance_cc_tran_num,
                    "performance_cc_tran_money"=>$performance_cc_tran_money,
                    "performance_cr_renew_num" =>$performance_cr_renew_num,
                    "performance_cr_renew_money" =>$performance_cr_renew_money,
                    "performance_cr_new_num"    =>$performance_cr_new_num,
                    "performance_cr_new_money" =>$performance_cr_new_money,
                ]);
 
            }

           

                        
            
        }




        
        //update
        $ass_list = $task->t_manager_info->get_adminid_list_by_account_role(1);
        /* $update_time = [
            4=>['start_time' => 1490976000,
                "end_time"   => 1493568000],
            5=>['start_time' => 1493568000,
                "end_time"   => 1496246400],
            6=>['start_time' => 1496246400,
                "end_time"   => 1498838400],
            7=>['start_time' => 1498838400,
                "end_time"   => 1501516800],
            8=>['start_time' => 1501516800,
                "end_time"   => 1504195200],
            9=>['start_time' => 1504195200,
                "end_time"   => 1506787200],
        ];
        foreach ($update_time  as $key => $value) {
            $start_time = $value['start_time'];
            $end_time   = $value['end_time'];*/
        $end_time= strtotime(date("Y-m-01",time()-86400));
        $start_time= strtotime(date("Y-m-01",$end_time-86400));

        $lesson_money_list = $task->t_manager_info->get_assistant_lesson_money_info($start_time,$end_time);
        $new_info          = $task->t_student_info->get_new_assign_stu_info($start_time,$end_time);
        $end_stu_info_new  = $task->t_student_info->get_end_class_stu_info($start_time,$end_time);
        $lesson_info       = $task->t_lesson_info_b2->get_ass_stu_lesson_list($start_time,$end_time);
        $assistant_renew_list = $task->t_manager_info->get_all_assistant_renew_list_new($start_time,$end_time);
        $ass_renw_money = $task->t_manager_info->get_ass_renw_money_new($start_time,$end_time);

        //cc签单助教转介绍数据
        $cc_tran_order = $task->t_manager_info->get_cc_tran_origin_order_info($start_time,$end_time);



        //主管2数据
        $month_middle = $start_time+15*86400;
        $lesson_list_first = $task->t_lesson_info_b2->get_all_ass_stu_lesson_info($start_time,$month_middle);
        $userid_list_first=[];
        $userid_list_first_all=[];
        foreach($lesson_list_first as $item1){
            $userid_list_first[$item1["uid"]][]=$item1["userid"];
            $userid_list_first_all[] = $item1["userid"];
        }
        $xq_revisit_first = $task->t_revisit_info->get_ass_xq_revisit_info_new($start_time,$month_middle,$userid_list_first_all,false);

        $lesson_list_second = $task->t_lesson_info_b2->get_all_ass_stu_lesson_info($month_middle,$end_time);
        $userid_list_second=[];
        $userid_list_second_all=[];
        foreach($lesson_list_second as $item2){
            $userid_list_second[$item2["uid"]][]=$item2["userid"];
            $userid_list_second_all[] = $item2["userid"];
        }

        $xq_revisit_second = $task->t_revisit_info->get_ass_xq_revisit_info_new($month_middle,$end_time,$userid_list_second_all,false);

        $new_info = $task->t_student_info->get_ass_new_stu_first_revisit_info($start_time,$end_time);
        $new_revisit=[];
        foreach($new_info as $vu){
            @$new_revisit[$vu["uid"]]["new_num"]++;
            if($vu["revisit_time"]>0){
                @$new_revisit[$vu["uid"]]["first_num"]++;
            }else{
                @$new_revisit[$vu["uid"]]["un_first_num"]++;
            }
        }



        $student_finish = $task->t_student_info->get_ass_first_revisit_info_finish($start_time,$end_time);//结课学生数
        $student_finish_detail = [];
        foreach ($student_finish as $key => $value) {  
            $student_finish_detail[$value['uid']] = $value['num']; 
        }
        $refund_score = $task->get_ass_refund_score($start_time,$end_time);

        $lesson_money_all = $task->t_manager_info->get_assistant_lesson_money_info_all($start_time,$end_time);
        $lesson_count_all = $task->t_manager_info->get_assistant_lesson_count_info_all($start_time,$end_time);
        $lesson_price_avg = !empty($lesson_count_all)?$lesson_money_all/$lesson_count_all:0;

        foreach($ass_list as $k=>&$item){
            //new add
            $item["lesson_money"]          = @$lesson_money_list[$k]["lesson_price"];//课耗收入
            $item["new_student"]           = isset($new_info[$k]["num"])?$new_info[$k]["num"]:0;//新签人数
            $item["new_lesson_count"]      = isset($new_info[$k]["lesson_count"])?$new_info[$k]["lesson_count"]:0;//购买课时
            $item["end_stu_num"]           = isset($end_stu_info_new[$k]["num"])?$end_stu_info_new[$k]["num"]:0;//结课学生
            $item["lesson_student"]        = isset($lesson_info[$k]["user_count"])?$lesson_info[$k]["user_count"]:0;//在读学生

            if($start_time>= strtotime("2017-10-01")){
                $item["renw_price"]            = @$ass_renw_money[$k]["money"];
            }else{
                $item["renw_price"]            = @$assistant_renew_list[$k]["renw_price"];       
            }

            $item["tran_price"]            = @$assistant_renew_list[$k]["tran_price"];
            $item["renw_student"]          = @$assistant_renew_list[$k]["all_student"];

            //cc签单助教转介绍数据

            $item["cc_tran_num"] =  @$cc_tran_order[$k]["stu_num"];
            $item["cc_tran_money"] =  @$cc_tran_order[$k]["all_price"];


            //主管2.0数据
            $item["userid_list_first"] = isset($userid_list_first[$k])?$userid_list_first[$k]:[];
            $item["userid_list_first_target"] = count($item["userid_list_first"]);
            $item["userid_list_first_count"] = @$xq_revisit_first[$k]["num"];
            $item["userid_list_second"] = isset($userid_list_second[$k])?$userid_list_second[$k]:[];
            $item["userid_list_second_target"] = count($item["userid_list_second"]);
            $item["userid_list_second_count"] = @$xq_revisit_second[$k]["num"];
            $item["revisit_target"] = $item["userid_list_first_target"]+$item["userid_list_second_target"];
            $item["revisit_real"] = $item["userid_list_first_count"]+$item["userid_list_second_count"];
            $item["tran_num"] = isset($assistant_renew_list[$k])?$assistant_renew_list[$k]["tran_num"]:0;
            // $item["new_num"] = isset($new_revisit[$k])?$new_revisit[$k]["new_num"]:0;
            $item["first_revisit_num"] = isset($new_revisit[$k]["first_num"])?$new_revisit[$k]["first_num"]:0;
            $item["un_first_revisit_num"] = isset($new_revisit[$k]["un_first_num"])?$new_revisit[$k]["un_first_num"]:0;
            // $item["refund_score"] = round((10-@$refund_score[$k])>=0?10-@$refund_score[$k]:0,2);
            $item["refund_score"] = (round(@$refund_score[$k],2))*100;
            $item["lesson_price_avg"] = (round(@$lesson_count_list[$k]["lesson_count"]*$lesson_price_avg/100,2))*100;
            $item["student_finish"] = isset($student_finish_detail[$k])?$student_finish_detail[$k]:0;


              


            $adminid_exist = $task->t_month_ass_student_info->get_ass_month_info($start_time,$k,1);
            if($adminid_exist){
                $update_arr =  [
                    // "lesson_money"          =>$item["lesson_money"],
                    "new_student"           =>$item["new_student"],
                    "new_lesson_count"      =>$item["new_lesson_count"],
                    "end_stu_num"           =>$item["end_stu_num"],
                    // "lesson_student"        =>$item["lesson_student"],

                    "renw_price"            =>$item["renw_price"],
                    "tran_price"            =>$item["tran_price"],
                    "renw_student"          =>$item["renw_student"],
                    "cc_tran_num"           =>$item["cc_tran_num"],
                    "cc_tran_money"           =>$item["cc_tran_money"],

                    //  "revisit_target"        =>$item["revisit_target"],
                    // "revisit_real"          => $item["revisit_real"],
                    // "first_revisit_num"     => $item["first_revisit_num"],
                    // "un_first_revisit_num"  => $item["un_first_revisit_num"],
                    "refund_score"          => $item["refund_score"],
                    // "lesson_price_avg"      => $item["lesson_price_avg"],
                    "student_finsh"         =>$item["student_finish"],
                    "tran_num"              =>$item["tran_num"],

                ];
                $task->t_month_ass_student_info->get_field_update_arr($k,$start_time,1,$update_arr);
            }       
        }
            // }
        // dd($ass_list);
    }

    //新版薪资 助教续费新签合同/销售转介绍合同 金额/个数计算
    public function get_ass_order_list_performance($start_time,$end_time){
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $ass_order_info = $task->t_order_info->get_assistant_performance_order_info($start_time,$end_time);

        $ass_order_period_list = $task->t_order_info->get_ass_self_order_period_money($start_time,$end_time);//助教自签合同金额(分期80%计算)
        $renew_list=$new_list=[];
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
            //  $price = $val["price"];
            $price = @$ass_order_period_list[$orderid]["reset_money"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            if(!isset($ass_renew_info[$uid]["user_list"][$userid])){
                $ass_renew_info[$uid]["user_list"][$userid]=$userid;
                @$ass_renew_info[$uid]["num"] +=1;
            }
            @$ass_renew_info[$uid]["money"] += $price-$real_refund;

        }
        foreach($new_list as $val){
            $orderid = $val["orderid"];
            $userid = $val["userid"];
            // $price = $val["price"];
            $price = @$ass_order_period_list[$orderid]["reset_money"];
            $uid = $val["uid"];
            $real_refund = $val["real_refund"];
            if(!isset($ass_new_info[$uid]["user_list"][$userid])){
                $ass_new_info[$uid]["user_list"][$userid]=$userid;
                @$ass_new_info[$uid]["num"] +=1;
            }
            @$ass_new_info[$uid]["money"] += $price-$real_refund;

        }


        //获取销售转介绍合同信息
        $cc_order_list = $task->t_order_info->get_seller_tran_order_info($start_time,$end_time);
        $cc_order_period_list = $task->t_order_info->get_seller_tran_order_period_money($start_time,$end_time);//CC转介绍合同金额(分期80%计算)

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
            if(!isset($ass_tran_info[$uid]["user_list"][$userid])){
                $ass_tran_info[$uid]["user_list"][$userid]=$userid;
                @$ass_tran_info[$uid]["num"] +=1;
            }
            @$ass_tran_info[$uid]["money"] += $price-$real_refund;

        }

        return [$ass_new_info,$ass_renew_info,$ass_tran_info];

    }

    //每周助教在册学生数量获取
    public function get_register_student_list($first_week,$n){
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $registered_student_num=[];
        for($i=0;$i<$n;$i++){
            $week = $first_week+$i*7*86400;
            $week_edate = $week+7*86400;
            $week_info = $task->t_ass_weekly_info->get_all_info($week);
            foreach($week_info as $val){
                @$registered_student_num[$val["adminid"]] +=@$week_info[$val["adminid"]]["registered_student_num"];
            } 
        }
        return $registered_student_num;

    }

    //助教销售月课时消耗相关数据获取
    public function get_seller_month_lesson_count_use_info($registered_student_list,$seller_stu_num,$n,$seller_lesson_count){
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();

        //平均学员数(销售月) $seller_stu_num
        //销售月周数 $n
        //销售月课耗 $seller_lesson_count

        /*课时消耗达成率*/
        if($registered_student_list){
            $registered_student_arr = json_decode($registered_student_list,true);
            $last_stu_num = count($registered_student_arr);//月初在册人员数
            $last_lesson_total = $task->t_week_regular_course->get_lesson_count_all($registered_student_arr);//月初周总课时消耗数
            $estimate_month_lesson_count =$n*$last_lesson_total/$last_stu_num;  //预估月课时消耗总量
        }else{
            $registered_student_arr=[];      
            $estimate_month_lesson_count =100;
        }
       
        if(empty($seller_stu_num)){
            $lesson_count_finish_per=0;
        }else{
            $lesson_count_finish_per= round($seller_lesson_count/$seller_stu_num/$estimate_month_lesson_count*100,2);
        }

        //算出kpi中课时消耗达成率的情况
        if($lesson_count_finish_per>=70){
            $kpi_lesson_count_finish_per = 0.4;
        }else{
            $kpi_lesson_count_finish_per=0;
        }
      
        return array($kpi_lesson_count_finish_per,$estimate_month_lesson_count);
 
    }

    //回访绩效分值计算
    public function get_ass_revisit_reword_value($account,$adminid,$start_time,$end_time,$first_lesson_stu_list,$read_student_list,$registered_student_list){
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();

        $month_half = $start_time+15*86400;

        /*回访*/
        $revisit_reword_per = 0.2;//初始值

        //先看第一课回访信息
        if($first_lesson_stu_list){                                    
            $first_lesson_stu_arr = json_decode($first_lesson_stu_list,true);
            foreach($first_lesson_stu_arr as $val){
                $first_userid = $val["userid"];
                $lesson_start = $val["lesson_start"];
                $revisit_end = $lesson_start+86400;                           
                $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($first_userid,$lesson_start,$revisit_end,$account,5);
                if($revisit_num <=0){
                    $revisit_reword_per -=0.05;
                }
                if($revisit_reword_per <=0){
                    break;
                }

                        
            }
        }

        //当前在读学员
        if($read_student_list && $revisit_reword_per >0){
            $read_student_arr = json_decode($read_student_list,true);
            foreach($read_student_arr as $val){
                //先检查是否是本月才开始上课的(获取各科目常规课最早上课时间)
                $first_regular_lesson_time = $task->t_lesson_info_b3->get_stu_first_regular_lesson_time($val);
                $assign_time = $task->t_student_info->get_ass_assign_time($val);                        

                //检查本月是否上过课
                $month_lesson_flag = $task->t_lesson_info_b3->check_have_lesson_stu($val,$start_time,$end_time);

                if($first_regular_lesson_time>0 && $first_regular_lesson_time<$month_half){
                    if($assign_time < $month_half){
                        $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val,$start_time,$end_time,$account,-2);
                        if($month_lesson_flag==1){
                            if($revisit_num <2){
                                $revisit_reword_per -=0.05*(2-$revisit_num);
                            }
 
                        }else{
                            if($revisit_num <1){
                                $revisit_reword_per -=0.05;
                            }

                        }
                    }elseif($assign_time>=$month_half && $assign_time <$end_time){                            
                        $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val,$month_half,$end_time,$account,-2);
                        if($revisit_num <1){
                            $revisit_reword_per -=0.05;
                        }

                    }
                }elseif($first_regular_lesson_time>0 && $first_regular_lesson_time>=$month_half &&  $first_regular_lesson_time<=$end_time){                       
                    $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val,$month_half,$end_time,$account,-2);
                    if($revisit_num <1){
                        $revisit_reword_per -=0.05;
                    }

                }
                if($revisit_reword_per <=0){
                    break;
                }                   
            }
        }
        if($revisit_reword_per >0){
            //检查本月带过的历史学生 
            $history_list = $task->t_ass_stu_change_list->get_ass_history_list($adminid,$start_time,$end_time);
                       
            foreach($history_list as $val){
                //检查本月是否上过课
                $month_lesson_flag = $task->t_lesson_info_b3->check_have_lesson_stu($val["userid"],$start_time,$end_time);

                $add_time = $val["add_time"];
                if($add_time<$month_half){
                    $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val["userid"],$start_time,$month_half,$account,-2);
                    if($revisit_num <1){
                        $revisit_reword_per -=0.05;
                    }

                }else{
                    $assign_time = $val["assign_ass_time"];
                    if($assign_time <$month_half){
                        $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val["userid"],$start_time,$end_time,$account,-2);
                        if($month_lesson_flag==1){
                            if($revisit_num <2){
                                $revisit_reword_per -=0.05*(2-$revisit_num);
                            }
 
                        }else{
                            if($revisit_num <1){
                                $revisit_reword_per -=0.05;
                            }

                        }                      

                    }else{
                        $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val["userid"],$month_half,$end_time,$account,-2);
                        if($revisit_num <1){
                            $revisit_reword_per -=0.05;
                        }

                    }
                }
                if($revisit_reword_per <=0){
                    break;
                }

            
            }

        }

        if($revisit_reword_per>0 && $registered_student_list){
            //检查未结课学生回访状态(需要剔除在读学员)
            $registered_student_arr = json_decode($registered_student_list,true);
            if($read_student_list){
                $read_student_arr = json_decode($read_student_list,true);
                $registered_student_arr = array_diff($registered_student_arr, $read_student_arr);//获得去除在读学员的数组
            }

            foreach($registered_student_arr as $val){
                //先检查是否是本月才开始上课的(获取各科目常规课最早上课时间)
                $first_regular_lesson_time = $task->t_lesson_info_b3->get_stu_first_regular_lesson_time($val);
                $assign_time = $task->t_student_info->get_ass_assign_time($val);                        


                if($first_regular_lesson_time>0 && $first_regular_lesson_time<$month_half){
                    if($assign_time < $month_half){
                        $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val,$start_time,$end_time,$account,-2);
                        if($revisit_num <1){
                            $revisit_reword_per -=0.05;
                        }                                   

                    }elseif($assign_time>=$month_half && $assign_time <$end_time){                            
                        $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val,$month_half,$end_time,$account,-2);
                        if($revisit_num <1){
                            $revisit_reword_per -=0.05;
                        }

                    }
                }elseif($first_regular_lesson_time>0 && $first_regular_lesson_time>=$month_half &&  $first_regular_lesson_time<=$end_time){                       
                    $revisit_num = $task->t_revisit_info->get_ass_revisit_info_personal($val,$month_half,$end_time,$account,-2);
                    if($revisit_num <1){
                        $revisit_reword_per -=0.05;
                    }

                }
                if($revisit_reword_per <=0){
                    break;
                }



                    
            }
            

        }
        if($revisit_reword_per <0){
            $revisit_reword_per=0;
        }
        
        return $revisit_reword_per;
    } 

    //生成助教学生第一次课信息(按科目)
    public function get_ass_stu_first_lesson_subject_info($start_time,$end_time){
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $regular_lesson_list = $task->t_lesson_info_b3->get_stu_first_lesson_time_by_subject(-1,$start_time,$end_time);
        $arr_first=[];
        foreach($regular_lesson_list as $vvoo){
            $arr_first[$vvoo["uid"]][]=$vvoo;
        }           
        return  $arr_first;

    }
}
