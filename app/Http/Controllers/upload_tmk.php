<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;


class upload_tmk extends Controller {
    use CacheNick;

    public function  post_list () {
        list($start_time,$end_time)= $this->get_in_date_range(-180,1);
        $page_info= $this->get_in_page_info();
        $ret_info=$this->t_upload_info->get_list($page_info,-1,$start_time,$end_time);

        foreach( $ret_info["list"]   as &$item) {
            $this->cache_set_item_account_nick($item,"upload_adminid","upload_admin_nick");
            \App\Helper\Utils::unixtime2date_for_item($item,"upload_time");
            E\Eboolean::set_item_value_str($item,"post_flag");
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function  post_student_list() {
        $postid= $this->get_in_int_val("postid");

        if ($postid == 0) {
            return $this->error_view(["请在[批次列表]中点击明细按钮"]);
        }

        $is_new_flag= $this->get_in_e_boolean(-1,"is_new_flag");
        $ret_info=$this->t_upload_student_info->get_list($postid,$is_new_flag); //

        $start_index= \App\Helper\Utils::get_start_index_from_ret_info($ret_info); 
        foreach( $ret_info["list"] as $i=> &$item) {
            $item["index"]= $start_index+$i;
            E\Egrade::set_item_value_simple_str($item);
            E\Esubject::set_item_value_simple_str($item);
            E\Epad_type::set_item_value_simple_str($item,"has_pad");
            E\Eboolean::set_item_value_str($item,"is_new_flag");
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"publish_time");

        }

        return $this->pageView(__METHOD__,$ret_info);
    }

    public function upload_xls() {
        $file = Input::file('file');

        \App\Helper\Utils::logger("UPLOAD_XLS");

        $postid=$this->get_in_postid();
        // dd(1);
        // dd($file);

        if ($file->isValid()) {
            //处理列
            $realPath = $file->getRealPath();
            \App\Helper\Utils::logger("UPLOAD_XLS:$realPath");
            $ret = $this->upload_from_xls_data($postid, $realPath);

            // dd($ret);
            return outputjson_success();
        } else {
            return outputjson_ret(false);
        }

    }


    public function upload_from_xls_data($postid,$obj_file) {
        $grade_map = [
            '200'    => 201,
            '小学'    => 100,
            '初中'    => 200,
            '高中'    => 300,
            '八年级' => 202,
            '初二'   => 202,
            '初三'   => 203,
            '初一'   => 201,
            '二年级' => 102,
            '高二'   => 302,
            '高三'   => 303,
            '高一'   => 301,
            '九年级' => 203,
            '六年级' => 201,
            '七年级' => 202,
            '三年级' => 103,
            '四年级' => 104,
            '未填写' => 100,
            '五年级' => 105,
            '小二'   => 102,
            '小六'   => 106,
            '小三'   => 103,
            '小四'   => 104,
            '小五'   => 106,
            '小学'   => 100,
            '小一'   => 101,
            '学龄前' => 101,
            '一年级' => 101,
        ];
        $subject_map = array(
            "语文" => 1,
            "数学" => 2,
            "英语" => 3,
            "化学" => 4,
            "物理" => 5,
            "生物" => 6,
            "政治" => 7,
            "历史" => 8,
            "地理" => 9,
        );
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');

        \App\Helper\Utils::logger("load file start :$obj_file" );
        $objPHPExcel = $objReader->load($obj_file);
        \App\Helper\Utils::logger("load file end ");
        $objPHPExcel->setActiveSheetIndex(0);
        $arr=$objPHPExcel->getActiveSheet()->toArray();

        // dd($arr);
        foreach ($arr as $index => $item) {
            if ($index== 0) { //标题
                //验证字段名
                if (trim($item[0]) != "手机号"
                    ||trim($item[1]) != "归属地"
                    ||trim($item[3]) != "来源"
                ) {
                    return "xxx" ;
                }
            } else {
                //导入数据
                /*
                  0 => "手机号"
                  1 => "归属地"
                  2 => "时间"
                  3 => "来源"
                  4 => "姓名"
                  5 => "用户备注"
                  6 => "年级"
                  7 => "科目"
                  8 => "是否有pad"
                  9 => "家长姓名"
                */
                $phone          = trim($item[0]);
                $phone_location = $item[1];
                $add_time = strtotime( $item[2]);
                $origin         = trim($item[3]);
                $nick           = $item[4];
                $user_desc      = $item[5];
                $grade          = trim($item[6]);
                $subject        = trim($item[7]);
                $has_pad        = $item[8];
                $parent_name = @$item[9] ;
                if (!($add_time>1000000) ) {
                    $add_time=time(NULL);
                }


                if (isset($grade_map[$grade])) {
                    $grade = $grade_map[$grade] ;
                }

                $subject_str=$subject;
                if (isset($subject_map[$subject])) {
                    $subject = $subject_map[$subject] ;
                }


                if (strpos($has_pad, "iPad")!== false) {
                    $has_pad=1;
                } elseif (strpos($has_pad, "安卓") !== false) {
                    $has_pad=2;
                } else{
                    $has_pad=0;
                }
                if ($parent_name)  {
                    $user_desc.="|$parent_name";
                }

                $is_new_flag =1;
                if ( $this->t_seller_student_new->get_userid_by_phone($phone) )  {
                    $is_new_flag =0;
                }

                if ($phone>10000) {
                    // dd(1);
                   $ret = $this->t_upload_student_info->row_insert([
                        "postid"=>  $postid,
                        "add_time"=>  $add_time,
                        "phone"=>  $phone,
                        "name"=>  $nick,
                        "origin"=>  $origin,
                        "subject"=>  $subject,
                        "grade"=>  $grade,
                        "user_desc"=>  $user_desc,
                        "has_pad"=>  $has_pad,
                        "is_new_flag"=>  $is_new_flag,
                    ],false,true);
                    // return $ret;
                    //$this->t_upload_student_info.
                }
            }
        }

    }
    public function get_in_postid($def_value=0) {
        return $this->get_in_int_val("postid",$def_value);
    }

    public function del_upload_post() {
        $postid= $this->get_in_postid();
        $ret_info=$this->t_upload_student_info->get_list($postid);
        if (count($ret_info["list"] )>0){
            return $this->output_err("有数据,不能删除");
        }
        $this->t_upload_info->row_delete($postid);
        return $this->output_succ();
    }

    public function add_upload_post() {
        $desc= $this->get_in_str_val("desc");
        $this->t_upload_info->row_insert([
            'upload_adminid' => $this->get_account_id(),
            'upload_time' =>time(NULL),
            'upload_desc' => $desc,
        ]);
        return $this->output_succ();
    }

    public function del_all_example () {
        $postid=$this->get_in_postid();
        $this->t_upload_student_info->row_delete($postid);
        return $this->output_succ();
    }
    public function  do_publish() {
        $postid = $this->get_in_postid();

        $ret_info=$this->t_upload_student_info->get_list($postid,1);
        foreach( $ret_info["list"] as $i=> &$item) {
            $this->t_seller_student_new->book_free_lesson_new( $item['name'],$item['phone'],$item['grade'], $item['origin'], $item['subject'], $item['has_pad'] , $item['user_desc'] );
        }

        $this->t_upload_info->field_update_list($postid,[
            "post_flag" => 1
        ]);
    }

}
