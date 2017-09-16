<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class test_lesson_review extends Controller
{
    use CacheNick;
    public function test_lesson_review_list(){
        $adminid = $this->get_account_id();
        $page_info = $this->get_in_page_info();
        $ret_info = $this->t_test_lesson_subject_require_review->get_all_list($page_info,$adminid);
        $num = 1;
        foreach($ret_info['list'] as &$item){
            $item['aid'] = $adminid;
            $item['num'] = $num++;
            $item["nick"]= $this->cache_get_account_nick($item["adminid"]);
            $item["group_nick"]= $this->cache_get_account_nick($item["group_adminid"]);
            $item["master_nick"]= $this->cache_get_account_nick($item["master_adminid"]);
            $item["group_suc_flag_str"] = \App\Helper\Common::get_boolean_color_str($item["group_suc_flag"]);
            $item["master_suc_flag_str"] = \App\Helper\Common::get_boolean_color_str($item["master_suc_flag"]);
            $item['create_time'] = \App\Helper\Utils::unixtime2date($item['create_time']);
        }
        return $this->pageView(__METHOD__,$ret_info,['adminid'=>$adminid]);
    }

    public function test_lesson_review_add(){
        $time = strtotime(date('Y-m-d',time()).'00:00:00');
        $week = date('w',$time);
        if($week == 0){
            $week = 7;
        }elseif($week == 1){
            $week = 8;
        }
        $start_time = $time-3600*24*($week-2);
        $end_time = $start_time+3600*24*7;
        $adminid = $this->get_account_id();
        $userid = $this->get_in_int_val('userid');
        $review_desc = $this->get_in_str_val('review_desc');
        $p_pp_adminid = $this->t_admin_group_user->get_group_master_adminid($adminid);
        $group_adminid = isset($p_pp_adminid['group_adminid'])?$p_pp_adminid['group_adminid']:0;
        $master_adminid = isset($p_pp_adminid['master_adminid'])?$p_pp_adminid['master_adminid']:0;
        $count = $this->t_test_lesson_subject_require_review->get_week_test_lesson_count($adminid,$start_time,$end_time);
        $ret = 0;
        if($count<3){
            $this->t_test_lesson_subject_require_review->row_insert([
                "adminid"        => $adminid,
                "group_adminid"  => $group_adminid,
                "master_adminid" => $master_adminid,
                "userid"         => $userid,
                "review_desc"    => $review_desc,
                "create_time"    => time(NULL),
            ],false,false,true);
            $ret = 1;
        }
        return $ret;
    }

    public function test_lesson_review_group_edit(){
        $id              = $this->get_in_int_val('id');
        $adminid         = $this->get_account_id();
        $group_adminid   = $this->get_in_int_val('group_adminid');
        $group_suc_flag  = $this->get_in_int_val('group_suc_flag');
        if($adminid == $group_adminid){
            $ret = $this->t_test_lesson_subject_require_review->field_update_list($id,[
                "group_suc_flag" => $group_suc_flag,
                "group_time"     => time(NULL),
            ]);
            return $this->output_succ();
        }
    }

    public function test_lesson_review_master_edit(){
        $id              = $this->get_in_int_val('id');
        $adminid         = $this->get_account_id();
        $master_adminid  = $this->get_in_int_val('master_adminid');
        $master_suc_flag = $this->get_in_int_val('master_suc_flag');
        if($adminid == $master_adminid){
            $ret = $this->t_test_lesson_subject_require_review->field_update_list($id,[
                "master_suc_flag" => $master_suc_flag,
                "master_time"     => time(NULL),
            ]);
            return $this->output_succ();
        }
    }

    public function test_lesson_review_del(){
        $id=$this->get_in_id();
        $this->t_test_lesson_subject_require_review->row_delete($id);
        return $this->output_succ();
    }

}
