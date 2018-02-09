<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMsgToNoticTeaSetFreeTime extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
           rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
           {{first.DATA}}
           待办主题：{{keyword1.DATA}}
           待办内容：{{keyword2.DATA}}
           日期：{{keyword3.DATA}}
           {{remark.DATA}}
        **/

        /**
         * @ 老师通过试讲
         * @ 非测试帐号
         * @ 绑定微信公众号
         * @ 离职时间未0
         **/

        $this->delete();
        $t_teacher_info = new  \App\Models\t_teacher_info();
        $t_parent_send_mgs_log  = new \App\Models\t_parent_send_mgs_log();

        $teacher_list = $t_teacher_info->getTeacherNumTrainThrough();

        // $teacher_list = [
        //     [
        //         "teacherid" => 225427,
        //         "wx_openid" => 'oJ_4fxPmwXgLmkCTdoJGhSY1FTlc',
        //         "nick"      => "James",
        //         "teacher_money_type" => 7,
        //         "teacher_type" => 1
        //     ],
        //     [
        //         "teacherid" => 225427,
        //         "wx_openid" => 'oJ_4fxAN36kXG9EmI0ttGzjymXm0',
        //         "nick"      => "侯勇",
        //         "teacher_money_type" => 7,
        //         "teacher_type" => 1
        //     ],
        //     [
        //         "teacherid" => 225427,
        //         "wx_openid" => 'oJ_4fxIFqfC_CzIGbKZSbKenAX_M',
        //         "nick"      => "侯勇",
        //         "teacher_money_type" => 7,
        //         "teacher_type" => 1
        //     ],

        //     //oJ_4fxIFqfC_CzIGbKZSbKenAX_M

        // ];

        $template_id = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";

        if(count($teacher_list)<5000){
            foreach($teacher_list as $item){
                $data = [
                    "first"    => '您好，'.$item['nick'].'老师，为及时准确地为您安排合适试听课，请您尽快在公号内更新您本周&下周的空闲时间，谢谢',
                    "keyword1" => '设置空闲时间',
                    "keyword2" => '请及时更新您能上课的空闲时间',
                    "keyword3" => date('Y-m-d'),
                ];
                //点击“详情”，或者老师帮的“个人中心”-“空闲时间”哦
                # 兼职老师点击
                $url = 'http://wx-teacher.leo1v1.com/wx_teacher_web/course_arrange';
                // $url = 'http://wx-teacher-web.leo1v1.com/course_arrange.html';
                $data['remark'] = '点击 "详情"，或者老师帮的 "个人中心"-"空闲时间" 哦';
                \App\Helper\Utils::send_teacher_msg_for_wx($item['wx_openid'],$template_id,$data,$url);
                $t_parent_send_mgs_log->row_insert([
                    "parentid"     => $item['teacherid'],
                    "create_time"  => time(),
                    "is_send_flag" => 8 // 市场活动推送模板消息
                ]);
            }
        }

    }

}
