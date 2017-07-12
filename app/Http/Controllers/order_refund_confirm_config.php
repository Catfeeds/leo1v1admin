<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

class order_refund_confirm_config extends Controller
{
    public function refund_info(){
        $key1=$this->get_in_int_val("key1",-1);
        $key2=$this->get_in_int_val("key2",-1);
        $key3=$this->get_in_int_val("key3",-1);

        list($refund_info ,$map) = $this->t_order_refund_confirm_config->get_refund_list_and_map( $key1, $key2, $key3 );

        $ret_info= \App\Helper\Utils::list_to_page_info($refund_info);

        $key2_list = $this->t_order_refund_confirm_config->get_key2_list($key1);
        $key3_list = $this->t_order_refund_confirm_config->get_key3_list($key1,$key2);
        return $this->pageView(__METHOD__,$ret_info, [
            "key1_list" => $this->t_order_refund_confirm_config->get_key1_list(),
            "key2_list" => $key2_list,
            "key3_list" => $key3_list,
        ] );


    }

    public function deal_refund_info () {

        $key1  = $this->get_in_int_val("key1",-1);
        $key2  = $this->get_in_int_val("key2",-1);
        $key3  = $this->get_in_int_val("key3",-1);
        $value = $this->get_in_str_val("value");
        $key4  = 0;

        if ($key1==-1) {
            $key1= $this->t_order_refund_confirm_config-> get_next_key_value($key1,$key2,$key3);
            $key2=0;
            $key3=0;
        }else if($key2==-1) {
            $key2= $this->t_order_refund_confirm_config-> get_next_key_value($key1,$key2,$key3);
            $key3=0;
        }else if($key3==-1) {
            $key3= $this->t_order_refund_confirm_config-> get_next_key_value($key1,$key2,$key3);
        }else  {
            $key4= $this->t_order_refund_confirm_config-> get_next_key_value($key1,$key2,$key3);
        }

        $refund_arr = [
            "key1"  => $key1,
            "key2"  => $key2,
            "key3"  => $key3,
            "key4"  => $key4,
            "value" => $value,
        ];

        $this->t_order_refund_confirm_config-> row_insert($refund_arr);
        return  $this->output_succ();

    }

    public function delete_refund_info () {
        $id = $this->get_in_int_val("id");
        $this->t_order_refund_confirm_config->row_delete($id);
        return  $this->output_succ();
    }




}
