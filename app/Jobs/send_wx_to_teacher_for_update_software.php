<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class send_wx_to_teacher_for_update_software extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $wx_openid;
    public function __construct($wx_openid)
    {
        $this->wx_openid = $wx_openid;
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


        $date_time = date("Y-m-d");

        $url_teacher = "";

        $template_id_teacher  = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
        $data_teacher['first']      = "";
        $data_teacher['keyword1']   = "【PC老师端4.0.0】";
        $data_teacher['keyword2']   = "
1、新增扩科（扩年级）功能；
2、白板中增加播放视频功能。
下载地址（老师端后台）：
http://www.leo1v1.com/login/teacher";
        $data_teacher['keyword3']   = "$date_time";

        $data_teacher['remark']     = "更新方法：输入下载地址→点击【下载】→【PC电脑】→【立即下载】";

        \App\Helper\Utils::send_teacher_msg_for_wx($this->wx_openid,$template_id_teacher, $data_teacher,$url_teacher);


    }
}
