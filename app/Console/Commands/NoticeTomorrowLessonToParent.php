<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NoticeTomorrowLessonToParent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:NoticeTomorrowLessonToParent';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = '晚 8点 通知家长上课短信';

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
        $task = new \App\Console\Tasks\TaskController();
        $ret  = \App\Helper\Utils::get_day_range(time(NULL)+86400);

        $start_time = $ret["sdate"];
        $end_time   = $ret["edate"];

        $ret_id   = $task->t_lesson_info->get_today_lesson_list($start_time,$end_time);
        $ret_list = $this->get_student_info($ret_id);

        foreach( $ret_list as $item ) {
            $phone        = $item["phone"];
            $lesson_start = $item["lesson_start"];
            $lesson_end   = $item["lesson_end"];
            $lessonid     = $item["lessonid"];
            $userid       = $item["userid"];
            $date_str     = date('m月d日 H:i', $lesson_start)."-".date('H:i', $lesson_end);
            $lesson_type  = $item["lesson_type"];
            $nick         = $item["nick"];

            if ($lesson_type==2){
                $course_name="试听课";
            }else{
                $course_name = \App\Helper\Utils::get_course_name($lesson_type);
            }
            \App\Helper\Utils::logger("SEND_LESSON_PARENT $phone ");


            /**
             * 通知明天上课2-14
             * SMS_46690154
             * 课程提醒：${name}家长您好，您的孩子明天 ${time} 有一节${course_name}要上，请保持网络畅通，提前做好上课准备。
             * 祝孩子学习愉快！
             */
            $sms_id    = 46690154;
            $sign_name = \App\Helper\Utils::get_sms_sign_name();
            $arr = [
                "name"        => $nick,
                "time"        => $date_str,
                "course_name" => $course_name,
            ];

            //短信黑名单(不发送)
            $sms_phone_refund_list=["13621298715","13661763881"];

            if ($phone && !in_array($phone,$sms_phone_refund_list)) {
                try {
                    \App\Helper\Utils::sms_common($phone,$sms_id,$arr,0,$sign_name);
                }catch(\Exception $e){

                }
            }
        }

        foreach($ret_list as $item){
            $phone        = $item["phone"];
            $lesson_start = $item["lesson_start"];
            $lesson_end   = $item["lesson_end"];
            $lessonid     = $item["lessonid"];
            $userid       = $item["userid"];
            $date_str     = date('m月d日 H:i', $lesson_start)."-".date('H:i', $lesson_end);
            $lesson_type  = $item["lesson_type"];
            $nick         = $task->cache_get_student_nick($userid);

            if ($lesson_type==2){
                $course_name = "试听课";
            }else{
                $course_name = \App\Helper\Utils::get_course_name($lesson_type);
            }

            if($userid){
                $title = $date_str."你有一堂课，上课前请提做好预习准备并保障家里网络通畅，祝听课愉快。";
                $push_num = 1;
                $task->t_baidu_msg->baidu_push_msg($userid,$title,$lessonid,1001,$push_num);
                $message = [
                    "push_num" => $push_num,
                    "lessonid" => (int)$lessonid,
                ];
                \App\Helper\Net::baidu_push($userid,'1001',$title,$message);
            }
        }
    }

    function get_student_info($ret_id){
        $task  = new \App\Console\Tasks\TaskController();
        $v1    = '';
        $open  = '';
        $small = '';
        foreach($ret_id as $val){
            if($val['lesson_type']<1000){
                $v1[]=$val['lessonid'];
            }else if($val['lesson_type']<2000 || $val['lesson_type']>4000){
                $open[]=$val['lessonid'];
            }else if($val['lesson_type']<4000 && $val['lesson_type']>3000){
                $small[]=$val['lessonid'];
            }
        }

        $v1_l    = is_array($v1)?implode(',',$v1):0;
        $open_l  = is_array($open)?implode(',',$open):0;
        $small_l = is_array($small)?implode(',',$small):0;

        $ret_1v1   = $task->t_lesson_info->get_1v1_user_list($v1_l);
        $ret_open  = $task->t_lesson_info->get_open_user_list($open_l);
        $ret_small = $task->t_lesson_info->get_small_user_list($small_l);
        $ret_list  = array_merge($ret_1v1,$ret_open,$ret_small);
        return $ret_list;
    }

}
