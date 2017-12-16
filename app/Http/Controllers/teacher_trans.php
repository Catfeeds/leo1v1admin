<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;


// 全职转兼职 兼职转全职
class teacher_trans extends Controller
{
    use CacheNick;

    public function full_to_part() { // 全转兼
        $teacherid = $this->get_in_str_val("teacherid");
        $level = $this->get_in_str_val('level');
        $teacher_money_type = $this->get_in_str_val("teacher_money_type");
        $require_reason = $this->get_in_str_val("require_reason");
        $adminid = $this->get_account_id();

        if (!$require_reason) {
            return $this->output_err("申请原因不能为空");
        }

        $id = $this->t_teacher_full_part_trans_info->is_exist_for_teacherid($teacherid);
        if ($id) {
            return $this->output_err("请不要重复申请");
        }

        $this->t_teacher_full_part_trans_info->row_insert([
            'teacherid' => $teacherid,
            'level_before' => $level,
            'level_after' => $level,
            'teacher_money_type_before' => $teacher_money_type,
            //'teacher_money_type_after' => $teacher_money_type,
            'teacher_money_type_after' => E\Eteacher_money_type::V_6,
            'require_adminid' => $adminid,
            'require_time' => time(),
            'require_reason' => $require_reason,
            'add_time' => time(),
            'type' => 1
        ]);
        return $this->output_succ();
    }

    public function full_to_part_trans_info() { // 全兼互转申请
        list($start_time, $end_time) = $this->get_in_date_range_day(0);
        $info = $this->t_teacher_full_part_trans_info->get_all_list($start_time, $end_time);

        foreach($info as &$item) {
            $item['nick'] = $this->cache_get_teacher_nick($item['teacherid']);
            $item['require_time'] = date('Y-m-d H:i:s', $item['require_time']);
            $item['require_adminid'] = $this->cache_get_account_nick($item['require_adminid']);
            E\Eteacher_money_type::set_item_value_str($item, 'teacher_money_type_before');
            E\Eteacher_money_type::set_item_value_str($item, 'teacher_money_type_after');
            if ($item['type'] == 1) { // 全转兼
                E\Elevel::set_item_value_str($item, 'level_before');
                $item['level_after_str'] = E\Enew_level::$simple_desc_map[$item['level_after']];
            } else {
                $item['level_before_str'] = E\Enew_level::$simple_desc_map[$item['level_before']];
                E\Elevel::set_item_value_str($item, 'level_after');
            }
            if ($item['accept_status'] == 0) {
                $item['accept_status'] = '未审核';
            } elseif ($item['accept_status'] == 1) {
                $item['accept_status'] = '未通过';
            } elseif ($item['accept_status'] == 2) {
                $item['accept_status'] = '已通过';
            }
        }
        return $this->pageView(__METHOD__, '', [
            'info' => $info
        ]);
    }

    public function update_accept_status() {
        $id = $this->get_in_str_val("id");
        $teacherid = $this->get_in_str_val('teacherid');
        $accept_status = $this->get_in_str_val("accept_status");
        $accept_info = $this->get_in_str_val("accept_info");

        if (!$accept_info && $accept_status == 1) {
            return $this->output_err('审核未通过时请填写未通过原因');
        }

        $acc = $this->get_account();
        $this->t_teacher_full_part_trans_info->field_update_list($id, [
            'acc' => $acc,
            'accept_status' => $accept_status,
            'accept_info' => $accept_info,
            "accept_time" => time(NULL)
        ]);

        if ($accept_status == 2) { // 审核通过修改老师为兼职
            $this->t_teacher_info->field_update_list($teacherid, [
                'teacher_type' => 0 
            ]);

            // 转职成功后 --- 微信推送
        }
        
        return $this->output_succ();
    }
}