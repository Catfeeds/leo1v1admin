<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class send_interview_remind_for_wx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_interview_remind_for_wx';

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
        //
        $task = new \App\Console\Tasks\TaskController();

        $now = time();

        $remind_list = $task->t_interview_remind->get_remind_list($now);

        $wx = new \App\Helper\Wx();
        $parent_template_id  = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU';
        $color = "#a930e7";

        foreach($remind_list as $v){
            //i.name,i.post,i.dept
            $data_leo = [
                'first'    => $v['account']." 您好，".date('Y-m-d H:i:s',$v['interview_time'])."有一场面试请及时处理",
                'keyword1' => "面试通知",
                'keyword2' => " 应聘人姓名: ".$v['name'].";
                                应聘职位: ".$v['post'].";
                                所属部门: ".$v['dept'].";
                                面试时间: ".date('Y-m-d H:i:s',$v['interview_time']),
                'keyword3' => date('Y-m-d H:i:s'),
                'remark'   => ""
            ];
            $url_leo = '';

            $wx->send_template_msg_color($v['wx_openid'], $parent_template_id, $data_leo, $url_leo,$color);

            $task->t_interview_remind->field_update_list($v['id'],[
                "is_send_flag" => 1,
                "send_msg_time" => time()
            ]);

        }


    }
}
