<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class test_lesson_review extends Controller
{
    use CacheNick;
    public function test_lesson_review_list(){
        $page_info = $this->get_in_page_info();
        $ret_info = $this->t_test_lesson_subject_require_review->get_all_list($page_info);
        $num = 0;
        foreach($ret_info['list'] as &$item){
            $item['num'] = $num+1;
            $item["nick"]= $this->cache_get_account_nick($item["adminid"]);
            $item["group_nick"]= $this->cache_get_account_nick($item["group_adminid"]);
            $item["master_nick"]= $this->cache_get_account_nick($item["master_adminid"]);
            $item["group_suc_flag_str"] = \App\Helper\Common::get_boolean_color_str($item["group_suc_flag"]);
            $item["master_suc_flag_str"] = \App\Helper\Common::get_boolean_color_str($item["master_suc_flag"]);
            $item['create_time'] = \App\Helper\Utils::unixtime2date($item['create_time']);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function test_lesson_review_add(){
        $adminid = $this->get_account_id();
        $userid = $this->get_in_int_val('userid');
        $p_pp_adminid = $this->t_admin_group_user->get_group_master_adminid($adminid);
        $group_adminid = isset($p_pp_adminid['group_adminid'])?$p_pp_adminid['group_adminid']:0;
        $master_adminid = isset($p_pp_adminid['master_adminid'])?$p_pp_adminid['master_adminid']:0;
        $this->t_test_lesson_subject_require_review->row_insert([
            "adminid"        => $adminid,
            "group_adminid"  => $group_adminid,
            "master_adminid" => $master_adminid,
            "userid"         => $userid,
            "create_time"    => time(NULL),
        ],false,false,true);
        return $this->output_succ('审核提交成功!');
    }
}
