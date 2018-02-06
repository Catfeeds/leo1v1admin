<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class cc_no_return_call extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cc_no_return_call';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cc安排试听成功未回访记录';

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
        //获取全部cc [系统分配seller_student_assign_type=1]
        $cc_list = $this->task->t_manager_info->get_seller_list(E\Eseller_student_assign_type::V_SYSTEM_ASSIGN);
        //遍历获取cc的已试听回访情况
        foreach($cc_list as $item){
            $this->update_no_return_call($item['uid']);
            echo $item['uid']."update ok \n";
        }
    }

    //@desn:更新销售的试听未回访情况
    //@param:$uid 销售id
    public function update_no_return_call($uid){
        //所有成功试听课信息
        $succ_test_lesson_info = $this->task->t_test_lesson_subject_require->get_succ_test_lesson_info($uid);
        $no_call_test_succ = 0;//未回访个数
        $no_call_arr = [];  //记录未回访电话arr
        foreach($succ_test_lesson_info as $val){
            $is_call_back = $this->task->t_tq_call_info->get_is_call_back($uid,$val['require_time'],$val['phone']);
            if(!$is_call_back){
                $no_call_test_succ ++;
                $no_call_arr[]['phone'] = $val['phone'];
            }

        }
        $no_call_test_succ_arr = array_column($no_call_arr, 'phone');
        $no_call_test_succ_str = join(',', $no_call_test_succ_arr);

        //判断是否存在该销售的此项信息
        $is_uid = $this->task->t_cc_no_return_call->field_get_value($uid, 'uid');
        if($is_uid){
            $this->task->t_cc_no_return_call->field_update_list($uid, [
                'no_return_call_num' => $no_call_test_succ,
                'no_call_str' => $no_call_test_succ_str,
                'add_time' => time(NULL)
            ]);
        }else{
            $this->task->t_cc_no_return_call->row_insert([
                'uid' => $uid,
                'no_return_call_num' => $no_call_test_succ,
                'no_call_str' => $no_call_test_succ_str,
                'add_time' => time(NULL)
            ]);
        }

    }

}