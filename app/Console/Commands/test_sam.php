<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class test_sam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test_sam';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '助教助长周报信息';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //every week
        /**  @var   $task \App\Console\Tasks\TaskController */
        $task=new \App\Console\Tasks\TaskController();
        //$ret_info = $task->t_cr_week_month_info->get_teacher_info();
        $ret_info = $task->t_cr_week_month_info->get_lesson_teacher_info();
        foreach ($ret_info as $key => $value) {
            $phone=trim($value['phone']);
            if ($phone =="" ) {
                $phone_location = "" ;
            }elseif($value['phone_location'] != ''){
                $phone_location = $value['phone_location'];
            }else{
                $url= "https://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=$phone";

                $data= preg_replace("/__GetZoneResult_ = /","", \App\Helper\Net::send_post_data($url,[] )
                );
                $data= preg_replace("/([A-Za-z]*):/","\"\\1\":", $data);
                $data= preg_replace("/'/","\"", $data);

                $data = iconv("GBK","utf-8",$data);
                $arr  = json_decode($data,true);
                if(isset($arr['province']) && isset($arr['carrier'])){
                    if(strpos($arr['carrier'],'移动') ||strpos($arr['carrier'],'联通')||strpos($arr['carrier'],'电信')){
                        $phone_location =  $arr["carrier"];
                    }else{
                        $phone_location =  $arr["province"]."其它";
                    }
                }else{
                    $phone_location =  "";
                }
            }
            //echo $phone_location.'<br/>';
            $task->t_teacher_info->field_update_list($value['teacherid'],[
                "phone_location" =>$phone_location,
            ]);
        }


        $ret_info_1 = $task->t_cr_week_month_info->get_teacher_info();
        //$ret_info = $task->t_cr_week_month_info->get_lesson_teacher_info();
        foreach ($ret_info_1     as $key => $value) {
            $phone=trim($value['phone']);
            if ($phone =="" ) {
                $phone_location = "" ;
            }elseif($value['phone_location'] != ''){
                $phone_location = $value['phone_location'];
            }else{
                $url= "https://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=$phone";

                $data= preg_replace("/__GetZoneResult_ = /","", \App\Helper\Net::send_post_data($url,[] )
                );
                $data= preg_replace("/([A-Za-z]*):/","\"\\1\":", $data);
                $data= preg_replace("/'/","\"", $data);

                $data = iconv("GBK","utf-8",$data);
                $arr  = json_decode($data,true);
                if(isset($arr['province']) && isset($arr['carrier'])){
                    if(strpos($arr['carrier'],'移动') ||strpos($arr['carrier'],'联通')||strpos($arr['carrier'],'电信')){
                        $phone_location =  $arr["carrier"];
                    }else{
                        $phone_location =  $arr["province"]."其它";
                    }
                }else{
                    $phone_location =  "";
                }
            }
            //echo $phone_location.'<br/>';
            $task->t_teacher_info->field_update_list($value['teacherid'],[
                "phone_location" =>$phone_location,
            ]);
        }
    }
}
