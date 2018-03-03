<?php

namespace App\Http\Controllers;
use \App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class info_resource_power extends Controller
{
    use CacheNick;
    var $check_login_flag=true;

    function __construct( ) {
        parent::__construct();
    }

    public function get_resource_power(){
        
        $ret = $this->t_info_resource_power->get_list();
        foreach($ret as &$item) {
            $item['consult_power'] = E\Epower_resource::get_desc($item['consult']);
            $item['assistant_power'] = E\Epower_resource::get_desc($item['assistant']);
            $item['market_power'] = E\Epower_resource::get_desc($item['market']);
        }

        return $this->pageView(__METHOD__,[],[
            '_publish_version'    => 20180226171440,
            "ret"          => $ret
        ]);
    }

    public function save_resource_power(){
        $id         = $this->get_in_int_val('id');
        $resource_id         = $this->get_in_int_val('resource_id');
        $resource_name       = trim($this->get_in_str_val('resource_name'));
        $type_id             = $this->get_in_int_val('type_id');
        $type_name           = trim($this->get_in_str_val('type_name'));
        $consult_power       = $this->get_in_int_val('consult_power');
        $assistant_power     = $this->get_in_int_val('assistant_power');
        $market_power        = $this->get_in_int_val('market_power');

        $data = [
            "type_name"  => $type_name,
            "consult"    => $consult_power,
            "assistant"  =>$assistant_power,
            "market"     => $market_power,   
        ];
        $ret;
        $result = ["status" => 201];
        if( $id == 0 ){

            if( $resource_id == 0 ){
                //新增资源类型
                $data['resource_name'] = $resource_name;
                $data['resource_id'] = $this->get_new_resource_id();
                $data['type_id'] = $this->get_new_type_id($resource_id);
                $ret = $this->t_info_resource_power->row_insert($data);
            }
            if( $resource_id != 0 && $type_id == 0){
                //新增细分类型
                $this->check_have_changed_resource_name($id,$resource_id,$resource_name);
                $data['resource_name'] = $resource_name;
                $data['resource_id'] = $resource_id;
                $data['type_id'] = $this->get_new_type_id($resource_id);
                $ret = $this->t_info_resource_power->row_insert($data);
            }
            if($ret){
                $result['status'] = 200;
                return $this->output_succ($result);
            }
        }else{
            $this->check_have_changed_resource_name($id,$resource_id,$resource_name);
            $data['resource_name'] = $resource_name;
            $data['resource_id'] = $resource_id;
            $data['type_id'] = $type_id;
            $ret = $this->t_info_resource_power->field_update_list($id,$data);
            if($ret){
                $result['status'] = 200;
                return $this->output_succ($result);
            }
        }
        return $this->output_succ($result);
    }

    private function get_new_resource_id(){
        $get_latest_resource_id = $this->t_info_resource_power->get_latest_resource_id();
        if(!$get_latest_resource_id){
            $get_latest_resource_id = 0;
        }
        $new_resource_id = $get_latest_resource_id + 1;
        return $new_resource_id;
    }

    private function get_new_type_id($resource_id){
        $get_latest_type_id = 0;
        if($resource_id > 0){
            $get_latest_type_id = $this->t_info_resource_power->get_latest_type_id($resource_id);
            if(!$get_latest_type_id){
                $get_latest_resource_id = 0;
            } 
        }
        $new_type_id = $get_latest_type_id + 1;
        return $new_type_id;
    }

    private function check_have_changed_resource_name($id,$resource_id,$resource_name){
        $old_resource_name = $this->t_info_resource_power->get_old_resource_name($resource_id,$id);
        if($old_resource_name && !empty($resource_name) && $old_resource_name != $resource_name ){
            $resource_arr = $this->t_info_resource_power->get_same_resource_id_arr($resource_id,$id);
            if($resource_arr){
                foreach($resource_arr as $re){
                    $ret = $this->t_info_resource_power->field_update_list($re['id'],[
                        "resource_name" => $resource_name
                    ]);
                }
            }
        }
    }
    public function dele_resource(){
        $resource_id         = $this->get_in_int_val('resource_id');
        $resource_arr   =  $this->t_info_resource_power->get_by_resource_id($resource_id);
        if($resource_arr){
            foreach($resource_arr as $re){
                $ret = $this->t_info_resource_power->field_update_list($re['id'],[
                    "is_del" => 1
                ]);
            }
        }
        return $this->output_succ();
    }

    public function dele_type(){
        $id         = $this->get_in_int_val('id');
        $ret = $this->t_info_resource_power->field_update_list($id,["is_del" => 1]);
        return $this->output_succ();
    }
}