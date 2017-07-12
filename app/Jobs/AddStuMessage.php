<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddStuMessage extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $stu_message;
    var $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($content,$value,$type)
    {
        $this->stu_message=[
            "content" => $content,
            "value"   => $value,
        ];
        $this->type=$type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        \App\Helper\Utils::logger("handle xxxx=====");
        $stu_message = $this->stu_message;
        $content = $stu_message['content'];
        $value   = $stu_message['value'];
        $tea_info    = new \App\Models\t_teacher_info () ;
        $stu_info    = new \App\Models\t_student_info () ;
        $baidu_msg   = new \App\Models\t_baidu_msg() ;
        $use_push_flag=1;
        if($this->type==1){
            $userid_list = $stu_info->get_every_studentid();
            $push_type   = 1007;
            $push_num    = 0;
        }else{
            $userid_list = $tea_info->get_every_teacherid();
            $push_type   = 2010;
            $push_num    = 100;
        }
        
        \App\Helper\Utils::logger("job start=====");
        foreach($userid_list as $val){
            $baidu_msg->baidu_push_msg($val['userid'],$content,$value,$push_type,$push_num,$use_push_flag);
        }
        \App\Helper\Utils::logger("job end=====");
    }
}
