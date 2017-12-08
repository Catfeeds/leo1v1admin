<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class jw_teacher_test_lesson_assign_auto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:jw_teacher_test_lesson_assign_auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '试听排课自动分配教务老师';

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
        $start_time  = strtotime(date("Y-m-d"),time());
        $end_time    = time() + 86400*7;
        $w = date("w");
        if($w != 2){
            $list = $task->t_test_lesson_subject_require->get_all_need_plan_require_list($start_time,$end_time,-1);
            foreach($list as $vall){
                $grade = $vall["grade"];
                $subject = $vall["subject"];
                if($subject==2){
                    $jw_list=[1328,1324];
                              
                    $num_all = count($jw_list);
                    $i=0;
                    foreach($jw_list as $k=>$val){
                        $json_ret=\App\Helper\Common::redis_get_json("JW_AUTO_ASSIGN_NEW_$val");
                        if (!$json_ret) {
                            $json_ret=0;
                            \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$val", $json_ret);
                        }
                        if($json_ret>0){
                            $i++;
                        }
                    }
                    if($i==$num_all){
                        foreach($jw_list as $k=>$val){
                            \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$val", 0);
                        }
                    }
                    foreach($jw_list as $k=>$val){
                        $json_ret=\App\Helper\Common::redis_get_json("JW_AUTO_ASSIGN_NEW_$val");
                        if($json_ret==0){                           
                            $accept_adminid=$val;
                            \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$val", 1);
                            break;
               
                        }
                    }

                    
                }elseif($subject==3){
                    $jw_list=[1329,1326];
                              
                    $num_all = count($jw_list);
                    $i=0;
                    foreach($jw_list as $k=>$val){
                        $json_ret=\App\Helper\Common::redis_get_json("JW_AUTO_ASSIGN_NEW_$val");
                        if (!$json_ret) {
                            $json_ret=0;
                            \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$val", $json_ret);
                        }
                        if($json_ret>0){
                            $i++;
                        }
                    }
                    if($i==$num_all){
                        foreach($jw_list as $k=>$val){
                            \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$val", 0);
                        }
                    }
                    foreach($jw_list as $k=>$val){
                        $json_ret=\App\Helper\Common::redis_get_json("JW_AUTO_ASSIGN_NEW_$val");
                        if($json_ret==0){                           
                            $accept_adminid=$val;
                            \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$val", 1);

                            break;
               
                        }
                    }

                    
                }elseif($grade>=100 && $grade<200 && $subject==2){
                    // $accept_adminid = 486;
                }else if($grade>=200 && $grade<300 && $subject==2){
                    // $accept_adminid = 895;
                }else if($grade>=300 && $grade<400 && $subject==2){
                    // $accept_adminid = 895;
                }else if($grade>=100 && $grade<200 && $subject==1){
                    $accept_adminid = 1238;
                }else if($grade>=200 && $grade<400 && $subject==1){
                    $accept_adminid = 513;
                }elseif($subject>3){
                    $accept_adminid = 436;
                }

                $task->t_test_lesson_subject_require->field_update_list($vall["require_id"],[
                    "accept_adminid"=>$accept_adminid,
                    "require_assign_time"=>time()
                ]);
                $task->t_manager_info->send_wx_todo_msg_by_adminid(349,"试听需求","试听需求","科目:".$subject.",年级:".$grade."教务:".$accept_adminid,"");
 
                               

            }


            // //小学数学
            // $ret = $task->t_test_lesson_subject_require->get_all_need_plan_require_list($start_time,$end_time,2);
            // $jw_list=[723,343,723,343,723,343,723,723,723,723,723,723,723];

            // if(!empty($ret)){
            //     foreach($ret as $item){
            //         $subject = $item["subject"];
            //         $grade = $item["grade"];
            
            //         $num_all = count($jw_list);
            //         $i=0;
            //         foreach($jw_list as $k=>$val){
            //             $json_ret=\App\Helper\Common::redis_get_json("JW_AUTO_ASSIGN_NEW_$k");
            //             if (!$json_ret) {
            //                 $json_ret=0;
            //                 \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", $json_ret);
            //             }
            //             // $seller_arr[$k] = $json_ret;
            //             if($json_ret>0){
            //                 $i++;
            //             }
            //             echo $json_ret;
            //         }
            //         if($i==$num_all){
            //             foreach($jw_list as $k=>$val){
            //                 \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", 0);
            //             }
            //         }
            //         foreach($jw_list as $k=>$val){
            //             $json_ret=\App\Helper\Common::redis_get_json("JW_AUTO_ASSIGN_NEW_$k");
            //             if($json_ret==0){
            //                 $task->t_test_lesson_subject_require->field_update_list($item["require_id"],[
            //                     "accept_adminid"=>$val,
            //                     "require_assign_time"=>time()
            //                 ]);                          
            //                 \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", 1);


            //                 break;
               
            //             }
            //         }
            //         $task->t_manager_info->send_wx_todo_msg_by_adminid(349,"试听需求","试听需求","科目:".$subject.",年级:".$grade."教务:".$val,"");


                   



            //     }
            // }
        }

               
        // $seller_top_list = $task->t_test_lesson_subject_require->get_seller_top_require_list($start_time,$end_time);
        // $jw_leader_list = $task->t_manager_info->get_jw_teacher_list_leader();
        // if(!empty($seller_top_list)){
        //     foreach($seller_top_list as $item){
            
        //         $num_all = count($jw_leader_list);
        //         $i=0;
        //         $seller_arr=[];
        //         foreach($jw_leader_list as $k=>$val){
        //             $json_ret=\App\Helper\Common::redis_get_json("JW_AUTO_ASSIGN_NEW_$k");
        //             if (!$json_ret) {
        //                 $json_ret=0;
        //                 \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", $json_ret);
        //             }
        //             // $seller_arr[$k] = $json_ret;
        //             if($json_ret>0){
        //                 $i++;
        //             }
        //             echo $json_ret;
        //         }
        //         //asort($seller_arr);
        //         if($i==$num_all){
        //             foreach($jw_leader_list as $k=>$val){
        //                 \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", 0);
        //             }
        //         }
        //         /* foreach($seller_arr as $k=>$val){
        //             $task->t_test_lesson_subject_require->field_update_list($item["require_id"],[
        //                 "accept_adminid"=>$k,
        //                 "require_assign_time"=>time()
        //             ]);

        //             $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
        //                 $item["require_id"]);

        //             $task->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
        //                 "history_accept_adminid"=>$k
        //             ]);

        //             $value = $val+1; 
        //             \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", $value);
        //             $require_lesson_time = date("Y-m-d",$item["stu_request_test_lesson_time"]);
        //             $url = "seller_student_new2/test_lesson_plan_list?date_type=2&opt_date_type=0&"
        //                  ."start_time=".$require_lesson_time."&end_time=".$require_lesson_time."&"
        //                  ."test_lesson_student_status=200&lessonid=undefined&is_test_user=0&"
        //                  ."require_assign_flag=1&jw_test_lesson_status=0&jw_teacher=".$k;

        //             $task->t_manager_info->send_wx_todo_msg_by_adminid($k,"销售top25试听需求","销售top25试听需求","销售top25试听需求,学生:".$item["nick"],$url);
        //             // $task->t_manager_info->send_wx_todo_msg_by_adminid(349,"销售top25试听需求","销售top25试听需求","销售top25试听需求,学生:".$item["nick"].",教务:".$k,$url);

        //             break;

        //             }*/
        
           
        //         foreach($jw_leader_list as $k=>$val){
        //             $json_ret=\App\Helper\Common::redis_get_json("JW_AUTO_ASSIGN_NEW_$k");
        //             if($json_ret==0){
        //                 $task->t_test_lesson_subject_require->field_update_list($item["require_id"],[
        //                     "accept_adminid"=>$val,
        //                     "require_assign_time"=>time()
        //                 ]);

        //                 $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
        //                     $item["require_id"]);

        //                 $task->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
        //                     "history_accept_adminid"=>$val
        //                 ]);

                       
        //                 \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", 1);
        //                 $require_lesson_time = date("Y-m-d",$item["stu_request_test_lesson_time"]);
        //                 $url = "seller_student_new2/test_lesson_plan_list?date_type=2&opt_date_type=0&"
        //                      ."start_time=".$require_lesson_time."&end_time=".$require_lesson_time."&"
        //                      ."test_lesson_student_status=200&lessonid=undefined&is_test_user=0&"
        //                      ."require_assign_flag=1&jw_test_lesson_status=0&jw_teacher=".$val;

        //                 $task->t_manager_info->send_wx_todo_msg_by_adminid($val,"销售top25试听需求","销售top25试听需求","销售top25试听需求,学生:".$item["nick"],$url);
        //                 // $task->t_manager_info->send_wx_todo_msg_by_adminid(349,"销售top25试听需求","销售top25试听需求","销售top25试听需求,学生:".$item["nick"].",教务:".$val,$url);
        //                 // if($item["seller_top_flag"]==1 && !in_array($val,[436,343])){
        //                 //     $task->t_manager_info->send_wx_todo_msg_by_adminid(349,"销售top25试听需求(例外)","销售top25试听需求(例外)","销售top25试听需求,学生:".$item["nick"].",教务:".$val.",途径:seller",$url);
        //                 // }

        //                 break;
               
        //             }
        //         }


        //     }

        // }


        // $list_left=[];
        // $histroy_accept_adminid_list=$task->t_test_lesson_subject_require->get_jw_teacher_history_accept_adminid($start_time,
        //                                                                                                          $end_time);
        // foreach($histroy_accept_adminid_list as $item){
        //     $history_adminid = $item["history_accept_adminid"];
        //     $work_status = $task->t_manager_info->get_admin_work_status($history_adminid);
        //     if($work_status==0){
        //         $list_left[] =$item; 
        //     }else if($work_status==1){
        //         $require_adminid = $item["require_adminid"];

        //         $require_adminid_name = $task->t_manager_info->get_account($require_adminid);
        //         $nick = $item["nick"];
        //         $require_lesson_time = date("Y-m-d",$item["stu_request_test_lesson_time"]);

        //         $task->t_test_lesson_subject_require->field_update_list($item["require_id"],[
        //             "accept_adminid"      => $history_adminid,
        //             "require_assign_time" => time()
        //         ]);

        //         $header_msg = "试听课取消重新申请排课";
        //         $msg        = "学生:".$nick."的试听课时间取消后重新申请至".$require_lesson_time."上课,请优先排课!";
        //         $url = "seller_student_new2/test_lesson_plan_list?date_type=2&opt_date_type=0&"
        //              ."start_time=".$require_lesson_time."&end_time=".$require_lesson_time."&"
        //              ."test_lesson_student_status=200&lessonid=undefined&is_test_user=0&"
        //              ."require_assign_flag=1&jw_test_lesson_status=0&jw_teacher=".$history_adminid;
        //         $task->t_manager_info->send_wx_todo_msg_by_adminid($history_adminid,$require_adminid_name,$header_msg,$msg,$url);
        //         if($item["seller_top_flag"]==1 && !in_array($history_adminid,[436,343])){
        //             $task->t_manager_info->send_wx_todo_msg_by_adminid(349,"销售top25试听需求(例外)","销售top25试听需求(例外)","销售top25试听需求,学生:".$item["nick"].",教务:".$history_adminid.",途径:history",$url);
        //         }

 
        //     }
           
        // }
        // $jw_teacher_list_all = $task->t_manager_info->get_jw_teacher_list_all();
        // if(!empty($list_left)){
        //     foreach($list_left as $v){
        //         $num_all = count($jw_teacher_list_all);
        //         $i=0;
        //         $left_arr=[];
        //         foreach($jw_teacher_list_all as $k=>$val){
        //             $json_ret=\App\Helper\Common::redis_get_json("JW_AUTO_ASSIGN_NEW_$k");
        //             if (!$json_ret) {
        //                 $json_ret=0;
        //                 \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", $json_ret);
        //             }
        //             if($json_ret>0){
        //                 $i++;
        //             }
        //             // echo $json_ret;
        //             // $left_arr[$k] = $json_ret;
        //         }
        //         // asort($left_arr);
        //         if($i==$num_all){
        //             foreach($jw_teacher_list_all as $k=>$val){
        //                 \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", 0);
        //             }
        //         }
        
        //         /*foreach($left_arr as $k=>$i){
        //             $task->t_test_lesson_subject_require->field_update_list($v["require_id"],[
        //                 "accept_adminid"      => $k,
        //                 "require_assign_time" => time()
        //             ]);
        //             $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
        //                 $v["require_id"]);
        //             $task->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
        //                 "history_accept_adminid"=>$k
        //             ]);
        //             $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
        //                 $v["require_id"]);
        //             $require_adminid_list= $task->t_test_lesson_subject->field_get_list($test_lesson_subject_id,
        //                                                                                 "require_adminid,userid,stu_request_test_lesson_time");
        //             $require_adminid_name = $task->t_manager_info->get_account($require_adminid_list["require_adminid"]);
        //             $nick = $task->t_student_info->get_nick($require_adminid_list["userid"]);
        //             $require_lesson_time = date("Y-m-d",$require_adminid_list["stu_request_test_lesson_time"]);
        //             $header_msg = "试听课取消重新申请排课";
        //             $msg        = "学生:".$nick."的试听课时间取消后重新申请至".$require_lesson_time."上课,请优先排课!";
        //             $url = "seller_student_new2/test_lesson_plan_list?date_type=2&opt_date_type=0&"
        //                  ."start_time=".$require_lesson_time."&end_time=".$require_lesson_time."&"
        //                  ."test_lesson_student_status=200&lessonid=undefined&is_test_user=0&"
        //                  ."require_assign_flag=1&jw_test_lesson_status=0&jw_teacher=".$k;
        //             $task->t_manager_info->send_wx_todo_msg_by_adminid($k,$require_adminid_name,$header_msg,$msg,$url);
        //             // $task->t_manager_info->send_wx_todo_msg_by_adminid(349,$require_adminid_name,$header_msg,$msg."教务:".$k,$url);
 
        //             $value= $i+1;
        //             \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", $value);
        //             break;
 
        //             }*/
        //         foreach($jw_teacher_list_all as $k=>$val){
        //             $json_ret=\App\Helper\Common::redis_get_json("JW_AUTO_ASSIGN_NEW_$k");
        //             if($json_ret==0){
        //                 $task->t_test_lesson_subject_require->field_update_list($v["require_id"],[
        //                     "accept_adminid"      => $k,
        //                     "require_assign_time" => time()
        //                 ]);
        //                 $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
        //                     $v["require_id"]);
        //                 $task->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
        //                     "history_accept_adminid"=>$k
        //                 ]);
        //                 $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
        //                     $v["require_id"]);
        //                 $require_adminid_list= $task->t_test_lesson_subject->field_get_list($test_lesson_subject_id,
        //                                                                                     "require_adminid,userid,stu_request_test_lesson_time");
        //                 $require_adminid_name = $task->t_manager_info->get_account($require_adminid_list["require_adminid"]);
        //                 $nick = $task->t_student_info->get_nick($require_adminid_list["userid"]);
        //                 $require_lesson_time = date("Y-m-d",$require_adminid_list["stu_request_test_lesson_time"]);
        //                 $header_msg = "试听课取消重新申请排课";
        //                 $msg        = "学生:".$nick."的试听课时间取消后重新申请至".$require_lesson_time."上课,请优先排课!";
        //                 $url = "seller_student_new2/test_lesson_plan_list?date_type=2&opt_date_type=0&"
        //                      ."start_time=".$require_lesson_time."&end_time=".$require_lesson_time."&"
        //                      ."test_lesson_student_status=200&lessonid=undefined&is_test_user=0&"
        //                      ."require_assign_flag=1&jw_test_lesson_status=0&jw_teacher=".$k;
        //                 $task->t_manager_info->send_wx_todo_msg_by_adminid($k,$require_adminid_name,$header_msg,$msg,$url);
        //                 //   $task->t_manager_info->send_wx_todo_msg_by_adminid(349,$require_adminid_name,$header_msg,$msg."教务:".$k,$url);
        //                 if($v["seller_top_flag"]==1 && !in_array($k,[436,343])){
        //                     $task->t_manager_info->send_wx_todo_msg_by_adminid(349,"销售top25试听需求(例外)","销售top25试听需求(例外)","销售top25试听需求,学生:".$nick.",教务:".$k.",途径:history",$url);
        //                 }

 
        //                 \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", 1);
        //                 break;
               
        //             }
        //         }


        //     }
        // }
        // /* shuffle($jw_teacher_list_all);
        // if(count($list_left) >0){
        //     $m = 0;
        //     foreach($jw_teacher_list_all as $val){
        //         if(isset($list_left[$m])){
        //             $task->t_test_lesson_subject_require->field_update_list($list_left[$m]["require_id"],[
        //                 "accept_adminid"      => $val,
        //                 "require_assign_time" => time()
        //             ]);
        //             $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
        //                 $list_left[$m]["require_id"]);
        //             $task->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
        //                 "history_accept_adminid"=>$val
        //             ]);
        //             $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
        //                 $list_left[$m]["require_id"]);
        //             $require_adminid_list= $task->t_test_lesson_subject->field_get_list($test_lesson_subject_id,
        //                                                                                 "require_adminid,userid,stu_request_test_lesson_time");
        //             $require_adminid_name = $task->t_manager_info->get_account($require_adminid_list["require_adminid"]);
        //             $nick = $task->t_student_info->get_nick($require_adminid_list["userid"]);
        //             $require_lesson_time = date("Y-m-d",$require_adminid_list["stu_request_test_lesson_time"]);
        //             $header_msg = "试听课取消重新申请排课";
        //             $msg        = "学生:".$nick."的试听课时间取消后重新申请至".$require_lesson_time."上课,请优先排课!";
        //             $url = "seller_student_new2/test_lesson_plan_list?date_type=2&opt_date_type=0&"
        //                  ."start_time=".$require_lesson_time."&end_time=".$require_lesson_time."&"
        //                  ."test_lesson_student_status=200&lessonid=undefined&is_test_user=0&"
        //                  ."require_assign_flag=1&jw_test_lesson_status=0&jw_teacher=".$val;
        //             $task->t_manager_info->send_wx_todo_msg_by_adminid($val,$require_adminid_name,$header_msg,$msg,$url);

        //             $m++;
        //         }           
        //     }
  
        //     }*/
        // $num_all = count($jw_teacher_list_all);

        // $green_channel_list = $task->t_test_lesson_subject_require->get_green_channel_require_id($start_time,$end_time,$num_all);
        // if(!empty($green_channel_list)){
        //     foreach($green_channel_list as $v){
        //         $num_all = count($jw_teacher_list_all);
        //         $i=0;
        //         $green_arr=[];
        //         foreach($jw_teacher_list_all as $k=>$val){
        //             $json_ret=\App\Helper\Common::redis_get_json("JW_AUTO_ASSIGN_NEW_$k");
        //             if (!$json_ret) {
        //                 $json_ret=0;
        //                 \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", $json_ret);
        //             }
        //             //$green_arr[$k] = $json_ret;
        //             if($json_ret>0){
        //                 $i++;
        //             }
        //             // echo $json_ret;
        //         }
        //         // asort($green_arr);
        //         if($i==$num_all){
        //             foreach($jw_teacher_list_all as $k=>$val){
        //                 \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", 0);
        //             }
        //         }
        //         /* foreach($green_arr as $k=>$u){
        //             $task->t_test_lesson_subject_require->field_update_list($v["require_id"],[
        //                 "accept_adminid"      => $k,
        //                 "require_assign_time" => time()
        //             ]);
        //             $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
        //                 $v["require_id"]);
        //             $task->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
        //                 "history_accept_adminid"=>$k
        //             ]);
        //             $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
        //                 $v["require_id"]);
        //             $require_adminid_list= $task->t_test_lesson_subject->field_get_list($test_lesson_subject_id,
        //                                                                                 "require_adminid,userid,stu_request_test_lesson_time");
        //             $require_adminid_name = $task->t_manager_info->get_account($require_adminid_list["require_adminid"]);
        //             $nick = $task->t_student_info->get_nick($require_adminid_list["userid"]);
        //             $require_lesson_time = date("Y-m-d",$require_adminid_list["stu_request_test_lesson_time"]);
        //             $task->t_manager_info->send_wx_todo_msg_by_adminid ($k,$require_adminid_name,"绿色通道排课申请","学生:".$nick."的试听课已由绿色通道申请至".$require_lesson_time."上课,请优先排课!","seller_student_new2/test_lesson_plan_list?date_type=2&has_1v1_lesson_flag=-1&opt_date_type=0&start_time=".$require_lesson_time."&end_time=".$require_lesson_time."&grade=-1&subject=-1&test_lesson_student_status=200&lessonid=undefined&userid=-1&teacherid=-1&success_flag=-1&require_admin_type=-1&require_adminid=".$require_adminid_list["require_adminid"]."&tmk_adminid=-1&is_test_user=0&test_lesson_fail_flag=-1&accept_flag=-1&seller_groupid_ex=&seller_require_change_flag=-1&require_assign_flag=1&jw_test_lesson_status=0&jw_teacher=".$k."&ass_test_lesson_type=-1");
        //             //  $task->t_manager_info->send_wx_todo_msg_by_adminid (349,$require_adminid_name,"绿色通道排课申请","学生:".$nick."的试听课已由绿色通道申请至".$require_lesson_time."上课,请优先排课!,教务:".$k,"seller_student_new2/test_lesson_plan_list?date_type=2&has_1v1_lesson_flag=-1&opt_date_type=0&start_time=".$require_lesson_time."&end_time=".$require_lesson_time."&grade=-1&subject=-1&test_lesson_student_status=200&lessonid=undefined&userid=-1&teacherid=-1&success_flag=-1&require_admin_type=-1&require_adminid=".$require_adminid_list["require_adminid"]."&tmk_adminid=-1&is_test_user=0&test_lesson_fail_flag=-1&accept_flag=-1&seller_groupid_ex=&seller_require_change_flag=-1&require_assign_flag=1&jw_test_lesson_status=0&jw_teacher=".$k."&ass_test_lesson_type=-1");
                       
        //             $value = $u+1;
        //             \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", $value);
        //             break;

        //             }*/
        
           
        //         foreach($jw_teacher_list_all as $k=>$val){
        //             $json_ret=\App\Helper\Common::redis_get_json("JW_AUTO_ASSIGN_NEW_$k");
        //             if($json_ret==0){
        //                 $task->t_test_lesson_subject_require->field_update_list($v["require_id"],[
        //                     "accept_adminid"      => $val,
        //                     "require_assign_time" => time()
        //                 ]);
        //                 $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
        //                     $v["require_id"]);
        //                 $task->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
        //                     "history_accept_adminid"=>$val
        //                 ]);
        //                 $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
        //                     $v["require_id"]);
        //                 $require_adminid_list= $task->t_test_lesson_subject->field_get_list($test_lesson_subject_id,
        //                                                                                     "require_adminid,userid,stu_request_test_lesson_time");
        //                 $require_adminid_name = $task->t_manager_info->get_account($require_adminid_list["require_adminid"]);
        //                 $nick = $task->t_student_info->get_nick($require_adminid_list["userid"]);
        //                 $require_lesson_time = date("Y-m-d",$require_adminid_list["stu_request_test_lesson_time"]);
        //                 $task->t_manager_info->send_wx_todo_msg_by_adminid ($val,$require_adminid_name,"绿色通道排课申请","学生:".$nick."的试听课已由绿色通道申请至".$require_lesson_time."上课,请优先排课!","seller_student_new2/test_lesson_plan_list?date_type=2&has_1v1_lesson_flag=-1&opt_date_type=0&start_time=".$require_lesson_time."&end_time=".$require_lesson_time."&grade=-1&subject=-1&test_lesson_student_status=200&lessonid=undefined&userid=-1&teacherid=-1&success_flag=-1&require_admin_type=-1&require_adminid=".$require_adminid_list["require_adminid"]."&tmk_adminid=-1&is_test_user=0&test_lesson_fail_flag=-1&accept_flag=-1&seller_groupid_ex=&seller_require_change_flag=-1&require_assign_flag=1&jw_test_lesson_status=0&jw_teacher=".$val."&ass_test_lesson_type=-1");
        //                 //  $task->t_manager_info->send_wx_todo_msg_by_adminid (349,$require_adminid_name,"绿色通道排课申请","学生:".$nick."的试听课已由绿色通道申请至".$require_lesson_time."上课,请优先排课!,教务:".$k,"seller_student_new2/test_lesson_plan_list?date_type=2&has_1v1_lesson_flag=-1&opt_date_type=0&start_time=".$require_lesson_time."&end_time=".$require_lesson_time."&grade=-1&subject=-1&test_lesson_student_status=200&lessonid=undefined&userid=-1&teacherid=-1&success_flag=-1&require_admin_type=-1&require_adminid=".$require_adminid_list["require_adminid"]."&tmk_adminid=-1&is_test_user=0&test_lesson_fail_flag=-1&accept_flag=-1&seller_groupid_ex=&seller_require_change_flag=-1&require_assign_flag=1&jw_test_lesson_status=0&jw_teacher=".$k."&ass_test_lesson_type=-1");

        //                 if($v["seller_top_flag"]==1 && !in_array($val,[436,343])){
        //                     $task->t_manager_info->send_wx_todo_msg_by_adminid(349,"销售top25试听需求(例外)","销售top25试听需求(例外)","销售top25试听需求,学生:".$nick.",教务:".$val.",途径:green",$url);
        //                 }

                       
 

        //                 \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", 1);
        //                 break;
               
        //             }
        //         }


        //     }
        // }


        // /*  $j = 0;
        // foreach($jw_teacher_list_all as $val){
        //     if(isset($green_channel_list[$j])){
        //         $task->t_test_lesson_subject_require->field_update_list($green_channel_list[$j]["require_id"],[
        //             "accept_adminid"      => $val,
        //             "require_assign_time" => time()
        //         ]);
        //         $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
        //             $green_channel_list[$j]["require_id"]);
        //         $task->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
        //             "history_accept_adminid"=>$val
        //         ]);
        //         $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
        //             $green_channel_list[$j]["require_id"]);
        //         $require_adminid_list= $task->t_test_lesson_subject->field_get_list($test_lesson_subject_id,
        //                                                                             "require_adminid,userid,stu_request_test_lesson_time");
        //         $require_adminid_name = $task->t_manager_info->get_account($require_adminid_list["require_adminid"]);
        //         $nick = $task->t_student_info->get_nick($require_adminid_list["userid"]);
        //         $require_lesson_time = date("Y-m-d",$require_adminid_list["stu_request_test_lesson_time"]);
        //         $task->t_manager_info->send_wx_todo_msg_by_adminid ($val,$require_adminid_name,"绿色通道排课申请","学生:".$nick."的试听课已由绿色通道申请至".$require_lesson_time."上课,请优先排课!","seller_student_new2/test_lesson_plan_list?date_type=2&has_1v1_lesson_flag=-1&opt_date_type=0&start_time=".$require_lesson_time."&end_time=".$require_lesson_time."&grade=-1&subject=-1&test_lesson_student_status=200&lessonid=undefined&userid=-1&teacherid=-1&success_flag=-1&require_admin_type=-1&require_adminid=".$require_adminid_list["require_adminid"]."&tmk_adminid=-1&is_test_user=0&test_lesson_fail_flag=-1&accept_flag=-1&seller_groupid_ex=&seller_require_change_flag=-1&require_assign_flag=1&jw_test_lesson_status=0&jw_teacher=".$val."&ass_test_lesson_type=-1");
        //         $j++;
        //     }           
        // }
        // */

        // $jw_teacher_list = $task->t_manager_info->get_jw_teacher_list();
        // //dd($jw_teacher_list);
        // // shuffle($jw_teacher_list);
        // $num = count($jw_teacher_list);


        // $test_lesson_require_list = $task->t_test_lesson_subject_require->get_test_lesson_require_list_for_jw(
        //     $start_time,$end_time,$num*4
        // );

        // if(!empty($test_lesson_require_list)){
        //     foreach($test_lesson_require_list as $v){
        //         $num_all = count($jw_teacher_list);
        //         $i=0;
        //         $normal_arr=[];
        //         foreach($jw_teacher_list as $k=>$val){
        //             $json_ret=\App\Helper\Common::redis_get_json("JW_AUTO_ASSIGN_NEW_$k");
        //             if (!$json_ret) {
        //                 $json_ret=0;
        //                 \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", $json_ret);
        //             }
        //             //$normal_arr[$k]=$json_ret;
        //             if($json_ret>0){
        //                 $i++;
        //             }
        //             // echo $json_ret;
        //         }
        //         //asort($normal_arr);
        //         if($i==$num_all){
        //             foreach($jw_teacher_list as $k=>$val){
        //                 \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", 0);
        //             }
        //         }
        
        //         /* foreach($normal_arr as $k=>$val){
        //             $task->t_test_lesson_subject_require->field_update_list($v["require_id"],[
        //                 "accept_adminid"=>$k,
        //                 "require_assign_time"=>time()
        //             ]);

        //             $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
        //                 $v["require_id"]);

        //             $task->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
        //                 "history_accept_adminid"=>$k
        //             ]);

        //             //  $task->t_manager_info->send_wx_todo_msg_by_adminid (349,"普通试听","普通试听申请","教务:".$k,"");


        //             $value = $val+1;   
        //             \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", $value);
        //             break;
 
        //             }*/
        //         foreach($jw_teacher_list as $k=>$val){
        //             $json_ret=\App\Helper\Common::redis_get_json("JW_AUTO_ASSIGN_NEW_$k");
        //             if($json_ret==0){
        //                 $task->t_test_lesson_subject_require->field_update_list($v["require_id"],[
        //                     "accept_adminid"=>$val,
        //                     "require_assign_time"=>time()
        //                 ]);

        //                 $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
        //                     $v["require_id"]);

        //                 $task->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
        //                     "history_accept_adminid"=>$val
        //                 ]);

        //                 if($v["seller_top_flag"]==1 && !in_array($val,[436,343])){
        //                     $task->t_manager_info->send_wx_todo_msg_by_adminid(349,"销售top25试听需求(例外)","销售top25试听需求(例外)","销售top25试听需求,教务:".$val.",途径:normal","");
        //                 }


        //                 // $task->t_manager_info->send_wx_todo_msg_by_adminid (349,"普通试听","普通试听申请","教务:".$k,"");
        //                 \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_$k", 1);
        //                 break;
               
        //             }
        //         }


        //     }
        // }


        ////旧版截止


        /* $i = 0;
        foreach($jw_teacher_list as $val){
            if(isset($test_lesson_require_list[$i])){
                $task->t_test_lesson_subject_require->field_update_list($test_lesson_require_list[$i]["require_id"],[
                    "accept_adminid"=>$val,
                    "require_assign_time"=>time()
                ]);

                $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
                    $test_lesson_require_list[$i]["require_id"]);

                $task->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
                    "history_accept_adminid"=>$val
                ]);
                $i++;
            }           
        }

        if(count($test_lesson_require_list) >= $num){
            $test_lesson_require_list1 = $task->t_test_lesson_subject_require->get_test_lesson_require_list_for_jw(
                $start_time,$end_time,$num
            );
            $j = 0;
            foreach($jw_teacher_list as $val){
                if(isset($test_lesson_require_list1[$j])){
                    $task->t_test_lesson_subject_require->field_update_list($test_lesson_require_list1[$j]["require_id"],[
                        "accept_adminid"=>$val,
                        "require_assign_time"=>time()
                    ]);

                    $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
                        $test_lesson_require_list1[$j]["require_id"]);

                    $task->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
                        "history_accept_adminid"=>$val
                    ]);
                    $j++;
                }
                
            }

            if(count($test_lesson_require_list1) >= $num){
                $test_lesson_require_list2 = $task->t_test_lesson_subject_require->get_test_lesson_require_list_for_jw(
                    $start_time,$end_time,$num
                );
                $n = 0;
                foreach($jw_teacher_list as $val){
                    if(isset($test_lesson_require_list2[$n])){
                        $task->t_test_lesson_subject_require->field_update_list($test_lesson_require_list2[$n]["require_id"],[
                            "accept_adminid"=>$val,
                            "require_assign_time"=>time()
                        ]);

                        $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
                            $test_lesson_require_list2[$n]["require_id"]);

                        $task->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
                            "history_accept_adminid"=>$val
                        ]);
                        $n++;
                    }
                
                }

                if(count($test_lesson_require_list2) >= $num){
                    $test_lesson_require_list3 = $task->t_test_lesson_subject_require->get_test_lesson_require_list_for_jw(
                        $start_time,$end_time,$num
                    );
                    $m = 0;
                    foreach($jw_teacher_list as $val){
                        if(isset($test_lesson_require_list3[$m])){
                            $task->t_test_lesson_subject_require->field_update_list($test_lesson_require_list3[$m]["require_id"],[
                                "accept_adminid"=>$val,
                                "require_assign_time"=>time()
                            ]);

                            $test_lesson_subject_id = $task->t_test_lesson_subject_require->get_test_lesson_subject_id(
                                $test_lesson_require_list3[$m]["require_id"]);

                            $task->t_test_lesson_subject->field_update_list($test_lesson_subject_id,[
                                "history_accept_adminid"=>$val
                            ]);
                            $m++;
                        }
                
                    }


                }



            }


            }*/
    }
}
