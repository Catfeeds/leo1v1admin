<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Common;
use \App\Enums as E;



class send_wx_msg_for_test_lesson extends Command
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
        $task = new \App\Console\Tasks\TaskController();

        $lesson_begin_halfhour = time()+30*60;
        $lesson_end_halfhour   = time()+31*60;

        // 获取试听课 课前30分钟
        $test_lesson_list_halfhour = $task->t_lesson_info_b2->get_test_lesson_info_for_time($lesson_begin_halfhour, $lesson_end_halfhour);

        foreach($test_lesson_list_halfhour as $item){
            $this->send_wx_msg($item,1);
        }

        // 试听课开始5分钟
        $lesson_begin_five = time()-5*60;
        $lesson_end_five   = time()-6*60;

        $test_lesson_list_five  = $task->t_lesson_info_b2->get_test_lesson_info_for_time($lesson_begin_five,$lesson_end_five);

        foreach($test_lesson_list_five as $item){
            // $this->send_wx_msg($item,2);


        }

        //

    }



    public function send_wx_msg($item, $type){
        /**
         // gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk // 老师帮

           {{first.DATA}}
           上课时间：{{keyword1.DATA}}
           课程类型：{{keyword2.DATA}}
           教师姓名：{{keyword3.DATA}}
           {{remark.DATA}}


           rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
           {{first.DATA}}
           待办主题：{{keyword1.DATA}}
           待办内容：{{keyword2.DATA}}
           日期：{{keyword3.DATA}}
           {{remark.DATA}}

        **/


        /**
         // 待办主题  9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU  // 家长
           {{first.DATA}}
           待办主题：{{keyword1.DATA}}
           待办内容：{{keyword2.DATA}}
           日期：{{keyword3.DATA}}
           {{remark.DATA}}



           // cef14RT4mQIDTQ4L5_rQCIynDL36FEeAuX0-nAj8XWU // 上课提醒
           {{first.DATA}}
           课程名称：{{keyword1.DATA}}
           时间：{{keyword2.DATA}}
           {{remark.DATA}}


         **/


        if($type == 1){
            $remark_tea = "开课前十五分钟可提前进入课堂，请及时登录老师端，做好课前准备工作";
            $remark_par = "开课前五分钟可提前进入课堂，请及时登录学生端进入课堂。";
            $remark_ass = "请及时跟进";

            $first_tea = "老师您好，您于30分钟后有一节xx课。";
            $first_par = "老师您好，您于30分钟后有一节xx课。";
            $first_ass = "您好，您的学员xx同学于30分钟后有一节xx课。";

        }elseif($type == 2){
            $remark_tea = "请尽快进入课堂，如有紧急情况请尽快联系咨询老师。";
            $remark_par = "请尽快进入课堂，如有紧急情况请尽快联系咨询老师。";
            $remark_ass = "请立刻联系同学/老师。";
        }

        // 给老师发送
        $template_id_teacher = "gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk";
        $data_tea = [
            "first" => '老师您好，您于30分钟后有一节xx课。',
            "keyword1" => date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end']),
            "keyword2" => '试听课',
            "keyword3" => "'".$item['teacher_nick']."'",
            "remark"   => "$remark_tea"
        ];
        $url_tea = '';
        \App\Helper\Utils::send_teacher_msg_for_wx($item['tea_openid'],$template_id_teacher, $data_tea,$url_tea);

        // 给家长发送
        $wx  = new \App\Helper\Wx();
        $subject_str = E\Esubject::get_desc($item['subject']);
        $template_id_parent = 'cef14RT4mQIDTQ4L5_rQCIynDL36FEeAuX0-nAj8XWU';
        $data_par = [
            "first"    => "家长您好，".$item['stu_nicl']."同学于30分钟后有一节 $subject_str 课。",
            "keyword1" => "$subject_str -- 课程类型: 试听课 -- 老师: ".$item['tea_nick'],
            "keyword2" => date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end']),
            "remark"   => "$remark_par"
        ];
        $url_parent = '';
        $wx->send_template_msg($item['par_openid'],$template_id_parent,$data_par ,$url_parent);

        // 给助教发送
        $data_ass = [
            "first"    => "您好，您的学员".$item['stu_nicl']."同学于30分钟后有一节 $subject_str 课。",
            "keyword1" => "$subject_str -- 课程类型: 试听课 -- 老师: ".$item['tea_nick'],
            "keyword2" => date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end']),
            "remark"   => "$remark_ass"
        ];
        $url_ass = '';
        $wx->send_template_msg($item['ass_openid'],$template_id_parent,$data_ass ,$ass_url);



    }
}
