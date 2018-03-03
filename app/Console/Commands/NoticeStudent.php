<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Enums as E;

class NoticeStudent extends Command
{
    use  \App\Http\Controllers\CacheNick;

    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'command:NoticeStudent {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '关于学生的情况处理';

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
     * @param type 1 课后5分钟学生未到,提醒助教或者销售
                   2 课前10分钟,提醒销售有学生试听要开始
                   3 学生迟到五分钟后，系统直接给家长打电话进行提醒
     * @return mixed
     */
    public function handle()
    {
        $task = new \App\Console\Tasks\TaskController();
        $type = $this->option('type');
        if($type===null){
            $type = 1;
        }

        if($type==1){
            $end_time   = time()-300;
            $start_time = $end_time-60;
            $first_str  = "课程开始5分钟,学生未进入课堂!";
        }elseif($type==2){
            $end_time   = time()+600;
            $start_time = $end_time-60;
            $first_str  = "课程还有10分钟开始,请关注学生的课堂!";
        } elseif($type == 3) {
            $end_time   = time()-300;
            $start_time = $end_time-60;
        }
        $now         = date("Y-m-d",time());
        $lesson_list = $task->t_lesson_info->get_lesson_stu_late($start_time,$end_time,$type);
        foreach($lesson_list as $val){
            if ($type == 3) {
                $data = [ // 可变
                    "name"        => $val["realname"],
                    "lesson_time" => date("Y年m月d日H:i:s", $val["lesson_start"]),
                    "subject"     => E\Esubject::get_desc($val["subject"]),
                ];
                //打电话方法
                $type = "125735110"; // 固定
                $phone = $val["phone"];
                echo $val["userid"]." ".$data["lesson_time"]." ".$data["subject"]." ".$phone.PHP_EOL;
                \App\Helper\Utils::tts_common($phone, $type, $data);
            } else {
                if($val['assistantid']>0 && $type==1){
                    $account     = $task->t_assistant_info->get_account_by_id($val['assistantid']);
                    $url         = "/supervisor/monitor_ass?date=".$now."&userid=".$val['userid'];
                    $account_str = "助教-".$account;
                }elseif($val['lesson_type']==2){
                    $account = $task->t_test_lesson_subject_sub_list->get_lesson_admin($val['lessonid']);
                    $url         = "/supervisor/monitor_seller?date=".$now."&userid=".$val['userid'];
                    $account_str = "申请人-".$account;
                }

                if(isset($account_str)){
                    $stu_nick   = $val['realname'];
                    $from_user  = "学生-$stu_nick";
                    $header_msg = $val['lessonid'].$first_str;
                    $msg        = $account_str;
                    // $ret         = $task->t_manager_info->send_template_msg($account,$template_id,[
                    //     "first"    => $val['lessonid'].$first_str,
                    //     "keyword1" => "学生-$stu_nick",
                    //     "keyword2" => $account_str,
                    //     "keyword3" => date("Y-m-d H:i:s"),
                    // ],$url);
                    $task->t_manager_info->send_wx_todo_msg($account,$from_user,$header_msg,$msg,$url);
                    \App\Helper\Utils::logger("student late for lesson, notice:".$account_str." this lessonid is".$val['lessonid']);
                }else{
                    \App\Helper\Utils::logger("student late for lesson, this lessonid is".$val['lessonid']." and not notice");
                }

            }
        }
    }


}
