<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class get_jw_lesson_info_update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_jw_lesson_info_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '教务首页数据每日更新';

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
       
       
        //教务数据更新
        $res        = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info($start_time,$end_time);
        //$res        = $this->t_test_lesson_subject_sub_list->get_teat_lesson_transfor_info($start_time,$end_time);
        $all        = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type($start_time,$end_time);
        $ass       = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type($start_time,$end_time,1);
        $seller        = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type($start_time,$end_time,2);
        $green        = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type($start_time,$end_time,-1,1);
        $ret_info   = $task->t_test_lesson_subject_require->get_jw_teacher_test_lesson_info($start_time,$end_time);
        $none_total = $task->t_test_lesson_subject_require->get_none_total_info($start_time,$end_time);
        $no_assign_total = $task->t_test_lesson_subject_require->get_no_assign_total_info($start_time,$end_time);
        $ass_green        = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type($start_time,$end_time,1,1);
        $seller_green     = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type($start_time,$end_time,2,1);
        $seller_top        = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_seller_top($start_time,$end_time,-1,1);

        $all_total  = 0;

        foreach($ret_info as &$val){
            $val["all_count"] = $val["all_count"]-$val["back_other_count"];

            if(isset($res[$val["accept_adminid"]])){
                $val["tra_count"] = @$all[$val["accept_adminid"]]["num"];
                $val["tra_count_ass"] = @$ass[$val["accept_adminid"]]["num"];
                $val["tra_count_seller"] = @$seller[$val["accept_adminid"]]["num"];
                $val["tra_count_green"] = @$green[$val["accept_adminid"]]["num"];
                $val["ass_tran_green_count"] = @$ass_green[$val["accept_adminid"]]["num"];
                $val["seller_tran_green_count"] = @$seller_green[$val["accept_adminid"]]["num"];
                $val["tran_count_seller_top"] = @$seller_top[$val["accept_adminid"]]["num"];
            }else{
                $val["tra_count"] =$val["tra_count_ass"]=$val["tra_count_seller"] = $val["tra_count_green"]=$val["ass_tran_green_count"]= $val["seller_tran_green_count"]=$val["tran_count_seller_top"]=0;
            }
            if($start_time == strtotime(date("2017-01-01"))){
                $s = strtotime(date("2017-01-01 00:00:00"));
                $e = strtotime(date("2017-01-03 12:00:00"));
                $bc_info   = $task->t_test_lesson_subject_require->get_jw_teacher_test_lesson_info_bc($s,$e);
                $val["all_count"] += @$bc_info[$val["accept_adminid"]]["all_count"];
                $val["set_count"] += @$bc_info[$val["accept_adminid"]]["set_count"];
                $val["gz_count"] += @$bc_info[$val["accept_adminid"]]["gz_count"];
                $val["back_count"] += @$bc_info[$val["accept_adminid"]]["back_count"];
                $val["un_count"] += @$bc_info[$val["accept_adminid"]]["un_count"];
            }
            $val["tra_per"] = $val["all_count"]==0?"0":(round($val["tra_count"]/$val["all_count"],4)*100);
            $val["tra_per_str"] = $val["tra_per"]."%";
            $val["set_per"] = $val["all_count"]==0?"无":(round($val["set_count"]/$val["all_count"],4)*100)."%";


            $a = $task->t_jw_teacher_month_plan_lesson_info->field_get_value_2($val["accept_adminid"],$start_time,"adminid");
            if($a>0){
                $task->t_jw_teacher_month_plan_lesson_info->field_update_list_2($val["accept_adminid"],$start_time,[
                    "all_plan"   =>$val["all_count"],
                    "all_plan_done"=>$val["set_count"],
                    "un_plan"      =>$val["un_count"],
                    "gz_count"     =>$val["gz_count"],
                    "back_count"   =>$val["back_count"],
                    "plan_per"     =>$val["set_per"],
                    "tran_count"   =>$val["tra_count"],
                    "tran_count_seller"=>$val["tra_count_seller"],
                    "tran_count_ass"=>$val["tra_count_ass"],
                    "tran_count_green"=>$val["tra_count_green"],
                    "tran_per"      =>$val["tra_per_str"],
                    "ass_tran_green_count"  =>$val["ass_tran_green_count"],
                    "seller_tran_green_count"  =>$val["seller_tran_green_count"],
                    "tran_count_seller_top"    =>$val["tran_count_seller_top"]
                ]);
            }else{
                $task->t_jw_teacher_month_plan_lesson_info->row_insert([
                    "adminid"    =>$val["accept_adminid"],
                    "month"      =>$start_time,
                    "all_plan"   =>$val["all_count"],
                    "all_plan_done"=>$val["set_count"],
                    "un_plan"      =>$val["un_count"],
                    "gz_count"     =>$val["gz_count"],
                    "back_count"   =>$val["back_count"],
                    "plan_per"     =>$val["set_per"],
                    "tran_count"   =>$val["tra_count"],
                    "tran_count_seller"=>$val["tra_count_seller"],
                    "tran_count_ass"=>$val["tra_count_ass"],
                    "tran_count_green"=>$val["tra_count_green"],
                    "tran_per"      =>$val["tra_per_str"],
                    "ass_tran_green_count"  =>$val["ass_tran_green_count"],
                    "seller_tran_green_count"  =>$val["seller_tran_green_count"],
                    "tran_count_seller_top"    =>$val["tran_count_seller_top"]
                ]);

            }
        }

        //更新7月数据
        $start_time = strtotime("2017-07-01");
        $end_time = strtotime("2017-08-01");
        $ass_green        = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type($start_time,$end_time,1,1);
        foreach($ass_green as $k=>$tt){
            $task->t_jw_teacher_month_plan_lesson_info->field_update_list_2($k,$start_time,[
                "ass_tran_green_count"  =>$tt["num"]
            ]);
 
        }
        $seller_green     = $task->t_test_lesson_subject_require->get_teat_lesson_transfor_info_type($start_time,$end_time,2,1);
        foreach($seller_green as $k=>$tt){
            $task->t_jw_teacher_month_plan_lesson_info->field_update_list_2($k,$start_time,[
                "seller_tran_green_count"  =>$tt["num"]
            ]);
 
        }

        



    }
}
