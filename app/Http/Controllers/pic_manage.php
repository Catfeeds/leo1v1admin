<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class pic_manage extends Controller
    {
        public function pic_info()
        {
            $type      = $this->get_in_int_val('type',-1);
            $usage_type= $this->get_in_int_val('usage_type',-1);
            $active_status = $this->get_in_int_val("active_status", 0);
            $page_num  = $this->get_in_page_num();

            $ret_info  = $this->t_pic_manage_info->get_pic_info_list($type,$usage_type,$active_status,$page_num);
            $current = time();
            $min_date = date('Y-m-d', strtotime('1day'));

            foreach($ret_info["list"] as &$item){
                E\Epic_type::set_item_value_str($item,"type");
                E\Epic_time_type::set_item_value_str($item,"time_type");
                E\Epic_usage_type::set_item_value_str($item,"usage_type");
                $item['active_status'] = '';
                // 判断活动状态
                if ($item['del_flag'] == 1) {
                    $item['active_status'] = '已删除';
                } else {
                    if ($current < $item['start_time']) {
                        $item['active_status'] = '待开始';
                    } elseif ($current < $item['end_time']) {
                        $item['active_status'] = '已发布';
                    } elseif ($current > $item['end_time']) {
                        $item['active_status'] = '已结束';
                    }
                }
                $item['min_date'] = $min_date;
            }

            return $this->pageView(__METHOD__,$ret_info,['min_date' => $min_date],[
                'qiniu_upload_domain_url' =>Config::get_qiniu_public_url()."/"
            ]);
        }

    public function get_pic_info()
    {
        $id = $this->get_in_int_val('id',-1);

        $ret_info = $this->t_pic_manage_info->field_get_list($id,'*');
        $ret_info['start_time'] = date("Y-m-d",$ret_info['start_time']);
        $ret_info['end_time']   = date("Y-m-d",$ret_info['end_time']);
        $ret_info['min_date'] = date('Y-m-d', strtotime('1day'));

        return outputjson_success(array('ret_info' => $ret_info));
    }

    public function add_pic_info()
    {
        $opt_type     = $this->get_in_str_val('opt_type','');
        $id           = $this->get_in_int_val('id',-1);
        $name         = $this->get_in_str_val('name','');
        $type         = $this->get_in_int_val('type',-1);
        $usage_type   = $this->get_in_int_val('usage_type',-1);
        $click_status = $this->get_in_int_val('click_status',-1);
        $order_by     = $this->get_in_int_val('order_by',0);
        $grade        = $this->get_in_int_val('grade',0);
        $subject      = $this->get_in_int_val('subject',0);
        $pic_url      = $this->get_in_str_val('pic_url','');
        $tag_url      = $this->get_in_str_val('tag_url','');

        $start_time  = $this->get_in_str_val('start_time');
        $end_time    = $this->get_in_str_val('end_time');
        $title_share = $this->get_in_str_val('title_share','');
        $info_share  = $this->get_in_str_val('info_share','');
        $jump_url    = $this->get_in_str_val('jump_url');
        $jump_type   = $this->get_in_int_val('jump_type');
        $acc = $this->get_account();

        if ($type == 3 && $usage_type == 302) {
            $size = getimagesize($pic_url);
            if ($size) {
                $width = $size[0];
                $height = $size[1];
                if (!($width == 1920 && $height == 750)) {
                    return $this->output_err("电脑端图片大小是1920*750");
                }
            }
        }


        if ($type == 3 && $usage_type == 303) {
            $size = getimagesize($pic_url);
            if ($size) {
                $width = $size[0];
                $height = $size[1];
                if (!($width == 750 && $height == 500)) {
                    return $this->output_err("手机端图片大小是750*500");
                }
            }
        }

        // 通过判断response响应头
        // file_get_contents($pic_url);
        // $responseInfo = $http_response_header;
        // foreach($responseInfo as $item) {
        //     $val = explode(":", $item);
        //     if (trim($val[0]) == 'Content-Type') {
        //         if (trim($val[1]) != 'image/png') {
        //             return $this->output_err("请上传png格式的图片");
        //         }
        //         break;
        //     }
        // }

        $ext = pathinfo($pic_url, PATHINFO_EXTENSION);
        if ($ext != 'jpg'  && $ext != 'png') {
            return $this->output_err("请上传png,jpg格式的图片");
        }

        $start = strtotime($start_time);
        $end   = strtotime($end_time);

        $ret_info=$this->t_pic_manage_info->add_pic_info($opt_type,$id,$name,$type,$usage_type,
                                                         $pic_url,$tag_url,$click_status,$order_by,$grade,
                                                         $subject,$start,$end,$title_share,$info_share,
                                                         $jump_url,$jump_type);
        if ($opt_type == "add") {
            $msg = "添加";
        } else {
            $msg = "修改";
        }
        $this->t_user_log->add_data("图片管理页:".$acc."执行了".$msg."操作");
        return outputjson_success();
    }

    public function del_pic_info()
    {
        $id = $this->get_in_str_val('id',-1);
        $acc = $this->get_account();

        //$ret_info=$this->t_pic_manage_info->row_delete($id);
        $this->t_pic_manage_info->field_update_list($id, [
            "del_flag" => 1
        ]);
        $this->t_user_log->add_data("图片管理页:".$acc."执行了删除操作");
        return $this->output_succ();
    }

}