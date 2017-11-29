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
            // \App\Helper\Utils::transform_1tg_0tr($item,'is_use');
            $item['nick'] = $this->cache_get_account_nick($item['edit_adminid']);
            $item['file_size'] = round( $item['file_size'] / 1024,2);
            if($item['is_use'] == 0) {
                $item['is_use_str'] = '否';
            } else {
                $item['is_use_str'] = '是';
            }
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
        $file_type     = $this->get_in_str_val('file_type');
        $file_size     = intval( $this->get_in_str_val('file_size') /1024);
        $file_hash     = $this->get_in_str_val('file_hash');
        $id_str        = $this->get_in_str_val('id_str');

        $adminid   = $this->get_account_id();
        if($id_str == '') {
            $arr       = explode('/',$file_type);
            $file_type = $arr[1];

            $this->t_resource->row_insert([
                'user_type'     => $user_type,
                'resource_type' => $resource_type,
                'subject'       => $subject,
                'grade'         => $grade,
                'tag_one'       => $tag_one,
                'tag_two'       => $tag_two,
                'tag_three'     => $tag_three,
                'file_title'    => $file_title,
                'file_type'     => $file_type,
                'file_size'     => $file_size,
                'file_hash'     => $file_hash,
                'adminid'       => $adminid,
                'edit_adminid'  => $adminid,
                'create_time'   => time(),
                'update_time'   => time(),
            ]);

        } else {
            $id_str = ltrim($id_str,'[');
            $id_str = rtrim($id_str,']');
            $id_arr = explode(',', $id_str);
            foreach($id_arr as $id){
                $this->t_resource->field_update_list($id, [
                    'user_type'     => $user_type,
                    'resource_type' => $resource_type,
                    'subject'       => $subject,
                    'grade'         => $grade,
                    'tag_one'       => $tag_one,
                    'tag_two'       => $tag_two,
                    'tag_three'     => $tag_three,
                    'edit_adminid'  => $adminid,
                    'update_time'   => time(),
                ]);
            }
        }
        return $this->output_succ();
    }

    public function rename_resource() {
        $file_title  = $this->get_in_str_val('file_title');
        $resource_id = $this->get_in_int_val('resource_id');

        $adminid   = $this->get_account_id();
        $this->t_resource->field_update_list($resource_id, [
            'file_title'   => $file_title,
            'edit_adminid' => $adminid,
            'update_time'  => time(),
        ]);
        return $this->output_succ();
    }

    public function reupload_resource() {
        $resource_id = $this->get_in_int_val('resource_id');
        // $file_title    = $this->get_in_str_val('file_title');
        $file_type     = $this->get_in_str_val('file_type');
        $file_size     = intval( $this->get_in_str_val('file_size') /1024);
        $file_hash     = $this->get_in_str_val('file_hash');

        $adminid   = $this->get_account_id();
        $this->t_resource->field_update_list($resource_id, [
            // 'file_title'   => $file_title,
            'file_type'    => $file_type,
            'file_size'    => $file_size,
            'file_hash'    => $file_hash,
            'edit_adminid' => $adminid,
            'update_time'  => time(),
        ]);
        return $this->output_succ();
    }


    public function del_resource() {

        $id_str = $this->get_in_str_val('id_str','');
        if($id_str != '') {
            $id_str = ltrim($id_str,'[');
            $id_str = rtrim($id_str,']');
            $id_arr = explode(',', $id_str);
            foreach($id_arr as $id){
               $this->t_resource->row_delete($id);
            }

            return $this->output_succ();
        }
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
