<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums as E;
use Illuminate\Support\Facades\Input;


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
       if (!$this->check_account_in_arr(["jim","amanda"]) ) {
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
    //@desn:通过excel添加赠送活动
    public function add_by_excel()
    {
        $file = Input::file('file');
        if ($file->isValid()) {
            //处理列
            $realPath = $file -> getRealPath();
            $flag = $this->upload_ass_stu_from_xls( $realPath);
            if($flag == 1)
                return outputjson_success();
            else
                return outputjson_ret(false);

        } else {
            return outputjson_ret(false);
        }
    }

    public function upload_ass_stu_from_xls($realPath){
        $adminid = $this->get_account_id();
        $file = Input::file('file');
        \App\Helper\Utils::logger("yayayyal 1111");
        if ($file->isValid()) {
            //处理列
            $realPath = $file -> getRealPath();
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');

            $objPHPExcel = $objReader->load($realPath);
            $objPHPExcel->setActiveSheetIndex(0);
            $arr=$objPHPExcel->getActiveSheet()->toArray();

            foreach($arr as $k=>&$val){
                if($k == 0)
                    continue;
                if(empty($val[0]) || $k==0 ){
                    unset($arr[$k]);
                }
                $agent_row = $this->t_agent->get_id_row_by_phone($val[0]);
                $agent_id = $agent_row['id'];
                $agent_money_ex_type = $val[1];
                $money = $val[2];
                if(!empty($agent_id) && !empty($agent_money_ex_type) && !empty($money)){
                    $this->t_agent_money_ex->row_insert([
                        "add_time" =>  time(NULL),
                        "agent_money_ex_type" =>  $agent_money_ex_type,
                        "agent_id" =>  $agent_id,
                        "adminid" =>  $adminid,
                        "money" =>  $money*100
                    ]);
                }
            }


            return 1;
        }else{
            return 0;
        }
    }

    //@desn:返回模板excel
    public function download_excel_blade(){
        return response()->download(public_path('/source/a.xlsx'), '优学优享添加活动奖励模板.xlsx');
    }
}

