<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class test_abner extends Controller
{
    use CacheNick;

    public function abner_list() {
        $page_info=$this->get_in_page_info();
        $grade =$this->get_in_el_grade();
        list($start_time, $end_time  ) =$this->get_in_date_range_day(0);
        $msg = $this->get_in_str_val("msg");
        $ret_info=$this->t_test_abner->get_list($page_info,$grade,$start_time, $end_time);
        //dd($ret_info);
        foreach ($ret_info["list"] as &$item ) {
            \App\Helper\Utils::unixtime2date_for_item($item,"value");
            E\Egrade::set_item_value_str($item);


        }
        return $this->pageView(__METHOD__,$ret_info);

    }
    public function sql() {
        $this->t_test_abner->get_value($id);
        $this->t_test_abner->field_get_list($id,"");
        $this->t_test_abner->row_insert($arr);
        $this->t_test_abner->row_delete($id);
        $this->t_test_abner->row_delete_2($id_value,$id_value_2);

    }
    public function test_set( ) {
        $id= $this->get_in_int_val("id");
        $value= $this->get_in_int_val("value");
        $this->t_test_abner->field_update_list($id,[
            "value" => $value,
        ]);
        return $this->output_err("xdaf dddkd看到");
        //return $this->output_succ();
    }

    public function test_add( ) {
        $grade= $this->get_in_int_val("grade");
        $this->t_test_abner->row_insert([
            "value" =>  time(NULL) ,
            "grade" =>  $grade ,
        ]);
        return $this->output_succ();
    }
    public function test_set2() {
        $id= $this->get_in_int_val("id");
        $grade= $this->get_in_e_grade();
        $this->t_test_abner->field_update_list($id,[
            "grade" => $grade,
        ]);
        return $this->output_succ();
    }

    public function test_del(){
        $id = $this->get_in_int_val("id");
        $this->t_test_abner->row_delete($id);

        return $this->output_succ();

    }
}
