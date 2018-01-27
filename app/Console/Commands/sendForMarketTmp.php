<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class sendForMarketTmp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendForMarketTmp';

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
    public function handle()
    {
        $task= new \App\Console\Tasks\TaskController();
        /**
           9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU

           {{first.DATA}}
           待办主题：{{keyword1.DATA}}
           待办内容：{{keyword2.DATA}}
           日期：{{keyword3.DATA}}
           {{remark.DATA}}
        **/

        echo "start :\n";
        $send_list = $task->t_parent_info->get_stu();
        echo "start 2\n";
        $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
        if(count($send_list)<7000){
            foreach($send_list as $i=> $item){
                $checkNeedSend = $task->t_lesson_info_b3->checkNeedSend($item['userid']);
                if($checkNeedSend == 1){
                    $data = [
                        "first"    => '99%的孩子都在学数学思维，到底为什么？特邀新东方数学思维名师来揭密！',
                        "keyword1" => '1小时培养孩子的思维力',
                        "keyword2" => '点击，识别二维码参与课程',
                        "keyword3" => date('Y年m月d日'),
                    ];
                    $url = "https://mp.weixin.qq.com/s/Kyy2bgMpjlOMtqOpQBZb-Q";
                    $data['remark'] = '';
                    echo "send:". $item['wx_openid']  ."\n";
                    \App\Helper\Utils::send_wx_to_parent($item['wx_openid'] ,$template_id,$data,$url);
                    $task->t_parent_send_mgs_log->row_insert([
                        "parentid"     => $item['parentid'],
                        "create_time"  => time(),
                        "is_send_flag" => 10 // 市场活动推送模板消息
                    ]);
                }
            }
        }
    }

}
