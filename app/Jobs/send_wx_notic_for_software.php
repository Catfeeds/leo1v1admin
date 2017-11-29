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

        $this->delete();// 防止队列失败后 重复推送
        $t_parent_info = new  \App\Models\t_parent_info();
        $t_parent_send_mgs_log = new  \App\Models\t_parent_send_mgs_log();
        // $parent_list = $t_parent_info->get_openid_list();

        $parent_list = [
            [
                'wx_openid' => 'orwGAs_IqKFcTuZcU1xwuEtV3Kek',
                'parentid'  => '271968'
            ],
            [
                'wx_openid' => 'orwGAs6J8tzBAO3mSKez8SX-DWq4',
                'parentid'  => '271968'
            ],
            [
                'wx_openid' => ' ',
                'parentid'  => '271968'
            ],

        ];
        /**
         * @ ios软件升级
         * @ is_send_flag:4
         */
        foreach($parent_list as $item){
            $data_leo = [
                'first'    => "家长,您好！ios端理优升学帮新版本V4.5.0正式上线了",
                'keyword1' => "新版本上线通知",
                'keyword2' => "该版本增加了建行分期付款功能，请及时更新哦~",
                'keyword3' => date('Y-m-d H:i:s'),
                'remark'   => "点击推文,前往下载页面"
            ];
            $url_leo = 'http://a.app.qq.com/o/simple.jsp?pkgname=com.yibai.android.parent&fromcase=40002';

            $WxSend = new \App\Helper\WxSendMsg();
            // if($item['wx_openid']){
            //     $WxSend::send_wx_notic_for_software($item['wx_openid'], $data_leo, $url_leo);
            // }

            // $t_parent_send_mgs_log->row_insert([
            //     "parentid"     => $item['parentid'],
            //     "create_time"  => time(),
            //     "is_send_flag" => 4 // ios升级微信通知
            // ]);
        }
    }
}
