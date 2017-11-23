<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Enums as E;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class send_error_mail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $mail_info;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to,$title,$content, $report_error_type=1 )
    {
        parent::__construct();
        $this->mail_info = [
            "to"                => $to,
            "title"             => $title,
            "content"           => $content,
            "report_error_type" => $report_error_type,
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mail_info = $this->mail_info;
        $to        = @$mail_info["to"];
        $title     = @$mail_info["title"];
        $content   = @$mail_info["content"];
        $report_error_type= @$mail_info["report_error_type"];

        \App\Helper\Utils::logger("send_error_mail:$to");
        if (!$to) {
            \App\Helper\Utils::logger("send_to_all");

            /*
            $email_list=[ "xcwenn@qq.com",  //"wg392567893@163.com", "jhp0416@163.com",
                          "2769730432@qq.com" ,"514728345@qq.com" ];
            foreach($email_list as $email) {
                $ret=\App\Helper\Common::send_mail_leo_com($email,$title,$content);
            }
            */
            //$this->task->t_sys

            $admin_list=["jim","jack","adrian", "tom","james", "boby", "sam","abner","ricky" ];

            foreach($admin_list as $account) {

                \App\Helper\Utils::logger(" send error to wx: $account");
                try {
                    $id = $this->task->t_sys_error_info->add(E\Ereport_error_from_type::V_1, $report_error_type , $title . "<br/>" .$content );
                    $this->task->t_manager_info->send_wx_todo_msg($account,"",$title,  $content, "/tongji_ex/show_sys_error_info?id=$id" );
                } catch (\Exception $e ) {
                    \App\Helper\Utils::logger("err: " . $e->getMessage() );
                }
            }


        }else{
            // echo " send mail to :$to:$title\n";
            \App\Helper\Utils::logger("ADMIN MAIL HANDLE :$to:$title:$content");

            //$ret=\App\Helper\Common::send_mail_admin($to, $title ,  $content );
            $ret = \App\Helper\Common::send_mail_leo_com($to,$title,$content);
            if (!$ret) { //com
            }
            \App\Helper\Utils::logger("ADMIN MAIL HANDLE END :$to");
        }
    }

}
