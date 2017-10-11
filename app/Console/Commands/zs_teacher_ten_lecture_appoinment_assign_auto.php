<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class zs_teacher_ten_lecture_appoinment_assign_auto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:zs_teacher_ten_lecture_appoinment_assign_auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '招师资源自动分配';

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
        $start_time = strtotime(date("2017-02-01"));
        // $ass_leader_arr=[1=>492,2=>513,3=>790,4=>492,5=>513,6=>790,7=>492,8=>513,9=>790,10=>513];
        // $ass_leader_arr=[1=>955,2=>917,3=>955,4=>917,5=>955];
        // $ass_leader_arr=[];
        // $ass_leader_list = $task->t_manager_info->get_zs_work_status_adminid(8);
        // foreach($ass_leader_list as $q){
        //$ass_leader_arr[$q["uid"]]=$q["uid"];
        //}
        //dd($ass_leader_arr);
        $list = $task->t_manager_info->get_zs_work_status_adminid(8);
        $ass_leader_arr=[];
        $i=1;
        foreach($list as $val){
            if($val["uid"]==513 || $val["uid"]==492){
                $ass_leader_arr[$i] = $val["uid"];
                $i++;
                $ass_leader_arr[$i] = $val["uid"];
                $i++;
            }elseif($val["uid"]==955){
                $ass_leader_arr[$i] = $val["uid"];
                $i++;
                $ass_leader_arr[$i] = $val["uid"];
                $i++;
                $ass_leader_arr[$i] = $val["uid"];
                $i++;

            }else{
                $ass_leader_arr[$i] = $val["uid"];
                $i++;
                $ass_leader_arr[$i] = $val["uid"];
                $i++;
                $ass_leader_arr[$i] = $val["uid"];
                $i++;
                $ass_leader_arr[$i] = $val["uid"];
                $i++;

            }
        }
        //dd($ass_leader_arr);
        // $ass_leader_arr=[1=>955,2=>1000,3=>790,4=>492,5=>513,6=>955,7=>1000,8=>790,9=>492,10=>513,11=>955,12=>1000,13=>790]; 
        $num_all = count($ass_leader_arr);
        $id = $task->t_teacher_lecture_appointment_info->get_id_list_desc_limit_ten($start_time);
        //dd($id);
        //shuffle($hh_adminid);
        $i=0;
        foreach($ass_leader_arr as $k=>$val){
            $json_ret=\App\Helper\Common::redis_get_json("ZS_AUTO_ASSIGN_$k");
            if (!$json_ret) {
                $json_ret=0;
            }
            \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_$k", $json_ret);
            if($json_ret==1){
                $i++;
            }
            // echo $json_ret;
        }
        if($i==$num_all){
            foreach($ass_leader_arr as $k=>$val){
                \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_$k", 0);
            }
        }
        
        if($id>0){
            foreach($ass_leader_arr as $k=>$val){
                $json_ret=\App\Helper\Common::redis_get_json("ZS_AUTO_ASSIGN_$k");
                if($json_ret==0){
                    $ret =  $task->t_teacher_lecture_appointment_info->field_update_list($id,[
                        "accept_adminid"  => $val,
                        "accept_time"     => time()
                    ]);

                    if($ret){
                        \App\Helper\Common::redis_set_json("ZS_AUTO_ASSIGN_$k", 1);
                        break;
                    }
               
                }
            }


        }

              
    }
}
