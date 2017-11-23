<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;

class seller_student2 extends Controller
{
    use CacheNick;
    use TeaPower;

    public function show_order_activity_info() {
        $open_flag   = $this->get_in_int_val('id_open_flag',-1);
        $can_disable_flag   = $this->get_in_int_val('id_can_disable_flag',-1);
        $contract_type_list     = $this->get_in_int_val('id_contract_type',-1);
        $period_flag_list     = $this->get_in_int_val('id_period_flag',-1);
        $page_num        = $this->get_in_page_num();
 
        $ret_list = $this->t_seller_student2->get_list($open_flag,$can_disable_flag,$contract_type_list,$period_flag_list,$page_num);
        //dd($ret_list);
        $gradeArr = E\Egrade::$desc_map;
        if($ret_list['list']){
            foreach( $ret_list['list'] as &$item){
                $item['period_flag_list_str']   = E\Eperiod_flag::get_desc($item['period_flag_list']);
                $item['contract_type_list_str']   = E\Econtract_type::get_desc($item['contract_type_list']);
                $item['can_disable_flag_str']   = E\Ecan_disable_flag::get_desc($item['can_disable_flag']);
                $item['open_flag_str']   = E\Eopen_flag::get_desc($item['open_flag']);
                $item['order_activity_discount_type_str']   = E\Eorder_activity_discount_type::get_desc($item['order_activity_discount_type']);
                if(!$item['grade_list']){
                    if( strpos($item['grade_list'], ",")){
                        $gradeArr = explode(",",$item['grade_list']);
                        $item['grade_list_str'] = '';
                        foreach( $gradeArr as $grade){
                            $item['grade_list_str'] .= E\Egrade::get_desc($grade).',';
                        }
                        $item['grade_list_str'] = substr($item['grade_list_str'],0,-1);

                    }else{
                        $item['grade_list_str'] = E\Egrade::get_desc($item['grade_list']);
                    }
                }else{
                    $item['grade_list_str'] = '未设置';
                }

                if( $item['date_range_start'] && $item['date_range_end']){
                    $item['date_range_time'] = \App\Helper\Utils::unixtime2date($item['date_range_start'] ).'-'.\App\Helper\Utils::unixtime2date($item['date_range_end'] );
                }else{
                    $item['date_range_time'] = "未设置";
                }

                if( $item['lesson_times_min'] && $item['lesson_times_max'] ){
                    $item['lesson_times_range'] = $item['lesson_times_min']."-".$item['lesson_times_max'];
                }else{
                    $item['lesson_times_range'] = "未设置";
                }
            }
        }
        return $this->pageView(__METHOD__,$ret_list,
           [
             "_publish_version"      => "2017112211839",
             "gradeArr" => $gradeArr,
           ]
        );
    }

    public function add_order_activity(){
        $title = $this->get_in_str_val('title');

        $period_flag_list = $this->get_in_int_val('period_flag_list',0);
        $contract_type_list = $this->get_in_int_val('contract_type_list',0);
        $grade_list = $this->get_in_str_val('grade_list',0);
        $max_count = $this->get_in_int_val('max_count',20);

        $can_disable_flag = $this->get_in_int_val('can_disable_flag',1);
        $open_flag = $this->get_in_int_val('open_flag',0);
        $order_activity_discount_type = $this->get_in_int_val('order_activity_discount_type',1);

        return $this->t_seller_student2->row_insert([
            "title"   => $title,
            "period_flag_list"   => $period_flag_list,
            "contract_type_list"   => $contract_type_list,
            "grade_list"   => $grade_list,
            "can_disable_flag"   => $can_disable_flag,
            "open_flag"   => $open_flag,
            "order_activity_discount_type"   => $order_activity_discount_type,
        ]);
    }

    public function dele_order_activity(){
        $id = $this->get_in_int_val('id');
        $this->t_seller_student2->del_by_id($id);
        return $this->output_succ(); 
    }
    
    public function get_order_activity(){
        $id = $this->get_in_int_val('id');
        $item = $this->t_seller_student2->get_by_id($id);
        
        $gradeArr = E\Egrade::$desc_map;

        if($item){
            $item['period_flag_list_str']   = E\Eperiod_flag::get_desc($item['period_flag_list']);
            $item['contract_type_list_str']   = E\Econtract_type::get_desc($item['contract_type_list']);
            $item['can_disable_flag_str']   = E\Ecan_disable_flag::get_desc($item['can_disable_flag']);
            $item['open_flag_str']   = E\Eopen_flag::get_desc($item['open_flag']);
            $item['order_activity_discount_type_str']   = E\Eorder_activity_discount_type::get_desc($item['order_activity_discount_type']);
            if(!$item['grade_list']){
                if( strpos($item['grade_list'], ",")){
                                            $gradeArr = explode(",",$item['grade_list']);
                    $item['grade_list_str'] = '';
                    foreach( $gradeArr as $grade){
                        $item['grade_list_str'] .= E\Egrade::get_desc($grade).',';
                    }
                    $item['grade_list_str'] = substr($item['grade_list_str'],0,-1);

                }else{
                    $item['grade_list_str'] = E\Egrade::get_desc($item['grade_list']);
                }
            }else{
                $item['grade_list_str'] = '未设置';
            }

        }

        return $this->pageView(__METHOD__,null,
            [
                "_publish_version"      => "2017112211750",
                "gradeArr" => $gradeArr,
                "ret_info" => $item
            ]
        );
 
    }

    public function update_order_activity(){
        $title = $this->get_in_str_val('title',-1);
        $date_range_start = $this->get_in_str_val('date_range_start');
        $date_range_end = $this->get_in_str_val('date_range_end');

        $user_join_time_srart = $this->get_in_str_val('user_join_time_srart');
        $user_join_time_end = $this->get_in_str_val('user_join_time_end');
        $last_test_lesson_srart = $this->get_in_str_val('last_test_lesson_srart');
        $last_test_lesson_end = $this->get_in_str_val('last_test_lesson_end');
        $lesson_times_min = $this->get_in_int_val('lesson_times_min');
        $lesson_times_max = $this->get_in_int_val('lesson_times_max');

        $contract_type_list = $this->get_in_int_val('contract_type_list');
        $period_flag_list = $this->get_in_int_val('period_flag_list');
        $grade_list = $this->get_in_str_val('grade_list');
        $power_value = $this->get_in_int_val('power_value');
        $max_count = $this->get_in_int_val('max_count');
        $max_change_value = $this->get_in_int_val('max_change_value');
        $max_count_activity_type_list = $this->get_in_str_val('max_count_activity_type_list');

        $can_disable_flag = $this->get_in_int_val('can_disable_flag');
        $open_flag = $this->get_in_int_val('open_flag');
        $order_activity_discount_type = $this->get_in_int_val('order_activity_discount_type');
        $discount_json = $this->get_in_str_val('discount_json');

        $input=Request::all();
        \App\Helper\Utils::logger("menu_str_show: ".json_encode($grade_list));
    }

}
