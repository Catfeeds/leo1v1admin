<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class send_wx_notic_to_tea extends Job implements ShouldQueue
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


        $t_teacher_info = new \App\Models\t_teacher_info();
        $t_parent_send_mgs_log = new  \App\Models\t_parent_send_mgs_log();
        // $tea_list = $t_teacher_info->get_openid_list();


        $tea_list = [
            [
                'wx_openid' => 'oJ_4fxPmwXgLmkCTdoJGhSY1FTlc',
                'teacherid' => '225427'

            ]
        ];

        foreach($tea_list as $item){
            $tea_template_id  = 'rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o';
            $data_leo = [
                'first'    => "老师您好，家长自主调时间功能上线了",
                'keyword1' => "功能上线通知",
                'keyword2' => "收到相关推送，点击详情可进行调课同意处理或者拒绝调课处理",
                'keyword3' => date('Y-m-d H:i:s'),
                'remark'   => ""
            ];
            $url_leo = '';

            \App\Helper\Utils::send_teacher_msg_for_wx($item['wx_openid'], $tea_template_id, $data_leo, $url_leo);

            if($test){
                
            }

            $t_parent_send_mgs_log->row_insert([
                "parentid" => $item['teacherid'],
                "create_time" => time(),
                "is_send_flag" => 2
            ]);

        }

    }
}
