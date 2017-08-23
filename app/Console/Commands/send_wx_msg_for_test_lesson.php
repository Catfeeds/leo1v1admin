<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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

        // 获取试听课 课前30分钟
        $test_lesson_list_halfhour = $task->t_lesson_info_b2->get_test_lesson_info_halfhour();

        foreach($test_lesson_list_halfhour as $item){

        }


    }



    public function send_wx_msg($item){
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


           //SqAHV3G2UM71LmLFRYeE0ub1-lDU0_JgrDNhdDd-FTA  // 课程开课通知


         **/




        $template_id_teacher = "gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk";
        $data_tea = [
            "first" => '老师您好，您于30分钟后有一节xx课。',
            "keyword1" => date('Y-m-d H:i:s',$item['lesson_start']).' ~ '.date('H:i:s',$item['lesson_end']),
            "keyword2" => '试听课',
            "keyword3" => "'".$item['teacher_nick']."'",
            "remark"   => "开课前十五分钟可提前进入课堂，请及时登录老师端，做好课前准备工作"
        ];

        $url_tea = '';

        \App\Helper\Utils::send_teacher_msg_for_wx($item['tea_openid'],$template_id_teacher, $data_tea,$url_tea);


    }
}
