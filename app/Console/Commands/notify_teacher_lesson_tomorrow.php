<?php

namespace App\Console\Commands;

use \App\Enums as E;
use Illuminate\Console\Command;

class notify_teacher_lesson_tomorrow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:notify_teacher_lesson_tomorrow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '通知老师 明天的上课列表 ';

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
        $ret       = \App\Helper\Utils::get_day_range(time(NULL)+86400);
        $sign_name = \App\Helper\Utils::get_sms_sign_name(0);

        $start_time = $ret["sdate"];
        $end_time   = $ret["edate"];

        $task = new \App\Console\Tasks\TaskController();
        $list = $task->t_lesson_info->get_teacher_lesson_list($start_time,$end_time);

        $teacher_map=[];
        foreach ($list as $item)  {
            if (!isset($teacher_map[$item['teacherid']] )){
                $teacher_map[$item['teacherid']] = [];
            }

            $lesson_start = $item["lesson_start"];
            $lesson_end   = $item["lesson_end"];
            if(isset($item['stu_nick'])){
                $stu_nick = $item["stu_nick"];
            }else{
                $stu_nick = $task->cache_get_student_nick($item['userid']);
            }
            if(isset($item['tea_nick'])){
                $tea_nick = $item["tea_nick"];
            }else{
                $tea_nick = $task->cache_get_teacher_nick($item['teacherid']);
            }
            $date_str = date('H:i',$lesson_start)."-".date('H:i', $lesson_end);

            $lesson_type = $item["lesson_type"];
            if (in_array($lesson_type,[0,1,3])){
                $course_name = "1对1课";
            }else{
                $course_name = @E\Econtract_type::get_desc($item["lesson_type"]);
            }

            $tea_arr = [];
            $tea_arr['tea_nick'] = $tea_nick;
            if($stu_nick){
                $tea_arr['lesson_info']="$date_str $course_name [$stu_nick]";
            }else{
                $tea_arr['lesson_info']="$date_str $course_name";
            }

            $teacher_map[$item['teacherid']][] = $tea_arr;
        }

        /**
         * 通知老师明天上课2-14
         * SMS_46805178
         * 上课通知：${name}老师您好，明天您的课程安排为：${lesson_info}，请准时进入课堂，祝老师上课愉快！
         */
        $sms_id = 46805178;
        foreach($teacher_map as $teacherid => $tea_val) {
            if(is_array($tea_val)){
                foreach($tea_val as $lesson_val){
                    if(is_array($lesson_val)){
                        $arr = [
                            "name"        => $lesson_val['tea_nick'],
                            "lesson_info" => $lesson_val['lesson_info'],
                        ];
                        $phone = $task->t_teacher_info->get_phone($teacherid);

                        if($phone){
                            \App\Helper\Utils::sms_common($phone,$sms_id,$arr,0,$sign_name);
                            \App\Helper\Utils::logger("$phone:".json_encode($arr));
                        }
                    }
                }
            }
        }

        foreach($teacher_map as $teacherid => $tea_val) {
            if(is_array($tea_val)){
                foreach($tea_val as $lesson_val){
                    if(is_array($lesson_val)){
                        $arr = [
                            "name"        => $lesson_val['tea_nick'],
                            "lesson_info" => $lesson_val['lesson_info'],
                        ];
                        $header_msg= "上课通知：{$arr["name"]}老师您好，明天您的课程安排为：{$arr["lesson_info"]}.";
                        $task->t_teacher_info->send_wx_todo_msg($teacherid,"理优教育",$header_msg,"请准时进入课堂，祝老师上课愉快！" );
                    }
                }
            }
        }

    }
}
