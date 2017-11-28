<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class resource extends Controller
{

    use CacheNick;
    var $check_login_flag=true;

    function __construct( ) {
        parent::__construct();
    }

    public function get_all() {
        $user_type     = $this->get_in_int_val('user_type', 1);
        $resource_type = $this->get_in_int_val('resource_type', 1);
        $subject       = $this->get_in_int_val('subject', -1);
        $grade         = $this->get_in_int_val('grade', -1);
        $file_title    = $this->get_in_str_val('file_title', '');
        $page_info     = $this->get_in_page_info();
        $ret_info      = $this->t_resource->get_all($user_type ,$resource_type, $subject, $grade, $file_title, $page_info);
        foreach($ret_info['list'] as &$item){
            \App\Helper\Utils::unixtime2date_for_item($item,"update_time");
            \App\Helper\Utils::transform_1tg_0tr($item,'is_use');
            $item['nick'] = $this->cache_get_account_nick($item['edit_adminid']);
            $item['file_size'] /= 1024;
        }

        return $this->pageView( __METHOD__,$ret_info);
    }

    public function check_able_edit(){
        $role = $this->get_account_role();
        if($role == 9 || $role == 12) {
            $flag = 1;
        } else {
            $flag = 0;
        }
        return $flag;
    }

    public function add_resource() {
        $user_type     = $this->get_in_int_val('user_type');
        $resource_type = $this->get_in_int_val('resource_type');
        $subject       = $this->get_in_int_val('subject');
        $grade         = $this->get_in_int_val('grade');
        $tag_one       = $this->get_in_int_val('tag_one');
        $tag_two       = $this->get_in_int_val('tag_two');
        $tag_three     = $this->get_in_int_val('tag_three');
        $file_title    = $this->get_in_str_val('file_title');
        $file_size     = $this->get_in_str_val('file_size');
        $file_hash     = $this->get_in_str_val('file_hash');

        $adminid = $this->get_account_id();

        $this->t_resource->row_insert([
            'resource_type' => $resource_type,
            'subject'       => $subject,
            'grade'         => $grade,
            'tag_one'       => $tag_one,
            'tag_two'       => $tag_two,
            'tag_three'     => $tag_three,
            'file_title'    => $file_title,
            'file_size'     => $file_size,
            'file_hash'     => $file_hash,
            'adminid'       => $adminid,
            'edit_adminid'  => $adminid,
            'create_time'   => time(),
            'update_time'   => time(),
        ]);

        return $this->output_succ();
    }

    public function add_or_update_name() {
        $name       = trim( $this->get_in_str_val('name') );
        $process_id = $this->get_in_int_val('process_id');
        $adminid    = $this->get_account_id();
        if( $process_id>0 ) {
            $this->t_process_info->field_update_list($process_id,[
                'name'        => $name,
                'create_time' => time(),
            ]);
        } else {
            $this->t_process_info->row_insert([
                'name'        => $name,
                'create_time' => time(),
                'adminid'     => $adminid,
            ]);
        }
        return $this->output_succ();
    }

    public function add_or_update_rule_detail() {
        $rule_id = $this->get_in_int_val('rule_id');
        $detail_id = $this->get_in_int_val('detail_id');
        $level = $this->get_in_int_val('level');
        $deduct_marks = $this->get_in_int_val('deduct_marks');
        $rank_num = $this->get_in_int_val('rank_num');

        $name = trim( $this->get_in_str_val('name') );
        $content = $this->get_in_str_val('content');
        $punish_type = $this->get_in_str_val('punish_type');
        $add_punish = $this->get_in_str_val('add_punish');

        $adminid = $this->get_account_id();
        if( $rule_id == 0 ) {
            return $this->output_err('信息有误！添加失败！');
        }
        if( $rank_num == 0 ) {
            $num = $this->t_rule_detail_info->get_max_rank_num($rule_id,$level);
            $rank_num = $num+1;
        }
        if( $detail_id>0 ) {
            $this->t_rule_detail_info->field_update_list($detail_id,[
                'rule_id'      => $rule_id,
                'name'         => $name,
                'level'        => $level,
                'content'      => $content,
                'rank_num'     => $rank_num,
                'deduct_marks' => $deduct_marks,
                'punish_type'  => $punish_type,
                'add_punish'   => $add_punish,
                'punish_type'  => $punish_type,
                'create_time'  => time(),
                'adminid'      => $adminid,
            ]);
        } else {
            $this->t_rule_detail_info->row_insert([
                'rule_id'      => $rule_id,
                'name'         => $name,
                'level'        => $level,
                'content'      => $content,
                'rank_num'     => $rank_num,
                'deduct_marks' => $deduct_marks,
                'punish_type'  => $punish_type,
                'add_punish'   => $add_punish,
                'punish_type'  => $punish_type,
                'create_time'  => time(),
                'adminid'      => $adminid,
            ]);
        }
        return $this->output_succ();
    }

    public function rule_detail() {
        $rule_id = $this->get_in_int_val('rule_id');
        $rule = $this->t_rule_info->get_rule_info($rule_id);
        if ($rule == false){
            return $this->error_view(["没有该信息！"]);
        }
        // \App\Helper\Utils::unixtime2date_for_item($rule,"create_time");
        $rule['create_time'] = date('Y-m-d', $rule['create_time']);
        $ret_info = $this->t_rule_detail_info->get_rule_detail($rule_id);
        $row = [];
        foreach($ret_info as $key => &$item){
            // \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            $item['create_time'] = date('Y-m-d', $item['create_time']);
            E\Ededuct_marks::set_item_value_simple_str($item);
            E\Erule_level::set_item_value_simple_str($item,'level');
            $row[$item['level_str']]['num'] = @$row[$item['level_str']]['num']+1;
            if ($row[$item['level_str']]['num'] == 1){
                $row[$item['level_str']]['start'] = $key;
            }
            if($item['add_punish']){
                $item['punish'] = '<a href="javascript:;" class="opt-punish" data-punish="'.$item["add_punish"].'">查看</a>';
            } else {
                $item['punish'] = '无';
            }
        }
        $edit_flag = $this->check_able_edit();

        return $this->pageView( __METHOD__,\App\Helper\Utils::list_to_page_info($ret_info) ,[
            'rule' => $rule,
            'row'  => $row,
            'edit_flag'  => $edit_flag
        ]);
    }

    public function process_info() {
        $process_id = $this->get_in_int_val('process_id');
        $pro = $this->t_process_info->get_process_info($process_id);
        if($pro == false){
            return $this->error_view(["没有该信息！"]);
        }
        $pro['create_time'] = date('Y-m-d', $pro['create_time']);
        $role = @explode(',',$pro['department']);
        $pro['department_str'] = '';
        foreach($role as $v){
            $pro['department_str'] .= E\Eaccount_role::get_desc($v) . ',';
        }

        $pro['department_str'] = rtrim($pro['department_str'], ',');
        $edit_flag = $this->check_able_edit();

        return $this->pageView( __METHOD__,[],[
            'pro'  => $pro,
            "qiniu_pub"  => \App\Helper\Config::get_qiniu_public_url(),
            'edit_flag'  => $edit_flag
        ]);
    }

    public function update_process() {
        $process_id  = $this->get_in_int_val('process_id');
        $name        = trim( $this->get_in_str_val('name'));
        $fit_range   = trim( $this->get_in_str_val('fit_range'));
        $department  = trim( $this->get_in_str_val('department'));
        $pro_explain = trim( $this->get_in_str_val('pro_explain'));
        $attention   = trim( $this->get_in_str_val('attention'));
        $pro_img     = trim( $this->get_in_str_val('pro_img'));

        if($process_id == 0) {
            return $this->output_err('修改失败！');
        }
        $this->t_process_info->field_update_list($process_id,[
            'name'        => $name,
            'fit_range'   => $fit_range,
            'department'  => $department,
            'pro_explain' => $pro_explain,
            'attention'   => $attention,
            'pro_img'     => $pro_img,
        ]);

        return $this->output_succ();
    }

    public function del_rule_detail() {
        $detail_id = $this->get_in_int_val('detail_id');
        $this->t_rule_detail_info->row_delete($detail_id);
        return $this->output_succ();
    }

    public function up_or_down() {
        $rule_id = $this->get_in_int_val('rule_id');
        $detail_id = $this->get_in_int_val('detail_id');
        $level = $this->get_in_int_val('level');
        $rank_num = $this->get_in_int_val('rank_num');

        $type = $this->get_in_str_val('type');
        $other_id = $this->t_rule_detail_info->get_detail_id_by_info($rule_id,$level,$rank_num,$type);
        if($type === 'up'){
            if($rank_num != 1) {
                $this->t_rule_detail_info->start_transaction();
                $res1 = $this->t_rule_detail_info->field_update_list($other_id,['rank_num' => $rank_num ]);
                $res2 = $this->t_rule_detail_info->field_update_list($detail_id,['rank_num' => $rank_num-1 ]);
                if ($res1 && $res2){
                    $this->t_rule_detail_info->commit();
                } else {
                    $this->t_rule_detail_info->rollback();
                }
            }
        } else if ($type === 'down'){

            $num = $this->t_rule_detail_info->get_max_rank_num($rule_id,$level);
            if ($rank_num != $num){
                $this->t_rule_detail_info->start_transaction();
                $res1 = $this->t_rule_detail_info->field_update_list($other_id,['rank_num' => $rank_num ]);
                $res2 = $this->t_rule_detail_info->field_update_list($detail_id,['rank_num' => $rank_num+1 ]);
                if ($res1 && $res2){
                    $this->t_rule_detail_info->commit();
                } else {
                    $this->t_rule_detail_info->rollback();
                }

            }
        }
        return $this->output_succ();
    }

    public function update_pro_img() {
        $teacherid = $this->get_login_teacher();
        $field     = $this->get_in_str_val('opt_field', '');
        $url       = $this->get_in_str_val('get_pdf_url', '');
        if ( $field == '' || $url == '' ) {
            $this->output_err("上传信息为空！");
        }
        $res_info = $this->t_teacher_info->field_update_list($teacherid, [$field  => $url]);

        if ($res_info) {
            return outputjson_success();
        } else {
            return outputjson_error('发生错误，设置失败！');
        }

    }

}
