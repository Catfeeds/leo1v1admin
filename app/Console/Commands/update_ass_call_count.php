<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class update_ass_call_count extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_ass_call_count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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

        /**  @var \App\Console\Tasks\TaskController  $task*/
        $task = new \App\Console\Tasks\TaskController();

        $time = time();
        //1,先查询今天已近记录的call_phone_id
        $start_time1 = strtotime('today');
        $id_str_list = $task->t_revisit_call_count->get_call_phone_id_str($start_time1,$time);
        $uid_phoneid = [];
        foreach ($id_str_list as $item) {
            if (is_array($item)) {
                $uid_phoneid[$item['uid']] = $item['phoneids'];
            }
        }

        //2,然后查询助教的学情回访    每分钟查询上一分钟的
        $end_time    = strtotime( date('Y-m-d H:i:00', $time) );
        $start_time2 = $end_time-60;
        $ret_info    = $task->t_revisit_info->get_revisit_type0_per_minute($start_time2, $end_time);

        //3,有学情回访后，在获取当日的其他回访信息
        foreach($ret_info as $item) {
            if (is_array($item)){
                $uid      = $item['uid'];
                $userid   = $item['userid'];
                $id_str   = @$uid_phoneid[$uid] ? $uid_phoneid[$uid] : 1;
                $ret_list = $task->t_revisit_info->get_revisit_type6_per_minute($start_time1, $end_time, $uid, $userid, $id_str);

                foreach($ret_list as $val) {
                    if (is_array($val)){
                        $task->t_revisit_call_count->row_insert([
                            'uid'           => $uid,
                            'userid'        => $userid,
                            'revisit_time1' => $item['revisit_time1'],
                            'revisit_time2' => $val['revisit_time2'],
                            'call_phone_id' => $val['call_phone_id'],
                            'create_time'   => $time,
                        ]);
                    }
                }
            }
        }

    }

}
