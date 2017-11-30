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
            E\Efile_type::set_item_value_str($item);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function add_file(){
        $file_type = $this->get_in_int_val("file_type",-1);
        $file_name = $this->get_in_str_val("file_name","");
        $file_url  = $this->get_in_str_val("file_url","");
        $data = [
            "publish_time" => time(),
            "file_path"    => $file_name,
            "file_type"    => $file_type,
            "file_url"     => $file_url,
        ];
        $ret_info = $this->t_version_control->row_insert($data);
        if($ret_info){
            return outputjson_success();
        }else{
            return outputjson_error();
        }
    }

}

