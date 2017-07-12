<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class stu_app extends Controller
{

    public function nav_info(){
        $type      = $this->get_in_int_val('type',-1);
        $time_type = $this->get_in_int_val('time_type',-1);
        $page_num  = $this->get_in_page_num();

        $ret_info = $this->t_pic_manage_info->get_pic_info_list($type,$time_type,$page_num);

        foreach($ret_info["list"] as &$item){
            E\Epic_type::set_item_value_str($item,"type");
            E\Epic_time_type::set_item_value_str($item,"time_type");
        }

        return $this->pageView(__METHOD__,$ret_info,array(),[
            'qiniu_upload_domain_url' => Config::get_qiniu_public_url()."/"
        ]);
    }
    public function get_pic_info(){
        $id = $this->get_in_int_val('id',-1);
        $ret_info = $this->t_pic_manage_info->field_get_list($id,'*');
        return outputjson_success(array('ret_info' => $ret_info));
    }
    public function add_pic_info(){
        $opt_type  = $this->get_in_str_val('opt_type','');
        $id        = $this->get_in_int_val('id',-1);
        $name      = $this->get_in_str_val('name','');
        $type      = $this->get_in_int_val('type',-1);
        $time_type = $this->get_in_int_val('time_type',-1);
        $url       = $this->get_in_str_val('url','');

        $ret_info=$this->t_pic_manage_info->add_pic_info($opt_type,$id,$name,$type,$time_type,$url);
        return outputjson_success();
    }
    public function del_pic_info(){
        $id = $this->get_in_int_val('id',-1);
        $ret_info=$this->t_pic_manage_info->row_delete($id);
        return outputjson_success();
    }
}