<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $mail_info;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to,$title,$content)
    {
        $this->mail_info = [
            "to"      => $to,
            "title"   => $title,
            "content" => $content,
        ];
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mail_info = $this->mail_info;
        $to        = $mail_info["to"];
        $title     = $mail_info["title"];
        $content   = $mail_info["content"];

        \App\Helper\Common::send_mail_leo_com($to, $title ,  $content );
        // \App\Helper\Email::SendMailLeoCom163($to, $title ,  $content );
    }
}
