<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class teacher_stu_paper_download_warn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:teacher_stu_paper_download_warn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '老师课前一小时未下载试卷提醒';

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
        $list = $task->t_test_lesson_subject_sub_list->get_no_download_list();
        foreach($list as $item){
            if($item["wx_openid"]!=''){
                /**
                 * 模板ID : uqBdLmuXzRRRGCrumxnA7Y31qQD3kZxIAleRRt3YYEg
                 * 标题   : 试卷下载通知
                 * {{first.DATA}}
                 * 服务内容：{{keyword1.DATA}}
                 * 截止时间：{{keyword2.DATA}}
                 * {{remark.DATA}}
                 */
                $template_id      = "uqBdLmuXzRRRGCrumxnA7Y31qQD3kZxIAleRRt3YYEg";
                $data['first']    = "老师您好，您的学生".$item["nick"]."的试听课试卷已经上传，请下载并对学生学习情况进行诊断分析。：";
                $data['keyword1'] = "学生学习分析";
                $data['keyword2'] = date("Y-m-d H:i:s",$item["lesson_start"]-3600);
                $data['remark'] = "请认真分析试卷，对学生学习情况全面了解是高转化率的基础，老师加油！";

                // $url = "http://admin.yb1v1.com/common/teacher_record_detail_info?teacherid=".$teacherid
                //   ."&type=1&add_time=".$add_time;
                //$data['remark']   = "如有疑问请联系各学科教研老师，理优期待与你一起共同进步，提供高品质教学服务。";
                \App\Helper\Utils::send_teacher_msg_for_wx($item["wx_openid"],$template_id,$data);
                $task->t_test_lesson_subject->field_update_list($item["test_lesson_subject_id"],["paper_send_wx_flag"=>1]);
            }
        }

    }
}
