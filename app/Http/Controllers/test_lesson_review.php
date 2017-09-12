<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class test_lesson_review extends Controller
{
    use CacheNick;
    public function test_lesson_review_list() {
        $p_pp_adminid = $this->t_admin_group_user->get_group_master_adminid($adminid=314);
        $p_pp_adminid_new = $this->t_admin_group_user->get_group_master_adminid($adminid=287);
        dd($p_pp_adminid,$p_pp_adminid_new);
        $page_info = $this->get_in_page_info();
        $ret_info = $this->t_test_lesson_subject_require_review->get_all_list($page_info);
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function test_lesson_review_add(){
        $adminid = $this->get_account_id();
        $userid = $this->get_in_int_val('userid');
        $p_pp_adminid = $this->t_admin_group_user->get_group_master_adminid($adminid);
        dd($p_pp_adminid);
        $group_adminid = $p_pp_adminid['group_adminid'];
        $master_adminid = $p_pp_adminid['master_adminid'];
        $this->t_agent->row_insert([
            "adminid"        => $adminid,
            "group_adminid"  => $group_adminid,
            "master_adminid" => $master_adminid,
            "userid"         => $userid,
            "create_time"    => time(NULL),
        ],false,false,true);
        return $this->output_succ();
    }
}
