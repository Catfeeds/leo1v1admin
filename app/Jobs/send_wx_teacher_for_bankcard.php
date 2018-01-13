<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class send_wx_teacher_for_bankcard extends Job implements ShouldQueue
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
         * @ 老师在职
         * @ 非测试帐号
         * @ 未绑定银行卡
         * @ 通过试讲
         **/

        $this->delete();
        $t_teacher_info = new  \App\Models\t_teacher_info();

        $unbound_list = $t_teacher_info->get_unbound_teacher_list();
        $template_id = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";

        if(count($unbound_list)<2000){
            foreach($unbound_list as $item){
                $data = [
                    "first"    => '老师您好，为本月薪资能如期发放，请立即绑定银行卡。如在下月7号前还未绑定，您本月薪资将延期发放;',
                    "keyword1" => '绑定银行卡',
                    "keyword2" => '绑定银行卡入口：老师帮公众号-【个人中心】-【我的收入】',
                    "keyword3" => date('Y-m-d H:i:s'),
                    "keyword4" => '感谢老师的理解与配合'
                ];
                \App\Helper\Utils::send_teacher_msg_for_wx($item['wx_openid'],$template_id,$data,'');
            }
            // \App\Helper\Utils::send_teacher_msg_for_wx("oJ_4fxPmwXgLmkCTdoJGhSY1FTlc",$template_id,$data,'');
        }


    }
}
