<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class update_teacher_advance_info extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_teacher_advance_info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '兼职老师每个季度晋升参考数据生成';

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
        $time = time()-86400;
        $season     = ceil((date('n',$time))/3);//上季度是第几季度
        $start_time = strtotime(date('Y-m-d H:i:s',mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
        $end_time   = strtotime(date('Y-m-d H:i:s',mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))));
        if($end_time>time()){
            $end_time = time();
        }
        $teacher_money_type=6;
        $list     = $task->t_teacher_info->get_teacher_info_by_money_type($teacher_money_type,$start_time,$end_time);
        $tea_list = [];
        foreach($list as $val){
            $tea_list[] = $val["teacherid"];
        }

        $ret_info = $task->t_teacher_info->get_teacher_level_info("",$tea_list,$start_time);
        $tea_arr=[];
        foreach($ret_info["list"] as $val){
            $tea_arr[]=$val["teacherid"];
        }

        $test_person_num        = $task->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        $kk_test_person_num     = $task->t_lesson_info->get_kk_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        $change_test_person_num = $task->t_lesson_info->get_change_teacher_test_person_num_list(
            $start_time,$end_time,-1,-1,$tea_arr);
        $teacher_record_score = $task->t_teacher_record_list->get_test_lesson_record_score($start_time,$end_time,$tea_arr);
        foreach($ret_info["list"] as &$item){
            $teacherid = $item["teacherid"];
            $item["lesson_count"] = round($list[$teacherid]["lesson_count"]/300,1);
            $item["lesson_count_score"] = $task->get_advance_score_by_num( $item["lesson_count"],1);//课耗得分
            $item["stu_num"] = $list[$teacherid]["stu_num"];
            $item["stu_num_score"]= $task->get_advance_score_by_num( $item["stu_num"],4);//常规学生签单得分


            $item["cc_test_num"]    = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["person_num"]:0;
            $item["cc_order_num"]   = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["have_order"]:0;
            $item["cc_order_per"]   = !empty($item["cc_test_num"])?round($item["cc_order_num"]/$item["cc_test_num"]*100,2):0;
            $item["cc_order_score"]= $task->get_advance_score_by_num( $item["cc_order_num"],2);//cc签单数得分
            $item["other_test_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_num"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_num"]:0);
            $item["other_order_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_order"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_order"]:0);
            $item["other_order_per"] = !empty($item["other_test_num"])?round($item["other_order_num"]/$item["other_test_num"]*100,2):0;
            $item["other_order_score"]= $task->get_advance_score_by_num( $item["other_order_num"],3);//cr签单得分
            $item["record_num"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["num"]:0;
            $item["record_score"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["score"]:0;
            $item["record_score_avg"] = !empty($item["record_num"])?round($item["record_score"]/$item["record_num"],1):0;
            $item["record_final_score"]= $task->get_advance_score_by_num( $item["record_score_avg"],5);//教学质量得分

            $order_score = $item["cc_order_score"]+ $item["other_order_score"];//签单总分
            if($order_score>=10){
                $order_score=10;
            }
            $item["total_score"] =$item["lesson_count_score"]+$item["record_final_score"]+$order_score+ $item["stu_num_score"];//总得分
            list($item["reach_flag"],$item["withhold_money"])=$task->get_tea_reach_withhold_list($item["level"],$item["total_score"]);

            $item["hand_flag"]=0;
            $exists = $task->t_teacher_advance_list->field_get_list_2($start_time,$teacherid,"teacherid");
            if(!$exists){
                $task->t_teacher_advance_list->row_insert([
                    "start_time" =>$start_time,
                    "teacherid"  =>$teacherid,
                    "level_before"=>$item["level"],
                    "lesson_count"=>$item["lesson_count"]*100,
                    "lesson_count_score"=>$item["lesson_count_score"]*100,
                    "cc_test_num"=>$item["cc_test_num"],
                    "cc_order_num" =>$item["cc_order_num"],
                    "cc_order_per" =>$item["cc_order_per"],
                    "cc_order_score" =>$item["cc_order_score"]*100,
                    "other_test_num"=>$item["other_test_num"],
                    "other_order_num" =>$item["other_order_num"],
                    "other_order_per" =>$item["other_order_per"],
                    "other_order_score" =>$item["other_order_score"]*100,
                    "record_final_score"=>$item["record_final_score"]*100,
                    "record_score_avg" =>$item["record_score_avg"],
                    "record_num"     =>$item["record_num"],
                    "total_score"    =>$item["total_score"]*100,
                    "teacher_money_type"=>$item["teacher_money_type"],
                    "stu_num"        =>$item["stu_num"],
                    "stu_num_score"  =>$item["stu_num_score"]*100,
                    "reach_flag"         => $item["reach_flag"],
                    "withhold_money"     => $item["withhold_money"]*100,
                ]);

            }else{
                $task->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
                    "level_before"=>$item["level"],
                    "lesson_count"=>$item["lesson_count"]*100,
                    "lesson_count_score"=>$item["lesson_count_score"]*100,
                    "cc_test_num"=>$item["cc_test_num"],
                    "cc_order_num" =>$item["cc_order_num"],
                    "cc_order_per" =>$item["cc_order_per"],
                    "cc_order_score" =>$item["cc_order_score"]*100,
                    "other_test_num"=>$item["other_test_num"],
                    "other_order_num" =>$item["other_order_num"],
                    "other_order_per" =>$item["other_order_per"],
                    "other_order_score" =>$item["other_order_score"]*100,
                    "record_final_score"=>$item["record_final_score"]*100,
                    "record_score_avg" =>$item["record_score_avg"],
                    "record_num"     =>$item["record_num"],
                    "total_score"    =>$item["total_score"]*100,
                    "teacher_money_type"=>$item["teacher_money_type"],
                    "stu_num"        =>$item["stu_num"],
                    "stu_num_score"  =>$item["stu_num_score"]*100,
                    "reach_flag"         => $item["reach_flag"],
                    "withhold_money"     => $item["withhold_money"]*100,
                ]);

            }

        }

    }
}
