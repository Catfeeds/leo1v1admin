<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class jhp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:jhp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
