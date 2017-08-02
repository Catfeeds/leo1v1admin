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
    protected $signature = 'command:name';

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
        // 昨天结束的试听课 [如果没有设置“课程确认”，发送课程确认的消息]
        $test_lesson_list_yes = $this->task->t_lesson_info_b2->get_test_lesson_success_list_yes();

        foreach( $test_lesson_list_yes as &$item_yes){
            if($item_yes['success_flag'] == 0){
                //课时成功未设置

                /**
                   {{first.DATA}}
                   待办主题：{{keyword1.DATA}}
                   待办内容：{{keyword2.DATA}}
                   日期：{{keyword3.DATA}}
                   {{remark.DATA}}
                 **/

                $now = date('Y-m-d H:i:s');

                E\Egrade::set_item_value_str($item_yes);
                E\Esubject::set_item_value_str($item_yes);

                $lesson_start_date = date("Y-m-d H:i:s",$item_yes['lesson_start']);
                $lesson_end_date   = date("H:i:s",$item_yes['lesson_end']);

                $lesson_info = $item_yes['stu_nick']." - ". $lesson_start_date." ~ ".$lesson_end_date." 试听课 ";
                $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";//待处理通知
                $data_msg = [
                    "first"     => "未设置试听课课时有效性",
                    "keyword1"  => "确认试听课课时有效性",
                    "keyword2"  => "$lesson_info",
                    "keyword3"  => "$now",
                ];
                $url = 'http://admin.yb1v1.com/user_manage/qc_complaint/';
                $wx=new \App\Helper\Wx();

                $wx_openid_arr = [
                ];
                $wx_openid_list = array_merge($wx_openid_arr,$subject_adminid_wx_openid_list);

                foreach($wx_openid_list as $qc_item){
                    $wx->send_template_msg($qc_item,$template_id,$data_msg ,$url);
                }

            }
        }


        //试听课结束的第三天下午2点，如果没有设置“试听结果”，发送试听结果设置的消息
        $test_lesson_list_two_day_ago_list = $this->task->t_lesson_info_b2->get_test_lesson_success_list_two_days_ago();

        foreach( $test_lesson_list_two_day_ago_list  as $item_two){
            if($item_two['order_confirm_flag'] == 0){
                // 助教未设置试听课结果
            }
        }


        // 试听课结束的第四天下午2点，如果没有设置“课程确认”或者“试听结果”，继续发送
        $test_lesson_list_three_day_ago_list  = $this->task->t_lesson_info_b2->get_test_lesson_success_list_three_days_ago();

        foreach( $test_lesson_list_three_day_ago_list as $item_three){
            if($item_three['success_flag'] == 0){
                //课时成功未设置
            }elseif($item_three['order_confirm_flag'] == 0){
                // 助教未设置试听课结果
            }
        }

    }
}
