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
        // $test_person_num        = $this->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        // $kk_test_person_num     = $this->t_lesson_info->get_kk_teacher_test_person_num_list( $start_time,$end_time,-1,-1,$tea_arr);
        // $change_test_person_num = $this->t_lesson_info->get_change_teacher_test_person_num_list(
        //     $start_time,$end_time,-1,-1,$tea_arr);
        // $teacher_record_score = $this->t_teacher_record_list->get_test_lesson_record_score($start_time,$end_time,$tea_arr);
        foreach($ret_info as &$item){
            $teacherid = $item["teacherid"];
            $item["level"]=$item["real_level"];
            $item["lesson_count"] = $item["lesson_count"]/100;
            $item["lesson_count_score"] = $this->get_advance_score_by_num( $item["lesson_count"],1);//课耗得分
            $item["stu_num_score"]= $this->get_advance_score_by_num( $item["stu_num"],4);//常规学生签单得分
           
            // $item["cc_test_num"]    = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["person_num"]:0;
            // $item["cc_order_num"]   = isset($test_person_num[$teacherid])?$test_person_num[$teacherid]["have_order"]:0;
            $item["cc_order_score"]= $this->get_advance_score_by_num( $item["cc_order_num"],2);//cc签单数得分

            // $item["other_test_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_num"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_num"]:0);
            // $item["other_order_num"] = (isset($kk_test_person_num[$teacherid])?$kk_test_person_num[$teacherid]["kk_order"]:0)+(isset($change_test_person_num[$teacherid])?$change_test_person_num[$teacherid]["change_order"]:0);
            $item["other_order_score"]= $this->get_advance_score_by_num( $item["other_order_num"],3);//cr签单得分

          
            // $item["record_num"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["num"]:0;
            // $item["record_score"] = isset($teacher_record_score[$teacherid])?$teacher_record_score[$teacherid]["score"]:0;
            // $item["record_score_avg"] = !empty($item["record_num"])?round($item["record_score"]/$item["record_num"],1):0;
            $item["record_final_score"]= $this->get_advance_score_by_num( $item["record_score_avg"],5);//教学质量得分

            $order_score = $item["cc_order_score"]+ $item["other_order_score"];//签单总分
            if($order_score>=10){
                $order_score=10;
            }
            $item["total_score"] =$item["lesson_count_score"]+$item["record_final_score"]+$order_score+ $item["stu_num_score"];//总得分
          
            $item["hand_flag"]=0;          
            $exists = $this->t_teacher_advance_list->field_get_list_2($start_time,$teacherid,"teacherid");
            if(!$exists){
                $this->t_teacher_advance_list->row_insert([
                    "start_time" =>$start_time,
                    "teacherid"  =>$teacherid,
                    "level_before"=>$item["level"],
                    // "lesson_count"=>$item["lesson_count"]*100,
                    "lesson_count_score"=>$item["lesson_count_score"],
                    // "cc_test_num"=>$item["cc_test_num"],
                    // "cc_order_num" =>$item["cc_order_num"],
                    // "cc_order_per" =>$item["cc_order_per"],
                    "cc_order_score" =>$item["cc_order_score"],
                    // "other_test_num"=>$item["other_test_num"],
                    // "other_order_num" =>$item["other_order_num"],
                    // "other_order_per" =>$item["other_order_per"],
                    "other_order_score" =>$item["other_order_score"],
                    "record_final_score"=>$item["record_final_score"],
                    // "record_score_avg" =>$item["record_score_avg"],
                    // "record_num"     =>$item["record_num"],
                    // "is_refund"      =>$item["is_refund"],
                    "total_score"    =>$item["total_score"],
                    // "teacher_money_type"=>$item["teacher_money_type"],
                    // "stu_num"        =>$item["stu_num"],
                    "stu_num_score"  =>$item["stu_num_score"]
                ]);

            }else{
                // $this->t_teacher_advance_list->field_update_list_2($start_time,$teacherid,[
                //     "level_before"=>$item["level"],
                //     "lesson_count"=>$item["lesson_count"]*100,
                //     "lesson_count_score"=>$item["lesson_count_score"],
                //     "cc_test_num"=>$item["cc_test_num"],
                //     "cc_order_num" =>$item["cc_order_num"],
                //     "cc_order_per" =>$item["cc_order_per"],
                //     "cc_order_score" =>$item["cc_order_score"],
                //     "other_test_num"=>$item["other_test_num"],
                //     "other_order_num" =>$item["other_order_num"],
                //     "other_order_per" =>$item["other_order_per"],
                //     "other_order_score" =>$item["other_order_score"],
                //     "record_final_score"=>$item["record_final_score"],
                //     "record_score_avg" =>$item["record_score_avg"],
                //     "record_num"     =>$item["record_num"],
                //     "is_refund"      =>$item["is_refund"],
                //     "total_score"    =>$item["total_score"],
                //     "teacher_money_type"=>$item["teacher_money_type"],
                //     "stu_num"        =>$item["stu_num"],
                //     "stu_num_score"  =>$item["stu_num_score"]
                // ]);

            }

        }

        $start_time = strtotime("2017-10-01");
        $ret_info = $task->t_teacher_advance_list->get_info_by_teacher_money_type($start_time,$teacher_money_type);
        foreach($ret_info as &$item){
            //$item["level"]=$item["level_before"];
            // $item["level"]=$item["real_level"];
            // if($teacher_money_type==6){
            //     //  E\Enew_level::set_item_value_str($item,"level_before");
            //     // E\Enew_level::set_item_value_str($item,"level_after");
            //     $item["level_str"] = E\Enew_level::get_simple_desc($item["level"]);
            //     $item["level_after_str"] = E\Enew_level::get_simple_desc($item["level_after"]);


            // }else{
            //     //  E\Elevel::set_item_value_str($item,"level_before");
            //     // E\Elevel::set_item_value_str($item,"level_after");
            //     $item["level_str"] = E\Elevel::get_simple_desc($item["level"]);
            //     $item["level_after_str"] = E\Elevel::get_simple_desc($item["level_after"]);

            // }
            // \App\Helper\Utils::unixtime2date_for_item($item,"accept_time","_str");
            // \App\Helper\Utils::unixtime2date_for_item($item,"require_time","_str");

            // E\Eaccept_flag::set_item_value_str($item);
            // E\Eaccept_flag::set_item_value_str($item,"withhold_final_trial_flag");
            // E\Eaccept_flag::set_item_value_str($item,"advance_first_trial_flag");
            // E\Eaccept_flag::set_item_value_str($item,"withhold_first_trial_flag");
            $item["lesson_count"] = $item["lesson_count"]/100;
            $item["lesson_count_score"] = $task->get_advance_score_by_num( $item["lesson_count"],1);//课耗得分
            $item["record_final_score"]= $task->get_advance_score_by_num( $item["record_score_avg"],5);//教学质量得分
            $item["cc_order_score"]= $task->get_advance_score_by_num( $item["cc_order_num"],2);//cc签单数得分
            $item["other_order_score"]= $task->get_advance_score_by_num( $item["other_order_num"],3);//cr签单得分
            $item["stu_num_score"]= $task->get_advance_score_by_num( $item["stu_num"],4);//常规学生签单得分
            $order_score = $item["cc_order_score"]+ $item["other_order_score"];//签单总分
            if($order_score>=10){
                $order_score=10;
            }
            $item["total_score"] =$item["lesson_count_score"]+$item["record_final_score"]+$order_score+ $item["stu_num_score"];//总得分
            list($item["reach_flag"],$item["withhold_money"])=$task->get_tea_reach_withhold_list($item["level_before"],$item["total_score"]);
            $task->t_teacher_advance_list->field_update_list_2($item["start_time"],$item["teacherid"],[
                "lesson_count_score" => $item["lesson_count_score"]*100,
                "cc_order_score"     => $item["cc_order_score"]*100,
                "other_order_score"  =>  $item["other_order_score"]*100,
                "stu_num_score"      => $item["stu_num_score"]*100,
                "total_score"        => $item["total_score"]*100,
                "reach_flag"         => $item["reach_flag"],
                "withhold_money"     => $item["withhold_money"]*100,
                "record_final_score" => $item["record_final_score"]*100
            ]);
            // E\Eboolean::set_item_value_str($item,"reach_flag");

        }
        dd(111);


        $user_map_60 = $task->t_lesson_info->get_user_list(60);
        $user_map_90 = $task->t_lesson_info->get_user_list(90);
        $user_map2 = $user_map3 = [];
        foreach ($user_map_90 as $key => $item){
            if(!isset($user_map_60[$key])){
                $user_map2[$key] = $item;
            }
        }
        $user_map_all = $task->t_student_info->get_student_lesson_all();
        $user_map_new = $task->t_student_info->get_student_list_new_id();
        foreach($user_map_all as $k => $v){
            if(!isset($user_map_new[$k]) && !isset($user_map_90[$k])){
                $user_map3[$k]= $v;
            }
        }
         #dd($user_map3);

        $ret_student_info = $task->t_student_info->get_student_list_id();
        $ret_student_end_info = $task->t_student_info->get_student_list_end_id();
        #dd($ret_student_info);
        foreach ($ret_student_info as $item) {
            $userid=$item["userid"];
            if ( isset( $user_map_60[$userid]) || isset($user_map_new[$userid] )) {
                if($item["type"] != 0){
                    $task->t_student_info->get_student_type_update($userid,0);
                    $task->t_student_type_change_list->row_insert([
                        "userid"    =>$userid,
                        "add_time"  =>time(),
                        "type_before" =>$item["type"],
                        "type_cur"    =>0,
                        "change_type" =>1,
                        "adminid"     =>0,
                        "reason"      =>"系统更新"
                    ]);

                }
            }
            if ( isset( $user_map2[$userid]  )) {
                if($item["type"] != 2){
                    $task->t_student_info->get_student_type_update($userid,2);
                    $task->t_student_type_change_list->row_insert([
                        "userid"    =>$userid,
                        "add_time"  =>time(),
                        "type_before" =>$item["type"],
                        "type_cur"    =>2,
                        "change_type" =>1,
                        "adminid"     =>0,
                        "reason"      =>"系统更新"
                    ]);

                }
            }
            if ( isset( $user_map3[$userid]  )) {
                if($item["type"] != 3){
                    $task->t_student_info->get_student_type_update($userid,3);
                    $task->t_student_type_change_list->row_insert([
                        "userid"    =>$userid,
                        "add_time"  =>time(),
                        "type_before" =>$item["type"],
                        "type_cur"    =>3,
                        "change_type" =>1,
                        "adminid"     =>0,
                        "reason"      =>"系统更新"
                    ]);

                }
            }
        }
        foreach ($ret_student_end_info as $item) {
            $userid=$item["userid"];
            if($item["type"] != 1){
                $task->t_student_info->get_student_type_update($userid,1);
                $task->t_student_type_change_list->row_insert([
                    "userid"    =>$userid,
                    "add_time"  =>time(),
                    "type_before" =>$item["type"],
                    "type_cur"    =>1,
                    "change_type" =>1,
                    "adminid"     =>0,
                    "reason"      =>"系统更新"
                ]);

                /*$seller_adminid = $task->t_seller_student_new->get_admin_revisiterid($userid);
                $nick = $task->t_student_info->get_nick($userid);                      
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($seller_adminid,"理优教育","结课学员通知","您的一个学生".$nick."已经结课,请关注","");*/

            }
        }

        /*
          $ret_lesson_stop_info = $task->t_lesson_info->get_lesson_list_stop_id();
          unset($ret_lesson_stop_info[0]);
          $ret_lesson_end_info = $task->t_lesson_info->get_lesson_list_end_id();
          unset($ret_lesson_end_info[0]);

        */

        //$list=$task->t_lesson_info-> //60
        //
    }
}
