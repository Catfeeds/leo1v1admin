<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Common;
use \App\Enums as E;



class check_test_lesson_succ_flag_for_send_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check_test_lesson_succ_flag_for_send_wx';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    public $task;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->task= new \App\Console\Tasks\TaskController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //

        /**
           // 测试数组
           $test_lesson_list_two_day_ago_list[] = [
           "lesson_start" => '1501344000',
           "lesson_end" => '1501372800',
           "require_adminid" => 684,
           "stu_nick" => 'james',
           "subject"  => 1,
           "teacher_nick" => "James",
           "grade" => 301,
           "success_flag" => 0,
           "order_confirm_flag" => 0
           ];
         **/
        // 昨天结束的试听课 [如果没有设置“课程确认”，发送课程确认的消息]
        $test_lesson_list_yes = $this->task->t_lesson_info_b2->get_test_lesson_success_list_yes();

        $test_lesson_list_yes = [];
        foreach( $test_lesson_list_yes as &$item_yes){
            if($item_yes['success_flag'] == 0){
                //课时成功未设置
                $is_success_flag = 1; // 课时未设置
                $this->send_wx_msg($item_yes,$is_success_flag);
            }
        }


        //试听课结束的第二天下午2点，如果没有设置“试听结果”，发送试听结果设置的消息
        $test_lesson_list_two_day_ago_list = $this->task->t_lesson_info_b2->get_test_lesson_success_list_two_days_ago();

        foreach( $test_lesson_list_two_day_ago_list  as &$item_two){
            if($item_two['order_confirm_flag'] == 0){
                // 助教未设置试听课结果
                $is_success_flag = 2; // 课时未设置
                $this->send_wx_msg($item_two,$is_success_flag);
            }
        }

        // 试听课结束的第三天下午2点，如果没有设置“课程确认”或者“试听结果”，继续发送
        $test_lesson_list_three_day_ago_list  = $this->task->t_lesson_info_b2->get_test_lesson_success_list_three_days_ago();

        foreach( $test_lesson_list_three_day_ago_list as &$item_three){
            if($item_three['success_flag'] == 0){
                //课时成功未设置
                $is_success_flag = 1; // 课时未设置
                $this->send_wx_msg($item_three,$is_success_flag);
            }elseif($item_three['order_confirm_flag'] == 0){
                // 助教未设置试听课结果
                $is_success_flag = 2; // 助教未设置
                $this->send_wx_msg($item_three,$is_success_flag);
            }
        }

    }

    public function send_wx_msg($item_yes,$is_success_flag){

        /**
           {{first.DATA}}
           待办主题：{{keyword1.DATA}}
           待办内容：{{keyword2.DATA}}
           日期：{{keyword3.DATA}}
           {{remark.DATA}}
        **/
        $now = date('Y-m-d H:i:s');
        $wx  = new \App\Helper\Wx();


        E\Egrade::set_item_value_str($item_yes);
        $item_yes['subject_str'] =  E\Esubject::get_desc($item_yes['subject']);

        $lesson_start_date = date("Y-m-d H:i:s",$item_yes['lesson_start']);
        $lesson_end_date   = date("H:i:s",$item_yes['lesson_end']);
        $ass_wx_openid = $this->task->t_manager_info->get_wx_openid($item_yes['require_adminid']);

        if($is_success_flag == 1){
            $first           = "课时有效性未设置";
            $keyword1 = "未设置试听课课时有效性";
        }else{
            $first           = "试听结果未设置";
            $keyword1 = "未确认试听课结果";
        }

        $lesson_info = " 试听课 ".$first." 课时信息:".$item_yes['stu_nick']."同学 - 上课时间:". $lesson_start_date." ~ ".$lesson_end_date." - 科目:".$item_yes['subject_str']." - 老师".$item_yes['teacher_nick'];
        $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";//待处理通知
        $data_msg_yes = [
            "first"     => "$first",
            "keyword1"  => "$keyword1",
            "keyword2"  => "$lesson_info",
            "keyword3"  => "$now",
        ];
        $url_yes = 'http://admin.yb1v1.com/seller_student_new2/get_ass_test_lesson_info?lessonid='.$item_yes['lessonid'];

        $wx->send_template_msg($ass_wx_openid,$template_id,$data_msg_yes ,$url_yes);

    }
}
