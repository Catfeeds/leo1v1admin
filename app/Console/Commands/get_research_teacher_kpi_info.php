<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class get_research_teacher_kpi_info extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_research_teacher_kpi_info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '教研老师KPI(个人)每日更新';

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
        $start_time = strtotime(date("Y-m-01",time()-86400));
        $end_time = strtotime(date("Y-m-01",$start_time+40*86400));
        $ret_info = $task->t_manager_info-> get_adminid_list_by_account_role(4);
        $tea_num = count($ret_info);
        //dd($ret_info);
        $interview_info=$task->t_teacher_lecture_info->get_interview_info_by_account($start_time,$end_time);
        $order_info = $task->t_teacher_lecture_info->get_research_teacher_test_lesson_info_account($start_time,$end_time);
        $first_info = $task->t_lesson_info->get_interview_teacher_first_lesson_info($start_time,$end_time);
        $tea_list = $task->t_lesson_info->get_all_first_lesson_teacher_list($start_time,$end_time);
        $record_list = $task->t_teacher_record_list->get_teacher_record_num_list_account($start_time,$end_time);
        $tea_arr=[];$record_info = [];
        foreach($tea_list as $k=>$val){
            if($val["add_time"]>0){
                $tea_arr[] = $k;
                @$record_info[$val["uid"]]["num"]++;
                @$record_info[$val["uid"]]["record_time"] +=$val["add_time"] - $val["lesson_start"];
            }
        }
        $lesson_info = $task->t_lesson_info->get_teacher_arr_lesson_order_info($start_time,$end_time,$tea_arr);
        $first_lesson_info = $task->t_lesson_info->get_teacher_arr_first_lesson_order_info($start_time,$end_time,$tea_arr);
        $other_record_info  = $task->t_seller_and_ass_record_list->get_seller_and_ass_record_by_account($start_time,$end_time);
        $arr=["account"=>"平均","uid"=>20000];
        foreach($ret_info as $k=>&$item){
            $subject_and_grade_arr = $task->get_tea_subject_and_grade_by_adminid_new($k);
            $subject_arr= $subject_and_grade_arr["subject"];
            $grade_arr= $subject_and_grade_arr["grade"];
            $test_person_num= $task->t_lesson_info->get_test_person_num_list_by_subject_grade( $start_time,$end_time,$subject_arr,$grade_arr);
            $test_person_num_other= $task->t_lesson_info->get_test_person_num_list_subject_grade_other( $start_time,$end_time,$subject_arr,$grade_arr);
            $kk_test_person_num= $task->t_lesson_info->get_kk_teacher_test_person_subject_grade_list( $start_time,$end_time,$subject_arr,$grade_arr);
            $change_test_person_num= $task->t_lesson_info->get_change_teacher_test_person_subject_grade_list( $start_time,$end_time,$subject_arr,$grade_arr);
            $item["lesson_num"]  = @$test_person_num["lesson_num"];
            $item["person_num"]  = @$test_person_num["person_num"];
            $item["have_order"]  = @$test_person_num["have_order"];
            $item["lesson_num_other"]  = @$test_person_num_other["lesson_num"];
            $item["have_order_other"]  = @$test_person_num_other["have_order"];
            $item["lesson_num_kk"]  = @$kk_test_person_num["lesson_num"];
            $item["have_order_kk"]  = @$kk_test_person_num["have_order"];
            $item["lesson_num_change"]  = @$change_test_person_num["lesson_num"];
            $item["have_order_change"]  = @$change_test_person_num["have_order"];


            $item["interview_num"] = @$interview_info[$k]["interview_num"];
            @$arr["interview_num"] += @$interview_info[$k]["interview_num"];
            $item["interview_time"] = @$interview_info[$k]["interview_time"];
            @$arr["interview_time"] += @$interview_info[$k]["interview_time"];
            $item["interview_lesson"] =  @$order_info[$k]["person_num"];
            @$arr["interview_lesson"] += @$order_info[$k]["person_num"];
            $item["interview_order"] =  @$order_info[$k]["order_num"];
            @$arr["interview_order"] += @$order_info[$k]["order_num"];
            $item["record_time"] = @$record_info[$k]["record_time"];
            @$arr["record_time"] += @$record_info[$k]["record_time"];
            $item["record_num"] = @$record_info[$k]["num"];
            @$arr["record_num"] += @$record_info[$k]["num"];
            $item["first_lesson"] = @$first_info[$k]["person_num"];
            @$arr["first_lesson"] += @$first_info[$k]["person_num"];
            $item["first_order"] = @$first_info[$k]["order_num"];
            @$arr["first_order"] += @$first_info[$k]["order_num"];
            $item["next_lesson"] = @$lesson_info[$k]["person_num"];
            @$arr["next_lesson"] += @$lesson_info[$k]["person_num"];
            $item["next_order"] = @$lesson_info[$k]["order_num"];
            @$arr["next_order"] += @$lesson_info[$k]["order_num"];
            $item["next_lesson_first"] = @$first_lesson_info[$k]["person_num"];
            @$arr["next_lesson_first"] += @$first_lesson_info[$k]["person_num"];
            $item["next_order_first"] = @$first_lesson_info[$k]["order_num"];
            @$arr["next_order_first"] += @$first_lesson_info[$k]["order_num"];
            $item["other_record_time"]  = @$other_record_info[$k]["deal_time"];
            @$arr["other_record_time"] += @$other_record_info[$k]["deal_time"];
            $item["other_record_num"]  = @$other_record_info[$k]["num"];
            @$arr["other_record_num"] += @$other_record_info[$k]["num"];
            $item["record_num_all"] = @$record_list[$k]["num"];
            @$arr["record_num_all"] += @$record_list[$k]["num"];


        }
        $test_person_num_all= $task->t_lesson_info->get_test_person_num_list_by_subject_grade( $start_time,$end_time);
        $test_person_num_other_all= $task->t_lesson_info->get_test_person_num_list_subject_grade_other( $start_time,$end_time);
        $kk_test_person_num_all= $task->t_lesson_info->get_kk_teacher_test_person_subject_grade_list( $start_time,$end_time);
        $change_test_person_num_all= $task->t_lesson_info->get_change_teacher_test_person_subject_grade_list( $start_time,$end_time);
        $arr["lesson_num"] = $test_person_num_all["lesson_num"];
        $arr["have_order"] = @$test_person_num_all["have_order"];
        $arr["lesson_per"] = !empty($test_person_num_all["person_num"])?round(@$test_person_num_all["have_order"]/$test_person_num_all["person_num"],4)*100:0;
        $arr["lesson_per_other"] = !empty($test_person_num_other_all["lesson_num"])?round($test_person_num_other_all["have_order"]/$test_person_num_other_all["lesson_num"],4)*100:0;
        $arr["lesson_per_kk"] = !empty($kk_test_person_num_all["lesson_num"])?round($kk_test_person_num_all["have_order"]/$kk_test_person_num_all["lesson_num"],4)*100:0;
        $arr["lesson_per_change"] = !empty($change_test_person_num_all["lesson_num"])?round($change_test_person_num_all["have_order"]/$change_test_person_num_all["lesson_num"],4)*100:0;

        array_unshift($ret_info,$arr);
        foreach($ret_info as &$vvv){
            $vvv["interview_time_avg"] = !empty($vvv["interview_num"])?round($vvv["interview_time"]/$vvv["interview_num"]/86400,2):0;
            $vvv["record_time_avg"] = !empty($vvv["record_num"])?round($vvv["record_time"]/$vvv["record_num"]/3600,2):0;
            $vvv["other_record_time_avg"] = !empty($vvv["other_record_num"])?round($vvv["other_record_time"]/$vvv["other_record_num"]/3600,2):0;
            $vvv["interview_per"] = !empty($vvv["interview_lesson"])?round($vvv["interview_order"]/$vvv["interview_lesson"],4)*100:0;
            $vvv["first_per"] = !empty($vvv["first_lesson"])?round($vvv["first_order"]/$vvv["first_lesson"],4)*100:0;
            $vvv["next_per"] = !empty($vvv["next_lesson"])?round($vvv["next_order"]/$vvv["next_lesson"],4)*100:0;
            $vvv["first_next_per"] = !empty($vvv["next_lesson_first"])?round($vvv["next_order_first"]/$vvv["next_lesson_first"],4)*100:0;
            $vvv["add_per"] =  round($vvv["next_per"]-$vvv["first_next_per"],2);
            if($vvv["account"] !="平均"){       
                $vvv["lesson_per"] = !empty($vvv["person_num"])?round(@$vvv["have_order"]/$vvv["person_num"],4)*100:0;
                $vvv["lesson_per_other"] = !empty($vvv["lesson_num_other"])?round(@$vvv["have_order_other"]/$vvv["lesson_num_other"],4)*100:0;
                $vvv["lesson_per_kk"] = !empty($vvv["lesson_num_kk"])?round(@$vvv["have_order_kk"]/$vvv["lesson_num_kk"],4)*100:0;
                $vvv["lesson_per_change"] = !empty($vvv["lesson_num_change"])?round(@$vvv["have_order_change"]/$vvv["lesson_num_change"],4)*100:0;

            }else{
                $vvv["record_num_all"] = ceil($vvv["record_num_all"]/$tea_num);
            }

            $vvv["lesson_num_per"] = !empty($arr["lesson_num"])?round(@$vvv["lesson_num"]/$arr["lesson_num"],4)*100:0;
            foreach($vvv as &$ttt){
                if(empty($ttt)){
                    $ttt=0;
                }
            }
        }

        $score_list = [];
        $group_score_list=[];
        $avg = [];
        $interview_time_standard=3;
        $record_time_standard=4;
        $other_record_time_standard=1;
        $add_per_standard=3;
        $research_num= $task->t_manager_info->get_adminid_num_by_account_role(4);
        $res= $ret_info;
        foreach($res as &$vv){
            if(in_array($vv["uid"],[478,486,754])){
                $interview_per_standard = 23;
                $first_per_standard = 20;
                $lesson_per_standard = 22;
                $lesson_per_other_standard = 75;
                $lesson_per_kk_standard = 75;
                $lesson_per_change_standard = 75;
            }else{
                $interview_per_standard = 18;
                $first_per_standard = 15;
                $lesson_per_standard = 17;
                $lesson_per_other_standard = 70;
                $lesson_per_kk_standard = 70;
                $lesson_per_change_standard = 70;
            }
            $vv["interview_per_range"] = $vv["interview_per"]- $interview_per_standard;
            $vv["first_per_range"] = $vv["first_per"]- $first_per_standard;
            $vv["lesson_per_range"] = $vv["lesson_per"]- $lesson_per_standard;
            $vv["lesson_per_other_range"] = $vv["lesson_per_other"]- $lesson_per_other_standard;
            $vv["lesson_per_kk_range"] = $vv["lesson_per_kk"]- $lesson_per_kk_standard;
            $vv["lesson_per_change_range"] = $vv["lesson_per_change"]- $lesson_per_change_standard;
        }
        foreach($res as $k=>$v){
            if($v["account"]=="平均"){
                $avg = $res[$k];
                unset($res[$k]);
            }
        }
        $record_num_standard = !empty($research_num)?round(@$avg["lesson_num"]/$research_num,1):0;
        $interview_time =  $res;
        $interview_time_last_arr=[];
        foreach($interview_time as $k=>$v){
            if($v["interview_time_avg"]==0){
                unset($interview_time[$k]);
                $interview_time_last_arr[] = $v["uid"];
            }
        }

        \App\Helper\Utils::order_list( $interview_time,"interview_time_avg", 1);
        $interview_time_first_uid =  @$interview_time[0]["uid"];
        \App\Helper\Utils::order_list( $interview_time,"interview_time_avg", 0);
        $interview_last_arr[] = @$interview_time[0]["uid"];

        $interview_per_last_arr=[];
        foreach($res as $k=>$v){
            if($v["interview_per"]==0){
                $interview_per_last_arr[] = $v["uid"];
            }
        }

        \App\Helper\Utils::order_list( $res,"interview_per_range", 0);
        $interview_per_first_uid = @$res[0]["uid"];
        \App\Helper\Utils::order_list( $res,"interview_per_range", 1);
        if(empty($interview_per_last_arr)){
            $interview_per_last_arr[] = @$res[0]["uid"];
        }
        $record_time =  $res;
        $record_time_last_arr=[];
        foreach( $record_time as $k=>$v){
            if($v["record_time_avg"]==0){
                unset( $record_time[$k]);
                $record_time_last_arr[] = $v["uid"];
            }
        }
        \App\Helper\Utils::order_list( $record_time,"record_time_avg", 1);
        $record_time_first_uid =  @$record_time[0]["uid"];
        \App\Helper\Utils::order_list( $record_time,"record_time_avg", 0);
        $record_time_last_arr[] =@$record_time[0]["uid"];
        $record_num_last_arr=[];
        foreach($res as $k=>$v){
            if($v["record_num_all"]==0){
                $record_num_last_arr[] = $v["uid"];
            }
        }        
        \App\Helper\Utils::order_list( $res,"record_num_all", 0);
        $record_num_first_uid = @$res[0]["uid"];
        \App\Helper\Utils::order_list( $res,"record_num_all", 1);
        if(empty($record_num_last_arr)){
            $record_num_last_arr[] = @$res[0]["uid"];
        }
        $first_per_last_arr =[];
        foreach($res as $k=>$v){
            if($v["first_per"]==0){
                $first_per_last_arr[] = $v["uid"];
            }
        }        

        \App\Helper\Utils::order_list( $res,"first_per_range", 0);
        $first_per_first_uid = @$res[0]["uid"];
        \App\Helper\Utils::order_list( $res,"first_per_range", 1);
        if(empty($first_per_last_arr)){
            $first_per_last_arr[] = @$res[0]["uid"];
        }    

        \App\Helper\Utils::order_list( $res,"add_per", 0);
        $add_per_first_uid = @$res[0]["uid"];
        \App\Helper\Utils::order_list( $res,"add_per", 1);
        $add_per_last_uid = @$res[0]["uid"];
        $other_record_time =$res;
        $other_record_time_last_arr=[];
        foreach( $other_record_time as $k=>$v){
            if($v["other_record_time_avg"]==0){
                unset( $other_record_time[$k]);
                $other_record_time_last_arr[]=$v["uid"];
            }
        }
        \App\Helper\Utils::order_list( $other_record_time,"other_record_time_avg", 1);
        $other_record_time_first_uid =  @$other_record_time[0]["uid"];
        \App\Helper\Utils::order_list( $other_record_time,"other_record_time_avg", 0);
        $other_record_time_last_arr[]=@$other_record_time[0]["uid"];

        \App\Helper\Utils::order_list( $res,"lesson_num_per", 0);
        $lesson_num_per_first_uid = @$res[0]["uid"];
        $lesson_per_last_arr=[];
        foreach($res as $k=>$v){
            if($v["lesson_per"]==0){
                $lesson_per_last_arr[] = $v["uid"];
            }
        }        
        \App\Helper\Utils::order_list( $res,"lesson_per_range", 0);
        $lesson_per_first_uid = @$res[0]["uid"];
        \App\Helper\Utils::order_list( $res,"lesson_per_range", 1);
        if(empty($lesson_per_last_arr)){
             $lesson_per_last_arr[] = @$res[0]["uid"];
        }

        $lesson_per_other_last_arr=[];
        foreach($res as $k=>$v){
            if($v["lesson_per_other"]==0){
                $lesson_per_other_last_arr[] = $v["uid"];
            }
        }        

        \App\Helper\Utils::order_list( $res,"lesson_per_other_range", 0);
        $lesson_per_other_first_uid = @$res[0]["uid"];
        \App\Helper\Utils::order_list( $res,"lesson_per_other_range", 1);
        if(empty($lesson_per_other_last_arr)){
            $lesson_per_other_last_arr[] = @$res[0]["uid"];
        }
        $lesson_per_kk_last_arr=[];
        foreach($res as $k=>$v){
            if($v["lesson_per_kk"]==0){
                $lesson_per_kk_last_arr[] = $v["uid"];
            }
        }        

        \App\Helper\Utils::order_list( $res,"lesson_per_kk_range", 0);
        $lesson_per_kk_first_uid = @$res[0]["uid"];
        \App\Helper\Utils::order_list( $res,"lesson_per_kk_range", 1);
        if(empty($lesson_per_kk_last_arr)){
            $lesson_per_kk_last_arr[] = @$res[0]["uid"];
        }
        
        $lesson_per_change_last_arr=[];
        foreach($res as $k=>$v){
            if($v["lesson_per_change"]==0){
                $lesson_per_change_last_arr[] = $v["uid"];
            }
        }        

        \App\Helper\Utils::order_list( $res,"lesson_per_change_range", 0);
        $lesson_per_change_first_uid = @$res[0]["uid"];
        \App\Helper\Utils::order_list( $res,"lesson_per_change_range", 1);
        if(empty($lesson_per_change_last_arr)){
            $lesson_per_change_last_arr[] = @$res[0]["uid"];
        }


        foreach($res as &$u){
            $uid= $u["uid"];
            if($uid==$interview_time_first_uid){
                if($u["interview_time_avg"] <= $avg["interview_time_avg"] && $u["interview_time_avg"]<=$interview_time_standard){
                    $u["interview_time_score"] = 5;
                }else{
                    $u["interview_time_score"] = 1;
                }
            }elseif(in_array($uid,$interview_time_last_arr)){
                $u["interview_time_score"]=0;
            }else{
                if($u["interview_time_avg"] <=$avg["interview_time_avg"] && $u["interview_time_avg"]<=$interview_time_standard && $u["interview_time_avg"] !=0){
                    $u["interview_time_score"] = 3;
                }else{
                    $u["interview_time_score"] = 1;
                }

            }
            if($uid==$interview_per_first_uid){
                if($u["interview_per"] >=$avg["interview_per"] && $u["interview_per_range"]>=0){
                    $u["interview_per_score"] = 15;
                }else if($u["interview_per"] >=$avg["interview_per"]){
                    $u["interview_per_score"] = 10;
                }else{
                    $u["interview_per_score"]=5;
                }
            }elseif(in_array($uid,$interview_per_last_arr)){
                $u["interview_per_score"]=0;
            }else{
                if($u["interview_per"] >=$avg["interview_per"]){
                    $u["interview_per_score"] = 10;
                }else{
                    $u["interview_per_score"] = 5;
                }

            }
            if($uid==$record_time_first_uid){
                if($u["record_time_avg"] <=$avg["record_time_avg"] && $u["record_time_avg"]<=$record_time_standard){
                    $u["record_time_score"] = 5;
                }else{
                    $u["record_time_score"] = 1;
                }
            }elseif(in_array($uid,$record_time_last_arr)){
                $u["record_time_score"]=0;
            }else{
                if($u["record_time_avg"] <$avg["record_time_avg"] && $u["record_time_avg"]<$record_time_standard && $u["record_time_avg"] !=0){
                    $u["record_time_score"] = 3;
                }else{
                    $u["record_time_score"] = 1;
                }

            }

            if($uid==$record_num_first_uid){
                if($u["record_num_all"] >=$avg["record_num_all"] && $u["record_num_all"]>=$record_num_standard){
                    $u["record_num_score"] = 5;
                }else if($u["record_num_all"] >=$avg["record_num_all"] ){
                    $u["record_num_score"] = 3;
                }else{
                    $u["record_num_score"]=1;
                }
            }elseif(in_array($uid,$record_num_last_arr)){
                $u["record_num_score"]=0;
            }else{
                if($u["record_num_all"] >=$avg["record_num_all"] ){
                    $u["record_num_score"] = 3;
                }else{
                    $u["record_num_score"] = 1;
                }

            }

            if($uid==$first_per_first_uid){
                if($u["first_per"] >=$avg["first_per"] && $u["first_per_range"]>=0){
                    $u["first_per_score"] = 5;
                }else if($u["first_per"] >= $avg["first_per"]){
                    $u["first_per_score"] = 3;
                }else{
                    $u["first_per_score"]=1;
                }
            }elseif(in_array($uid,$first_per_last_arr)){
                $u["first_per_score"]=0;
            }else{
                if($u["first_per"] >= $avg["first_per"]){
                   $u["first_per_score"] = 3;
                }else{
                    $u["first_per_score"] = 1;
                }

            }
            if($uid==$add_per_first_uid){
                if($u["add_per"] >=$avg["add_per"] && $u["add_per"]>=$add_per_standard){
                    $u["add_per_score"] = 5;
                }else if($u["add_per"] >=$avg["add_per"] ){
                    $u["add_per_score"] = 3;
                }else{
                    $u["add_per_score"]=1;
                }
            }elseif($uid==$add_per_last_uid){
                 $u["add_per_score"]=0;
            }else{
                if($u["add_per"] >=$avg["add_per"] ){
                    $u["add_per_score"] = 3;
                }else{
                    $u["add_per_score"] = 1;
                }

            }

            if($uid==$other_record_time_first_uid){
                if($u["other_record_time_avg"] <= $avg["other_record_time_avg"] && $u["other_record_time_avg"] <= $other_record_time_standard){
                    $u["other_record_time_score"] = 5;
                }else{
                    $u["other_record_time_score"] = 1;
                }
            }elseif(in_array($uid,$other_record_time_last_arr)){
                $u["other_record_time_score"] = 0;
            }else{
                if($u["other_record_time_avg"] <=$avg["other_record_time_avg"] && $u["other_record_time_avg"]<=$other_record_time_standard && $u["other_record_time_avg"] !=0){
                    $u["other_record_time_score"] = 3;
                }else{
                    $u["other_record_time_score"] = 1;
                }

            }
            if($uid==$lesson_per_first_uid){
                if($u["lesson_per"] >=$avg["lesson_per"] && $u["lesson_per_range"]>=0){
                    $u["lesson_per_score"] = 15;
                }else if($u["lesson_per"] >=$avg["lesson_per"]){
                    $u["lesson_per_score"] = 10;
                }else{
                    $u["lesson_per_score"]=5;
                }
            }elseif(in_array($uid,$lesson_per_last_arr)){
                $u["lesson_per_score"]=0;
            }else{
                if($u["lesson_per"] >=$avg["lesson_per"]){
                    $u["lesson_per_score"] = 10;
                }else{
                    $u["lesson_per_score"] = 5;
                }

            }
            if($uid==$lesson_per_other_first_uid){
                if($u["lesson_per_other"] >= $avg["lesson_per_other"] && $u["lesson_per_other_range"]>=0){
                    $u["lesson_per_other_score"] = 5;
                }else if($u["lesson_per_other"] >=$avg["lesson_per_other"]){
                    $u["lesson_per_other_score"] = 3;
                }else{
                    $u["lesson_per_other_score"]=1;
                }
            }else if(in_array($uid,$lesson_per_other_last_arr)){
                $u["lesson_per_other_score"]=0;
            }else{
                if($u["lesson_per_other"] >=$avg["lesson_per_other"]){
                    $u["lesson_per_other_score"] = 3;
                }else{
                    $u["lesson_per_other_score"] = 1;
                }

            }

            if($uid==$lesson_per_kk_first_uid){
                if($u["lesson_per_kk"] >=$avg["lesson_per_kk"] && $u["lesson_per_kk_range"]>=0){
                    $u["lesson_per_kk_score"] = 5;
                }else if($u["lesson_per_kk"] >=$avg["lesson_per_kk"]){
                    $u["lesson_per_kk_score"] = 3;
                }else{
                    $u["lesson_per_kk_score"]=1;
                }
            }elseif(in_array($uid,$lesson_per_kk_last_arr)){
                $u["lesson_per_kk_score"]=0;
            }else{
                if($u["lesson_per_kk"] >=$avg["lesson_per_kk"]){
                    $u["lesson_per_kk_score"] = 3;
                }else{
                    $u["lesson_per_kk_score"] = 1;
                }

            }

            if($uid==$lesson_per_change_first_uid){
                if($u["lesson_per_change"] >=$avg["lesson_per_change"] && $u["lesson_per_change_range"]>=0){
                    $u["lesson_per_change_score"] = 5;
                }else if($u["lesson_per_change"] >= $avg["lesson_per_change"]){
                    $u["lesson_per_change_score"] = 3;
                }else{
                    $u["lesson_per_change_score"]=1;
                }
            }elseif(in_array($uid,$lesson_per_change_last_arr)){
                $u["lesson_per_change_score"]=0;
            }else{
                if($u["lesson_per_change"] >= $avg["lesson_per_change"]){
                    $u["lesson_per_change_score"] = 3;
                }else{
                    $u["lesson_per_change_score"] = 1;
                }

            }
              
            if($u["lesson_num_per"]>=15){
                $u["lesson_num_per_score"]=5;
            }else if($u["lesson_num_per"]>=10){
                $u["lesson_num_per_score"]=3;
            }else if($u["lesson_num_per"]>=5){
                $u["lesson_num_per_score"]=1;
            }else{
                $u["lesson_num_per_score"]=0;
            }

            $u["total_score"] = $u["interview_time_score"]+$u["interview_per_score"]+$u["record_time_score"]+$u["record_num_score"]+$u["first_per_score"]+$u["add_per_score"]+$u["other_record_time_score"]+$u["lesson_per_score"]+$u["lesson_per_other_score"]+$u["lesson_per_kk_score"]+$u["lesson_per_change_score"]+$u["lesson_num_per_score"];

        }
           
        array_unshift($res,$avg);
        foreach($res as $q){
            $kid= $q["uid"];
            
            $check = $task->t_research_teacher_kpi_info->get_type_flag($kid,$start_time);
            if($check>0){
                $task->t_research_teacher_kpi_info->field_update_list_2($kid,$start_time,[
                    "interview_time"         =>$q["interview_time_avg"],
                    "interview_lesson"       =>$q["interview_lesson"],
                    "interview_order"        =>$q["interview_order"],
                    "interview_per"          =>$q["interview_per"],
                    "record_time"            =>$q["record_time_avg"],
                    "record_num"             =>$q["record_num_all"],
                    "first_lesson"           =>$q["first_lesson"],
                    "first_order"            =>$q["first_order"],
                    "first_per"              =>$q["first_per"],
                    "first_next_per"         =>$q["first_next_per"],
                    "next_per"               =>$q["next_per"],
                    "add_per"                =>$q["add_per"],
                    "other_record_time"      =>$q["other_record_time_avg"],
                    "lesson_num"             =>$q["lesson_num"],
                    "lesson_num_per"         =>$q["lesson_num_per"],
                    "lesson_per"             =>$q["lesson_per"],
                    "lesson_per_other"       =>$q["lesson_per_other"],
                    "lesson_per_kk"          =>$q["lesson_per_kk"],
                    "lesson_per_change"      =>$q["lesson_per_change"],
                    "interview_time_score"   =>@$q["interview_time_score"],
                    "interview_per_score"    =>@$q["interview_per_score"],
                    "record_time_score"      =>@$q["record_time_score"],
                    "record_num_score"       =>@$q["record_num_score"],
                    "first_per_score"        =>@$q["first_per_score"],
                    "add_per_score"          =>@$q["add_per_score"],
                    "other_record_time_score"=>@$q["other_record_time_score"],
                    "lesson_num_per_score"   =>@$q["lesson_num_per_score"],
                    "lesson_per_score"       =>@$q["lesson_per_score"],
                    "lesson_per_other_score" =>@$q["lesson_per_other_score"],
                    "lesson_per_kk_score"    =>@$q["lesson_per_kk_score"],
                    "lesson_per_change_score"=>@$q["lesson_per_change_score"],
                    "total_score"            =>@$q["total_score"]
                ]);
            }else{
                $task->t_research_teacher_kpi_info->row_insert([
                    "kid"            =>$kid,
                    "month"          =>$start_time,
                    "type_flag"      =>1,
                    "name"           =>$q["account"],
                    "interview_time"         =>$q["interview_time_avg"],
                    "interview_lesson"       =>$q["interview_lesson"],
                    "interview_order"        =>$q["interview_order"],
                    "interview_per"          =>$q["interview_per"],
                    "record_time"            =>$q["record_time_avg"],
                    "record_num"             =>$q["record_num_all"],
                    "first_lesson"           =>$q["first_lesson"],
                    "first_order"            =>$q["first_order"],
                    "first_per"              =>$q["first_per"],
                    "first_next_per"         =>$q["first_next_per"],
                    "next_per"               =>$q["next_per"],
                    "add_per"                =>$q["add_per"],
                    "other_record_time"      =>$q["other_record_time_avg"],
                    "lesson_num"             =>$q["lesson_num"],
                    "lesson_num_per"         =>$q["lesson_num_per"],
                    "lesson_per"             =>$q["lesson_per"],
                    "lesson_per_other"       =>$q["lesson_per_other"],
                    "lesson_per_kk"          =>$q["lesson_per_kk"],
                    "lesson_per_change"      =>$q["lesson_per_change"],
                    "interview_time_score"   =>@$q["interview_time_score"],
                    "interview_per_score"    =>@$q["interview_per_score"],
                    "record_time_score"      =>@$q["record_time_score"],
                    "record_num_score"       =>@$q["record_num_score"],
                    "first_per_score"        =>@$q["first_per_score"],
                    "add_per_score"          =>@$q["add_per_score"],
                    "other_record_time_score"=>@$q["other_record_time_score"],
                    "lesson_num_per_score"   =>@$q["lesson_num_per_score"],
                    "lesson_per_score"       =>@$q["lesson_per_score"],
                    "lesson_per_other_score" =>@$q["lesson_per_other_score"],
                    "lesson_per_kk_score"    =>@$q["lesson_per_kk_score"],
                    "lesson_per_change_score"=>@$q["lesson_per_change_score"],
                    "total_score"            =>@$q["total_score"]
                ]);
            }
        }
            
        // dd($res);

       

              
    }
}
