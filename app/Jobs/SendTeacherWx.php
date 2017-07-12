<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTeacherWx extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    var $wx_info;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tea_list,$template_id,$data,$url)
    {
        //
        $this->wx_info=[
            "template_id" => $template_id,
            "data"        => $data,
            "tea_list"    => $tea_list,
            "url"         => $url,
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $wx_info     = $this->wx_info;
        $template_id = $wx_info['template_id'];
        $data        = $wx_info['data'];
        $tea_list    = $wx_info['tea_list'];
        $url         = $wx_info['url'];

        foreach($tea_list as $tea_val){
            if(isset($tea_val['wx_openid'])){
                \App\Helper\Utils::send_teacher_msg_for_wx($tea_val['wx_openid'] ,$template_id,$data,$url);
            }
        }
    }

    public function get_qq_group_html($subject){
        $qq_common = ["问题答疑","528851744","用于薪资，软件等综合问题"];
        $qq_group  = [
            1=>[
                ["教研-语文","126321887","处理教学相关事务"],
                ["排课-语文","103229898","用于抢课"]
            ],2=>[
                ["教研-数学","29759286","处理教学相关事务"],
                ["排课-数学","132041242","用于排课"],
            ],3=>[
                ["教研-英语","451786901","处理教学相关事务"],
                ["排课-英语","41874330","用于排课"],
            ],4=>[
                ["教研-综合","513683916","处理教学相关事务"],
                ["排课-理化","129811086","用于排课"],
            ],5=>[
                ["教研-综合","513683916","处理教学相关事务"],
                ["排课-文理综合","538808064","用于排课"],
            ],
        ];
        if($subject<=3){
            $key=$subject;
        }elseif(in_array($subject,[4,5])){
            $key=4;
        }else{
            $key=5;
        }
        $qq_group[$key][]=$qq_common;
        $html="";
        foreach($qq_group[$key] as $qq_val){
            if($html==""){
                $html = "\n【LEO】".$qq_val[0]."\n群号：".$qq_val[1]."\n群介绍：".$qq_val[2]."\n";
            }else{
                $html .= "【LEO】".$qq_val[0]."\n群号：".$qq_val[1]."\n群介绍：".$qq_val[2]."\n";
            }
        }
        return $html;
    }

}
