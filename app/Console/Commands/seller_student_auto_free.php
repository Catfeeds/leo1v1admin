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
        $this->del_seller_auto_free_log();
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
            if($left_time<=0){
                // $this->set_seller_free($item['phone'],$item['userid']);
                $left_time = abs($left_time);
                $this->task->t_seller_auto_free_log->row_insert([
                    'userid'=>$item['userid'],
                    'adminid'=>$item['admin_revisiterid'],
                    'assign_type'=>$item['seller_student_assign_type'],
                    'assign_time'=>$item['admin_assign_time'],
                    'last_revisit_time'=>$item['last_revisit_time'],
                    'last_edit_time'=>$item['last_edit_time'],
                    'first_contact_time'=>$item['first_contact_time'],
                    'left_time'=>strtotime(date('Y-m-d',$first_time))+8*24*3600,
                    'left_time_long'=>$left_time,
                    'create_time'=>time(),
                ]);
                $hour = floor($left_time/3600);
                $min = floor($left_time%3600/60);
                $sec = floor($left_time%3600%60);
                $left_time_desc = $hour.'时'.$min.'分'.$sec.'秒';
                $send_account = $this->task->cache_get_account_nick($item['admin_revisiterid']);
                $this->send_wx_msg($item['phone'],$item['assign_type'],$send_account,$item['admin_assign_time'],$item['last_revisit_time'],$item['last_edit_time'],$item['first_contact_time'],$first_time,$left_time_desc);
            }
        }
    }

    public function set_seller_free($phone,$userid){
        $this->task->t_book_revisit->add_book_revisit(
            $phone,
            "操作者:系统 状态: 过期回到公海",
            "system"
        );
        $this->task->t_seller_student_new->set_user_free($userid);
    }

    public function send_wx_msg($phone,$assign_type,$send_account,$admin_assign_time,$last_revisit_time,$last_edit_time,$first_contact_time,$first_time,$left_time_desc){
        $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
        $theme = "私海过期例子回流";
        $desc = "例子：".$phone."\n"
              ."例子类型：".$assign_type."\n"
              ."分配人：".$send_account."\n"
              ."分配时间：".date('Y-m-d H:i:s',$admin_assign_time)."\n"
              ."最后拨打时间：".date('Y-m-d H:i:s',$last_revisit_time)."\n"
              ."最后编辑时间：".date('Y-m-d H:i:s',$last_edit_time)."\n"
              ."首次拨通时间：".date('Y-m-d H:i:s',$first_contact_time)."\n"
              ."过期时间:".date('Y-m-d H:i:s',strtotime(date('Y-m-d',$first_time))+8*24*3600)."\n"
              ."过期时长:".$left_time_desc;
        $account_arr = ['tom'];
        foreach($account_arr as $account){
            $this->task->t_manager_info->send_template_msg(
                $account,
                $template_id,
                [
                    "first"    => "",
                    "keyword1" => $theme,
                    "keyword2" => "",
                    "keyword3" => date("Y-m-d H:i:s"),
                    "remark"   => $desc,
                ]
            );
        }
    }

    public function del_seller_auto_free_log(){
        $ret_info = $this->task->t_seller_auto_free_log->get_all_list();
        foreach($ret_info as $item){
            $res = $this->task->t_seller_auto_free_log->row_delete($item['id']);
            echo $item['id'].'=>'.$res."\n";
        }
    }
}
