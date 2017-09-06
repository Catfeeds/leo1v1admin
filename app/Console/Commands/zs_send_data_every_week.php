<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

require_once  app_path("/Libs/Qiniu/functions.php");

class zs_send_data_every_week extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:zs_send_data_every_week';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * task
     *
     * @var \App\Console\Tasks\TaskController
     */

    var $task       ;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->task        = new \App\Console\Tasks\TaskController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $task=new \App\Console\Tasks\TaskController();
        $now = time();

        $start_time = 1503849600;//2017/8/28 0:0:0
        $end_time = 1504454400;//2017/9/4 0:0:0
        $success_through       = $task->t_teacher_info->get_success_through($start_time,$end_time);
        $success_apply         = $task->t_teacher_info->get_success_apply($start_time,$end_time);
        $video_apply           = $task->t_teacher_info->get_video_apply($start_time,$end_time);
        $lesson_apply          = $task->t_teacher_info->get_lesson_apply($start_time,$end_time);
        $ret = [];
        foreach($success_through as $key => $value){
            $ret[$value['phone']] = [
                "phone"           => $value['phone'],
                "teacherid"       => $value['teacherid'],
                "nick"            => $value["nick"],
                "reference"       => $value["reference"],
                "wx_openid"       => $value["wx_openid"],
                "success_through" => $value["sum"],
                "success_apply"   => 0,
                "total_apply"     => 0,
            ];
        }

        foreach($success_apply as $key => $value){
            if(isset($ret[$value['phone']])){
                $ret[$value['phone']]['success_apply'] = $value['sum'];
            }else{
                $ret[$value['phone']] = [
                    "phone"           => $value['phone'],
                    "teacherid"       => $value["teacherid"],
                    "nick"            => $value["nick"],
                    "reference"       => $value["reference"],
                    "wx_openid"       => $value["wx_openid"],
                    "success_through" => 0,
                    "success_apply"   => $value['sum'],
                    "total_apply"     => 0,
                ];
            }
        }

        foreach($video_apply as $key => $value){
            if(isset($ret[$value['phone']])){
                $ret[$value['phone']]['total_apply'] = $value['sum'];
            }else{
                $ret[$value['phone']] = [
                    "phone"           => $value['phone'],
                    "teacherid"       => $value['teacherid'],
                    "nick"            => $value['nick'],
                    "reference"       => $value['reference'],
                    "wx_openid"       => $value["wx_openid"],
                    "success_through" => 0,
                    "success_apply"   => 0,
                    "total_apply"     => $value['sum'],
                ];
            }
        }

        foreach($lesson_apply as $key => $value){
            if(isset($ret[$value['phone']])){
                $ret[$value['phone']]['total_apply'] += $value['sum'];
            }else{
                $ret[$value['phone']] = [
                    "phone"           => $value['phone'],
                    "teacherid"       => $value['teacherid'],
                    "nick"            => $value["nick"],
                    "reference"       => $value["reference"],
                    "wx_openid"       => $value["wx_openid"],
                    "success_through" => 0,
                    "success_apply"   => 0,
                    "total_apply"     => $value['sum'],
                ];
            }
        }

        foreach ($ret as $key => $value) {
            // $wx_openid      = $value['wx_openid'];
            $wx_openid   = "oJ_4fxH0imLIImSpAEOPqZjxWtDA";
             /**
                 * 模板ID : kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc
                 * 标题   : 反馈进度通知
                 * {{first.DATA}}
                 * 反馈内容：{{keyword1.DATA}}
                 * 处理结果：{{keyword2.DATA}}
                 * {{remark.DATA}}
                 */
            if($wx_openid!=""){
                $record_info = $value['nick'];
                $status_str  = "本周代理详情";
                $template_id         = "kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc";
                $wx_data["first"]    = $record_info;
                $wx_data["keyword1"] = $status_str;
                $wx_data["keyword2"] = "\n 1、上周填写报名".$value['success_apply']."人"
                                     ."\n 2、上周录制预约".$value["total_apply"]."人"
                                     ."\n 3、上周成功入职".$value['success_through']."人";
                $wx_data["remark"] = "好友成功入职后，即可获得伯乐奖，"
                                   ."伯乐奖将于每月10日结算（如遇节假日，会延后到之后的工作日），"
                                   ."请及时绑定银行卡号，如未绑定将无法发放。";
                self::send_teacher_msg_for_wx($openid,$template_id,$wx_data);
                \App\Helper\Utils::send_reference_msg_for_wx($wx_openid,$record_info,$status_str);
            }
        }
    }
}
