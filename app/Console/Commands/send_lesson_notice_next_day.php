<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use \App\Enums as E;
class send_lesson_notice_next_day extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_lesson_notice_next_day';

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

        $task->t_lesson_info_b3->switch_tongji_database();

        $lesson_info_list = $task->t_lesson_info_b3->get_next_day_lesson_info();

        $wx = new \App\Helper\Wx();
        $template_id_parent  = 'QdFD9O7SPf1eYO_46ptbVeHPnYwTQjCI4_Vj4-wukC8';
        $template_id_teacher = 'gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk';

        foreach($lesson_info_list as $item){
            $data_tea = $this->get_data_tea($item);
            $data_stu = $this->get_data_par($item);


            if($item['par_openid']){
                $wx->send_template_msg($item['par_openid'],$template_id_parent,$data_stu ,'');
            }

            if($item['tea_openid']){
                \App\Helper\Utils::send_teacher_msg_for_wx($item['tea_openid'],$template_id_teacher, $data_tea,'');
            }
        }

    }


    public function get_data_tea($item){
        $subject_str = E\Esubject::get_desc($item['subject']);
        $data_msg = [
            'first'    =>"老师您好，您于明天".date('H:i',$item['lesson_start'])."有一节 $subject_str 课。",
            'keyword1' => '"'.date('Y-m-d H:i',$item['lesson_start']).' ~ '.date('H:i',$item['lesson_end']),
            'keyword2' => "试听课",
            'keyword3' => "'".$item['tea_nick']."'",
            'remark'   => "请确保讲义已上传，保持网络畅通，提前做好上课准备。"
        ];

        return $data_msg;
    }

    public function get_data_par($item){
        $subject_str = E\Esubject::get_desc($item['subject']);
        $data_msg = [
            'first'    =>"家长您好，".$item['stu_nick']."同学于明天".date('H:i',$item['lesson_start'])."有一节".$item['tea_nick']."老师的 $subject_str 课。",
            'keyword1' => "$subject_str",
            'keyword2' => date('Y-m-d H:i',$item['lesson_start']).' ~ '.date('H:i',$item['lesson_end']),
            'keyword3' => "学生端",
            'keyword4' => "'".$item['phone']."'",
            'remark'   => "请保持网络畅通，提前做好上课准备。 祝学习愉快！"
        ];

        return $data_msg;
    }



    /***
        QdFD9O7SPf1eYO_46ptbVeHPnYwTQjCI4_Vj4-wukC8 // 家长

        {{first.DATA}}
        课程名称：{{keyword1.DATA}}
        上课时间：{{keyword2.DATA}}
        上课地点：{{keyword3.DATA}}
        联系电话：{{keyword4.DATA}}
        {{remark.DATA}}


        // gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk // 老师帮

        {{first.DATA}}
        上课时间：{{keyword1.DATA}}
        课程类型：{{keyword2.DATA}}
        教师姓名：{{keyword3.DATA}}
        {{remark.DATA}}






     ***/
}
