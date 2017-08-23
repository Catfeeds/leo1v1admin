<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class send_teacher_train_interview_before extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_teacher_train_interview_before';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '1对1课前1小时发送微信通知';

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
        $ret = $task->t_lesson_info_b2->get_train_lesson_intervie_before_info();
        $time_str = date("Y-m-d H:i:s",time());
        $arr=[];
        foreach($ret as &$val){
            $lessonid = $val["lessonid"];
            $userid = $val["userid"];
            $teacherid = $val["teacherid"];
            $subject_str = E\Esubject::get_desc($val["subject"]);
            $grade_str = E\Egrade::get_desc($val["grade"]);
            $lesson_start_str = date("Y-m-d H:i:s",$val["lesson_start"]);
            $val["time"] = $lesson_start_str;
            $phone_spare = $val["phone_spare"];
            $wx_openid = $val["wx_openid"];
            $lesson_time = date("Y-m-d",$val["lesson_start"]);
            $start_str = date("H:i",$val["lesson_start"]);
            $end_str = date("H:i",$val["lesson_end"]);
            $lesson_time_str = $lesson_time." ".$start_str."-".$end_str;


            //微信通知面试老师
            /**
             * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
             * 标题课程 : 待办事项提醒
             * {{first.DATA}}
             * 待办主题：{{keyword1.DATA}}
             * 待办内容：{{keyword2.DATA}}
             * 日期：{{keyword3.DATA}}
             * {{remark.DATA}}
             */
            if($val["wx_openid"]){
                $data=[];
                $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
                $data['first']    = $val["train_realname"]."老师您好,您1小时后有一节面试课程";
                $data['keyword1'] = "1对1面试课程";
                $data['keyword2'] = "\n面试时间：$lesson_time_str "
                                  ."\n面试账号：$phone_spare"
                                  ."\n面试密码：123456"
                                  ."\n年级科目 :".$grade_str."".$subject_str;
                $data['keyword3'] = date("Y-m-d H:i",time());
                $data['remark']   = "请查阅邮件(报名时填写的邮箱),准备好耳机和话筒,并在面试开始前5分钟进入软件,理优教育致力于打造高水平的教学服务团队,期待您的加入,加油!";
                $url = "";
                // $wx_openid = "oJ_4fxLZ3twmoTAadSSXDGsKFNk8";

                \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
                // \App\Helper\Utils::send_teacher_msg_for_wx("oJ_4fxLZ3twmoTAadSSXDGsKFNk8",$template_id,$data,$url);



            }
            //微信通知教研老师
            $name = $task->t_manager_info->get_account($val["uid"]);
            $task->t_manager_info->send_wx_todo_msg_by_adminid($val["uid"],"1对1面试课程",$name."老师您好,您1小时后有一节面试课程","
面试时间:".$lesson_time_str."
面试老师:".$val["train_realname"]."
年级科目:".$grade_str."".$subject_str."
请准备好耳机和话筒,并在面试开始前5分钟进入软件","http://admin.yb1v1.com/tea_manage/train_lecture_lesson?lessonid=".$lessonid);


            $task->t_lesson_info->field_update_list($lessonid,[
                "train_lesson_wx_before"  =>1
            ]);




        }



    }
}
