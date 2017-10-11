<?php
namespace App\Console\Tasks;

use \App\Enums as E;
use Illuminate\Support\Facades\Log;

use App\Helper\Net;
use App\Helper\Utils;

class LessonTask extends TaskController
{
    public function set_begin_lesson() {
        Log::debug("=============================set_begin_lesson");
        $lesson_list=$this->t_lesson_info->get_need_set_lesson_begin_list();
        $now=time(NULL);
        foreach ($lesson_list as $item) {
            $lessonid = $item["lessonid"];
            $this->t_lesson_info->field_update_list($lessonid,[
                "lesson_status" => E\Elesson_status::V_START,
                "real_begin_time" =>  $now,
            ]);
        };
    }

    public function set_end_lesson() {
        Log::debug("==============================set_end_lesson");
        $lesson_list = $this->t_lesson_info->get_need_set_lesson_end_list();
        $server_name_map = $this->t_xmpp_server_config->get_server_name_map();
        $now         = time();
        foreach ($lesson_list as $item) {
            $teacherid   = $item["teacherid"];
            $userid      = $item["userid"];
            $lessonid    = $item["lessonid"];
            $courseid    = $item["courseid"];
            $lesson_type = $item["lesson_type"];
            $lesson_num  = $item["lesson_num"];
            $lesson_end  = $item["lesson_end"];
            if ($lesson_end < $now-1800) {
                $this->t_lesson_info->field_update_list($lessonid,[
                    "lesson_status" => E\Elesson_status::V_END
                ]);
                $this->t_baidu_msg->change_lesson_start_message_status($lessonid);
                continue;
            }

            //检测老师是否还在课堂内
            $xmpp_server_name=$item["xmpp_server_name"];
            $current_server=$item["current_server"];

            $server_config = $this->t_lesson_info_b3->eval_real_xmpp_server($xmpp_server_name,$current_server ,$server_name_map  ) ;
            $roomid    = Utils::gen_roomid_name($lesson_type,$courseid,$lesson_num);
            if(isset($server_config['ip'])){
                $user_list = Utils::get_room_users($roomid,$server_config);

                Log::debug("check room info  $roomid  , userid_list:".json_encode($user_list));

                if (is_array ($user_list)  ) {
                    if ( !in_array($teacherid,$user_list) ) {
                        //设置 db
                        Log::debug("opt db lessonid=$lessonid  " );
                        $this->t_lesson_info->field_update_list($lessonid,[
                            "lesson_status" => E\Elesson_status::V_END
                        ]);
                        $this->t_baidu_msg->change_lesson_start_message_status($lessonid);
                    }
                }
            }
        }
    }

}
