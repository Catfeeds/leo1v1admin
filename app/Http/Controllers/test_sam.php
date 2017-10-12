<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use \App\Libs;
use \App\Config as C;
use \App\Enums as E;
use App\Helper\Utils;

class test_sam  extends Controller
{
    use CacheNick;
    use TeaPower;
    
    public function lesson_list()
    {
      
    }
    
    private function get_lesson_quiz_cfg($lesson_quiz_status, $lesson_type)
    {
    }
    public function manager_list()
    {
    }
    public function test(){
        $start_time = 1504195200;
        $end_time   = 1506787200;
        $ret_info = $this->t_cr_week_month_info->get_total($start_time,$end_time);
        

        foreach ($ret_info as $key => &$value) {
            if($value['phone_location'] == "鹏博士" || $value['phone_location'] == '' || $value['phone_location'] == '免商店充值卡' || $value['phone_location'] == '中麦通信' ||$value['phone_location'] == '重庆U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '小米移动' || $value['phone_location'] == '北京U友' || $value['phone_location'] == "全国其它 " || $value['phone_location'] == '话机通信' || $value['phone_location'] == '阿里通信' || $value['phone_location'] == '辽宁U友' || $value['phone_location'] == '吉林省移动'){

                $value['phone_location'] = '其它';
            }else{
                $pro = substr($value['phone_location'],0,strlen($value['phone_location'])-6);
                $value['phone_location'] = $pro;
            }
            if($value['subject'] < 1 || $value['subject'] > 11){
                $value['subject'] = '其它';
            }else{
                $sub = E\Esubject::get_desc($value['subject']);
                $value['subject'] = $sub;
            }

            if($value['grade'] < 100 ){
                $value['grade'] = '其它';
            }else{
                $gr = E\Egrade::get_desc($value['grade']);
                $value['grade'] = $gr;
            }
        }
        $result = [];
        $month = date('Y.m',$start_time);
        foreach ($ret_info as $key => $value) {
            $index = $month.'|'.$value['phone_location'].'|'.$value['subject'].'|'.$value['grade'];
            if(!isset($result[$index])){
                $result[$index] = 0;
                $result[$index] += $value['total'];
            }else{
                $result[$index] += $value['total'];
            }
        }
        foreach ($result as $key => $value) {
            echo $key.'|'.$value.'<br/>';
        }
        dd(1);
    }
    public function ll(){
        
        /*$ret_info = $this->t_cr_week_month_info->get_tongji();
        echo "统计时间:2016.10.1~2017.10.1"."<br/>";
        echo "总注册学生数:".$ret_info['total_student']."<br/>";
        echo "总签单数:".$ret_info['total_order']."<br/>";
        echo "第二次购买的学生数".$ret_info['total_renew_order'].'<br/>';
        echo "电话接通数:".$ret_info['total_call']."<br/>";
        */

        $start_time = 1504195200;
        $end_time   = 1506787200;
        $ret_info = $this->t_cr_week_month_info->get_total_province($start_time,$end_time);
        $province = [];
        $province['其它'] = 0;
        foreach($ret_info as $key => $value){
            if($value['phone_location'] == "鹏博士" || $value['phone_location'] == '' || $value['phone_location'] == '免商店充值卡' || $value['phone_location'] == '中麦通信' ||$value['phone_location'] == '重庆U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '小米移动' || $value['phone_location'] == '北京U友' || $value['phone_location'] == "全国其它 " || $value['phone_location'] == '话机通信' || $value['phone_location'] == '阿里通信' || $value['phone_location'] == '辽宁U友'){

                $province['其它'] += $value['total'];
            }else{
                $pro = substr($value['phone_location'],0,strlen($value['phone_location'])-6);
                if(!isset($province[$pro])){
                    $province[$pro] = 0;
                    $province[$pro] += $value['total'];
                }else{
                    $province[$pro] += $value['total'];
                }

            }
        }
        foreach ($province as $key => $value) {
            echo $key."|".$value."<br/>";
        }
        echo '--------------------'.'<br/>';
        $ret_info_grade = $this->t_cr_week_month_info->get_total_grade_num($start_time,$end_time);
        $grade = [];
        $grade['其它'] = 0;
        foreach ($ret_info_grade as $key => $value) {
            if($value['grade'] < 100 ){
                $grade['其它'] += $value['total'];
            }else{
                $gr = E\Egrade::get_desc($value['grade']);
                if(!isset($grade[$gr])){
                    $grade[$gr] = 0;
                    $grade[$gr] += $value['total'];
                }else{
                    $grade[$gr] += $value['total'];
                }
            }
        }
        foreach ($grade as $key => $value) {
            echo $key."|".$value."<br/>";
        }
        echo '--------------------'.'<br/>';
        $ret_info_subject = $this->t_cr_week_month_info->get_total_subject_num($start_time,$end_time);
        $subject = [];
        $subject['其它'] = 0;
        foreach ($ret_info_subject as $key => $value) {
            if($value['subject'] < 1 || $value['subject'] > 11){
                $subject['其它'] += $value['total'];
            }else{
                $gr = E\Esubject::get_desc($value['subject']);
                if(!isset($subject[$gr])){
                    $subject[$gr] = 0;
                    $subject[$gr] += $value['total'];
                }else{
                    $subject[$gr] += $value['total'];
                }
            }
        }
        foreach ($subject as $key => $value) {
            echo $key."|".$value."<br/>";
        }
        echo '################################################################################'.'<br/>';
        echo '--------签单率--------'.'<br/>';
        $ret_info_order = $this->t_cr_week_month_info->get_order_province($start_time,$end_time);
        $province_order = [];
        $province_order['其它'] = 0;
        foreach($ret_info_order as $key => $value){
            if($value['phone_location'] == "鹏博士" || $value['phone_location'] == '' || $value['phone_location'] == '免商店充值卡' || $value['phone_location'] == '中麦通信' ||$value['phone_location'] == '重庆U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '小米移动' || $value['phone_location'] == '北京U友' || $value['phone_location'] == "全国其它 " || $value['phone_location'] == '话机通信' || $value['phone_location'] == '阿里通信' || $value['phone_location'] == '辽宁U友'){

                $province_order['其它'] += $value['total'];
            }else{
                $pro_order = substr($value['phone_location'],0,strlen($value['phone_location'])-6);
                if(!isset($province_order[$pro_order])){
                    $province_order[$pro_order] = 0;
                    $province_order[$pro_order] += $value['total'];
                }else{
                    $province_order[$pro_order] += $value['total'];
                }

            }
        }
        foreach ($province_order as $key => $value) {
            echo $key."|".$value."<br/>";
        }

        echo '--------------------'.'<br/>';
        $ret_info_order_grade = $this->t_cr_week_month_info->get_total_order_grade_num($start_time,$end_time);
        $order_grade = [];
        $order_grade['其它'] = 0;
        foreach ($ret_info_order_grade as $key => $value) {
            if($value['grade'] < 100 ){
                $order_grade['其它'] += $value['total'];
            }else{
                $order_gr = E\Egrade::get_desc($value['grade']);
                if(!isset($order_grade[$order_gr])){
                    $order_grade[$order_gr] = 0;
                    $order_grade[$order_gr] += $value['total'];
                }else{
                    $order_grade[$order_gr] += $value['total'];
                }
            }
        }
        foreach ($order_grade as $key => $value) {
            echo $key."|".$value."<br/>";
        }
        echo '--------------------'.'<br/>';
        $ret_info_order_subject = $this->t_cr_week_month_info->get_total_order_subject_num($start_time,$end_time);
        $order_subject = [];
        $order_subject['其它'] = 0;
        foreach ($ret_info_order_subject as $key => $value) {
            if($value['subject'] < 1 || $value['subject'] > 11){
                $order_subject['其它'] += $value['total'];
            }else{
                $subject_gr = E\Esubject::get_desc($value['subject']);
                if(!isset($order_subject[$subject_gr])){
                    $order_subject[$subject_gr] = 0;
                    $order_subject[$subject_gr] += $value['total'];
                }else{
                    $order_subject[$subject_gr] += $value['total'];

                }
            }
        }
        foreach ($order_subject as $key => $value) {
            echo $key."|".$value."<br/>";
        }
        //dd($province);
    }

    public function tt(){
        $start_time = 1504195200;
        $end_time   = 1506787200;
        echo '--------续费率--------'.'<br/>';
        $ret_info_order = $this->t_cr_week_month_info->get_renew_province($start_time,$end_time);
        $province_order = [];
        $province_order['其它'] = 0;
        foreach($ret_info_order as $key => $value){
            if($value['phone_location'] == "鹏博士" || $value['phone_location'] == '' || $value['phone_location'] == '免商店充值卡' || $value['phone_location'] == '中麦通信' ||$value['phone_location'] == '重庆U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '小米移动' || $value['phone_location'] == '北京U友' || $value['phone_location'] == "全国其它 " || $value['phone_location'] == '话机通信' || $value['phone_location'] == '阿里通信' || $value['phone_location'] == '辽宁U友'){

                $province_order['其它'] += $value['total'];
            }else{
                $pro_order = substr($value['phone_location'],0,strlen($value['phone_location'])-6);
                if(!isset($province_order[$pro_order])){
                    $province_order[$pro_order] = 0;
                    $province_order[$pro_order] += $value['total'];
                }else{
                    $province_order[$pro_order] += $value['total'];
                }

            }
        }
        foreach ($province_order as $key => $value) {
            echo $key."|".$value."<br/>";
        }

        echo '--------------------'.'<br/>';
        $ret_info_order_grade = $this->t_cr_week_month_info->get_total_renew_grade_num($start_time,$end_time);
        $order_grade = [];
        $order_grade['其它'] = 0;
        foreach ($ret_info_order_grade as $key => $value) {
            if($value['grade'] < 100 ){
                $order_grade['其它'] += $value['total'];
            }else{
                $order_gr = E\Egrade::get_desc($value['grade']);
                if(!isset($order_grade[$order_gr])){
                    $order_grade[$order_gr] = 0;
                    $order_grade[$order_gr] += $value['total'];
                }else{
                    $order_grade[$order_gr] += $value['total'];
                }
            }
        }
        foreach ($order_grade as $key => $value) {
            echo $key."|".$value."<br/>";
        }
        echo '--------------------'.'<br/>';
        $ret_info_order_subject = $this->t_cr_week_month_info->get_total_renew_subject_num($start_time,$end_time);
        $order_subject = [];
        $order_subject['其它'] = 0;
        foreach ($ret_info_order_subject as $key => $value) {
            if($value['subject'] < 1 || $value['subject'] > 11){
                $order_subject['其它'] += $value['total'];
            }else{
                $subject_gr = E\Esubject::get_desc($value['subject']);
                if(!isset($order_subject[$subject_gr])){
                    $order_subject[$subject_gr] = 0;
                    $order_subject[$subject_gr] += $value['total'];
                }else{
                    $order_subject[$subject_gr] += $value['total'];

                }
            }
        }
        foreach ($order_subject as $key => $value) {
            echo $key."|".$value."<br/>";
        }
    }

    public function  aa(){
        $ret_info = $this->t_cr_week_month_info->get_tongji2();
        foreach ($ret_info as $key => $value) {
            $phone=trim($value['phone']);
            if ($phone =="" ) {
                $phone_location = "" ;
            }else{
                $url= "https://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=$phone";

                $data= preg_replace("/__GetZoneResult_ = /","", \App\Helper\Net::send_post_data($url,[] )
                );
                $data= preg_replace("/([A-Za-z]*):/","\"\\1\":", $data);
                $data= preg_replace("/'/","\"", $data);

                $data = iconv("GBK","utf-8",$data);
                $arr  = json_decode($data,true);


                if(isset($arr['province']) && isset($arr['carrier'])){
                    if(strpos($arr['carrier'],'移动') ||strpos($arr['carrier'],'联通')||strpos($arr['carrier'],'电信')){
                        $phone_location =  $arr["carrier"];
                    }else{
                        $phone_location =  $arr["province"]."其它";
                    }
                }else{
                    $phone_location =  "";
                }
            }
            echo $phone_location.'<br/>';
            $this->t_student_info->field_update_list($value['userid'],[
                "phone_location" =>$phone_location,
            ]);

        }

    }
}

