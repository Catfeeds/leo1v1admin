<?php

namespace App\Jobs;

use App\Jobs\Job;
use \App\Enums as E;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class noti_add_seller_user extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $phone;
    var $subject;
    var $grade;
    var $origin;
    var $has_pad;
    var $phone_location;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct( $phone,$subject, $grade,$origin,$has_pad )
    {
        $this->phone=$phone;
        $this->subject=$subject;
        $this->grade=$grade;
        $this->origin=$origin;
        $this->has_pad=$has_pad;
    }



    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->phone_location=\App\Helper\Common::get_phone_location($this->phone);
        //louis
        //$this->noit_master("jim");
        //$this->noit_master("louis");
        $this->noit_master("leowang");

        if ($this->origin=="www" || $this->origin=="www_mobile") {
            $this->noit_master("anne");
        }

    }
    public function noit_master($account) {
        
        //通知wx
        $header_msg="有新例子啦!";
        $msg= $this->phone_location.":". $this->phone;
        $url="/seller_student/student_list";
        
        $desc=
             "渠道:".$this->origin ."\n".
             "年级:". E\Egrade::get_desc( $this->grade) ."\n".
             "科目:". E\Esubject::get_desc( $this->subject ) ."\n".
             "Pad:". E\Epad_type::get_desc( $this->has_pad ) ."\n";

        /**  @var  $tt  \App\Console\Tasks\TaskController  */

        $tt= new \App\Console\Tasks\TaskController () ;
        
        $ret=$tt->t_manager_info->send_wx_todo_msg($account,"系统" ,$header_msg,$msg ,$url,$desc);
        if($ret) {
            \App\Helper\Utils::logger("SEND  WX SUCC:$account");
        }else{
            \App\Helper\Utils::logger("SEND  WX ERR:$account");
        }

    }
}
