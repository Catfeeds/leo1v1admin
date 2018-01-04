<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;


class user_power extends Controller
{
    public $desc_map = [
        "get_student_list" => \App\Config\url_desc_power\test\get_student_list::class,

    ];
    public function get_desc_power()
	{
		$url       = $this->get_in_str_val('url',"ff/get_student_list");
        $group_id  = $this->get_in_int_val('group_id',"");
        $controller_name = substr($url, strripos($url,"/") + 1);
        if(!$controller_name){
            return $this->output_succ();
        }
        //dd($controller_name);
        //查找是否存在该类
        if(!array_key_exists($controller_name, $this->desc_map)){
            return $this->output_succ();
        };
        $desc_class = $this->desc_map[$controller_name];
        $desc_power = $desc_class::get_config();
        //dd($desc_power2);
        $select_power = [];
        if($group_id){        
            $select_power = $this->t_url_desc_power->url_desc_power_list($group_id,$url);
            $result = [];

            if($select_power){
                $result = array_column($select_power,"open_flag","opt_key");
            }
            
            foreach( $desc_power as &$item){
                if( !empty($result) && array_key_exists($item[0],$result) && $result[$item[0]] != 1 ){
                    array_push($item,0);
                }else{
                    array_push($item,1);
                }
            }
        }
        return $this->output_succ(["data"=> $desc_power]);
	}

    public function save_desc_power(){
        $url       = $this->get_in_str_val('url');
        $group_id  = $this->get_in_int_val('group_id');
        $opt_key_list  = $this->get_in_str_val('opt_key_list');
        $all_list  = $this->get_in_str_val('all_list');

        if(!$url || !$group_id ){
            return $this->output_succ();
        }
        $opt_key_arr = json_decode($opt_key_list,true);
        $all_arr = json_decode($all_list,true);
        $forbid_arr = array_diff($all_arr,$opt_key_arr);
        $item = 0;
        $data = [
            "url" => $url,
            "role_groupid" => $group_id,
            "open_flag" => 1
        ];
        if($opt_key_arr){
            foreach($opt_key_arr as $opt_key){
                $data["opt_key"] = $opt_key;
                $this->save_edit_desc_power($data);
            }
        }
  
        if($forbid_arr){
            $data['open_flag'] = 0;
            foreach($forbid_arr as $opt_key){
                $data["opt_key"] = $opt_key;
                $this->save_edit_desc_power($data);
            }
        }
        return $this->output_succ();
        // echo $url;
        // dd($opt_key_arr);
    }

    private function save_edit_desc_power($data){
        $url = $data['url'];
        $role_groupid = $data['role_groupid'];
        $opt_key = $data['opt_key'];

        //查看是否存在
        $url_desc_power_id = $this->t_url_desc_power->url_desc_power_id($url,$role_groupid,$opt_key);
        if($url_desc_power_id){
            //更新
            $ret = $this->t_url_desc_power->field_update_list($url_desc_power_id,$data); 
        }else{
            //添加
            $ret = $this->t_url_desc_power->row_insert($data);
        }

    }
}