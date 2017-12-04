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
    public function test_kk(){
        $start_time = 1488297600;
        $end_time   = time();

        $first_time  = strtotime(date('Y-m-01',$start_time));
        $second_time = strtotime(date('Y-m-01',$end_time));
        $i = $first_time;
        $montharr = [];
        while($i  <= $second_time){
            $montharr[] = date('Y-m-01',$i);                                                                     
            $i = strtotime('+1 month', $i);
        }
        $i = 0;
        $subject_chinese = [];
        $subject_math = [];
        $subject_english = [];
        $date_list = [];
        foreach ($montharr as $key => $value) {
            $time1 = strtotime($value);
            $month = date('Y-m',$time1);
            $time2 = strtotime('+1 month',$time1);
            $student_num = $this->t_teacher_info->get_student_number($time1,$time2);
            $lesson_count = $this->t_manager_info->get_fulltime_teacher_lesson_count($time1,$time2);
            $cc_transfer_all = $this->t_manager_info->get_fulltime_teacher_cc_transfer($time1,$time2);
            $cc_transfer_sh = $this->t_manager_info->get_fulltime_teacher_cc_transfer($time1,$time2,1);
            $cc_transfer_wh = $this->t_manager_info->get_fulltime_teacher_cc_transfer($time1,$time2,2);
            $cc_transfer_all_per = $cc_transfer_all['all_lesson'] > 0?100 * round(100*$cc_transfer_all['order_num']/$cc_transfer_all['all_lesson'],2):0;
            if($cc_transfer_all['all_lesson']>0){
              dd($cc_transfer_all,$cc_transfer_all_per);  
            }
            $cc_transfer_sh_per      = $cc_transfer_sh['all_lesson'] > 0?100 * round(100*$cc_transfer_sh['order_num']/$cc_transfer_sh['all_lesson'],2):0;
            $cc_transfer_wh_per      = $cc_transfer_wh['all_lesson']>0?100 * round(100*$cc_transfer_wh['order_num']/$cc_transfer_wh['all_lesson'],2):0;
            $data_all = [
                "create_time" => $time1,
                "time_range" => date("Y-m-d",$time1).'--'.date("Y-m-d",$time2),
                "teacher_type" => 0,//all
                "student_num" => $student_num['stu_num'],
                "lesson_count" => $lesson_count['lesson_all'],
                "cc_transfer_per" => $cc_transfer_all_per
            ];
            $data_sh = [
                 "create_time" => $time1,
                "time_range" => date("Y-m-d",$time1).'--'.date("Y-m-d",$time2),
                "teacher_type" => 1,//sh
                "student_num" => $student_num['sh_num'],
                "lesson_count" => $lesson_count['sh_lesson_all'],
                "cc_transfer_per" => $cc_transfer_sh_per
            ];
            $data_wh = [
                 "create_time" => $time1,
                "time_range" => date("Y-m-d",$time1).'--'.date("Y-m-d",$time2),
                "teacher_type" => 2,//wh
                "student_num" => $student_num['wh_num'],
                "lesson_count" => $lesson_count['wh_lesson_all'],
                "cc_transfer_per" =>  $cc_transfer_wh_per
            ];
            $create_time = $time1;

            $ret_all = $this->t_fulltime_teacher_data->get_info_by_type_and_time(0,$create_time);
            if($ret_all>0){
                $this->t_fulltime_teacher_data->field_update_list($ret_all,$data_all);
            }else{
                $this->t_fulltime_teacher_data->row_insert($data_all);
            }

            $ret_sh = $this->t_fulltime_teacher_data->get_info_by_type_and_time(1,$create_time);
            if($ret_sh>0){
                $this->t_fulltime_teacher_data->field_update_list($ret_sh,$data_sh);
            }else{
                $this->t_fulltime_teacher_data->row_insert($data_sh);
            }

            $ret_wh = $this->t_fulltime_teacher_data->get_info_by_type_and_time(2,$create_time);
            if($ret_wh>0){
                $this->t_fulltime_teacher_data->field_update_list($ret_wh,$data_wh);
            }else{
                $this->t_fulltime_teacher_data->row_insert($data_wh);
            }

        }
    }


    /**
     * 获取API访问授权码
     * @param ak: ak from 
     * @param sk: sk from
     * @return - access_token string.
     */
    public function test_api(){
        /*
        $url = 'https://aip.baidubce.com/oauth/2.0/token';
        $post_data = array();
        $post_data['grant_type']  = 'client_credentials';
        $post_data['client_id']   = "DnWrWPzs2ttw1i4gz5Fw3DDW";
        $post_data['client_secret'] = "P3Pv2nGctlWo0aMdmhBI2BQfiFdG7aD0";

        //$res = request_post($url, $post_data);
        $paramsString = http_build_query($post_data);//生成 URL-encode 之后的请求字符串
        $content = @file_get_contents($url.'?'.$paramsString);
        $result = json_decode($content,true);
        dd($result['access_token']);
        */
        //access_token = 24.a61cb86d3bb7dec62573e2255533810d.2592000.1512616816.282335-10331868
         /*
        $url = 'https://aip.baidubce.com/oauth/2.0/token';
        $post_data = array();
        $post_data['grant_type']  = 'client_credentials';
        $post_data['client_id']   = "DnWrWPzs2ttw1i4gz5Fw3DDW";
        $post_data['client_secret'] = "P3Pv2nGctlWo0aMdmhBI2BQfiFdG7aD0";

        //$res = request_post($url, $post_data);
        $paramsString = http_build_query($post_data);//生成 URL-encode 之后的请求字符串
        $content = @file_get_contents($url.'?'.$paramsString);
        $result = json_decode($content,true);
        dd($result['access_token']);
        */
        //access_token = 24.a61cb86d3bb7dec62573e2255533810d.2592000.1512616816.282335-10331868
        $url = "https://aip.baidubce.com/rest/2.0/ocr/v1/general_basic";
        $access_token = "24.a61cb86d3bb7dec62573e2255533810d.2592000.1512616816.282335-10331868";


        //        $token = '#####调用鉴权接口获取的token#####';
        //$url = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general?access_token=' . $token;
        $url = $url .'?access_token='.$access_token;
        $img_url = "https://www.sy8.com/image/banner0.jpg";
        $img = file_get_contents($img_url);
        $img = base64_encode($img);
        $bodys = array(
            "image" => $img
        );
        $res = $this->request_post($url, $bodys);
        dd($res);
        var_dump($res);
        dd(2);
        if (!!$res) {
            $res = json_decode($res, true);
            return $res['access_token'];
        } else {
            return false;
        }
    }

    function request_post($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = $param;
        // 初始化curl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $postUrl);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // post提交方式
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        // 运行curl
        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }

    
    public function ll(){
        $ret_info = $this->t_student_info->get_finish_num_new_list(1506787200,1509465600);
    }
    public function hello_list(){
        
        $warning_list = $this->t_cr_week_month_info->get_student_list_new(1,1506787200);
        $renew_student_list = $this->t_order_info->get_renew_student_list_new(1506787200,1509465600);
        $warning_num = 0;
        if($warning_list != 0){
            $warning_list = explode(",",$warning_list);
            $warning_num = empty($warning_list) ? 0 : count($warning_list);
        }
        $arr['real_renew_num'] = empty($renew_student_list)?0: count($renew_student_list); //   实际续费学生数量
        if($arr['real_renew_num'] == 0){
            $arr['plan_renew_num'] = 0; //计划内续费学生数量
            $arr['other_renew_num'] = 0;//计划外续费学生数量
        }else{
// 			dd($warning_list);           
            $arr['plan_renew_num'] = 0;
            if(!empty($warning_list)){
                foreach($warning_list as $key => $value){

                    $userid = $value;
                    if(!empty($renew_student_list[$userid])){
                        ++$arr['plan_renew_num'];
                    }
                    /*if(isset($renew_student_list($value))){
                        ++$arr['plan_renew_num'];
                    }*/
                }
            }
            $arr['other_renew_num'] = $arr['real_renew_num'] - $arr['plan_renew_num'];
        }
        echo($arr['plan_renew_num']);
        dd($renew_student_list);
        

        $list = "53438,57625,58808,60607,62720,70502,72798,76535,77137,78663,78961,79968,80019,82064,87068,87949,88170,93576,93880,94197,94633,95563,97282,99086,99787,100580,101474,102265,103100,104761,104864,105452,109494,109613,111253,111887,113385,114104,115305,116906,117052,118248,118756,124831,127365,129393,129687,131854,135497,136949,137648,141988,142065,143199,146825,147080,148123,148193,148629,149113,150455,151208,152143,153082,155277,155472,155970,156325,156630,156938,158360,158763,158783,158967,159691,160637,160697,161341,161693,161929,163139,163295,163594,164961,164962,165566,165692,165703,165953,166031,167430,167758,167772,168650,169869,170119,170258,170899,170990,173707,174255,174861,175286,177305,177817,178851,179122,180057,181322,182334,188378,189133,189869,190585,190978,192344,198059,199145,199459,200219,200614,200874,201496,203228,211370,217827,219486,219758,220789,224581,225971,227626,229056,229559,229965,234117,234273,235081,241841,243476,244955,245274,247294,248099,250129,250208,255353,259789,263306,263686,273287,277667,284174,287480,291054,300739";
        $student = explode(",",$list);
        foreach ($student as $key => $value) {
            $ret_info = $this->t_order_info->get_info_by_userid($value);
            if($ret_info){
                echo $ret_info['userid'].'-'.$ret_info['price'].'-'.$ret_info['contract_type']."-".$ret_info['contract_status'];
                echo "<br/>";
            }
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
        $grade_list = ["(300,301,302,303)"];
        $subject_list = [2];
        echo "<table >";
                    echo "<tr>"."<td width=30px>date</td>"
                            ."<td width=30px>年级</td>"
                            ."<td width=30px>科目</td>"
                            ."<td width=200px>试听需求</td>"
                            ."<td width=30px>教材版本</td>"
                            ."<td width=30px>地区</td>";
        //foreach ($subject_list as $kkey => $kvalue) {
        foreach ($date_time as $key => $value) {
            $start_time = $value['start_time'];
            $end_time   = $value['end_time'];
            foreach ($subject_list as $kkey => $kvalue) {
            //foreach ($date_time as $key => $value) {
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
                    //echo date("Y-m-d",$start_time).'-'.date("Y-m-d",$end_time).'-'.E\Esubject::get_desc($subject).'-'.$grade.'<br/><br/><br/><br/><br/><br/>';
                    
                    foreach ($ret_info as $key => $value) {
                        echo "<tr>";
                        echo "<td width=30px>".$value['date']."</td>";
                        echo "<td width=30px>".$value['userid']."</td>";
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
        echo 1;
        echo 2;
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


    /**
     * @author    jack
     * @function  全职老师考勤
     */
    public function fulltime_teacher_work_attendance_info(){
        //list($start_time,$end_time) = $this->get_in_date_range(0,0,0,[],3);
        $start_time = 1509465600;
        $end_time   = 1512057600;


        //$adminid= $this->get_in_int_val("adminid",480 );
        $adminid_list = $this->t_manager_info->get_all_fulltime_teacherinfo();
        foreach ($adminid_list as $key => $value) {
            $adminid = $value['uid'];
            # code...
            $date_list=\App\Helper\Common::get_date_time_list($start_time, $end_time-1);
            $ret_info=$this->t_admin_card_log->get_list( 1, $start_time,$end_time,$adminid,100000,5 );
            $teacher_info = $this->t_manager_info->get_teacher_info_by_adminid($adminid);
            $teacherid = @$teacher_info["teacherid"];

            foreach ($ret_info["list"] as $item ) {
                $logtime=$item["logtime"];
                $opt_date=date("Y-m-d",$logtime);
                $date_item= &$date_list[$opt_date];
                if (!isset($date_item["start_logtime"])) {
                    $date_item["start_logtime"]=$logtime;
                    $date_item["end_logtime"]=$logtime;
                }else{
                    if ($date_item["start_logtime"] > $logtime  ) {
                        $date_item["start_logtime"] = $logtime;
                    }
                    if ($date_item["end_logtime"] < $logtime  ) {
                        $date_item["end_logtime"] = $logtime;
                    }
                }
            }

            $today_time = strtotime(date("Y-m-d",time()));
            foreach( $date_list as  &$d_item ) {
                $year=date("Y",$start_time);
                $day_time = strtotime($year."-".$d_item["title"]);
                $w = date("w",$day_time);
                $check_holiday = $this->t_festival_info->check_is_holiday($day_time);

                if (isset ( $d_item["start_logtime"]) ){
                    $d_item["work_time"]=  $d_item["end_logtime"] -  $d_item["start_logtime"] ;
                    $d_item["work_time_str"] =\App\Helper\Common::get_time_format( $d_item["work_time"]  );
                    \App\Helper\Utils::unixtime2date_for_item($d_item,"start_logtime", "_str","H:i:s");
                    \App\Helper\Utils::unixtime2date_for_item($d_item,"end_logtime" ,"_str", "H:i:s");    
                }
                if(!$check_holiday && in_array($w,[0,3,4,5,6]) && $adminid>0 && !empty($ret_info["list"]) && $day_time<$today_time){
                    $check_holiday_flag = $this->t_fulltime_teacher_attendance_list->check_is_in_holiday($teacherid,$day_time);
                    if(!$check_holiday_flag){
                        $id = $this->t_fulltime_teacher_attendance_list->check_is_exist($teacherid,$day_time);
                        if($id>0){

                            $attendance_info = $this->t_fulltime_teacher_attendance_list->field_get_list($id,"attendance_time,attendance_type,off_time,delay_work_time");
                            $attendance_type = $attendance_info["attendance_type"];
                            if($attendance_type==2){
                                if (isset ( $d_item["start_logtime"]) ){              
                                    $off_time = $attendance_info["off_time"]==0?($day_time+9.5*3600):$attendance_info["off_time"];                              
                                    $delay_time = $attendance_info["delay_work_time"]==0?($day_time+18.5*3600):$attendance_info["delay_work_time"];
                                    if($off_time < $d_item["start_logtime"] ||  $delay_time> $d_item["end_logtime"]){
                                        $d_item["error_flag"]=true;
                                        $d_item["error_flag_str"] ="是"; 
                                    }

                                }else{
                                    $d_item["error_flag"]=true;
                                    $d_item["error_flag_str"] ="是";
                                }

                            }
                           
     
                        }else{
                            if (isset ( $d_item["start_logtime"]) ){              
                                $off_time = $day_time+9.5*3600;                              
                                $delay_time = $day_time+18.5*3600;
                                if($off_time < $d_item["start_logtime"] ||  $delay_time> $d_item["end_logtime"]){
                                    $d_item["error_flag"]=true;
                                    $d_item["error_flag_str"] ="是"; 
                                }
                                // $d_item["error_flag"]= ($d_item["work_time"] < 9*3600);
                                // if ($d_item["error_flag"]) {
                                //     $d_item["error_flag_str"] ="是";
                                // }
                            }else{
                                $d_item["error_flag"]=true;
                                $d_item["error_flag_str"] ="是";
                            }
     
                        }
                    }
                    
                }
            }
            echo "<table >";
            echo "<tr><td>姓名</td><td>日期</td><td>开始</td><td>结束</td> <td>间隔</td><td>异常</td></tr>";
            foreach ($date_list as $var) {
                # code...
                echo "<tr>";
                echo "<td>".@$value['name']."</td>";
                echo "<td>".@$var["title"]."</td>";
                echo "<td>".@$var["start_logtime_str"]."</td>";
                echo "<td>".@$var["end_logtime_str"]."</td>";
                echo "<td>".@$var["work_time_str"]."</td>";
                echo "<td>".@$var["error_flag_str"]."</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<tr/>";
        }

    }
}

