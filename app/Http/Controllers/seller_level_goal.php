<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class seller_level_goal extends Controller
{
    public function seller_level_goal_list(){
        $seller_level = $this->get_in_int_val('seller_level');
        $level_goal = $this->get_in_int_val('level_goal');
        $level_face = $this->get_in_str_val('level_face');
        $level_icon = $this->get_in_str_val('level_icon');
        $num = $this->get_in_int_val('num');
        $page_num   = $this->get_in_page_num();
        $page_info  = $this->get_in_page_info();
        $ret_info  = $this->t_seller_level_goal->get_all_list($page_info);
        foreach($ret_info['list'] as &$item){
            E\Eseller_level::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,'create_time');
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function add_seller_level_goal(){
        $seller_level = $this->get_in_int_val('seller_level');
        $level_goal = $this->get_in_int_val('level_goal');
        $level_face = $this->get_in_str_val('level_face');
        $level_icon = $this->get_in_str_val('level_icon');
        $num = $this->get_in_int_val('num');
        if($level_face){
            $domain = config('admin')['qiniu']['public']['url'];
            $level_face_url = $domain.'/'.$level_face;
        }else{
            $level_face_url = '';
        }
        if($level_icon){
            $domain = config('admin')['qiniu']['public']['url'];
            $level_icon_url = $domain.'/'.$level_icon;
        }else{
            $level_icon_url = '';
        }
        $ret_info = $this->t_seller_level_goal->field_get_list($seller_level,'*');
        if($ret_info){
            return $this->output_err('该等级信息已存在,不能重复添加!');
        }
        $this->t_seller_level_goal->row_insert([
            "seller_level" => $seller_level,
            "level_goal"   => $level_goal ,
            "level_face"   => $level_face_url,
            "level_icon"   => $level_icon_url,
            "num"          => $num ,
            "create_time"  => time(null),
        ]);

        return $this->output_succ();
    }

    public function edit_seller_level_goal(){
        $seller_level = $this->get_in_int_val('seller_level');
        $level_goal = $this->get_in_int_val('level_goal');
        $level_face = $this->get_in_str_val('level_face');
        $level_face_old = $this->get_in_str_val('level_face_old');
        $level_icon = $this->get_in_str_val('level_icon');
        $level_icon_old = $this->get_in_str_val('level_icon_old');
        $num = $this->get_in_int_val('num');
        if($level_face){
            if($level_face == $level_face_old){
                $level_face_url = $level_face_old;
            }else{
                $domain = config('admin')['qiniu']['public']['url'];
                $level_face_url = $domain.'/'.$level_face;
            }
        }else{
            $level_face_url = '';
        }
        if($level_icon){
            if($level_icon == $level_icon_old){
                $level_icon_url = $level_icon_old;
            }else{
                $domain = config('admin')['qiniu']['public']['url'];
                $level_icon_url = $domain.'/'.$level_icon;
            }
        }else{
            $level_icon_url = '';
        }

        $this->t_seller_level_goal->field_update_list($seller_level,[
            "level_goal" => $level_goal ,
            "level_face" => $level_face_url,
            "level_icon" => $level_icon_url,
            "num"        => $num ,
        ]);

        return $this->output_succ();
    }

    public function del_seller_level_goal(){
        $seller_level = $this->get_in_int_val('seller_level');

        $this->t_seller_level_goal->row_delete($seller_level);

        return $this->output_succ();
    }
}
