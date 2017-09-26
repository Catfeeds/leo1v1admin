<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;
class t_yxyx_test_pic_info extends Controller
{
    use CacheNick;
    public function get_all_info(){
        $grade     = $this->get_in_int_val('grade',-1);
        $subject   = $this->get_in_int_val('subject',-1);
        $test_type = $this->get_in_int_val('test_type',-1);
        $page_info = $this->get_in_page_info();

        list( $order_in_db_flag, $order_by_str, $order_field_name,$order_type)
            = $this->get_in_order_by_str([],"",["visit_num" => "y.visit_num" ,
                                               "share_num" => "y.share_num",
            ]);

        $ret_info = $this->t_yxyx_test_pic_info->get_all($grade, $subject, $test_type, $page_info, $order_by_str);
        $type_info = $this->t_yxyx_custom_type->get_type_id_name_info();
        $type_arr  = [];
        foreach ($type_info as $v) {
            $type_arr[$v['custom_type_id']] = $v['type_name'];
        }
        foreach ($ret_info['list'] as &$item) {
               \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
               E\Egrade::set_item_value_str($item,"grade");
               E\Esubject::set_item_value_str($item,"subject");
               E\Etest_type::set_item_value_str($item,"test_type");
               $item['test_des'] = mb_substr( $item['test_des'], 0, 15, "utf-8");
               $item["new_arr"]  = explode(',',$item['custom_type']);
               foreach ($item['new_arr'] as &$v) {
                   $v = @$type_arr[$v];
               }
        }
        return $this->pageView(__METHOD__,$ret_info, array("type_arr" => $type_arr),['qiniu_upload_domain_url' =>
                                                             Config::get_qiniu_public_url()."/"
        ]);
    }

    public function get_one_test() {
        $id = $this->get_in_int_val('id',-1);
        $ret_info = $this->t_yxyx_test_pic_info->get_one_info($id);
        \App\Helper\Utils::unixtime2date_for_item($ret_info,"create_time");
        E\Egrade::set_item_value_str($ret_info,"grade");
        E\Esubject::set_item_value_str($ret_info,"subject");
        E\Etest_type::set_item_value_str($ret_info,"test_type");
        $ret_info['pic_arr']    = explode( '|',$ret_info['pic']);
        $ret_info["custom_arr"] = explode(',',$ret_info['custom_type']);
        return outputjson_success(array('ret_info' => $ret_info));

    }

    public function add_test_info() {
        $test_title  = $this->get_in_str_val('test_title','');
        $test_des    = $this->get_in_str_val('test_des','');
        $grade       = $this->get_in_int_val('grade','');
        $subject     = $this->get_in_int_val('subject','');
        $test_type   = $this->get_in_int_val('test_type','');
        $pic         = $this->get_in_str_val('pic','');
        $poster      = $this->get_in_str_val('poster','');
        $create_time = time();
        $adminid     = $this->get_account_id();
        $custom_type = $this->get_in_str_val('custom_type','');
        $res = preg_match_all('/\d/', $custom_type, $m);
        if($res) {
            $custom_type = join(',', $m[0]);
        }
        $ret_info = $this->t_yxyx_test_pic_info->add_test($test_title, $test_des, $grade, $subject, $test_type,
                                                          $pic, $poster, $create_time,$adminid,$custom_type);
        return outputjson_success();
    }

    public function update_test_info() {
        $id         = $this->get_in_int_val('id','');
        $test_title = $this->get_in_str_val('test_title','');
        $test_des   = $this->get_in_str_val('test_des','');
        $grade      = $this->get_in_int_val('grade','');
        $subject    = $this->get_in_int_val('subject','');
        $test_type  = $this->get_in_int_val('test_type','');
        $pic        = $this->get_in_str_val('pic','');
        $poster     = $this->get_in_str_val('poster','');
        $custom_type = $this->get_in_str_val('custom_type','');
        $res = preg_match_all('/\d/', $custom_type, $m);
        if($res) {
            $custom_type = join(',', $m[0]);
        }

        $ret_info = $this->t_yxyx_test_pic_info->update_test($id,$test_title, $test_des, $grade, $subject,
                                                               $test_type, $pic, $poster, $custom_type);
        return outputjson_success();
    }

    public function del_test_info() {
        $id = $this->get_in_int_val('id',-1);
        $ret_info = $this->t_yxyx_test_pic_info->row_delete($id);
        return outputjson_success();
    }
}
