<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class pic_manage extends Controller
{
    public function pic_info()
    {
        $type      = $this->get_in_int_val('type',-1);
        $usage_type= $this->get_in_int_val('usage_type',-1);
        $page_num  = $this->get_in_page_num();

        $ret_info  = $this->t_pic_manage_info->get_pic_info_list($type,$usage_type,$page_num);

        foreach($ret_info["list"] as &$item){
            E\Epic_type::set_item_value_str($item,"type");
            E\Epic_time_type::set_item_value_str($item,"time_type");
            E\Epic_usage_type::set_item_value_str($item,"usage_type");
        }
        return $this->pageView(__METHOD__,$ret_info,array(),[
            'qiniu_upload_domain_url' =>Config::get_qiniu_public_url()."/"
        ]);
    }

    public function get_pic_info()
    {
        $id = $this->get_in_int_val('id',-1);

        $ret_info = $this->t_pic_manage_info->field_get_list($id,'*');
        $ret_info['start_time'] = date("Y-m-d",$ret_info['start_time']);
        $ret_info['end_time']   = date("Y-m-d",$ret_info['end_time']);

        return outputjson_success(array('ret_info' => $ret_info));
    }

    public function add_pic_info()
    {
        $opt_type     = $this->get_in_str_val('opt_type','');
        $id           = $this->get_in_int_val('id',-1);
        $name         = $this->get_in_str_val('name','');
        $type         = $this->get_in_int_val('type',-1);
        $usage_type   = $this->get_in_int_val('usage_type',-1);
        $click_status = $this->get_in_int_val('click_status',-1);
        $order_by     = $this->get_in_int_val('order_by',0);
        $grade        = $this->get_in_int_val('grade',0);
        $subject      = $this->get_in_int_val('subject',0);
        $pic_url      = $this->get_in_str_val('pic_url','');
        $tag_url      = $this->get_in_str_val('tag_url','');

        $start_time  = $this->get_in_str_val('start_time');
        $end_time    = $this->get_in_str_val('end_time');
        $title_share = $this->get_in_str_val('title_share');
        $info_share  = $this->get_in_str_val('info_share');
        $jump_url    = $this->get_in_str_val('jump_url');
        $jump_type   = $this->get_in_int_val('jump_type');

        $start = strtotime($start_time);
        $end   = strtotime("+1 day",strtotime($end_time));

        $ret_info=$this->t_pic_manage_info->add_pic_info($opt_type,$id,$name,$type,$usage_type,
                                                         $pic_url,$tag_url,$click_status,$order_by,$grade,
                                                         $subject,$start,$end,$title_share,$info_share,
                                                         $jump_url,$jump_type);
        return outputjson_success();
    }

    public function del_pic_info()
    {
        $id = $this->get_in_int_val('id',-1);

        $ret_info=$this->t_pic_manage_info->row_delete($id);

        return outputjson_success();
    }


}