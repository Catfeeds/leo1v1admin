<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;


class ss_deal2 extends Controller
{
    use CacheNick;
    use TeaPower;

    public function  seller_set_deal_cur_new_user()  {
        $adminid= $this->get_account_id();
        $userid= $this->get_in_userid();

        $key="DEAL_NEW_USER_$adminid";
        $old_userid=\App\Helper\Common::redis_get($key);
        $tq_called_flag= $this->t_seller_student_new->get_tq_called_flag($old_userid);
        if (!$tq_called_flag) {
            return $this->output_err("当前例子还未拨通!");
        }

        \App\Helper\Common::redis_set($key, $userid );
        return $this->output_succ();
    }
    public function  test_subject_free_list_get_list_js()  {
        $page_info = $this->get_in_page_info();

        $userid= $this->get_in_userid();
        $ret_info= $this->t_test_subject_free_list->get_list_by_userid($page_info, $userid );
        foreach($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            E\Etest_subject_free_type::set_item_value_str($item);
            $this->cache_set_item_account_nick($item);
        }
        return $this->output_ajax_table ($ret_info);
    }

    public function get_origin_phone_list_js() {
        $page_info = $this->get_in_page_info();
        list($start_time, $end_time) = $this->get_in_date_range(0,0);
        $opt_type_str                = $this->get_in_str_val("opt_type_str");
        $key1 = $this->get_in_str_val("key1");
        $key2 = $this->get_in_str_val("key2");
        $key3 = $this->get_in_str_val("key3");
        $key4 = $this->get_in_str_val("key4");

        $origin_ex_arr= preg_split("/,/", session("ORIGIN_EX"));

        if (!$origin_ex_arr[0] ){
            if ($key1!="全部") {
                $origin_ex_arr[0]=$key1;
            }
        }

        if (!isset($origin_ex_arr[1] )){
            $origin_ex_arr[1]=$key2;
        }

        if (!isset($origin_ex_arr[2] )){
            $origin_ex_arr[2]=$key3;
        }

        if (!isset($origin_ex_arr[3] )){
            $origin_ex_arr[3]=$key4;
        }

        $origin_ex= join(",", $origin_ex_arr );


        $ret_info= $this->t_seller_student_new ->get_origon_list( $page_info, $start_time, $end_time,$opt_type_str, $origin_ex) ;
        foreach($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
        }


        return $this->output_ajax_table ($ret_info);
    }


    public function seller_student_del() {
        $userid=$this->get_in_userid();
        $this->t_seller_student_new->row_delete($userid);
        $this->t_test_lesson_subject->del_by_userid( $userid );
        return $this->output_succ() ;

    }

    public function reset_sys_invaild_flag () {
        $userid=$this->get_in_userid();
        $this->t_seller_student_new->reset_sys_invaild_flag($userid);
        return $this->output_succ() ;
    }

    public function get_origin_phone_list_js_bd2() {
        $page_info = $this->get_in_page_info();
        list($start_time, $end_time) = $this->get_in_date_range(0,0);
        $opt_type_str                = $this->get_in_str_val("opt_type_str");
        $key1 = $this->get_in_str_val("key1");
        $key2 = $this->get_in_str_val("key2");
        $key3 = $this->get_in_str_val("key3");
        $key4 = $this->get_in_str_val("key4");

        $origin_ex_arr= preg_split("/,/", session("ORIGIN_EX"));

        if (!$origin_ex_arr[0] ){
            if ($key1!="全部") {
                $origin_ex_arr[0]=$key1;
            }
        }

        if (!isset($origin_ex_arr[1] )){
            $origin_ex_arr[1]=$key2;
        }

        if (!isset($origin_ex_arr[2] )){
            $origin_ex_arr[2]=$key3;
        }

        if (!isset($origin_ex_arr[3] )){
            $origin_ex_arr[3]=$key4;
        }

        $origin_ex= join(",", $origin_ex_arr );

        $this->t_seller_student_new->switch_tongji_database();

        $ret_info= $this->t_seller_student_new->get_origon_list_bd2( $page_info, $start_time, $end_time,$opt_type_str, $origin_ex) ;
        foreach($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
        }

        // dd($ret_info);


        return $this->output_ajax_table ($ret_info);
    }


    public function get_tmk_assign_time(){

        $tmk_adminid = $this->get_in_int_val('tmk_adminid');
        $start_time  = strtotime($this->get_in_str_val('start_time'));
        $end_time    = strtotime($this->get_in_str_val('end_time'));

        $ret = $this->t_seller_student_new->get_tmk_assign_time_by_adminid($tmk_adminid, $start_time, $end_time);

        foreach($ret as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"tmk_assign_time",'_str');
            \App\Helper\Utils::unixtime2date_for_item($item,"first_call_time",'_str');

           $item['global_tq_called_flag_str'] = \App\Enums\Etq_called_flag::get_desc($item['global_tq_called_flag']);
        }

        return $this->output_succ(['data'=>$ret]);

    }


}
