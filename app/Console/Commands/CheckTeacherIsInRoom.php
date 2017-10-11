<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
class CheckTeacherIsInRoom extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CheckTeacherIsInRoom {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '前40分钟检测老师是否在课程中';

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
     * type 1 检测公开课
     * @return mixed
     */
    public function handle()
    {
        $type = $this->option('type');
        if($type==null){
            $type=1;
        }

        $task = new \App\Console\Tasks\TaskController();
        if($type==1){
            $start_time  = strtotime(date("Y-m-d",time()));
            $end_time    = strtotime(date("Y-m-d 24:00",time()));
            $lesson_list = $task->t_lesson_info->get_need_check_teacher_lesson($type,$start_time,$end_time);
            $server_name_map = $this->t_xmpp_server_config->get_server_name_map();
            if(is_array($lesson_list)){
                foreach($lesson_list as $lesson_val){
                    $lesson_start = $lesson_val['lesson_start'];
                    $lesson_end   = $lesson_val['lesson_end'];
                    $courseid     = $lesson_val['courseid'];
                    $lessonid     = $lesson_val['lessonid'];
                    $lesson_type  = $lesson_val['lesson_type'];
                    $lesson_num   = $lesson_val['lesson_num'];
                    $teacherid    = $lesson_val['teacherid'];
                    $lesson_time  = date("Y-m-d H:i",$lesson_start)."-".date("H:i",$lesson_end);
                    if($lesson_start-time()<=2400){
                        $server_config = [];
                        $xmpp_server_name=$lesson_val["xmpp_server_name"];
                        $current_server=$lesson_val["current_server"];
                        $server_config        = $this->t_lesson_info_b3->eval_real_xmpp_server($xmpp_server_name,$current_server,$server_name_map) ;
                        $roomid        = \App\Helper\Utils::gen_roomid_name($lesson_type,$courseid,$lesson_num);
                        $user_list     = \App\Helper\Utils::get_room_users($roomid,$server_config);
                        $teacher_nick  = $task->cache_get_teacher_nick($teacherid);

                        if(is_array($user_list)){
                            if(!in_array($teacherid,$user_list)){
                                $header_msg = "有一节公开课已经开始，但老师不在课堂内！";
                                $from_user  = "公开课老师检测";
                                $msg        = "\n老师：".$teacher_nick."\n课程时间:".$lesson_time;
                                $url        = "http://admin.yb1v1.com/tea_manage/lesson_list?lessonid=".$lessonid;
                                $task->t_manager_info->send_wx_todo_msg("amanda",$from_user,$header_msg,$msg,$url);
                                $task->t_manager_info->send_wx_todo_msg("yueyue",$from_user,$header_msg,$msg,$url);
                                $task->t_manager_info->send_wx_todo_msg("adrian",$from_user,$header_msg,$msg,$url);
                            }
                        }
                    }
                }
            }
        }
    }

}
