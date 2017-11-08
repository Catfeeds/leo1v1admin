<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use \App\Enums as E;

class send_wx_tomorrow_tea extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $lesson_start;
    var $lesson_end;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lesson_start, $lesson_end)
    {
        //
        $this->lesson_start = $lesson_start;
        $this->lesson_end = $lesson_end;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        /**
           gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk
           {{first.DATA}}
           上课时间：{{keyword1.DATA}}
           课程类型：{{keyword2.DATA}}
           教师姓名：{{keyword3.DATA}}
           {{remark.DATA}}
        ***/
        $t_lesson_info_b3  = new \App\Models\t_lesson_info_b3();
        $t_assistant_info  = new \App\Models\t_assistant_info();

        $tea_lesson_list = $t_lesson_info_b3->get_teacher_tomorrow_lesson_list($this->lesson_start, $this->lesson_end);
        $template_id_teacher = 'gC7xoHWWX9lmbrJrgkUNcdoUfGER05XguI6dVRlwhUk';
        foreach($tea_lesson_list as $item){
            $tea_lesson_info = $t_lesson_info_b3->get_tea_lesson_info($this->lesson_start, $this->lesson_end,$item['teacherid']);
            $keyword1 = '';
            foreach($tea_lesson_info as $i=> $v){
                $keyword1 .=$i."、".E\Esubject::get_desc($v['subject'])." - ".$v['nick']."-".date('Y-m-d',$v['lesson_start'])."~".date('Y-m-d',$v['lesson_end']);
            }

            $data_tea = [
                "first" => "老师您好，请注意明天的课程安排",
                "keyword1" => $keyword1,
                "keyword2" => '常规课',
                "keyword3" => $item['nick']."老师",
                "remark"   => "请确保讲义已上传，保持网络畅通，提前做好上课准备。"
            ];
            \App\Helper\Utils::send_teacher_msg_for_wx($item['wx_openid'],$template_id_teacher, $data_tea,'');
        }

        /**
         *{{first.DATA}}
         课程名称：{{keyword1.DATA}}
         上课时间：{{keyword2.DATA}}
         上课地点：{{keyword3.DATA}}
         联系电话：{{keyword4.DATA}}
         {{remark.DATA}}
         *
         **/

        $par_lesson_list = $t_lesson_info_b3->get_parent_tomorrow_lesson_list($this->lesson_start, $this->lesson_end);
        $template_id_parent = 'QdFD9O7SPf1eYO_46ptbVeHPnYwTQjCI4_Vj4-wukC8';
        foreach($par_lesson_list as $item){
            $par_lesson_info = $t_lesson_info_b3->get_par_lesson_info($this->lesson_start, $this->lesson_end,$item['parentid']);
            $keyword1 = '';
            foreach($par_lesson_info as $i=> $v){
                $keyword1 .=$i."、".E\Esubject::get_desc($v['subject'])." - ".$v['nick']."-".date('Y-m-d',$v['lesson_start'])."~".date('Y-m-d',$v['lesson_end']);
            }

            $ass_phone = $t_assistant_info->get_phone($item['assistantid']);

            $data_par = [
                "first" => "家长您好，请注意明天的课程安排",
                "keyword1" => $keyword1,
                "keyword2" => '常规课',
                "keyword3" => "学生端",
                "keyword4" => $ass_phone,
                "remark"   => "请保持网络畅通，提前做好上课准备。 祝学习愉快！"
            ];
            $wx  = new \App\Helper\Wx();
            $wx->send_template_msg($item['wx_openid'],$template_id_parent, $data_par,'');
        }
    }
}
