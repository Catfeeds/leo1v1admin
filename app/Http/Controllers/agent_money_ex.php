<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class agent_money_ex extends Controller
{
    
    use CacheNick;
    //
    public function agent_money_ex_list(){
        $page_info=$this->get_in_page_info();
        list($start_time, $end_time  ) =$this->get_in_date_range_day(0);
        $ret_info=$this->t_agent_money_ex->get_list($page_info,$start_time, $end_time);
        //dd($ret_info);
        foreach ($ret_info["list"] as &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            // E\Egrade::set_item_value_str($item);


        }
        return $this->pageView(__METHOD__,$ret_info);

    }

    public function agent_add( ) {
        $ex_type = $this->get_in_str_val("ex_type");
        $agent_id = $this->get_in_int_val("agent_id");
        $adminid_id = $this->get_in_int_val("adminid_id");
        $money = $this->get_in_int_val("money");
        $this->t_agent_money_ex->row_insert([
            "add_time" =>  time(NULL),
            "ex_type" =>  $ex_type,
            "agent_id" =>  $agent_id,
            "adminid_id" =>  $adminid_id,
            "money" =>  $money
        ]);
        return $this->output_succ();
    }
    public function agent_money_ex_set() {
        $id= $this->get_in_int_val("id");
        $grade= $this->get_in_e_grade();
        $this->t_agent_money_ex->field_update_list($id,[
            "grade" => $grade,
        ]);
        return $this->output_succ();
    }
    public function agent_money_ex_del() {
        $id= $this->get_in_int_val("id");
        $this->t_agent_money_ex->row_delete($id);
        return $this->output_succ();
    }


}
