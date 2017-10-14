<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums as E;

use App\Http\Requests;

class agent_money_ex extends Controller
{
    //

    use CacheNick;
    //
    public function agent_money_ex_list(){
        $page_info=$this->get_in_page_info();
        list($start_time, $end_time  ) =$this->get_in_date_range_month(0);
        $ret_info=$this->t_agent_money_ex->get_list($page_info,$start_time, $end_time);
        //dd($ret_info);
        foreach ($ret_info["list"] as &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            E\Eagent_money_ex_type::set_item_value_str($item);
            $item["money"] = $item["money"]/100;
            \App\Helper\Common::set_item_enum_flow_status($item);
            

        }
        return $this->pageView(__METHOD__,$ret_info);

    }

    public function agent_add( ) {
        if (!$this->check_account_in_arr(["jim"]) ) {
            return $this->output_err("没有权限");
        }

        $agent_money_ex_type = $this->get_in_int_val("agent_money_ex_type");
        $agent_id = $this->get_in_int_val("agent_id");
        $money = $this->get_in_str_val("money");
        $adminid = $this->get_account_id();
        $this->t_agent_money_ex->row_insert([
            "add_time" =>  time(NULL),
            "agent_money_ex_type" =>  $agent_money_ex_type,
            "agent_id" =>  $agent_id,
            "adminid" =>  $adminid,
            "money" =>  $money*100
        ]);
        return $this->output_succ();
    }
   public function agent_money_ex_del() {
       if (!$this->check_account_in_arr(["jim"]) ) {
           return $this->output_err("没有权限");
       }
       
       $id= $this->get_in_int_val("id");
       $flow_status = $this->t_agent_money_ex->get_flow_status($id);

       if($flow_status > 0)
           return $this->output_err("该订单已经申请不能删除!");

       $this->t_agent_money_ex->row_delete($id);
        return $this->output_succ();
    }

     //@desn:发放现金审批
    public function examine(){
        $reason          = $this->get_in_str_val("reason");
        $agent_money_id = $this->get_in_int_val('agent_money_id');
        $flow_type       = $this->get_in_e_flow_type(0);
        $from_key_int    = $this->get_in_int_val("from_key_int",0);
        $from_key2_int   = $this->get_in_int_val("from_key2_int",0);
 
        
        $ret=$this->t_flow->add_flow(
            $flow_type,$this->get_account_id(),$reason,$from_key_int,NULL,$from_key2_int
        );

        if($ret) {
            return $this->output_succ();
        }else{
            return $this->output_err( "已经申请过了" );
        }
  
    }
}
