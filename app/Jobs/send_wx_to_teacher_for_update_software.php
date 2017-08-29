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
    public $ret;
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

        $url_teacher = "http://admin.yb1v1.com/article_wx/leo_teacher_software";

        $template_id_teacher  = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
        $data_teacher['first']      = "PC老师端软件更新";
        $data_teacher['keyword1']   = "【PC老师端4.1.0】";
        $data_teacher['keyword2']   = "
1、修复上传讲义后PC老师端无法调取讲义的问题；
2、支持试听课咨询老师监课和课堂签单。
下载地址（老师端后台）：http://www.leo1v1.com/login/teacher
";
        $data_teacher['keyword3']   = "$date_time";

        $data_teacher['remark']     = "
更新方法：输入下载地址→点击【下载】→【PC电脑】→【立即下载】
";

        $wx_openid = $this->wx_openid;
        if($wx_openid){
            \App\Helper\Utils::send_teacher_msg_for_wx($this->wx_openid,$template_id_teacher, $data_teacher,$url_teacher);
        }


        /**
           待办事项提醒
           待办主题：【PC老师端4.1.0】
           待办内容：
           1、修复上传讲义后PC老师端无法调取讲义的问题；
           2、支持试听课咨询老师监课和课堂签单。
           下载地址（老师端后台）：http://www.leo1v1.com/login/teacher
           日期：{2017/06/01}
           更新方法：输入下载地址→点击【下载】→【PC电脑】→【立即下载】

           跳转链接：http://admin.yb1v1.com/article_wx/leo_teacher_software
           针对用户：3.2.0及之前版本的平台老师

         **/






    }
}
