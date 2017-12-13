<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class teacher_assign_jw_teacher_auto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:teacher_assign_jw_teacher_auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '新通过培训老师自动分配教务';

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
        $time= strtotime(date("2017-03-15"));
        $list = $task->t_teacher_info->get_train_through_teacher_list($time);
        foreach($list as $item){
            $subject = $item["subject"];
            if($subject<4){
                if($subject==1){
                    $jw_list=[1238,513];
                }elseif($subject==2){
                    $jw_list=[1324,1328];
                }else{
                    $jw_list=[1329,723];
                }
                              
                $num_all = count($jw_list);
                $i=0;
                foreach($jw_list as $k=>$val){
                    $json_ret=\App\Helper\Common::redis_get_json("JW_AUTO_ASSIGN_NEW_TEACHER_$val");
                    if (!$json_ret) {
                        $json_ret=0;
                        \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_TEACHER_$val", $json_ret);
                    }
                    if($json_ret>0){
                        $i++;
                    }
                }
                if($i==$num_all){
                    foreach($jw_list as $k=>$val){
                        \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_TEACHER_$val", 0);
                    }
                }
                foreach($jw_list as $k=>$val){
                    $json_ret=\App\Helper\Common::redis_get_json("JW_AUTO_ASSIGN_NEW_TEACHER_$val");
                    if($json_ret==0){                           
                        $assign_jw_adminid=$val;
                        \App\Helper\Common::redis_set_json("JW_AUTO_ASSIGN_NEW_TEACHER_$val", 1);

                        break;
               
                    }
                }
 
            }else{
                $assign_jw_adminid= 436;
            }
            $task->t_teacher_info->field_update_list($item["teacherid"],[
                "assign_jw_adminid" =>$assign_jw_adminid,
                "assign_jw_time"     => time()
            ]);
        }
        // $jw_list = $task->t_manager_info->get_adminid_list_by_account_role(3);
        // $arr=[];
        // foreach($jw_list as $item){
        //     if(!in_array($item["uid"],[454,492,513])){
        //         $arr[] =  $item["uid"];  
        //     }
        // }
        // asort($arr);
        // $num = count($arr);

        // // dd($num);
        // //语文科目新老师
        // $subject =1;
        // // $substr_str ="(4,5,6)";
        // $teacherid = $task->t_teacher_info->get_train_through_teacher_info($time,$subject);
        // $i=0;
        // foreach($arr as $val){
        //     $json_ret=\App\Helper\Common::redis_get_json("ZS_AUTO_ASSIGN_YUWEN_$val");
        //     if (!$json_ret) {
        //         $json_ret=0;
        //     }
        //     \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_YUWEN_$val", $json_ret);
        //     if($json_ret==1){
        //         $i++;
        //     }
        //     // echo "yuwen".$json_ret."<br>";
        // }
        // if($i==$num){
        //     foreach($arr as $val){
        //         \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_YUWEN_$val", 0);
        //     }
        // }
        
        // if($teacherid>0){
        //     foreach($arr as $val){
        //         $json_ret=\App\Helper\Common::redis_get_json("ZS_AUTO_ASSIGN_YUWEN_$val");
        //         if($json_ret==0){
        //             $ret =  $task->t_teacher_info->field_update_list($teacherid,[
        //                 "assign_jw_adminid"  => $val,
        //                 "assign_jw_time"     => time()
        //             ]);

        //             if($ret){
        //                 \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_YUWEN_$val", 1);
        //                 break;
        //             }
               
        //         }
        //     }


        // }

        // //数学科目新老师
        // $subject =2;
        // // $substr_str ="(4,5,6)";
        // $teacherid = $task->t_teacher_info->get_train_through_teacher_info($time,$subject);
        // $i=0;
        // foreach($arr as $val){
        //     $json_ret=\App\Helper\Common::redis_get_json("ZS_AUTO_ASSIGN_SHUXUE_$val");
        //     if (!$json_ret) {
        //         $json_ret=0;
        //     }
        //     \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_SHUXUE_$val", $json_ret);
        //     if($json_ret==1){
        //         $i++;
        //     }
        // }
        // if($i==$num){
        //     foreach($arr as $val){
        //         \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_SHUXUE_$val", 0);
        //     }
        // }
        
        // if($teacherid>0){
        //     foreach($arr as $val){
        //         $json_ret=\App\Helper\Common::redis_get_json("ZS_AUTO_ASSIGN_SHUXUE_$val");
        //         if($json_ret==0){
        //             $ret =  $task->t_teacher_info->field_update_list($teacherid,[
        //                 "assign_jw_adminid"  => $val,
        //                 "assign_jw_time"     => time()
        //             ]);

        //             if($ret){
        //                 \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_SHUXUE_$val", 1);
        //                 break;
        //             }
               
        //         }
        //     }


        // }


        // //英语科目新老师
        // $subject =3;
        // // $substr_str ="(4,5,6)";
        // $teacherid = $task->t_teacher_info->get_train_through_teacher_info($time,$subject);
        // $i=0;
        // foreach($arr as $val){
        //     $json_ret=\App\Helper\Common::redis_get_json("ZS_AUTO_ASSIGN_YINGYU_$val");
        //     if (!$json_ret) {
        //         $json_ret=0;
        //     }
        //     \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_YINGYU_$val", $json_ret);
        //     if($json_ret==1){
        //         $i++;
        //     }
        // }
        // if($i==$num){
        //     foreach($arr as $val){
        //         \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_YINGYU_$val", 0);
        //     }
        // }
        
        // if($teacherid>0){
        //     foreach($arr as $val){
        //         $json_ret=\App\Helper\Common::redis_get_json("ZS_AUTO_ASSIGN_YINGYU_$val");
        //         if($json_ret==0){
        //             $ret =  $task->t_teacher_info->field_update_list($teacherid,[
        //                 "assign_jw_adminid"  => $val,
        //                 "assign_jw_time"     => time()
        //             ]);

        //             if($ret){
        //                 \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_YINGYU_$val", 1);
        //                 break;
        //             }
               
        //         }
        //     }


        // }


        // //化学科目新老师
        // $subject =4;
        // // $substr_str ="(4,5,6)";
        // $teacherid = $task->t_teacher_info->get_train_through_teacher_info($time,$subject);
        // $i=0;
        // foreach($arr as $val){
        //     $json_ret=\App\Helper\Common::redis_get_json("ZS_AUTO_ASSIGN_HUAXUE_$val");
        //     if (!$json_ret) {
        //         $json_ret=0;
        //     }
        //     \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_HUAXUE_$val", $json_ret);
        //     if($json_ret==1){
        //         $i++;
        //     }
        // }
        // if($i==$num){
        //     foreach($arr as $val){
        //         \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_HUAXUE_$val", 0);
        //     }
        // }
        
        // if($teacherid>0){
        //     foreach($arr as $val){
        //         $json_ret=\App\Helper\Common::redis_get_json("ZS_AUTO_ASSIGN_HUAXUE_$val");
        //         if($json_ret==0){
        //             $ret =  $task->t_teacher_info->field_update_list($teacherid,[
        //                 "assign_jw_adminid"  => $val,
        //                 "assign_jw_time"     => time()
        //             ]);

        //             if($ret){
        //                 \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_HUAXUE_$val", 1);
        //                 break;
        //             }
               
        //         }
        //     }


        // }

        // //物理科目新老师
        // $subject =5;
        // // $substr_str ="(4,5,6)";
        // $teacherid = $task->t_teacher_info->get_train_through_teacher_info($time,$subject);
        // $i=0;
        // foreach($arr as $val){
        //     $json_ret=\App\Helper\Common::redis_get_json("ZS_AUTO_ASSIGN_WULI_$val");
        //     if (!$json_ret) {
        //         $json_ret=0;
        //     }
        //     \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_WULI_$val", $json_ret);
        //     if($json_ret==1){
        //         $i++;
        //     }
        // }
        // if($i==$num){
        //     foreach($arr as $val){
        //         \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_WULI_$val", 0);
        //     }
        // }
        
        // if($teacherid>0){
        //     foreach($arr as $val){
        //         $json_ret=\App\Helper\Common::redis_get_json("ZS_AUTO_ASSIGN_WULI_$val");
        //         if($json_ret==0){
        //             $ret =  $task->t_teacher_info->field_update_list($teacherid,[
        //                 "assign_jw_adminid"  => $val,
        //                 "assign_jw_time"     => time()
        //             ]);

        //             if($ret){
        //                 \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_WULI_$val", 1);
        //                 break;
        //             }
               
        //         }
        //     }


        // }


        // //小科目新老师
        // $subject =-1;
        // $substr_str ="(6,7,8,9,10)";
        // $teacherid = $task->t_teacher_info->get_train_through_teacher_info($time,$subject,$substr_str);
        // $i=0;
        // foreach($arr as $val){
        //     $json_ret=\App\Helper\Common::redis_get_json("ZS_AUTO_ASSIGN_SMALL_$val");
        //     if (!$json_ret) {
        //         $json_ret=0;
        //     }
        //     \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_SMALL_$val", $json_ret);
        //     if($json_ret==1){
        //         $i++;
        //     }
        // }
        // if($i==$num){
        //     foreach($arr as $val){
        //         \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_SMALL_$val", 0);
        //     }
        // }
        
        // if($teacherid>0){
        //     foreach($arr as $val){
        //         $json_ret=\App\Helper\Common::redis_get_json("ZS_AUTO_ASSIGN_SMALL_$val");
        //         if($json_ret==0){
        //             $ret =  $task->t_teacher_info->field_update_list($teacherid,[
        //                 "assign_jw_adminid"  => $val,
        //                 "assign_jw_time"     => time()
        //             ]);

        //             if($ret){
        //                 \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_SMALL_$val", 1);
        //                 break;
        //             }
               
        //         }
        //     }


        // }


              
    }
}
