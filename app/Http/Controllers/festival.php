<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class festival extends Controller
{
    public function festival_list()
    {
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $page_info    = $this->get_in_page_info(); 
        $ret_info = $this->t_festival_info->get_new_create_festival_list($page_info,$start_time,$end_time);
        foreach($ret_info["list"] as &$item){
            $item["begin_time_str"] = date("Y-m-d",$item["begin_time"]);
            $item["end_time_str"] = date("Y-m-d",$item["end_time"]);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function update_festival_info(){
        $arr=array(
            "5月12日"=>"护士节",
            "5月14日"=>"提前批填报志愿",
            "5月15日"=>"提前批填报志愿",
            "5月16日"=>"统一志愿",
            "5月22日"=>"中职校填志愿",
            "5月31日"=>"统一志愿",
            "6月1日"=>"儿童节",
            "6月5日"=>"环境日",
            "6月7日"=>"高考",
            "6月8日"=>"高考",
            "6月9日"=>"端午节",
            "6月18日"=>"中考",
            "6月19日"=>"父亲节",
            "6月23日"=>"奥林匹克日",
            "7月1日"=>"建党节",
            "7月7日"=>"七七事变纪念日",
            "8月1日"=>"建军节",
            "8月6日"=>"理优教育2周年",
            "8月9日"=>"七夕节",
            "8月17日"=>"中元节"
        );
        foreach($arr as $k => $v){
            $this->t_festival_info->add_festival_info($k,$v);
        }
    }

    public function add_new_festival(){
        $begin_time = $this->get_in_str_val("start");
        $end_time = $this->get_in_str_val("end");
        if(empty($begin_time) || empty($end_time) || strtotime($begin_time)> strtotime($end_time)){
            return $this->output_err("时间错误");
        }
        //$days = $this->get_in_int_val("days");
        $name = trim($this->get_in_str_val("name"));
        $days = (strtotime($end_time)-strtotime($begin_time))/86400+1;
        
        $this->t_festival_info->row_insert([
            "begin_time"  =>strtotime($begin_time), 
            "end_time"    =>strtotime($end_time),
            "days"        =>$days,
            "name"        =>$name
        ]);
        return $this->output_succ();
    }

    public function update_festival_new(){
        $begin_time = $this->get_in_str_val("start");
        $end_time = $this->get_in_str_val("end");
        if(empty($begin_time) || empty($end_time) || strtotime($begin_time)> strtotime($end_time)){
            return $this->output_err("时间错误");
        }

        //$days = $this->get_in_int_val("days");
        $id = $this->get_in_int_val("id");
        $name = trim($this->get_in_str_val("name"));
        $days = (strtotime($end_time)-strtotime($begin_time))/86400+1;
        $this->t_festival_info->field_update_list($id,[
            "begin_time"  =>strtotime($begin_time), 
            "end_time"    =>strtotime($end_time),
            "days"        =>$days,
            "name"        =>$name
        ]);
        return $this->output_succ();

    }

    public function del_festival(){
        $id = $this->get_in_int_val("id");
        $this->t_festival_info->row_delete($id);
        return $this->output_succ();
    }
}