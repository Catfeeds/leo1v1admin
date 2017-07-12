<?php
namespace App\Console\Commands;
use \App\Enums as E;
class update_agent_order extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_agent_order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';




    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userid_array      = [];
        $orderid_array     = [];
        $userid_array_str  = '';
        $orderid_array_str = '';
        $userid_list       = $this->task->t_agent->get_all_userid();
        $orderid_list      = $this->task->t_agent_order->get_all_orderid();
        $userid_array      = array_filter(array_column($userid_list,'userid'));
        $orderid_array     = array_filter(array_column($orderid_list,'orderid'));
        $userid_array_str  = implode(',',$userid_array);
        $orderid_array_str = implode(',',$orderid_array);
        $agent_order = $this->task->t_order_info->get_agent_order($orderid_array_str,$userid_array_str);
        if(0<count($agent_order)){
            $this->insert_agent_order($agent_order);
        }
    }

    public function insert_agent_order($agent_order){
        foreach($agent_order as $item){
            $orderid = $item['orderid'];
            $pid = $item['pid'];
            $ppid = $item['ppid'];
            $p_phone = $item['p_phone'];
            $pp_phone = $item['pp_phone'];
            $price = $item['price'];
            $p_level = $this->check_agent_level($p_phone);
            $pp_level = $this->check_agent_level($pp_phone);
            if($p_level == 2){
                $p_price = $price/10>1000?1000:$price/10;
            }elseif($p_level == 1){
                $p_price = $price/20;
            }else{
                $p_price = 0;
            }
            if($pp_level == 2){
                $pp_price = $price/20>500?500:$price/20;
            }else{
                $pp_price = 0;
            }
            $this->task->t_agent_order->add_agent_order_row($orderid,$pid,$p_price,$ppid,$pp_price);
        }
    }

    public function check_agent_level($phone){//黄金1,水晶2,无资格0
        $phone = $this->get_in_str_val('phone',$phone,-1);
        $student_info = [];
        $student_info = $this->task->t_student_info->get_stu_row_by_phone($phone);
        if($student_info){
            return 2;
        }else{
            $agent_item = [];
            $agent_item = $this->task->t_agent->get_agent_info_row_by_phone($phone);
            if($agent_item){
                $test_lesson = [];
                $test_lesson = $this->task->t_agent->get_agent_test_lesson_count_by_id($agent_item['id']);
                if(2<=$test_lesson['count']){
                    return 2;
                }else{
                    return 1;
                }
            }else{
                return 0;
            }
        }
    }

}