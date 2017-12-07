<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class seller_level_goal extends Controller
{
    use CacheNick;
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
        $seller_level_goal = $this->get_in_int_val('seller_level_goal');
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
            "seller_level_goal"   => $seller_level_goal,
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
        $seller_level_goal = $this->get_in_int_val('seller_level_goal');
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
            "seller_level_goal" => $seller_level_goal ,
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

    public function seller_level_salary_list(){
        $seller_level = $this->get_in_int_val('seller_level',-1);
        $define_date = $this->get_in_int_val('define_date');
        $base_salary = $this->get_in_int_val('base_salary');
        $sup_salary = $this->get_in_int_val('sup_salary');
        $per_salary = $this->get_in_int_val('per_salary');
        $page_info = $this->get_in_page_info();
        $ret_info = $this->t_seller_level_salary->get_all_list($seller_level,$page_info);
        foreach($ret_info['list'] as &$item){
            E\Eseller_level::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,'define_date','','Y-m-d');
            \App\Helper\Utils::unixtime2date_for_item($item,'create_time');
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function add_seller_level_goal_salary(){
        $seller_level = $this->get_in_int_val('seller_level');
        $define_date = $this->get_in_str_val('define_date');
        $base_salary = $this->get_in_int_val('base_salary');
        $sup_salary = $this->get_in_int_val('sup_salary');
        $per_salary = $this->get_in_int_val('per_salary');
        $define_date = strtotime(date('Y-m-1',strtotime($define_date)));
        $row = $this->t_seller_level_salary->get_row_by_seller_level_define_date($seller_level,$define_date);
        if($row){
            return $this->output_err('该月份等级工资已存在,不能重复添加');
        }
        $this->t_seller_level_salary->row_insert([
            'seller_level' => $seller_level,
            'define_date'  => $define_date,
            'base_salary'  => $base_salary,
            'sup_salary'   => $sup_salary,
            'per_salary'   => $per_salary,
            'create_time'  => time(null),
        ]);
        return $this->output_succ();
    }

    public function edit_seller_level_goal_salary(){
        $seller_level = $this->get_in_int_val('seller_level');
        $define_date = $this->get_in_str_val('define_date');
        $base_salary = $this->get_in_int_val('base_salary');
        $sup_salary = $this->get_in_int_val('sup_salary');
        $per_salary = $this->get_in_int_val('per_salary');
        $define_date = strtotime(date('Y-m-1',strtotime($define_date)));
        
        $this->t_seller_level_salary->field_update_list($seller_level,[
            'define_date'  => $define_date,
            'base_salary'  => $base_salary,
            'sup_salary'   => $sup_salary,
            'per_salary'   => $per_salary,
        ]);
        return $this->output_succ();
    }

    public function del_seller_level_salary(){
        $seller_level = $this->get_in_int_val('seller_level');
        $this->t_seller_level_salary->row_delete($seller_level);

        return $this->output_succ();
    }

    public function seller_level_month_list(){
        $adminid   = $this->get_in_int_val('adminid',-1);
        $page_info = $this->get_in_page_info();
        $ret_info  = $this->t_seller_level_month->get_all_list($adminid,$page_info);
        foreach($ret_info['list'] as &$item){
            $item["account"] = $this->cache_get_account_nick($item["adminid"]);
            E\Eseller_level::set_item_value_str($item);
            \App\Helper\Utils::unixtime2date_for_item($item,'month_date','','Y-m');
            \App\Helper\Utils::unixtime2date_for_item($item,'create_time');
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function add_seller_level_month(){
        $adminid = $this->get_in_int_val('adminid');
        $seller_level = $this->get_in_int_val('seller_level');
        $month_date = $this->get_in_str_val('month_date');
        $month_date = strtotime(date('Y-m-1',strtotime($month_date)));
        $row = $this->t_seller_level_month->get_row_by_adminid_month_date($adminid,$month_date);
        if($row){
            return $this->output_err('该定级已存在,不能重复添加');
        }
        $this->t_seller_level_month->row_insert([
            'adminid'      => $adminid,
            'month_date'   => $month_date,
            'seller_level' => $seller_level,
            'create_time'  => time(null),
        ]);
        return $this->output_succ();
    }

    public function edit_seller_level_month(){
        $id = $this->get_in_int_val('id');
        $seller_level = $this->get_in_int_val('seller_level');
        
        $this->t_seller_level_month->field_update_list($id,[
            'seller_level'  => $seller_level,
        ]);
        return $this->output_succ();
    }

    public function del_seller_level_month(){
        $id = $this->get_in_int_val('id');
        $this->t_seller_level_month->row_delete($id);

        return $this->output_succ();
    }
}
