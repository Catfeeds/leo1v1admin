<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class send_wx_notic_for_software extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $t_parent_info = new  \App\Models\t_parent_info();
        $t_parent_send_mgs_log = new  \App\Models\t_parent_send_mgs_log();
        // $parent_list = $t_parent_info->get_openid_list();

        $wx = new \App\Helper\Wx();

        $parent_list = [
            [
                'wx_openid' => 'orwGAs_IqKFcTuZcU1xwuEtV3Kek',
                'parentid' => '271968'

            ]
        ];

        $parent_template_id  = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU';

        foreach($parent_list as $item){
            // $data_leo = [
            //     'first'    => "家长您好，【理优在线教育】调时间功能上线了",
            //     'keyword1' => "功能上线通知",
            //     'keyword2' => "点击个人中心，课程详情列表中调课申请按钮，选择想要调换至的时间发给老师，老师将结合自己情况进行处理",
            //     'keyword3' => date('Y-m-d H:i:s'),
            //     'remark'   => ""
            // ];
            // $url_leo = '';


              // 成绩记录功能
            $data_leo = [
                'first'    => "家长您好，【理优在线教育】成绩记录功能上线了",
                'keyword1' => "功能上线通知",
                'keyword2' => "点击个人中心，成绩记录功能，录入孩子成绩让班主任准确掌握孩子情况，制定实际有效解决孩子学习问题的课程规划",
                'keyword3' => date('Y-m-d H:i:s'),
                'remark'   => ""
            ];
            $url_leo = '';


            $wx->send_template_msg($item['wx_openid'], $parent_template_id, $data_leo, $url_leo);

            $t_parent_send_mgs_log->row_insert([
                "parentid" => $item['parentid'],
                "create_time" => time(),
                "is_send_flag" => 3
            ]);

        }

    }
}
