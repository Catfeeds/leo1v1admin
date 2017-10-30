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
    public function total_student(){
        echo '------------------------------Student Total Number--------------'.'<br/>';
        $ret_info = $this->t_cr_week_month_info->get_total_province(-1,1509163200);
        $province = [];
        $province['总计'] = 0;
        $province['其它'] = 0;

        foreach($ret_info as $key => $value){
            if($value['phone_location'] == "鹏博士" || $value['phone_location'] == '' || $value['phone_location'] == '免商店充值卡' || $value['phone_location'] == '中麦通信' ||$value['phone_location'] == '重庆U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '小米移动' || $value['phone_location'] == '北京U友' || $value['phone_location'] == "全国其它 " || $value['phone_location'] == '话机通信' || $value['phone_location'] == '阿里通信' || $value['phone_location'] == '辽宁U友'){
                $province['总计'] += $value['total'];
                $province['其它'] += $value['total'];
                
            }else{
                $pro = substr($value['phone_location'],0,strlen($value['phone_location'])-6);
                if(!isset($province[$pro])){
                    $province[$pro] = 0;
                    $province[$pro] += $value['total'];
                    $province['总计'] += $value['total'];
                }else{
                    $province[$pro] += $value['total'];
                    $province['总计'] += $value['total'];
                }

            }
        }
        foreach ($province as $key => $value) {
            echo $key."|".$value."<br/>";
        }
    }
    public function total_teacher(){
        echo '------------------------------Teacher Total Number--------------'.'<br/>';
        $ret_info_teacher = $this->t_cr_week_month_info->get_total_province_teacher(0,1509163200);
        $province_teacher = [];
        $province_teacher['总计'] = 0;
        $province_teacher['其它'] = 0;

        foreach($ret_info_teacher as $key => $value){
            if($value['phone_location'] == "鹏博士" || $value['phone_location'] == '' || $value['phone_location'] == '免商店充值卡' || $value['phone_location'] == '中麦通信' ||$value['phone_location'] == '重庆U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '小米移动' || $value['phone_location'] == '北京U友' || $value['phone_location'] == "全国其它 " || $value['phone_location'] == '话机通信' || $value['phone_location'] == '阿里通信' || $value['phone_location'] == '辽宁U友'){

                $province_teacher['其它'] += $value['total'];
                $province_teacher['总计'] += $value['total'];
            }else{
                $teacher_pro = substr($value['phone_location'],0,strlen($value['phone_location'])-6);
                if(!isset($province_teacher[$teacher_pro])){
                    $province_teacher[$teacher_pro] = 0;
                    $province_teacher[$teacher_pro] += $value['total'];
                    $province_teacher['总计'] += $value['total'];
                }else{
                    $province_teacher[$teacher_pro] += $value['total'];
                    $province_teacher['总计'] += $value['total'];
                }

            }
        }
        foreach ($province_teacher as $key => $value) {
            echo $key."|".$value."<br/>";
        }
    }
    public function total_lesson_student(){
        echo "------------------------------------Student 8-1 0:0:0- 10-28 12:0:0------------------------------------------"."<br/>";
        $ret_info_lesson_student = $this->t_cr_week_month_info->get_total_province_lesson_student(1501516800,1509163200);
        $province_lesson_student = [];
        $province_lesson_student['总计'] = 0;
        $province_lesson_student['其它'] = 0;

        foreach($ret_info_lesson_student as $key => $value){
            if($value['phone_location'] == "鹏博士" || $value['phone_location'] == '' || $value['phone_location'] == '免商店充值卡' || $value['phone_location'] == '中麦通信' ||$value['phone_location'] == '重庆U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '小米移动' || $value['phone_location'] == '北京U友' || $value['phone_location'] == "全国其它 " || $value['phone_location'] == '话机通信' || $value['phone_location'] == '阿里通信' || $value['phone_location'] == '辽宁U友'){

                $province_lesson_student['其它'] += $value['total'];
                $province_lesson_student['总计'] += $value['total'];
            }else{
                $student_lesson_pro = substr($value['phone_location'],0,strlen($value['phone_location'])-6);
                if(!isset($province_lesson_student[$student_lesson_pro])){
                    $province_lesson_student[$student_lesson_pro] = 0;
                    $province_lesson_student[$student_lesson_pro] += $value['total'];
                    $province_lesson_student['总计'] += $value['total'];
                }else{
                    $province_lesson_student[$student_lesson_pro] += $value['total'];
                    $province_lesson_student['总计'] += $value['total'];
                }

            }
        }
        foreach ($province_lesson_student as $key => $value) {
            echo $key."|".$value."<br/>";
        }
    }
    public function tt(){
    
    }
    public function lesson_list()
    {
        $month_list = [
                        '2017-09',
                        '2017-10'];
        for ($i=0; $i < 12; $i++) { 
            # code...
            $start_time = strtotime($month_list[$i]);
            $end_time   = strtotime($month_list[$i+1]);
            //var_dump(date("Y-m-d H:i:s",$start_time));
            //echo '<br/>';
            $ret_info = $this->t_cr_week_month_info->get_total_order($start_time,$end_time);
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
        }
    }
    
    private function get_lesson_quiz_cfg($lesson_quiz_status, $lesson_type)
    {
    }
    public function manager_list()
    {
    }
    public function test(){
        
    }
    public function total_lesson_teacher(){
    
        //$start_time = 1504195200;
        //$end_time   = 1506787200;
        
        
        echo "------------------------------------Teacher 8-1 0:0:0- 10-28 12:0:0------------------------------------------"."<br/>";
        $ret_info_lesson_teacher = $this->t_cr_week_month_info->get_total_province_lesson_teacher(1501516800,1509163200);
        $province_lesson_teacher = [];
        $province_lesson_teacher['总计'] = 0;
        $province_lesson_teacher['其它'] = 0;

        foreach($ret_info_lesson_teacher as $key => $value){
            if($value['phone_location'] == "鹏博士" || $value['phone_location'] == '' || $value['phone_location'] == '免商店充值卡' || $value['phone_location'] == '中麦通信' ||$value['phone_location'] == '重庆U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '小米移动' || $value['phone_location'] == '北京U友' || $value['phone_location'] == "全国其它 " || $value['phone_location'] == '话机通信' || $value['phone_location'] == '阿里通信' || $value['phone_location'] == '辽宁U友'){

                $province_lesson_teacher['其它'] += $value['total'];
                $province_lesson_teacher['总计'] += $value['total'];
            }else{
                $teacher_lesson_pro = substr($value['phone_location'],0,strlen($value['phone_location'])-6);
                if(!isset($province_lesson_teacher[$teacher_lesson_pro])){
                    $province_lesson_teacher[$teacher_lesson_pro] = 0;
                    $province_lesson_teacher[$teacher_lesson_pro] += $value['total'];
                    $province_lesson_teacher['总计'] += $value['total'];
                }else{
                    $province_lesson_teacher[$teacher_lesson_pro] += $value['total'];
                    $province_lesson_teacher['总计'] += $value['total'];
                }

            }
        }
        foreach ($province_lesson_teacher as $key => $value) {
            echo $key."|".$value."<br/>";
        }
       

    }

    public function kk(){
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
        //$ret_info = $this->t_cr_week_month_info->get_teacher_info();
        $ret_info = $this->t_cr_week_month_info->get_lesson_teacher_info();
        foreach ($ret_info as $key => $value) {
            $phone=trim($value['phone']);
            if ($phone =="" ) {
                $phone_location = "" ;
            }elseif($value['phone_location'] != ''){
                $phone_location = $value['phone_location'];
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
            $this->t_teacher_info->field_update_list($value['teacherid'],[
                "phone_location" =>$phone_location,
            ]);
        }

    }

    public function total_test_lesson_phone(){
        $time = [
            [
                'start_time' => 1501516800,
                'end_time'   => 1504195200,
            ],
            [
                'start_time' => 1504195200,
                'end_time'   => 1506787200,
            ],
            [
                'start_time' => 1506787200,
                'end_time'   => 1509465600,
            ]
        ];
                echo "---------------------------------------"."<br/>";
        foreach ($time as $key => $value) {
            $start_time = $value['start_time'];
            $end_time   = $value['end_time'];
            $phone_location_list = $this->t_cr_week_month_info->get_test_lesson_subject($start_time,$end_time);
            $new_list = [];
            foreach (E\Esubject::$desc_map as $key => $value) {
                 $new_list['其它'][$key] = 0;
            }
            foreach($phone_location_list as $key => $value){
                if($value['phone_location'] == "鹏博士" || $value['phone_location'] == '' || $value['phone_location'] == '免商店充值卡' || $value['phone_location'] == '中麦通信' ||$value['phone_location'] == '重庆U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '小米移动' || $value['phone_location'] == '北京U友' || $value['phone_location'] == "全国其它 " || $value['phone_location'] == '话机通信' || $value['phone_location'] == '阿里通信' || $value['phone_location'] == '辽宁U友'){

                    $new_list['其它'][$value['subject']] += $value['total'];
                    //$province_lesson_student['总计'] += $value['total'];
                }else{
                    $pro = substr($value['phone_location'],0,strlen($value['phone_location'])-6);

                    if(!isset($new_list[$pro])){
                        foreach (E\Esubject::$desc_map as $kaey => $vaalue) {
                            if(!isset($new_list[$pro][$kaey])){
                                $new_list[$pro][$kaey] = '';
                            }
                        }
                        $new_list[$pro][$value['subject']] = 0;
                        $new_list[$pro][$value['subject']] += $value['total'];
                    }else{
                        $new_list[$pro][$value['subject']] += $value['total'];
                    }
                }
            }

            //dd($list);
            echo date("Y-m-d H:i:s",$start_time).'----'.date("Y-m-d H:i:s",$end_time)."<br/>";
            echo "<table >";
            echo "<tr><th >城市|</th>";
            foreach (E\Esubject::$desc_map as $akkey => $akvalue) {
                echo "<th>".E\Esubject::get_desc($akkey)."|</th>";
            }
            echo "</tr>";
            foreach ($new_list as $akey => $avalue) {
                $row = $avalue;
                echo "<tr>";
                echo "<td width=60px>".E\Egrade::get_desc($akey)."|</td>";
                foreach (E\Esubject::$desc_map as $bkey => $bvalue) {
                    if(isset($new_list[$akey][$bkey])){
                        echo "<td width=60px>".$new_list[$akey][$bkey]."|</td>";
                    }else{
                        echo "<td width=60px>"."|</td>";
                    }
                }

                echo "</tr>";
            }
            echo "</table>";
            echo "<br />";
            echo "<br/>";
        }
    }
    public function total_test_lesson_grade(){
        $time = [
            [
                'start_time' => 1501516800,
                'end_time'   => 1504195200,
            ],
            [
                'start_time' => 1504195200,
                'end_time'   => 1506787200,
            ],
            [
                'start_time' => 1506787200,
                'end_time'   => 1509465600,
            ]
        ];
        
        foreach ($time as $key => $value) {
            $start_time = $value['start_time'];
            $end_time   = $value['end_time'];
            $subject_list = $this->t_cr_week_month_info->get_test_lesson($start_time,$end_time);
            $list = [];
            foreach (E\Esubject::$desc_map as $key => $value) {
                foreach (E\Egrade::$desc_map as $kkey => $kvalue) {
                    $list[$kkey][$key] = '';
                }
            }
            foreach ($subject_list as $key => $value) {
                if(isset($list[$value['grade']][$value['subject']])){
                    $list[$value['grade']][$value['subject']] = $value['total'];
                }else{
                    //var_dump($value);
                }
            }
            //dd($list);
            echo date("Y-m-d H:i:s",$start_time).'----'.date("Y-m-d H:i:s",$end_time)."<br/>";
            echo "<table >";
            echo "<tr><th >|</th>";
            foreach (E\Esubject::$desc_map as $akkey => $akvalue) {
                echo "<th>".E\Esubject::get_desc($akkey)."|</th>";
            }
            echo "</tr>";
            foreach ($list as $akey => $avalue) {
                $row = $avalue;
                echo "<tr>";
                echo "<td>".E\Egrade::get_desc($akey)."|</td>";
                foreach ($row as $bkey => $bvalue) {
                    echo "<td width=60px>".$bvalue."|</td>";
                }

                echo "</tr>";
            }
            echo "</table>";
            echo "<br />";
            echo "<br/>";
        }
    }
}

