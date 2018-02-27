<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Common;

class send_courseware_PDF_email_to_stu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_courseware_PDF_email_to_stu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "课堂结束发送PDF讲义给学生";

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
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task       = new \App\Console\Tasks\TaskController();
        $start_time = strtotime(date("Y-m-d",time()));
        $end_time   = time();

        $lesson_info = $task->t_lesson_info->get_lesson_info_for_send_email($start_time,$end_time);
        if(!empty($lesson_info)){
            foreach($lesson_info as $item){
                $lesson_start = date('Y-m-d H:i',$item['lesson_start']);
                $lesson_end   = date('H:i',$item['lesson_end']);
                $stu_email    = $item['stu_email'];
                $tea_cw_url   = $item['tea_cw_url'];
                $work_status  = $item['work_status'];
                if($work_status >= 1){
                    $homework_url    = $item['issue_url'];
                    $homework_url_ex = config("admin")['monitor_new_url']."/common/email_open_address?url=".$homework_url;
                }else{
                    $homework_url_ex = config("admin")['monitor_new_url']."/common/email_open_address?url=";

                }

                if($tea_cw_url){
                    $tea_cw_url_ex = config("admin")['monitor_new_url']."/common/email_open_address?url=".$tea_cw_url;
                }else{
                    $tea_cw_url_ex = config("admin")['monitor_new_url']."/common/email_open_address?url="; 
                }

                if(!empty($stu_email) && (!empty($tea_cw_url) || $work_status >= 1)){
                    if(empty($tea_cw_url)){
                        dispatch( new \App\Jobs\SendEmail(
                            $stu_email,$lesson_start."-".$lesson_end."课堂讲义",
                            "<div style=\"text-indent:2em;margin-\">家长您好!</div><br><div style=\"text-indent:2em\">以下是您孩子本次课的课程讲义和课后作业,请及时下载</div><br><div style=\"text-indent:2em\">本邮件由\"理优升学帮\"发送,".
                            "若想获得更加便捷和专业的服务</div><br><div style=\"text-indent:2em\">请务必下载\"理优升学帮\"关注孩子点滴成长!</div><br><div style=\"color:red;text-indent:2em\">".
                            "高效的学习,从课后巩固和完成作业开始</div></br>".
                            
                            " <a href=".$homework_url_ex." target=\"_blank\" style=\"color:blue;text-indent:2em;display:block;margin-top:-3px\" >作业下载</a><br>".
                            "<div style=\"color:#545454;text-indent:2em;margin-top:-3px\">单击文字即可下载</div><br>".
                            "<div style=\"color:#545454;text-indent:2em\">(若下载中遇到任何问题,可直接联系学员的私人助教获得更多帮助)</div><br>".
                            "<div style=\"margin-top:2px;font-weight:bold;text-indent:2em\">理优家长端\"理优升学帮\"功能介绍</div><br>".
                            "<div style=\"text-indent:2em\">通过下载并使用\"理优升学帮\"可以全过程透明化的进行教学反馈,随时随地查看学员的上课情况、作业情况".
                            "以及学员针对性的反馈与评价。</div><br><div style=\"text-indent:2em\">无论身处何地,都能及时准确地了解您孩子的辅导情况!</div><br><div style=\"text-indent:2em\">家长端APP下载".
                            "<span style=\"color:#545454;ext-indent:2em\" >(请用手机的二维码扫描工具扫描下载)</span></div><br>".
                            "<img src=\" http://admin.leo1v1.com/images/shsxb.png\" alt=\" 对不起,图片失效了\">"
                        )); 

                    }else if($work_status == 0){
                        dispatch( new \App\Jobs\SendEmail(
                            $stu_email,$lesson_start."-".$lesson_end."课堂讲义",
                            "<div style=\"text-indent:2em;margin-\">家长您好!</div><br><div style=\"text-indent:2em\">以下是您孩子本次课的课程讲义和课后作业,请及时下载</div><br><div style=\"text-indent:2em\">本邮件由\"理优升学帮\"发送,".
                            "若想获得更加便捷和专业的服务</div><br><div style=\"text-indent:2em\">请务必下载\"理优升学帮\"关注孩子点滴成长!</div><br><div style=\"color:red;text-indent:2em\">".
                            "高效的学习,从课后巩固和完成作业开始</div></br>".
                            " <a href=".$tea_cw_url_ex." target=\"_blank\" style=\"color:blue;text-indent:2em;display:block\">讲义下载</a><br>".
                            "<div style=\"color:#545454;text-indent:2em;margin-top:-3px\">单击文字即可下载</div><br>".
                            "<div style=\"color:#545454;text-indent:2em\">(若下载中遇到任何问题,可直接联系学员的私人助教获得更多帮助)</div><br>".
                            "<div style=\"margin-top:2px;font-weight:bold;text-indent:2em\">理优家长端\"理优升学帮\"功能介绍</div><br>".
                            "<div style=\"text-indent:2em\">通过下载并使用\"理优升学帮\"可以全过程透明化的进行教学反馈,随时随地查看学员的上课情况、作业情况".
                            "以及学员针对性的反馈与评价。</div><br><div style=\"text-indent:2em\">无论身处何地,都能及时准确地了解您孩子的辅导情况!</div><br><div style=\"text-indent:2em\">家长端APP下载".
                            "<span style=\"color:#545454;ext-indent:2em\" >(请用手机的二维码扫描工具扫描下载)</span></div><br>".
                            "<img src=\" http://admin.leo1v1.com/images/shsxb.png\" alt=\" 对不起,图片失效了\">"
                        )); 

                    }else{
                        dispatch( new \App\Jobs\SendEmail(
                            $stu_email,$lesson_start."-".$lesson_end."课堂讲义",
                            "<div style=\"text-indent:2em;margin-\">家长您好!</div><br><div style=\"text-indent:2em\">以下是您孩子本次课的课程讲义和课后作业,请及时下载</div><br><div style=\"text-indent:2em\">本邮件由\"理优升学帮\"发送,".
                            "若想获得更加便捷和专业的服务</div><br><div style=\"text-indent:2em\">请务必下载\"理优升学帮\"关注孩子点滴成长!</div><br><div style=\"color:red;text-indent:2em\">".
                            "高效的学习,从课后巩固和完成作业开始</div></br>".
                            " <a href=".$tea_cw_url_ex." target=\"_blank\" style=\"color:blue;text-indent:2em;display:block\">讲义下载</a><br>".
                            " <a href=".$homework_url_ex." target=\"_blank\" style=\"color:blue;text-indent:2em;display:block;margin-top:-3px\" >作业下载</a><br>".
                            "<div style=\"color:#545454;text-indent:2em;margin-top:-3px\">单击文字即可下载</div><br>".
                            "<div style=\"color:#545454;text-indent:2em\">(若下载中遇到任何问题,可直接联系学员的私人助教获得更多帮助)</div><br>".
                            "<div style=\"margin-top:2px;font-weight:bold;text-indent:2em\">理优家长端\"理优升学帮\"功能介绍</div><br>".
                            "<div style=\"text-indent:2em\">通过下载并使用\"理优升学帮\"可以全过程透明化的进行教学反馈,随时随地查看学员的上课情况、作业情况".
                            "以及学员针对性的反馈与评价。</div><br><div style=\"text-indent:2em\">无论身处何地,都能及时准确地了解您孩子的辅导情况!</div><br><div style=\"text-indent:2em\">家长端APP下载".
                            "<span style=\"color:#545454;ext-indent:2em\" >(请用手机的二维码扫描工具扫描下载)</span></div><br>".
                            "<img src=\" http://admin.leo1v1.com/images/shsxb.png\" alt=\" 对不起,图片失效了\">"
                        )); 
                    }
                    $task->t_lesson_info->field_update_list($item['lessonid'],["lesson_end_todo_flag"=>1]);
                }           
            }
        }
       


    }
}
