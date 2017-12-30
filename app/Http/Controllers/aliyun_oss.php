<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use \App\Libs;
use \App\Config as C;
use \App\Enums as E;
use App\Helper\Utils;
use App\Http\Requests;
use Request;
class aliyun_oss  extends Controller
{
    use CacheNick;
    use TeaPower;
    public function __construct(){
    }
    public function upload_list(){
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],3);
        $page_info=$this->get_in_page_info();
        $ret_info = $this->t_version_control->get_list($page_info,$start_time,$end_time);
        foreach($ret_info['list'] as $key => &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"publish_time");
            E\Eversion_status::set_item_value_str($item,"is_publish");
            $item["add_nick"]   = $this->cache_get_account_nick($item["adminid"]);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }
    public function update_status(){
        $id = $this->get_in_int_val("id");
        $ret_update = $this->t_version_control->update_info_new($id);
        if($ret_update){
            return outputjson_success();
        }else{
            return outputjson_error();
        }
    }
    public function add_file(){
        $version   = $this->get_in_str_val("version","");
        $exe_file_url  = $this->get_in_str_val("file_url","");
        $yml_file_url  = $this->get_in_str_val("file_url_yml","");
        $dmg_file_url  = $this->get_in_str_val("file_url_dmg","");
        //update 
        $adminid       = $this->get_account_id();
        if($exe_file_url != ''){
            $ret_update    = $this->t_version_control->update_info_filetype(1);
            $data = [
                "publish_time" => time(),
                "file_path"    => pathinfo($exe_file_url)['filename'],
                "file_url"     => $exe_file_url,    
                "is_publish"   => 1,
                "publish_time" => time(),
                "file_type"    => 1,
                "version_name" => $version,
                "adminid"      => $adminid,
            ];
            $ret_info = $this->t_version_control->row_insert($data);
        }
        
        if($yml_file_url != ''){
            $ret_update    = $this->t_version_control->update_info_filetype(2);
            $data_b2 = [
                "publish_time" => time(),
                "file_path"    => pathinfo($yml_file_url)['filename'],
                "file_url"     => $yml_file_url,    
                "is_publish"   => 1,
                "publish_time" => time(),
                "file_type"    => 2,
                "version_name" => $version,
                "adminid"      => $adminid,
            ];
            $ret_info = $this->t_version_control->row_insert($data_b2);
        }
        
        if($dmg_file_url != ''){
            $ret_update    = $this->t_version_control->update_info_filetype(3);
            $data_b3 = [
                "publish_time" => time(),
                "file_path"    => pathinfo($dmg_file_url)['filename'],
                "file_url"     => $dmg_file_url,    
                "is_publish"   => 1,
                "publish_time" => time(),
                "file_type"    => 3,
                "version_name" => $version,
                "adminid"      => $adminid,
            ];
            $ret_info = $this->t_version_control->row_insert($data_b3);
        }
        
        if($ret_info){
            return outputjson_success();
        }else{
            return outputjson_error();
        }  
    }

}

