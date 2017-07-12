<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class teacher_change_good_send_wx_week extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:teacher_change_good_send_wx_week';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '优秀老师一周微信推送';

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
        //
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        $start_time = time()-140*86400;
        $list = $task->t_teacher_info->get_good_teacher_list($start_time);
        //dd($list);
        $all = $teacher_name=[];
        foreach($list as $item){
            @$all["num"]++;
            @$all["name"] .=$item["realname"].",";
            @$teacher_name[$item["subject"]]["num"]++;
            @$teacher_name[$item["subject"]]["name"] .=$item["realname"].",";
            $task->t_teacher_info->field_update_list($item["teacherid"],["is_good_wx_flag"=>1]);
        }
          if(!empty($all)){
            $all["name"] = trim($all["name"],",");
             $all_arr=["72"=>"Erick","349"=>"Jack","287"=>"leo","364"=>"龚昊天","416"=>"童宇宙","323"=>"louis"];
              // $all_arr=["349"=>"Jack"];
            foreach($all_arr as $k=>$val){
                $task->t_manager_info->send_wx_todo_msg_by_adminid ($k,"理优教研组","老师推荐通知",$val."老师你好,系统检查到上周有".$all["num"]."位老师推荐,名单如下:
".$all["name"],"http://admin.yb1v1.com/human_resource/index_seller");

            }
        }


        //dd($list);
          foreach($teacher_name as $k=>&$item){
              $teacher_name_list = trim($item["name"],",");
              $num = $item["num"];
              $tea_arr =$task->get_admin_group_subject_list($k);
              $subject_str = E\Esubject::get_desc($k);
              foreach($tea_arr as $kk=>$vv){
                  $task->t_manager_info->send_wx_todo_msg_by_adminid ($kk,"理优教研组","老师推荐通知",$vv."老师你好,系统检查到".$subject_str."学科上周有".$num."位老师推荐,名单如下:
".$teacher_name_list,"http://admin.yb1v1.com/human_resource/index_tea_qua?is_good_flag=1&subject=".$k);
              }
          }

    }
}
