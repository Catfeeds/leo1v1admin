<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Redis;

class page_common extends Controller
{

    public function opt_table_field_list(){
        $opt_type=$this->get_in_str_val("opt_type");
        $table_key=$this->get_in_str_val("table_key");
        $data=$this->get_in_str_val("data");
        $account=$this->get_account();
        $key="T_".$table_key."_".$account;
        switch ( $opt_type ) {
        case "set" :
            Redis::set($key, $data);
            return $this->output_succ();
            break;
        case "get" :
            $field_list=null;
            try {
                $field_list= \App\Helper\Utils::json_decode_as_array(Redis::get($key));
            }catch( \Exception $e  ) {

            }
            $table_config=\App\Config\page_table::get_config($table_key);
            $row_opt_list=null;
            $field_default_flag=true;
            $filter_list=null;
            $hide_filter_list=null;
            if ($field_list) {
                $field_default_flag=false;
            }
            if ($table_config) { //
                if (!$field_list){
                    $field_list=[];
                }
                if (!$field_list) {
                    foreach (  $table_config["field_list"] as  $field_name ) {
                        if (!isset($field_list[$field_name])){
                            $field_list[$field_name]=true;
                        }
                    }
                }
                $filter_list=@$table_config["filter_list"];
                $row_opt_list=@$table_config["row_opt_list"];
                $hide_filter_list=@$table_config["hide_filter_list"];
            }
            return $this->output_succ([
                "field_list"   => $field_list,
                "field_default_flag"   => $field_default_flag,
                "filter_list"  => $filter_list,
                "hide_filter_list"  => $hide_filter_list,
                "row_opt_list" => $row_opt_list,
            ]);
            break;
        default:
            break;
        }
    }

    public function upload_xls_data() {
        /**
         *@ 将下载记录存取到数据库中
         *@ 产品部需求
        */
        $xls_data = $this->get_in_str_val("xls_data");
        $xls_arr  = json_decode($xls_data,true);
        $this->t_user_log->row_insert([
            "add_time" => time(),
            "adminid"  => $this->get_account_id(),
            "msg"      => "下载记录,下载数量:".count($xls_arr),
            "user_log_type" => 1, //下载记录
        ]);

        session([
            "xls_data"=>$xls_arr,
        ]);
        return outputjson_success();
    }

    public function  reload_account_power(){
        (new  login() )->reset_power($this->get_account());
        return $this->output_succ();
    }


}