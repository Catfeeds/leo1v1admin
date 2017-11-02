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
    public function hello_list(){
        $list = "53438,57625,58808,60607,62720,70502,72798,76535,77137,78663,78961,79968,80019,82064,87068,87949,88170,93576,93880,94197,94633,95563,97282,99086,99787,100580,101474,102265,103100,104761,104864,105452,109494,109613,111253,111887,113385,114104,115305,116906,117052,118248,118756,124831,127365,129393,129687,131854,135497,136949,137648,141988,142065,143199,146825,147080,148123,148193,148629,149113,150455,151208,152143,153082,155277,155472,155970,156325,156630,156938,158360,158763,158783,158967,159691,160637,160697,161341,161693,161929,163139,163295,163594,164961,164962,165566,165692,165703,165953,166031,167430,167758,167772,168650,169869,170119,170258,170899,170990,173707,174255,174861,175286,177305,177817,178851,179122,180057,181322,182334,188378,189133,189869,190585,190978,192344,198059,199145,199459,200219,200614,200874,201496,203228,211370,217827,219486,219758,220789,224581,225971,227626,229056,229559,229965,234117,234273,235081,241841,243476,244955,245274,247294,248099,250129,250208,255353,259789,263306,263686,273287,277667,284174,287480,291054,300739";
        $student = explode(",",$list);
        foreach ($student as $key => $value) {
            $ret_info = $this->t_order_info->get_info_by_userid($value);
            echo "<pre>";
            var_dump($ret_info);
            echo "</pre>";
        }
        dd($student);
    }
    public function hello_li(){
        $date_time = [
            [
                "start_time" => 1493568000, //5-1
                "end_time"   => 1496246400, //6-1
            ],
            
            [
                "start_time" => 1496246400, //6-1
                "end_time"   => 1498838400, //7-1
            ],
            
            [
                "start_time" => 1498838400, //7-1
                "end_time"   => 1501516800, //8-1
            ],

            [
                "start_time" => 1501516800, //8-1
                "end_time"   => 1504195200, //9-1
            ],
            [
                "start_time" => 1504195200, //9-1
                "end_time"   => 1506787200, //10-1
            ],
            [
                "start_time" => 1506787200, //10-1
                "end_time"   => 1509465600, //11-1
            ],
            /*
            */
        ];
        $grade_list = ["(100,101,102,103,104,105,106)","(200,201,202,203)","(300,301,302,303)"];
        $subject_list = [1,2,3,4,5,10];
        echo "<table >";
                    echo "<tr>"."<td width=30px>date</td>"
                            ."<td width=30px>年级</td>"
                            ."<td width=30px>科目</td>"
                            ."<td width=200px>试听需求</td>"
                            ."<td width=30px>教材版本</td>"
                            ."<td width=30px>地区</td>";

        foreach ($date_time as $key => $value) {
            $start_time = $value['start_time'];
            $end_time   = $value['end_time'];
            foreach ($subject_list as $kkey => $kvalue) {
                $subject = $kvalue;
                
                foreach ($grade_list as $vkey => $vvalue) {
                    $grade = "a.grade in ".$vvalue;
                    //echo date("Y-m-d",$start_time).'-'.date("Y-m-d",$end_time).'-'.E\Esubject::get_desc($subject).'-'.$grade.'<br/>';
                    $ret_info = $this->t_cr_week_month_info->get_apply_info_month($start_time,$end_time,$subject,$grade);
                    foreach ($ret_info as $key => &$value) {
                        $value['date'] = date("Y-m-d",$value['stu_request_test_lesson_time']);
                        $value['grade_str'] = E\Egrade::get_desc($value['grade']);
                        $value['subject_str'] = E\Esubject::get_desc($value['subject']);
                        if($value['phone_location'] == "鹏博士" || $value['phone_location'] == '' || $value['phone_location'] == '免商店充值卡' || $value['phone_location'] == '中麦通信' ||$value['phone_location'] == '重庆U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '小米移动' || $value['phone_location'] == '北京U友' || $value['phone_location'] == "全国其它 " || $value['phone_location'] == '话机通信' || $value['phone_location'] == '阿里通信' || $value['phone_location'] == '辽宁U友'){
                            $value['phone_location'] = "其它";
                        }else{
                            $pro = substr($value['phone_location'],0,strlen($value['phone_location'])-6);
                            $value['phone_location'] = $pro;
                        }
                    }
                   // echo date("Y-m-d",$start_time).'-'.date("Y-m-d",$end_time).'-'.E\Esubject::get_desc($subject).'-'.$grade.'<br/><br/><br/><br/><br/><br/>';
                    
                    foreach ($ret_info as $key => $value) {
                        echo "<tr>";
                        echo "<td width=30px>".$value['date']."</td>";
                        echo "<td width=30px>".$value['grade_str']."</td>";
                        echo "<td width=30px>".$value['subject_str']."</td>";
                        echo "<td width=200px>".$value['stu_request_test_lesson_demand']."</td>";
                        echo "<td width=30px>".$value['textbook']."</td>";
                        echo "<td width=30px>".$value['phone_location']."</td>";
                        echo "</tr>";
                    }
                }
            }
        }
        echo "</table>"; 
    }

    public function hello_world(){
        $ret_info = $this->t_cr_week_month_info->get_apply_info_a1();
        foreach ($ret_info as $key => &$value) {
            $value['grade_str'] = E\Egrade::get_desc($value['grade']);
            $value['subject_str'] = E\Esubject::get_desc($value['subject']);
            if($value['phone_location'] == "鹏博士" || $value['phone_location'] == '' || $value['phone_location'] == '免商店充值卡' || $value['phone_location'] == '中麦通信' ||$value['phone_location'] == '重庆U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '小米移动' || $value['phone_location'] == '北京U友' || $value['phone_location'] == "全国其它 " || $value['phone_location'] == '话机通信' || $value['phone_location'] == '阿里通信' || $value['phone_location'] == '辽宁U友'){
                $value['phone_location'] = "其它";
            }else{
                $pro = substr($value['phone_location'],0,strlen($value['phone_location'])-6);
                $value['phone_location'] = $pro;
            }
            if($value['lesson_user_online_status'] == 0 ){
                $value['lesson_user_online_status_str'] = "未设置"; 
            }elseif($value['lesson_user_online_status'] == 1){
                $value['lesson_user_online_status_str'] = "有效";
            }else{
                $value['lesson_user_online_status_str'] = "无效";
            }

            if($value['price'] > 0 and $value['contract_status'] != 0){
                $value['status_str'] = "有效";
            }else{
                $value['status_str'] = "无效";
            }
        }
        echo "<table >";
        echo "<tr>"."<td width=30px>ID|</td>"
                ."<td width=30px>姓名|</td>"
                ."<td width=30px>年级|</td>"
                ."<td width=30px>科目|</td>"
                ."<td width=200px>试听需求|</td>"
                ."<td width=30px>教材版本|</td>"
                ."<td width=30px>地区|</td>"
                ."<td width=30px>status|</td>"
                ."<td width=30px>order_status</td></tr>";
        foreach ($ret_info as $key => $value) {
            echo "<tr>";
            echo "<td width=30px>".$value['userid']."|</td>";
            echo "<td width=30px>".$value['nick']."|</td>";
            echo "<td width=30px>".$value['grade_str']."|</td>";
            echo "<td width=30px>".$value['subject_str']."|</td>";
            echo "<td width=200px>".$value['stu_request_test_lesson_demand']."|</td>";
            echo "<td width=30px>".$value['textbook']."|</td>";
            echo "<td width=30px>".$value['phone_location']."|</td>";
            echo "<td width=30px>".$value['lesson_user_online_status_str']."|</td>";
            echo "<td width=30px>".$value['status_str']."</td>";
            echo "</tr>";
        }
        echo "</table>";    
    }

    public function world(){
        $ret_info = $this->t_cr_week_month_info->get_all_teacher_info_total();
        $ret_info_success  = $this->t_cr_week_month_info->get_all_teacher_info_success();
        //dd($ret_info_success);
        foreach ($ret_info as $key => &$value) {
            # code...
            if(isset($ret_info_success[$key])){
                unset($ret_info[$key]);
            }
        }

        foreach ($ret_info as $key => $value) {
            if($value['phone_location'] == "鹏博士" || $value['phone_location'] == '' || $value['phone_location'] == '免商店充值卡' || $value['phone_location'] == '中麦通信' ||$value['phone_location'] == '重庆U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '小米移动' || $value['phone_location'] == '北京U友' || $value['phone_location'] == "全国其它 " || $value['phone_location'] == '话机通信' || $value['phone_location'] == '阿里通信' || $value['phone_location'] == '辽宁U友'){
                if(isset($province['其它'])){
                     ++$province['其它'] ;
                }else{
                    $province['其它'] = 0;
                    ++$province['其它'] ;
                }
            }else{
                $pro = substr($value['phone_location'],0,strlen($value['phone_location'])-6);
                if(!isset($province[$pro])){
                    $province[$pro] = 0;
                    ++$province[$pro];
                }else{
                    ++$province[$pro];
                }

            }
        }
        echo "<table>";
        foreach ($province as $key => $value) {
            echo "<tr>";
            echo "<td width=100px>".$key."</td>";
            echo "<td width=100px>".$value."</td>";
            echo "</tr>";
        }
        echo "</table>";

        foreach ($ret_info as $key => $value) {
            if($value['subject'] == '' || $value['subject'] < 0 || $value['subject'] > 11){
                $subject_str = "未设置";
            }else{
                $subject_str = E\Esubject::get_desc($value['subject']);
            }
            
            if(isset($subject[$subject_str])){
                ++$subject[$subject_str];
            }else{
                $subject[$subject_str] = 0;
                ++$subject[$subject_str];
            }
        }

        echo '----------------------------------------------'."<br/>";
        echo "<table>";
        foreach ($subject as $key => $value) {
            echo "<tr>";
            echo "<td width=100px>".$key."</td>";
            echo "<td width=100px>".$value."</td>";
            echo "</tr>";
        }
        echo "</table>";
    }


    public function hello(){
        $ret_info = $this->t_cr_week_month_info->get_apply_info();
        foreach ($ret_info as $key => &$value) {
            $value['grade_str'] = E\Egrade::get_desc($value['grade']);
            $value['subject_str'] = E\Esubject::get_desc($value['subject']);
            if($value['phone_location'] == "鹏博士" || $value['phone_location'] == '' || $value['phone_location'] == '免商店充值卡' || $value['phone_location'] == '中麦通信' ||$value['phone_location'] == '重庆U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '江苏U友' || $value['phone_location'] == '小米移动' || $value['phone_location'] == '北京U友' || $value['phone_location'] == "全国其它 " || $value['phone_location'] == '话机通信' || $value['phone_location'] == '阿里通信' || $value['phone_location'] == '辽宁U友'){
                $value['phone_location'] = "其它";
            }else{
                $pro = substr($value['phone_location'],0,strlen($value['phone_location'])-6);
                $value['phone_location'] = $pro;
            }
            if($value['lesson_user_online_status'] == 0 ){
                $value['lesson_user_online_status_str'] = "未设置"; 
            }elseif($value['lesson_user_online_status'] == 1){
                $value['lesson_user_online_status_str'] = "有效";
            }else{
                $value['lesson_user_online_status_str'] = "无效";
            }

            if($value['price'] > 0 and $value['contract_status'] != 0){
                $value['status_str'] = "有效";
            }else{
                $value['status_str'] = "无效";
            }
        }
        echo "<table >";
        echo "<tr>"."<td width=30px>ID|</td>"
                ."<td width=30px>姓名|</td>"
                ."<td width=30px>年级|</td>"
                ."<td width=30px>科目|</td>"
                ."<td width=200px>试听需求|</td>"
                ."<td width=30px>教材版本|</td>"
                ."<td width=30px>地区|</td>"
                ."<td width=30px>status|</td>"
                ."<td width=30px>order_status</td></tr>";
        foreach ($ret_info as $key => $value) {
            echo "<tr>";
            echo "<td width=30px>".$value['userid']."|</td>";
            echo "<td width=30px>".$value['nick']."|</td>";
            echo "<td width=30px>".$value['grade_str']."|</td>";
            echo "<td width=30px>".$value['subject_str']."|</td>";
            echo "<td width=200px>".$value['stu_request_test_lesson_demand']."|</td>";
            echo "<td width=30px>".$value['textbook']."|</td>";
            echo "<td width=30px>".$value['phone_location']."|</td>";
            echo "<td width=30px>".$value['lesson_user_online_status_str']."|</td>";
            echo "<td width=30px>".$value['status_str']."</td>";
            echo "</tr>";
        }
        echo "</table>";    
    }







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

