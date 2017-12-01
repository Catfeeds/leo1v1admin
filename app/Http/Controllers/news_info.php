<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class news_info extends Controller
{
    public function news_ad_info(){
        $start_date   = $this->get_in_str_val('start_date',date('Y-m-d', time(NULL)-3*86400 ));
        $end_date     = $this->get_in_str_val('end_date',date('Y-m-d', time(NULL)+86400 ));
        $status       = $this->get_in_int_val('status',-1);
        $news_ad_info = $this->get_in_str_val("news_ad_info","");
        $page_num     = $this->get_in_page_num();

        $start_date_s = strtotime($start_date);
        $end_date_s   = strtotime($end_date)+86400;

        $ret_info=$this->t_news_ad_info->get_ad_info_list($start_date_s,$end_date_s,$status,$news_ad_info,$page_num);

        foreach($ret_info['list'] as &$val){
            E\Ead_status::set_item_value_str($val,'status');
            $val['time_str']=date('Y-m-d ',$val['start_time'])." ~ ".date('Y-m-d',$val['end_time']);
        }

        return $this->pageView(__METHOD__,$ret_info,array(),['qiniu_upload_domain_url' =>
                                                             Config::get_qiniu_public_url()."/"]);
    }


    public function get_ad_info(){
        $id = $this->get_in_int_val('id',-1);

        $ret_info = $this->t_news_ad_info->field_get_list($id,'*');
        $ret_info['start_time_str']=date('Y-m-d',$ret_info['start_time']);
        $ret_info['end_time_str']=date('Y-m-d',$ret_info['end_time']);

        return outputjson_success(array('data' => $ret_info));
    }

    public function add_ad_info(){
        $id         = $this->get_in_int_val('id',-1);
        $start_date = $this->get_in_str_val('start_date',date('Y-m-d', time(NULL)-3*86400 ));
        $end_date   = $this->get_in_str_val('end_date',date('Y-m-d', time(NULL)+86400 ));
        $ad_url     = $this->get_in_str_val('ad_url','');
        $img_url    = $this->get_in_str_val('img_url','');
        $url        = $this->get_in_str_val('url','');
        $title      = $this->get_in_str_val('title','');
        $intro      = $this->get_in_str_val('intro','');
        $status     = $this->get_in_int_val('status',-1);
        $opt_type   = $this->get_in_int_val('opt_type','');

        $start_date_s = strtotime($start_date);
        $end_date_s   = strtotime($end_date)+86400;

        $ret_info=$this->t_news_ad_info->add_ad_info($opt_type,$id,$start_date_s,$end_date_s
                                                     ,$ad_url,$img_url,$url,$title,$intro,$status);

        return outputjson_success();
    }

    public function del_ad_info(){
        $id = $this->get_in_int_val('id',-1);

        $ret_info = $this->t_news_ad_info->row_delete($id);

        return outputjson_success();
    }

    public function stu_message_list(){
        $page_num = $this->get_in_page_num();

        $ret_info = $this->t_baidu_push_msg->get_stu_message_list($page_num);
        foreach($ret_info['list'] as &$val){
            if($val['message_type']==1007){
                $val['message_type']="学生";
            }else{
                $val['message_type']="老师";
            }
        }

        return $this->pageView(__METHOD__,$ret_info);
    }

    /**
     * @param message_content 消息内容
     * @param value 跳转地址，课程id等额外内容
     * @param type 待发送的角色 1 学生 2 老师
     */
    public function add_stu_message_content(){
        $message_content = $this->get_in_str_val("message_content",'');
        $value           = $this->get_in_str_val("value",'');
        $type            = $this->get_in_int_val("type",1);

        if($message_content==''){
            return outputjson_error("推送内容不能为空!");
        }else{
            $push_type = $type==1?1007:2010;
            $messageid = $this->t_baidu_push_msg->add_baidu_push_msg($message_content,$push_type);
            $job       = new \App\Jobs\AddStuMessage($messageid,$value,$type);
            dispatch($job);
            \App\Helper\Utils::logger("job success");
        }

        return outputjson_success();
    }

    public function del_baidu_push_msg(){
        $messageid=$this->get_in_int_val("messageid",0);

        $ret_info=$this->t_baidu_push_msg->row_delete($messageid);

        return outputjson_success();
    }

    public function stu_detail_message_list(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,1);
        $studentid    = $this->get_in_int_val("studentid");
        $message_type = $this->get_in_int_val("userid");
        $page_num     = $this->get_in_page_num();

        $ret_info = $this->t_baidu_msg->get_stu_detail_message_list($page_num,$start_time,$end_time,$studentid,$message_type);
        foreach($ret_info['list'] as &$val){
            E\Emessage_type::set_item_value_str($val);
            \App\Helper\Utils::unixtime2date_for_item($val,"date","_str");
        }

        return $this->pageView(__METHOD__,$ret_info);
    }


    public function push_news_info(){
        $id        = $this->get_in_int_val("id");
        $device    = $this->get_in_int_val("device",4);
        $grade     = $this->get_in_int_val("grade",1);
        $messageid = $this->get_in_int_val("messageid");
        if($messageid == 4011){
            $message = $this->t_news_headlines->get_h_info($id);
            $title   = "升学头条";
        }else{
            $message = $this->t_news_info->get_news_info($id);
            $title   = "政策百科";
        }

        $result = $this->baidu_push_news($device,$grade,$messageid,$message,$title);

        $ret=$this->t_news_headlines->field_update_list($id,['push_status'=>1]);
        return outputjson_success();
    }

    public function baidu_push_news($device,$grade,$messageid,$message,$title){
        $len=$this->utf8_strlen($message);
        if($len>60){
            $message=mb_substr($message,0,55,"utf-8")."  >";
        }

        $message_content=array(
            "title"   => $title,
            "grade"   => $grade,
            "message" => $message,
        );
        $result = \App\Helper\Net::baidu_push_all(E\Erole::V_PARENT,$device,$messageid,$message_content);
        return $result;
    }

    private function utf8_strlen($string = null) {
        // 将字符串分解为单元
        preg_match_all("/./us", $string, $match);
        // 返回单元个数
        return count($match[0]);
    }

    public function add_user_message(){
        $userid  = $this->get_in_str_val("userid");
        $content = $this->get_in_str_val("content");
        $value   = $this->get_in_str_val("value");
        $acc     = $this->get_account();

        $parentid = $this->t_student_info->get_parentid($userid);
        $this->t_baidu_msg->start_transaction();
        $ret = $this->t_baidu_msg->baidu_push_msg($userid,$content,$value,1007,0);
        if(!$ret){
            $this->t_baidu_msg->rollback();
            return $this->output_err("添加失败！请重试！");
        }

        if($parentid>0){
            $ret = $this->t_baidu_msg->baidu_push_msg($parentid,$content,$value,4014,0);
            if(!$ret){
                $this->t_baidu_msg->rollback();
                return $this->output_err("添加失败！请重试！");
            }
            $wx_openid = $this->t_parent_info->get_wx_openid($parentid);
            if($wx_openid!=""){
                $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
                $data= [
                    "first"     => "您有一条未处理的\"理优618\"活动奖励，请及时处理",
                    "keyword1"  => "理优618活动",
                    "keyword2"  => "京东电子现金劵兑换码",
                    "keyword3"  => date("Y-m-d"),
                    "remark"    => $content,
                ];
                \App\Helper\Utils::send_wx_to_parent($wx_openid,$template_id,$data);
            }
        }
        $this->t_baidu_msg->commit();

        return $this->output_succ();
    }

}