<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Enums as E;

class seller_student_auto_free extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "command:seller_student_auto_free";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "系统自动回流";


    /**
     * Execute the console command.
     * @return mixed
     */
    public function do_handle(){
        $ret = $this->task->t_seller_student_new->get_auto_free_list();
        foreach($ret as $item){
            if($item['seller_student_assign_type']==1 && $item['first_contact_time']>$item['admin_assign_time']){
                if($item['last_revisit_time']>$item['first_contact_time'] && $item['last_edit_time']>$item['first_contact_time']){
                    $first_time = max($item['last_revisit_time'],$item['last_edit_time']);
                }else{
                    $first_time = $item['first_contact_time'];
                }
                $item['assign_type'] = '系统分配';
            }else{
                if($item['last_revisit_time']>$item['admin_assign_time'] && $item['last_edit_time']>$item['admin_assign_time']){
                    $first_time = max($item['last_revisit_time'],$item['last_edit_time']);
                }else{
                    $first_time = $item['admin_assign_time'];
                }
                $item['assign_type'] = '抢单';
            }
            $left_time = strtotime(date('Y-m-d',$first_time))+8*24*3600-time();
            $item['left_time'] = $left_time;
            if($left_time>7*24*3600 || $left_time<0){
                $item['left_time_desc'] = '';
            }else{
                $hour = floor($item['left_time']/3600);
                $min = floor($item['left_time']%3600/60);
                $sec = floor($item['left_time']%3600%60);
                $item['left_time_desc'] = $hour.'时'.$min.'分'.$sec.'秒';
            }
            echo $this->task->cache_get_account_nick($item['admin_revisiterid']).':'.$item['userid'].'=>'.$item['left_time_desc']."\n";
        }
    }

}
