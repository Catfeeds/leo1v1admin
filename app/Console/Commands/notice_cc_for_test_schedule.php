<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class notice_cc_for_test_schedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:notice_cc_for_test_schedule ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        /**
           为方便后期与CC做好在试听课前4h，老师匹配信息对称，增加推送通知内容如下：   您好，XX老师。 您于XX时间提交的试听课程【课程信息】，目前距开课时间还有4h。由于教务老师暂时尚未筛选到合适的授课老师进行匹配，特发此通知请您知悉。
         **/
        $task= new \App\Console\Tasks\TaskController();
        $now = time()+3600*4;
        $test_list = $task->t_test_lesson_subject_require->get_test_list($now);

        foreach($test_list as $item){
            $subject_str = E\Esubject::get_desc($item['subject']);
            $grade_str   = E\Egrade::get_desc($item['grade']);
            $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";//待处理通知
            $data_msg = [
                "first"     => "试听课排课反馈",
                "keyword1"  => "试听课排课反馈",
                "keyword2"  => "\n 您好，".$item['account']."老师。 您于".date('Y-m-d H:i',$item['require_time'])." 提交的试听课程 \n \n 课程信息: \n 科目:".$subject_str." \n 年级:".$grade_str." \n 学生姓名: ".$item['nick']." \n 期望上课时间: ".date('Y-m-d H:i',$item['stu_request_test_lesson_time'])."
\n 目前距开课时间还有4h。由于教务老师暂时尚未筛选到合适的授课老师进行匹配，特发此通知请您知悉。",
                "keyword3"  => date('Y-m-d H:i:s'),
            ];
            // $url = "http://admin.leo1v1.com/seller_student_new2/test_lesson_plan_list";
            $url = '';
            $wx=new \App\Helper\Wx();
            // orwGAs_IqKFcTuZcU1xwuEtV3Kek
            // orwGAs6J8tzBAO3mSKez8SX-DWq4 //孙
            $wx->send_template_msg('orwGAs_IqKFcTuZcU1xwuEtV3Kek',$template_id,$data_msg ,$url);
            $wx->send_template_msg($item['wx_openid'],$template_id,$data_msg ,$url);
        }

    }
}
