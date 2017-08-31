<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class audio_post extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:audio_post {--day=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function do_handle()
    {
        $day=$this->get_arg_day();
        $start_time=$day;
        $end_time=$start_time+86400;

        $list=$this->task->t_tq_call_info->get_list_for_post($start_time,$end_time);
        $url='http://rcrai.com:8001/leoedu/call/';

        $clink_args="?enterpriseId=3005131&userName=admin&pwd=".md5(md5("Aa123456" )."seed1")  . "&seed=seed1"  ;
        $admin_map=$this->task->t_manager_info->get_admin_member_list();

        foreach ($list as $item) {
            $record_url = $item["record_url"];
            $adminid= $item["adminid"];
            $admin_info= @$admin_map[$adminid];

            dd($adminid, $admin_info);

            if ( $admin_info && preg_match("/api.clink.cn/", $record_url ) ) {
                $post_data=[];
                $post_data["unique_id"]=$item["id"];
                $post_data["url"]=$item["record_url"] .$clink_args;
                $post_data["timestamp"]=$item["start_time"];

                $post_data["customer"]=[
                    "phone" => $phone,
                    "name" => "",
                ];


                $post_data["staff"] =[
                    "name"=>  $admin_info["account"] ,
                    "roles"=>[
                        "销售",
                    ],
                    "job_number"=>"99999999999990",
                    "dept"=>[
                        "name"=> $admin_info["group_name"] ,
                        "id"=> $admin_info["adminid"]
                    ]
                ];
                dd( $post_data );

            }

        }
        /*
        \App\Helper\Net::http_post_data($url,$data);
        {
            "unique_id": "133333333",
                "url":"http://voice-2.cticloud.cn/05062017/record/7000001/7000001-20170605192458-15302529829-02145994742--record-sip-1-1496661898.303292.mp3?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Date=20170605T113115Z&X-Amz-SignedHeaders=host&X-Amz-Expires=86400&X-Amz-Credential=AKIAPJ2BZJEGHHO4ZI5A%2F20170605%2Fcn-north-1%2Fs3%2Faws4_request&X-Amz-Signature=fa23085879b09d8be859c55299e8cf62259c0069f3dd1b9fa614a6165c13a28a",
                "timestamp":1484640092,
                "staff":{
                "name":"张三",
                    "roles":[
                        "销售",
                        "主管"
                    ],
                    "job_number":"99999999999990",
                    "dept":{
                    "name":"特攻一部c组",
                        "id": "1206"
                        }
            },
                "customer":{
                "phone":"xxxxxxx5229",
                    "name":"李四"
                    }
}
        */

    }
}
