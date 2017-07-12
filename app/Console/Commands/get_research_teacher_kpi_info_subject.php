<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class get_research_teacher_kpi_info_subject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_research_teacher_kpi_info_subject';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '教研老师KPI(学科)每日更新';

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
        $ret_info =[];
        for($i=1;$i<11;$i++){
            $ret_info[$i]=["subject"=>$i];
        }
        $interview_info=$task->t_teacher_lecture_info->get_interview_info_by_subject($start_time,$end_time);
        $order_info = $task->t_teacher_lecture_info->get_research_teacher_test_lesson_info_subject($start_time,$end_time);
        // $order_info = $task->t_teacher_lecture_info->get_interview_lesson_order_info_subject($start_time,$end_time);
        $first_info = $task->t_lesson_info->get_interview_teacher_first_lesson_info_subject($start_time,$end_time);
        $tea_list = $task->t_lesson_info->get_all_first_lesson_teacher_list($start_time,$end_time);
        $record_list = $task->t_teacher_record_list->get_teacher_record_num_list_subject($start_time,$end_time);
        $tea_arr=[];$record_info = [];
        foreach($tea_list as $k=>$val){
            if($val["add_time"]>0){
                $tea_arr[] = $k;
                @$record_info[$val["subject"]]["num"]++;
                @$record_info[$val["subject"]]["record_time"] +=$val["add_time"] - $val["lesson_start"];
            }
        }
        $lesson_info = $task->t_lesson_info->get_teacher_arr_lesson_order_info_subject($start_time,$end_time,$tea_arr);
        $first_lesson_info = $task->t_lesson_info->get_teacher_arr_first_lesson_order_info_subject($start_time,$end_time,$tea_arr);
        $other_record_info  = $task->t_seller_and_ass_record_list->get_seller_and_ass_record_by_subject($start_time,$end_time);
        $test_person_num= $task->t_lesson_info->get_test_person_num_list_by_subject( $start_time,$end_time);
        $test_person_num_other= $task->t_lesson_info->get_test_person_num_list_subject_other( $start_time,$end_time);
        $kk_test_person_num= $task->t_lesson_info->get_kk_teacher_test_person_subject_list( $start_time,$end_time);
        $change_test_person_num= $task->t_lesson_info->get_change_teacher_test_person_subject_list( $start_time,$end_time);



        $arr=["subject_str"=>"平均","subject"=>20];
        $zh = ["subject_str"=>"综合学科","subject"=>21];
        $wz = ["subject_str"=>"文科","subject"=>22];
        $xxk = ["subject_str"=>"小学科","subject"=>23];
        $slh=[];
        foreach($ret_info as $k=>&$item){
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
            $item["lesson_num"]  = @$test_person_num[$k]["lesson_num"];
            $item["person_num"]  = @$test_person_num[$k]["person_num"];
            $item["have_order"]  = @$test_person_num[$k]["have_order"];
            $item["lesson_num_other"]  = @$test_person_num_other[$k]["lesson_num"];
            $item["have_order_other"]  = @$test_person_num_other[$k]["have_order"];
            $item["lesson_num_kk"]  = @$kk_test_person_num[$k]["lesson_num"];
            $item["have_order_kk"]  = @$kk_test_person_num[$k]["have_order"];
            $item["lesson_num_change"]  = @$change_test_person_num[$k]["lesson_num"];
            $item["have_order_change"]  = @$change_test_person_num[$k]["have_order"];
            $item["record_num_all"] = @$record_list[$k]["num"];
            @$arr["record_num_all"] += @$record_list[$k]["num"];


            if($k==1 || $k==3){
                @$wz["interview_num"] += @$interview_info[$k]["interview_num"];
                @$wz["interview_time"] += @$interview_info[$k]["interview_time"];
                @$wz["interview_time"] += $item["interview_time"];
                @$wz["interview_num"] += $item["interview_num"];
                @$wz["interview_lesson"] += @$order_info[$k]["person_num"];
                @$wz["interview_order"] += @$order_info[$k]["order_num"];
                @$wz["record_time"] += @$record_info[$k]["record_time"];
                @$wz["record_num"] += @$record_info[$k]["num"];
                @$wz["first_lesson"] += @$first_info[$k]["person_num"];
                @$wz["first_order"] += @$first_info[$k]["order_num"];
                @$wz["next_lesson"] += @$lesson_info[$k]["person_num"];
                @$wz["next_order"] += @$lesson_info[$k]["order_num"];
                @$wz["next_lesson_first"] += @$first_lesson_info[$k]["person_num"];
                @$wz["next_order_first"] += @$first_lesson_info[$k]["order_num"];
                @$wz["other_record_time"] += @$other_record_info[$k]["deal_time"];
                @$wz["other_record_num"] += @$other_record_info[$k]["num"];
                @$wz["lesson_num"] += @$test_person_num[$k]["lesson_num"];
                @$wz["person_num"] += @$test_person_num[$k]["person_num"];
                @$wz["have_order"] += @$test_person_num[$k]["have_order"];
                @$wz["lesson_num_other"] += @$test_person_num_other[$k]["lesson_num"];
                @$wz["have_order_other"] += @$test_person_num_other[$k]["have_order"];
                @$wz["lesson_num_kk"] += @$kk_test_person_num[$k]["lesson_num"];
                @$wz["have_order_kk"] += @$kk_test_person_num[$k]["have_order"];
                @$wz["lesson_num_change"] += @$change_test_person_num[$k]["lesson_num"];
                @$wz["have_order_change"] += @$change_test_person_num[$k]["have_order"];
                @$wz["record_num_all"] += @$record_list[$k]["num"];


            }else if($k>3){
                @$zh["interview_num"] += @$interview_info[$k]["interview_num"];
                @$zh["interview_time"] += @$interview_info[$k]["interview_time"];
                @$zh["interview_time"] += $item["interview_time"];
                @$zh["interview_num"] += $item["interview_num"];
                @$zh["interview_lesson"] += @$order_info[$k]["person_num"];
                @$zh["interview_order"] += @$order_info[$k]["order_num"];
                @$zh["record_time"] += @$record_info[$k]["record_time"];
                @$zh["record_num"] += @$record_info[$k]["num"];
                @$zh["first_lesson"] += @$first_info[$k]["person_num"];
                @$zh["first_order"] += @$first_info[$k]["order_num"];
                @$zh["next_lesson"] += @$lesson_info[$k]["person_num"];
                @$zh["next_order"] += @$lesson_info[$k]["order_num"];
                @$zh["next_lesson_first"] += @$first_lesson_info[$k]["person_num"];
                @$zh["next_order_first"] += @$first_lesson_info[$k]["order_num"];
                @$zh["other_record_time"] += @$other_record_info[$k]["deal_time"];
                @$zh["other_record_num"] += @$other_record_info[$k]["num"];
                @$zh["lesson_num"] += @$test_person_num[$k]["lesson_num"];
                @$zh["person_num"] += @$test_person_num[$k]["person_num"];
                @$zh["have_order"] += @$test_person_num[$k]["have_order"];
                @$zh["lesson_num_other"] += @$test_person_num_other[$k]["lesson_num"];
                @$zh["have_order_other"] += @$test_person_num_other[$k]["have_order"];
                @$zh["lesson_num_kk"] += @$kk_test_person_num[$k]["lesson_num"];
                @$zh["have_order_kk"] += @$kk_test_person_num[$k]["have_order"];
                @$zh["lesson_num_change"] += @$change_test_person_num[$k]["lesson_num"];
                @$zh["have_order_change"] += @$change_test_person_num[$k]["have_order"];
                @$zh["record_num_all"] += @$record_list[$k]["num"];

            }
            if($k >5){
                @$xxk["interview_num"] += @$interview_info[$k]["interview_num"];
                @$xxk["interview_time"] += @$interview_info[$k]["interview_time"];
                @$xxk["interview_time"] += $item["interview_time"];
                @$xxk["interview_num"] += $item["interview_num"];
                @$xxk["interview_lesson"] += @$order_info[$k]["person_num"];
                @$xxk["interview_order"] += @$order_info[$k]["order_num"];
                @$xxk["record_time"] += @$record_info[$k]["record_time"];
                @$xxk["record_num"] += @$record_info[$k]["num"];
                @$xxk["first_lesson"] += @$first_info[$k]["person_num"];
                @$xxk["first_order"] += @$first_info[$k]["order_num"];
                @$xxk["next_lesson"] += @$lesson_info[$k]["person_num"];
                @$xxk["next_order"] += @$lesson_info[$k]["order_num"];
                @$xxk["next_lesson_first"] += @$first_lesson_info[$k]["person_num"];
                @$xxk["next_order_first"] += @$first_lesson_info[$k]["order_num"];
                @$xxk["other_record_time"] += @$other_record_info[$k]["deal_time"];
                @$xxk["other_record_num"] += @$other_record_info[$k]["num"];
                @$xxk["lesson_num"] += @$test_person_num[$k]["lesson_num"];
                @$xxk["person_num"] += @$test_person_num[$k]["person_num"];
                @$xxk["have_order"] += @$test_person_num[$k]["have_order"];
                @$xxk["lesson_num_other"] += @$test_person_num_other[$k]["lesson_num"];
                @$xxk["have_order_other"] += @$test_person_num_other[$k]["have_order"];
                @$xxk["lesson_num_kk"] += @$kk_test_person_num[$k]["lesson_num"];
                @$xxk["have_order_kk"] += @$kk_test_person_num[$k]["have_order"];
                @$xxk["lesson_num_change"] += @$change_test_person_num[$k]["lesson_num"];
                @$xxk["have_order_change"] += @$change_test_person_num[$k]["have_order"];
                @$xxk["record_num_all"] += @$record_list[$k]["num"];

            }
            if($k<4){
                @$slh["person_num"] += @$test_person_num[$k]["person_num"];
                @$slh["have_order"] += @$test_person_num[$k]["have_order"];
            }
            $item["subject_str"] =   E\Esubject::get_desc($item["subject"]);
        }
        $slh_per = !empty($slh["person_num"])?round(@$slh["have_order"]/$slh["person_num"],4)*100:0;

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

        array_unshift($ret_info,$xxk);
        array_unshift($ret_info,$zh);
        array_unshift($ret_info,$wz);
        array_unshift($ret_info,$arr);
        foreach($ret_info as &$vvv){
            $vvv["interview_time_avg"] = !empty($vvv["interview_num"])?round($vvv["interview_time"]/$vvv["interview_num"]/86400,2):0;
            $vvv["record_time_avg"] = !empty($vvv["record_num"])?round($vvv["record_time"]/$vvv["record_num"]/3600,2):0;
            $vvv["other_record_time_avg"] = !empty($vvv["other_record_num"])?round($vvv["other_record_time"]/$vvv["other_record_num"]/3600,2):0;
            $vvv["interview_per"] = !empty($vvv["interview_lesson"])?round(@$vvv["interview_order"]/$vvv["interview_lesson"],4)*100:0;
            $vvv["first_per"] = !empty($vvv["first_lesson"])?round(@$vvv["first_order"]/$vvv["first_lesson"],4)*100:0;
            $vvv["next_per"] = !empty($vvv["next_lesson"])?round(@$vvv["next_order"]/$vvv["next_lesson"],4)*100:0;
            $vvv["first_next_per"] = !empty($vvv["next_lesson_first"])?round(@$vvv["next_order_first"]/$vvv["next_lesson_first"],4)*100:0;
            $vvv["add_per"] =  round($vvv["next_per"]-$vvv["first_next_per"],2);
            if($vvv["subject_str"] !="平均"){       
                $vvv["lesson_per"] = !empty($vvv["person_num"])?round(@$vvv["have_order"]/$vvv["person_num"],4)*100:0;
                $vvv["lesson_per_other"] = !empty($vvv["lesson_num_other"])?round(@$vvv["have_order_other"]/$vvv["lesson_num_other"],4)*100:0;
                $vvv["lesson_per_kk"] = !empty($vvv["lesson_num_kk"])?round(@$vvv["have_order_kk"]/$vvv["lesson_num_kk"],4)*100:0;
                $vvv["lesson_per_change"] = !empty($vvv["lesson_num_change"])?round(@$vvv["have_order_change"]/$vvv["lesson_num_change"],4)*100:0;

            }else{
                $vvv["record_num_all"] = $vvv["record_num_all"]/3;
            }

            $vvv["lesson_num_per"] = !empty($arr["lesson_num"])?round(@$vvv["lesson_num"]/$arr["lesson_num"],4)*100:0;
            foreach($vvv as &$vvvv){
                if(empty($vvvv)){
                    $vvvv=0;
                }
            }            

        }

        $data=[];
        foreach($ret_info as $kk=>$gg){
            $data[$gg["subject"]] = $gg;
            if($gg["subject"]==2){
               $data[24] = $gg; 
            }
        }       

        $avg = [];
        $interview_time_standard=3;
        $record_time_standard=4;
        $other_record_time_standard=1;
        $add_per_standard=3;
        $research_num= $task->t_manager_info->get_adminid_num_by_account_role(4);
        $res= $ret_info;        
        $person_arr = $list_arr=[];
        foreach($res as $kk=>&$vv){
            if(in_array($vv["subject"],[4,5,6,7,8,9,10,21,23])){
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
            if(in_array($vv["subject"],[1,2,3,4,5,23])){
                $person_arr[$kk] = $vv;
            }
            if(in_array($vv["subject"],[2,21,22])){
                $list_arr[$kk]= $vv;
            }
        }

        foreach($res as $k=>$v){
            if($v["subject_str"]=="平均"){
                $avg = $res[$k];
                unset($res[$k]);
            }
        }
        $record_num_standard = round($avg["lesson_num"]/$research_num,1);
        $interview_time_person =  $person_arr;
        foreach($interview_time_person as $k=>$v){
            if($v["interview_time_avg"]==0){
                unset($interview_time_person[$k]);
            }
        }

        \App\Helper\Utils::order_list( $interview_time_person,"interview_time_avg", 1);
        $interview_time_first_person =  @$interview_time_person[0]["subject"];
        \App\Helper\Utils::order_list(  $person_arr,"interview_per_range", 0);
        $interview_per_first_person =  @$person_arr[0]["subject"];
        $record_time_person = $person_arr;
        foreach( $record_time_person as $k=>$v){
            if($v["record_time_avg"]==0){
                unset( $record_time_person[$k]);
            }
        }
        \App\Helper\Utils::order_list( $record_time_person,"record_time_avg", 1);
        $record_time_first_person =  @$record_time_person[0]["subject"];
        \App\Helper\Utils::order_list($person_arr,"record_num_all", 0);
        $record_num_first_person = @$person_arr[0]["subject"];
        \App\Helper\Utils::order_list($person_arr,"first_per_range", 0);
        $first_per_first_person = @$person_arr[0]["subject"];
        \App\Helper\Utils::order_list($person_arr,"add_per", 0);
        $add_per_first_person = @$person_arr[0]["subject"];
        $other_record_time_person =$person_arr;
        foreach( $other_record_time_person as $k=>$v){
            if($v["other_record_time_avg"]==0){
                unset( $other_record_time_person[$k]);
            }
        }
        \App\Helper\Utils::order_list( $other_record_time_person,"other_record_time_avg", 1);
        $other_record_time_first_person =  @$other_record_time_person[0]["subject"];
        \App\Helper\Utils::order_list( $person_arr,"lesson_num_per", 0);
        $lesson_num_per_first_person = @$person_arr[0]["subject"];
        \App\Helper\Utils::order_list( $person_arr,"lesson_per_range", 0);
        $lesson_per_first_person = @$person_arr[0]["subject"];
        \App\Helper\Utils::order_list( $person_arr,"lesson_per_other_range", 0);
        $lesson_per_other_first_person = @$person_arr[0]["subject"];
        \App\Helper\Utils::order_list($person_arr,"lesson_per_kk_range", 0);
        $lesson_per_kk_first_person = @$person_arr[0]["subject"];
        \App\Helper\Utils::order_list( $person_arr,"lesson_per_change_range", 0);
        $lesson_per_change_first_person = @$person_arr[0]["subject"];

        foreach($person_arr as $u){
            $person= $u["subject"];
            if($person==$interview_time_first_person){
                if($u["interview_time_avg"] <=$avg["interview_time_avg"] && $u["interview_time_avg"]<=$interview_time_standard){
                    $data[$person]["interview_time_score"] = 5;
                }else{
                    $data[$person]["interview_time_score"] = 1;
                }
            }else{
                if($u["interview_time_avg"] <=$avg["interview_time_avg"] && $u["interview_time_avg"]<=$interview_time_standard && $u["interview_time_avg"] !=0){
                    $data[$person]["interview_time_score"] = 3;
                }else{
                    $data[$person]["interview_time_score"] = 1;
                }

            }
            if($person==$interview_per_first_person){
                if($u["interview_per"] >=$avg["interview_per"] && $u["interview_per_range"]>=0){
                    $data[$person]["interview_per_score"] = 15;
                }else if($u["interview_per"] >=$avg["interview_per"]){
                    $data[$person]["interview_per_score"] = 10;
                }else{
                    $data[$person]["interview_per_score"]=5;
                }
            }else{
                if($u["interview_per"] >=$avg["interview_per"]){
                    $data[$person]["interview_per_score"] = 10;
                }else{
                    $data[$person]["interview_per_score"] = 5;
                }

            }
            if($person==$record_time_first_person){
                if($u["record_time_avg"] <=$avg["record_time_avg"] && $u["record_time_avg"]<=$record_time_standard){
                    $data[$person]["record_time_score"] = 5;
                }else{
                    $data[$person]["record_time_score"] = 1;
                }
            }else{
                if($u["record_time_avg"] <=$avg["record_time_avg"] && $u["record_time_avg"]<=$record_time_standard && $u["record_time_avg"] !=0){
                    $data[$person]["record_time_score"] = 3;
                }else{
                    $data[$person]["record_time_score"] = 1;
                }

            }

            if($person==$record_num_first_person){
                if($u["record_num_all"] >=$avg["record_num_all"] && $u["record_num_all"]>=$record_num_standard){
                    $data[$person]["record_num_score"] = 5;
                }else if($u["record_num_all"] >=$avg["record_num_all"] ){
                    $data[$person]["record_num_score"] = 3;
                }else{
                    $data[$person]["record_num_score"]=1;
                }
            }else{
                if($u["record_num_all"] >=$avg["record_num_all"] ){
                    $data[$person]["record_num_score"] = 3;
                }else{
                    $data[$person]["record_num_score"] = 1;
                }

            }

            if($person==$first_per_first_person){
                if($u["first_per"] >=$avg["first_per"] && $u["first_per_range"]>=0){
                    $data[$person]["first_per_score"] = 5;
                }else if($u["first_per"] >=$avg["first_per"]){
                    $data[$person]["first_per_score"] = 3;
                }else{
                    $data[$person]["first_per_score"]=1;
                }
            }else{
                if($u["first_per"] >=$avg["first_per"]){
                    $data[$person]["first_per_score"] = 3;
                }else{
                    $data[$person]["first_per_score"] = 1;
                }

            }
            if($person==$add_per_first_person){
                if($u["add_per"] >=$avg["add_per"] && $u["add_per"]>=$add_per_standard){
                    $data[$person]["add_per_score"] = 5;
                }else if($u["add_per"] >=$avg["add_per"] ){
                    $data[$person]["add_per_score"] = 3;
                }else{
                    $data[$person]["add_per_score"]=1;
                }
            }else{
                if($u["add_per"] >=$avg["add_per"] ){
                    $data[$person]["add_per_score"] = 3;
                }else{
                    $data[$person]["add_per_score"] = 1;
                }

            }

            if($person==$other_record_time_first_person){
                if($u["other_record_time_avg"] <=$avg["other_record_time_avg"] && $u["other_record_time_avg"]<=$other_record_time_standard){
                    $data[$person]["other_record_time_score"] = 5;
                }else{
                    $data[$person]["other_record_time_score"] = 1;
                }
            }else{
                if($u["other_record_time_avg"] <=$avg["other_record_time_avg"] && $u["other_record_time_avg"]<=$other_record_time_standard && $u["other_record_time_avg"] !=0){
                    $data[$person]["other_record_time_score"] = 3;
                }else{
                    $data[$person]["other_record_time_score"] = 1;
                }

            }
            if($person==$lesson_per_first_person){
                if($u["lesson_per"] >=$avg["lesson_per"] && $u["lesson_per_range"]>=0){
                    $data[$person]["lesson_per_score"] = 15;
                }else if($u["lesson_per"] >=$avg["lesson_per"]){
                    $data[$person]["lesson_per_score"] = 10;
                }else{
                    $data[$person]["lesson_per_score"]=5;
                }
            }else{
                if($u["lesson_per"] >=$avg["lesson_per"]){
                    $data[$person]["lesson_per_score"] = 10;
                }else{
                    $data[$person]["lesson_per_score"] = 5;
                }

            }
            if($person==$lesson_per_other_first_person){
                if($u["lesson_per_other"] >=$avg["lesson_per_other"] && $u["lesson_per_other_range"]>=0){
                    $data[$person]["lesson_per_other_score"] = 5;
                }else if($u["lesson_per_other"] >=$avg["lesson_per_other"]){
                    $data[$person]["lesson_per_other_score"] = 3;
                }else{
                    $data[$person]["lesson_per_other_score"]=1;
                }
            }else{
                if($u["lesson_per_other"] >=$avg["lesson_per_other"]){
                    $data[$person]["lesson_per_other_score"] = 3;
                }else{
                    $data[$person]["lesson_per_other_score"] = 1;
                }

            }

            if($person==$lesson_per_kk_first_person){
                if($u["lesson_per_kk"] >=$avg["lesson_per_kk"] && $u["lesson_per_kk_range"]>=0){
                    $data[$person]["lesson_per_kk_score"] = 5;
                }else if($u["lesson_per_kk"] >=$avg["lesson_per_kk"]){
                    $data[$person]["lesson_per_kk_score"] = 3;
                }else{
                    $data[$person]["lesson_per_kk_score"]=1;
                }
            }else{
                if($u["lesson_per_kk"] >=$avg["lesson_per_kk"]){
                    $data[$person]["lesson_per_kk_score"] = 3;
                }else{
                    $data[$person]["lesson_per_kk_score"] = 1;
                }

            }

            if($person==$lesson_per_change_first_person){
                if($u["lesson_per_change"] >=$avg["lesson_per_change"] && $u["lesson_per_change_range"]>=0){
                    $data[$person]["lesson_per_change_score"] = 5;
                }else if($u["lesson_per_change"] >=$avg["lesson_per_change"]){
                    $data[$person]["lesson_per_change_score"] = 3;
                }else{
                    $data[$person]["lesson_per_change_score"]=1;
                }
            }else{
                if($u["lesson_per_change"] >=$avg["lesson_per_change"]){
                    $data[$person]["lesson_per_change_score"] = 3;
                }else{
                    $data[$person]["lesson_per_change_score"] = 1;
                }

            }
              
            if($u["lesson_num_per"]>=30){
                $data[$person]["lesson_num_per_score"]=5;
            }else if($u["lesson_num_per"]>=20){
                $data[$person]["lesson_num_per_score"]=3;
            }else if($u["lesson_num_per"]>=10){
                $data[$person]["lesson_num_per_score"]=1;
            }else{
                $data[$person]["lesson_num_per_score"]=0;
            }


            $data[$person]["subject_str"] = $u["subject_str"];

        }

        $interview_time_list =  $list_arr;
        foreach($interview_time_list as $k=>$v){
            if($v["interview_time_avg"]==0){
                unset($interview_time_list[$k]);
            }
        }

        \App\Helper\Utils::order_list( $interview_time_list,"interview_time_avg", 1);
        $interview_time_first_list =  @$interview_time_list[0]["subject"];
        \App\Helper\Utils::order_list(  $list_arr,"interview_per_range", 0);
        $interview_per_first_list =  @$list_arr[0]["subject"];
        $record_time_list = $list_arr;
        foreach( $record_time_list as $k=>$v){
            if($v["record_time_avg"]==0){
                unset( $record_time_list[$k]);
            }
        }
        \App\Helper\Utils::order_list( $record_time_list,"record_time_avg", 1);
        $record_time_first_list =  @$record_time_list[0]["subject"];
        \App\Helper\Utils::order_list($list_arr,"record_num_all", 0);
        $record_num_first_list = @$list_arr[0]["subject"];
        \App\Helper\Utils::order_list($list_arr,"first_per_range", 0);
        $first_per_first_list = @$list_arr[0]["subject"];
        \App\Helper\Utils::order_list($list_arr,"add_per", 0);
        $add_per_first_list = @$list_arr[0]["subject"];
        $other_record_time_list =$list_arr;
        foreach( $other_record_time_list as $k=>$v){
            if($v["other_record_time_avg"]==0){
                unset( $other_record_time_list[$k]);
            }
        }
        \App\Helper\Utils::order_list( $other_record_time_list,"other_record_time_avg", 1);
        $other_record_time_first_list =  @$other_record_time_list[0]["subject"];
        \App\Helper\Utils::order_list( $list_arr,"lesson_num_per", 0);
        $lesson_num_per_first_list = @$list_arr[0]["subject"];
        \App\Helper\Utils::order_list( $list_arr,"lesson_per_range", 0);
        $lesson_per_first_list = @$list_arr[0]["subject"];
        \App\Helper\Utils::order_list( $list_arr,"lesson_per_other_range", 0);
        $lesson_per_other_first_list = @$list_arr[0]["subject"];
        \App\Helper\Utils::order_list($list_arr,"lesson_per_kk_range", 0);
        $lesson_per_kk_first_list = @$list_arr[0]["subject"];
        \App\Helper\Utils::order_list( $list_arr,"lesson_per_change_range", 0);
        $lesson_per_change_first_list = @$list_arr[0]["subject"];

        foreach($list_arr as $u){
            $list= $u["subject"];
            if($list==2){
                $list=24;
            }
            if($list==$interview_time_first_list){
                if($u["interview_time_avg"] <=$avg["interview_time_avg"] && $u["interview_time_avg"]<=$interview_time_standard){
                    $data[$list]["interview_time_score"] = 5;
                }else{
                    $data[$list]["interview_time_score"] = 1;
                }
            }else{
                if($u["interview_time_avg"] <=$avg["interview_time_avg"] && $u["interview_time_avg"]<=$interview_time_standard && $u["interview_time_avg"] !=0){
                    $data[$list]["interview_time_score"] = 3;
                }else{
                    $data[$list]["interview_time_score"] = 1;
                }

            }
            if($list==$interview_per_first_list){
                if($u["interview_per"] >=$avg["interview_per"] && $u["interview_per_range"]>=0){
                    $data[$list]["interview_per_score"] = 15;
                }else if($u["interview_per"] >=$avg["interview_per"]){
                    $data[$list]["interview_per_score"] = 10;
                }else{
                    $data[$list]["interview_per_score"]=5;
                }
            }else{
                if($u["interview_per"] >=$avg["interview_per"]){
                    $data[$list]["interview_per_score"] = 10;
                }else{
                    $data[$list]["interview_per_score"] = 5;
                }

            }
            if($list==$record_time_first_list){
                if($u["record_time_avg"] <=$avg["record_time_avg"] && $u["record_time_avg"]<=$record_time_standard){
                    $data[$list]["record_time_score"] = 5;
                }else{
                    $data[$list]["record_time_score"] = 1;
                }
            }else{
                if($u["record_time_avg"] <=$avg["record_time_avg"] && $u["record_time_avg"]<=$record_time_standard && $u["record_time_avg"] !=0){
                    $data[$list]["record_time_score"] = 3;
                }else{
                    $data[$list]["record_time_score"] = 1;
                }

            }

            if($list==$record_num_first_list){
                if($u["record_num_all"] >=$avg["record_num_all"] && $u["record_num_all"]>=$record_num_standard){
                    $data[$list]["record_num_score"] = 5;
                }else if($u["record_num_all"] >=$avg["record_num_all"] ){
                    $data[$list]["record_num_score"] = 3;
                }else{
                    $data[$list]["record_num_score"]=1;
                }
            }else{
                if($u["record_num_all"] >=$avg["record_num_all"] ){
                    $data[$list]["record_num_score"] = 3;
                }else{
                    $data[$list]["record_num_score"] = 1;
                }

            }

            if($list==$first_per_first_list){
                if($u["first_per"] >=$avg["first_per"] && $u["first_per_range"]>=0){
                    $data[$list]["first_per_score"] = 5;
                }else if($u["first_per"] >=$avg["first_per"]){
                    $data[$list]["first_per_score"] = 3;
                }else{
                    $data[$list]["first_per_score"]=1;
                }
            }else{
                if($u["first_per"] >=$avg["first_per"]){
                    $data[$list]["first_per_score"] = 3;
                }else{
                    $data[$list]["first_per_score"] = 1;
                }

            }
            if($list==$add_per_first_list){
                if($u["add_per"] >=$avg["add_per"] && $u["add_per"]>=$add_per_standard){
                    $data[$list]["add_per_score"] = 5;
                }else if($u["add_per"] >=$avg["add_per"] ){
                    $data[$list]["add_per_score"] = 3;
                }else{
                    $data[$list]["add_per_score"]=1;
                }
            }else{
                if($u["add_per"] >=$avg["add_per"] ){
                    $data[$list]["add_per_score"] = 3;
                }else{
                    $data[$list]["add_per_score"] = 1;
                }

            }

            if($list==$other_record_time_first_list){
                if($u["other_record_time_avg"] <=$avg["other_record_time_avg"] && $u["other_record_time_avg"]<=$other_record_time_standard){
                    $data[$list]["other_record_time_score"] = 5;
                }else{
                    $data[$list]["other_record_time_score"] = 1;
                }
            }else{
                if($u["other_record_time_avg"] <=$avg["other_record_time_avg"] && $u["other_record_time_avg"]<=$other_record_time_standard && $u["other_record_time_avg"] !=0){
                    $data[$list]["other_record_time_score"] = 3;
                }else{
                    $data[$list]["other_record_time_score"] = 1;
                }

            }
            if($list==$lesson_per_first_list){
                if($list==21){
                    if($u["lesson_per"] >=($slh_per+5) && $u["lesson_per_range"]>=0){
                        $data[$list]["lesson_per_score"] = 15;
                    }else if($u["lesson_per"] >=($slh_per+5)){
                        $data[$list]["lesson_per_score"] = 10;
                    }else{
                        $data[$list]["lesson_per_score"]=5;
                    }
 
                }else{
                    if($u["lesson_per"] >=$slh_per && $u["lesson_per_range"]>=0){
                        $data[$list]["lesson_per_score"] = 15;
                    }else if($u["lesson_per"] >=$slh_per){
                        $data[$list]["lesson_per_score"] = 10;
                    }else{
                        $data[$list]["lesson_per_score"]=5;
                    }
                }
            }else{
                if($list==21){
                    if($u["lesson_per"] >=($slh_per+5)){
                        $data[$list]["lesson_per_score"] = 10;
                    }else{
                        $data[$list]["lesson_per_score"]=5;
                    }
 
                }else{
                    if($u["lesson_per"] >=$slh_per){
                        $data[$list]["lesson_per_score"] = 10;
                    }else{
                        $data[$list]["lesson_per_score"]=5;
                    }
                }

            }
            if($list==$lesson_per_other_first_list){
                if($u["lesson_per_other"] >=$avg["lesson_per_other"] && $u["lesson_per_other_range"]>=0){
                    $data[$list]["lesson_per_other_score"] = 5;
                }else if($u["lesson_per_other"] >=$avg["lesson_per_other"]){
                    $data[$list]["lesson_per_other_score"] = 3;
                }else{
                    $data[$list]["lesson_per_other_score"]=1;
                }
            }else{
                if($u["lesson_per_other"] >=$avg["lesson_per_other"]){
                    $data[$list]["lesson_per_other_score"] = 3;
                }else{
                    $data[$list]["lesson_per_other_score"] = 1;
                }

            }

            if($list==$lesson_per_kk_first_list){
                if($u["lesson_per_kk"] >=$avg["lesson_per_kk"] && $u["lesson_per_kk_range"]>=0){
                    $data[$list]["lesson_per_kk_score"] = 5;
                }else if($u["lesson_per_kk"] >=$avg["lesson_per_kk"]){
                    $data[$list]["lesson_per_kk_score"] = 3;
                }else{
                    $data[$list]["lesson_per_kk_score"]=1;
                }
            }else{
                if($u["lesson_per_kk"] >= $avg["lesson_per_kk"]){
                    $data[$list]["lesson_per_kk_score"] = 3;
                }else{
                    $data[$list]["lesson_per_kk_score"] = 1;
                }

            }

            if($list==$lesson_per_change_first_list){
                if($u["lesson_per_change"] >=$avg["lesson_per_change"] && $u["lesson_per_change_range"]>=0){
                    $data[$list]["lesson_per_change_score"] = 5;
                }else if($u["lesson_per_change"] >=$avg["lesson_per_change"]){
                    $data[$list]["lesson_per_change_score"] = 3;
                }else{
                    $data[$list]["lesson_per_change_score"]=1;
                }
            }else{
                if($u["lesson_per_change"] >=$avg["lesson_per_change"]){
                    $data[$list]["lesson_per_change_score"] = 3;
                }else{
                    $data[$list]["lesson_per_change_score"] = 1;
                }

            }
              
            if($u["lesson_num_per"]>=30){
                $data[$list]["lesson_num_per_score"]=5;
            }else if($u["lesson_num_per"]>=20){
                $data[$list]["lesson_num_per_score"]=3;
            }else if($u["lesson_num_per"]>=10){
                $data[$list]["lesson_num_per_score"]=1;
            }else{
                $data[$list]["lesson_num_per_score"]=0;
            }



        }
       
        foreach($data as $o=>&$q){
            if(isset($q["interview_time_score"])){                            
                $q["total_score"] = $q["interview_time_score"]+$q["interview_per_score"]+$q["record_time_score"]+$q["record_num_score"]+$q["first_per_score"]+$q["add_per_score"]+$q["other_record_time_score"]+$q["lesson_per_score"]+$q["lesson_per_other_score"]+$q["lesson_per_kk_score"]+$q["lesson_per_change_score"]+$q["lesson_num_per_score"];
            }
            $kid= $o;
            $check = $task->t_research_teacher_kpi_info->get_type_flag($kid,$start_time);
            if($check>0){
                $task->t_research_teacher_kpi_info->field_update_list_2($kid,$start_time,[
                    "name"                   =>$q["subject_str"],
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
                    "type_flag"      =>2,
                    "name"           =>$q["subject_str"],
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
           
                   

       

              
    }
}
