<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Libs;
use \App\Helper\Config;
use Illuminate\Support\Facades\Redis ;

class test_code extends Controller
{

    use CacheNick;
    use TeaPower;
    var $br;
    var $red;
    var $blue;
    var $div;
    var $teacherid;

    var $level_simulate_count_key = "level_simulate_count";
    var $all_money_count_key      = "all_money_count";
    var $has_month_key            = "has_month";
    var $teacher_ref_rate_key     = "teacher_ref_rate";
    var $month_money_key          = "month_money";
    var $lesson_total_key         = "lesson_total";

    var $already_lesson_count_key          = "already_lesson_count_month";
    var $already_lesson_count_simulate_key = "already_lesson_count_simulate_month";
    var $money_month_key                   = "money_month";
    var $teacher_money_type_month_key      = "teacher_money_type_month";

    public function __construct(){
        // $this->switch_tongji_database();
        $this->br="<br>";
        $this->red="<div color=\"red\">";
        $this->blue="<div color=\"blue\">";
        $this->div="</div>";
        if(\App\Helper\Utils::check_env_is_release()){
            $this->teacherid="50728";
        }else{
            $this->teacherid="60024";
        }
    }

    public function get_b_txt($file_name="b"){
        $info = file_get_contents("/tmp/".$file_name.".txt");
        $arr  = explode("\n",$info);
        return $arr;
    }

    /**
     * 批量导入公开课学生
     */
    public function add_open_user(){
        $num = 0;
        foreach($user_info as $val){
            if($val!=""){
                $user_arr = explode("|",$val);
                if(preg_match("/^1\d{10}$/",$user_arr[1])){
                    $userid = $this->t_student_info->get_userid_by_phone($user_arr[1]);
                    if(!$userid){
                        $num++;
                        echo $num.$br;
                        echo $red."reg :".$user_arr[1].$div;
                        $userid = $this->t_user_info->user_reg($passwd,0,0);
                        $this->t_phone_to_user->add($user_arr[1],1,$userid);
                        $this->t_student_info->add_student($userid,$user_arr[2],$user_arr[1],$user_arr[0],0);
                        $this->users->add_ejabberd_info($userid,$passwd);
                    }

                    if($userid){
                        $job = new \App\Jobs\add_lesson_user($lessonid_list[$user_arr[2]],$userid);
                        dispatch($job);
                    }
                }
            }
        }
    }

    /**
     * 添加老师工资分类
     */
    public function add_money_type(){
        $file    = "/tmp/bb.txt";
        $content = file_get_contents($file); //读取文件中的内容
        $info    = explode("\n",$content);
        $br      = "<br>";
        $red     = "<div style=\"color:red\">";
        $div     = "</div>".$br;

        \App\Helper\Utils::debug_to_html( $info );

        /**
         * 0 teacher_money_type 1 level 2 grade 3 money 4 type
         */
        foreach($info as $val){
            $arr=explode("|",$val);
            if(is_array($arr)){
                if($arr[0]!=""){
                    $check_flag=$this->t_teacher_money_type->check_is_exists($arr[0],$arr[1],$arr[2]);
                    if(!$check_flag){
                        $this->t_teacher_money_type->row_insert([
                            "teacher_money_type" => $arr[0],
                            "level"              => $arr[1],
                            "grade"              => $arr[2],
                            "money"              => $arr[3],
                            "type"               => $arr[4],
                        ]);
                        echo $red;
                        echo "teacher_money_type:".$arr[0]." level:".$arr[1]." grade:".$arr[2]." money:".$arr[3]." type:".$arr[4];
                        echo $div;
                    }
                }
            }
        }
    }


    function GetIp(){
        $realip = '';
        $unknown = 'unknown';
        if (isset($_SERVER)){
            if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach($arr as $ip){
                    $ip = trim($ip);
                    if ($ip != 'unknown'){
                        $realip = $ip;
                        break;
                    }
                }
            }else if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)){
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            }else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)){
                $realip = $_SERVER['REMOTE_ADDR'];
            }else{
                $realip = $unknown;
            }
        }else{
            if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)){
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            }else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)){
                $realip = getenv("HTTP_CLIENT_IP");
            }else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)){
                $realip = getenv("REMOTE_ADDR");
            }else{
                $realip = $unknown;
            }
        }
        $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;
        return $realip;
    }

    /**
     * 新浪接口
     */
    function GetIpLookup($ip = ''){
        if(empty($ip)){
            $ip = GetIp();
        }
        $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
        if(empty($res)){ return false; }
        $jsonMatches = array();
        preg_match('#\{.+?\}#', $res, $jsonMatches);
        if(!isset($jsonMatches[0])){ return false; }
        $json = json_decode($jsonMatches[0], true);
        if(isset($json['ret']) && $json['ret'] == 1){
            $json['ip'] = $ip;
            unset($json['ret']);
        }else{
            return false;
        }
        return $json;
    }

    public function set_teacher_address(){
        $tea_list = $this->t_teacher_info->get_teacher_simple_list();
        foreach($tea_list as $val){
            if($val['address']==""){
                $address = $this->get_mobile_area($val['phone']);
                if($address){
                    $this->t_teacher_info->field_update_list($val['teacherid'],[
                        "address"=>$address
                    ]);
                }
            }
        }
    }

    /**
     * 从淘宝接口获取电话号码所在地
     */
    function get_mobile_area($mobile){
        $url = "https://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=".$mobile;

        $content  = file_get_contents($url);
        $content  = mb_convert_encoding( $content, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
        $str      = strstr($content,"province");
        $province = mb_substr($str,10,2,"utf-8");

        return $province;
    }

    public function get_test_lesson(){
        return $this->output_succ();
        $begin_date = "2016-12-1";
        $begin_time = strtotime($begin_date);

        $this->t_test_lesson_subject_sub_list->switch_tongji_database();
        $list = $this->t_test_lesson_subject_sub_list->get_teacher_trial_success_list($begin_time);

        echo "<pre>";
        var_dump($list);
        echo "</pre>";
        exit;


        $begin_time = strtotime("2017-2-1 10:00");
        foreach($list as $val){
            $time = strtotime($val['last_modified_time']);
            if($time<$begin_time){
                $time = $begin_time;
            }

        }
    }

    /*
      测试发送微信推送信息
     */
    public function test_template(){
        $teacherid=$this->get_in_int_val("teacherid");
        if($teacherid==0){
            echo "teacherid is 0";
            exit;
        }

        // $tea_list['wx_openid'] = $this->t_teacher_info->get_wx_openid($teacherid);
        // $time     = strtotime("2017-4-21");
        $tea_list = $this->t_teacher_info->get_tea_wx_openid_list();
        foreach($tea_list as $val){
            $user_agent = json_decode($val['user_agent'],true);
            $os         = substr($user_agent["device_model"],0,4);
            $version    = $user_agent["version"];
            // if($os=="Win" || $os=="Mac"){
            //     if($version != "3.1.0"){
            //         $push_list[] = $val;
            //     }
            // }
            if($os=="iPad"){
                if($version != "5.0.4"){
                    $push_list[] = $val;
                }
            }
        }

        echo "<pre>";
        var_dump($push_list);
        echo "</pre>";
        exit;

        $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
        $data['first']    = "您有新的客户端版本等待更新";
        $data['keyword1'] = "【iPad老师端 5.0.4版本】";
        $data['keyword2'] = "\n-增加课堂中一键pdf上传功能";
        $data['keyword3'] = date("Y-m-d",time());
        $data['remark']   = "为了保证授课正常进行，请您参考【帮助中心】的【使用手册】教程尽快更新。";
        $url              = "http://admin.yb1v1.com/article_wx/leo_teacher_ipad";

        // \App\Helper\Utils::send_teacher_msg_for_wx($tea_list['wx_openid'] ,$template_id,$data,$url);
        // $job = new \App\Jobs\SendTeacherWx($tea_list,$template_id,$data,$url);
        // dispatch($job);
    }

    /**
     * 转介绍最少成功一人切双方在读
     * 12月份开始至4月8日
     */
    public function zhuan_1(){
        $start_time = strtotime("2016-12-1");
        $end_time   = strtotime("2017-4-8");
        $list       = $this->t_student_info->get_zhuan_1($start_time,$end_time);

        foreach($list as $val){
            $ass_nick=$this->cache_get_assistant_nick($val['assistantid']);
            echo "姓名：".$val['nick']." 成功学生数：".$val['stu_num']." 剩余课时数：".($val['lesson_count_left']/100)." 助教：".$ass_nick;
            echo "<br>";
        }
    }

    /**
     * 1-3月份合同总课时以及总金额
     */
    public function get_order_info(){
        $start     = strtotime("2017-1-1");
        $shut_time = strtotime("2017-4-1");
        $end       = 0;
        for($i=0;$end<$shut_time;$i++){
            $end   = strtotime("+1 month",$start);
            if($start==1483200000){
                $start=strtotime("2017-1-4");
            }
            $list  = $this->t_order_info->get_order_total_price($start,$end);
            echo "月份:".date("m",$start)."  总价格:".($list['all_price']/100)."  总课时:".($list['lesson_total']/100);
            echo "<br>";
            $start = $end;
        }
    }

    /**
     * 12,1,2,3月份的试听申请;成功，未设置课程数;签单数，签单课时及金额;
     */
    public function test_plan_data(){
        $num=$this->get_in_int_val("num");
        $begin_date = strtotime("2016-12-1");
        $start_time = strtotime("+$num month",$begin_date);
        $num++;
        $end_time   = strtotime("+$num month",$begin_date);
        echo "start_time|".date("Y-m-d",$start_time)."|end_time|".date("Y-m-d",$end_time);
        echo "<br>";

        $list = $this->t_test_lesson_subject_require->get_test_plan_data($start_time,$end_time);
        $lesson_num      = 0;
        $not_set_lesson_num= 0;
        $succ_lesson_num = 0;
        $all_price       = 0;
        $all_total       = 0;
        $order_num       = 0;
        foreach($list as $val){
            if(isset($val['current_lessonid']) && $val['current_lessonid']>0){
                $lesson_num++;
            }

            if(isset($val['success_flag'])){
                if($val['success_flag']==0){
                    $not_set_lesson_num++;
                }
                if($val['tea_attend']>0 && $val['stu_attend']>0){
                    $succ_lesson_num++;
                }
            }
            if(isset($val['orderid']) && $val['orderid']>0){
                $order_num++;
            }
        }
        $order_list=$this->t_order_info->get_test_plan_order($start_time,$end_time);
        foreach($order_list as $val){
            if(isset($val['price'])){
                $all_price+=($val['price']/100);
            }
            if(isset($val['lesson_total'])){
                $all_total+=($val['lesson_total']*$val['default_lesson_count']/100);
            }
        }
        echo "lesson_num|".$lesson_num."|not_set_lesson_num|".$not_set_lesson_num
                          ."|succ_lesson_num|".$succ_lesson_num
                          ."|order_num|".$order_num
                          ."|all_price|".$all_price
                          ."|all_total|".$all_total
                          ;
    }


    /**
     * 被转介绍用户是否签单，签单课时，签单金额
     */
    public function origin_user(){
        $userid = $this->get_in_int_val("userid");
        $type   = $this->get_in_int_val("type",1);
        if($userid==0){
            echo "userid 为 0";
            exit;
        }

        $list = $this->t_student_info->get_orgin_user($userid,$type);
        if($type == 1){
            $origin_name = $this->cache_get_student_nick($userid);
        }elseif($type == 2){
            $origin_userid = $this->t_student_info->get_origin_userid($userid);
            $origin_name   = $this->cache_get_student_nick($origin_userid);
        }
        foreach($list as $key=>$val){
            if($key == 0){
                echo "手机|姓名|年级|科目|支付日期|金额|课时|推荐人<br>";
            }
            $grade_str = E\Egrade::get_desc($val['grade']);
            $sub_str   = E\Esubject::get_desc($val['subject']);
            echo $val['phone']."|".$val['nick']."|".$grade_str."|".$sub_str."|"
                              .date("Y-m-d",$val['pay_time'])."|".($val['price']/100)."|"
                              .($val['order_total']/100)."|".$origin_name."<br>";
        }
    }

    /**
     * 设置工作室助理的老师类型
     */
    public function set_teacher_type(){
        $list = $this->t_teacher_info->get_teacher_type_list();
        \App\Helper\Utils::debug_to_html(  );
        foreach($list as $val){
            $this->t_teacher_info->field_update_list($val['teacherid'],[
                "teacher_type"=>22
            ]);
        }
    }

    public function teacher_type(){
        return $this->output_succ();
        $type = $this->get_in_int_val("type",3);
        if($type==3){
            $begin_date = "2017-4-1";
        }else{
            $begin_date = "2016-12-1";
        }
        $begin_time = strtotime($begin_date);
        $list = $this->t_test_lesson_subject_sub_list->get_teacher_trial_success_list($begin_time,$type);
        \App\Helper\Utils::debug_to_html( $list );
    }

    public function push_parent(){
        $template_id = "5FzZ_eN4qabZMh8FgKZvPCA7TZyvfmBROsTpaWbyEKw";
        $data        = [
            "first"    => "",
            "keyword1" => "免费领取《吴姐姐讲历史故事》音频 1000集",
            "keyword2" => "5月3日~5月10日",
            "keyword3" => "理优1对1平台",
            "keyword4" => "理优1对1辅导订阅号",
            "remark"   => "",
        ];
        $url = "https://mp.weixin.qq.com/s?__biz=MzIwMzI0NDcxNQ==&mid=504260746&idx=1&sn=de9c415202ad91e8e4b603e1b1035613";
        exit;
        $job = new \App\Jobs\PushWxToParent($template_id,$data,$url);
        dispatch($job);
    }

    public function lesson_pay_order(){
        $t2 = strtotime("2017-2-1");
        $t3 = strtotime("2017-3-1");
        $t4 = strtotime("2017-4-1");
        $t5 = strtotime("2017-5-1");

        $list = $this->t_lesson_info->get_lesson_pay_order($t2,$t5);
        $month_list[2]=[
            1=>0,
            2=>0,
            3=>0,
        ];
        $month_list[3]=[
            1=>0,
            2=>0,
            3=>0,
        ];
        $month_list[4]=[
            1=>0,
            2=>0,
            3=>0,
        ];

        $one = 86400;
        foreach($list as $val){
            $lesson_time = $val['lesson_start'];
            $order_time  = $val['order_time'];
            $pay_time    = $val['pay_time'];
            $o = $pay_time-$order_time;
            $t = $order_time-$lesson_time;

            if($lesson_time<$t3){
                $month=2;
            }elseif($lesson_time<$t4){
                $month=3;
            }elseif($lesson_time<$t5){
                $month=4;
            }

            if($o<$one){
                $month_list[$month][3]++;
            }elseif($t<$one && $o>$one){
                $month_list[$month][2]++;
            }elseif($t>$one){
                $month_list[$month][1]++;
            }
        }
        foreach($month_list as $key=>$val){
            echo $key." |".$val[1]."|".$val[2]."|".$val[3];
            echo "<br>";
        }
    }

    public function trial(){
        $begin_date = strtotime("2016-11-1");
        $end_date   = strtotime("2017-5-1");
        for($i=1;$begin_date<=$end_date;$i++){
            $start_time = $begin_date;
            $begin_date = strtotime("+1 month",$begin_date);
            $count      = $this->t_test_lesson_subject_require->get_count($start_time,$begin_date);
            echo date("Y-m-d",$start_time).":".$count;
            echo "<br>";
        }
    }

    public function change_teacher_grade(){
        $list = $this->t_teacher_info->get_change_teacher_list();
        echo "<pre>";
        var_dump($list);
        echo "</pre>";
        exit;
        foreach($list as $val){
            $grade=$val['grade_part_ex'];
            if($grade==1){
                $data=["grade_start"=>1,"grade_end"=>2];
            }elseif($grade==2){
                $data=["grade_start"=>3,"grade_end"=>4];
            }elseif($grade==3){
                $data=["grade_start"=>5,"grade_end"=>6];
            }elseif($grade==4){
                $data=["grade_start"=>1,"grade_end"=>4];
            }elseif($grade==5){
                $data=["grade_start"=>3,"grade_end"=>6];
            }elseif($grade==6){
                $data=["grade_start"=>2,"grade_end"=>4,"not_grade"=>"104,105"];
            }elseif($grade==7){
                $data=["grade_start"=>3,"grade_end"=>6];
            }
            if(isset($data)){
                $this->t_teacher_info->field_update_list($val['teacherid'],$data);
            }
        }
    }

    public function notice_feedback(){
        $list = $this->t_teacher_feedback_list->get_admin_list();

        $has_push   = [];
        $from_user  = "反馈系统";
        $header_msg = "今天有未处理的老师反馈，请及时处理。";
        $msg        = "";

        foreach($list as $val){
            $acc = "";
            if(in_array($val['feedback_type'],[201,202,203,204,205])){
                if($val['accept_adminid']){
                    if(!in_array($val['accept_adminid'],$has_push)){
                        $acc = $this->t_manager_info->get_account($val['accept_adminid']);
                        $has_push[] = $val['accept_adminid'];
                    }
                }elseif($val['assistantid']){
                    if($val['assistantid']=="134509"){
                        echo $val['lessonid'];
                        exit;
                    }
                    if(!in_array($val['assistantid'],$has_push)){
                        $acc = $this->t_assistant_info->get_account_by_id($val['assistantid']);
                        $has_push[] = $val['assistantid'];
                    }
                }
            }else{
                if(!in_array("adrian",$has_push)){
                    $acc        = "adrian";
                    $has_push[] = $acc;
                }
            }
        }
        \App\Helper\Utils::debug_to_html( $has_push );
    }

    public function get_grade_count(){
        $stu_list = $this->t_student_info->get_grade_count(0);
        $grade   = [];
        $teacher = [];
        if(!empty($stu_list)){
            foreach($stu_list as $val){
                $stu_grade          = $val['grade'];
                $teacher_money_type = $val['teacher_money_type'];
                $level              = $val['level'];
                if(!isset($grade[$stu_grade])){
                    $grade[$stu_grade]=1;
                }else{
                    $grade[$stu_grade]++;
                }
                if(!isset($teacher[$teacher_money_type][$level])){
                    $teacher[$teacher_money_type][$level]=1;
                }else{
                    $teacher[$teacher_money_type][$level]++;
                }
            }
        }
        // foreach($grade as $key=>$val){
        //     echo $key.":".$val;
        //     echo "<br>";
        // }
        foreach($teacher as $key=>$val){
            foreach($val as $l_k =>$l_v){
                $key_str = E\Eteacher_money_type::get_desc($key);
                $l_k_str = E\Elevel::get_desc($l_k);
                echo $key_str."|".$l_k_str."|".$l_v;
                echo "<br>";
            }
        }
    }

    public function get_student_type(){
        $start_time = strtotime("2017-1-1");
        $end_date   = strtotime("2017-5-1");

        $this->t_student_info->switch_readonly_database();
        for($i=0;$start_time<$end_date;$i++){
            $end_time=strtotime("+1 month",$start_time);
            echo date("Y-m-d",$start_time);
            echo "<br>";
            $list = $this->t_student_info->get_student_type_list($start_time,$end_time);
            foreach($list as $val){
                $val['first_lesson'] = $this->t_lesson_info->get_first_lesson($val['userid']);
                echo $val['phone']."|".E\Egrade::get_desc($val['grade'])."|".date("Y-m-d",$val['first_lesson']);
                echo "<br>";
            }
            $start_time = strtotime("+1 month ",$start_time);
        }
    }

    public function get_teacher_by_openid(){
        $wx_openid = "oJ_4fxMnrQoavTSNF09JEngG0X7k";
        $teacherid = $this->t_teacher_info->get_teacher_info_by_wx_openid($wx_openid);
    }

    public function send_curl_post(){
        $data = [
            "account"    => "推送测试1",
            "from_user"  => "测试2",
            "header_msg" => "测试3",
            "msg"        => "",
            "url"        => "",
        ];
        $post_url = "http://admin.yb1v1.com/common/send_wx_todo_msg?data=".base64_encode(json_encode($data));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $post_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
    }

    public function get_all_reference_teacher(){
        $teacher_ref_type = $this->get_in_int_val("teacher_ref_type",-1);

        $teacher_list = $this->t_teacher_info->get_all_reference_teacher($teacher_ref_type);

        foreach($teacher_list as $key=>$val){
            $money_start = "";
            $money_end   = "";
            if($val['teacher_money_type'] != $val['ref_teacher_money_type']
               || $val['teacher_ref_type'] !=$val['ref_teacher_ref_type']
            ){
                $money_start="<font color='red'>";
                $money_end="</font>";
            }
            if($key==0){
                echo "姓名|电话|渠道|姓名（推）|电话（推）|渠道（推）";
                echo "<br>";
            }

            $teacher_money_type_str = E\Eteacher_money_type::get_desc($val['teacher_money_type']);
            $teacher_ref_type_str   = E\Eteacher_ref_type::get_desc($val['teacher_ref_type']);
            $ref_money_type         = E\Eteacher_money_type::get_desc($val['ref_teacher_money_type']);
            $ref_type_str           = E\Eteacher_ref_type::get_desc($val['ref_teacher_ref_type']);

            echo $money_start;
            echo $val['realname']."|".$val['phone']."|".$teacher_ref_type_str."|";
            echo $val['ref_realname']."|".$val['ref_phone']."|".$ref_type_str;
            echo $money_end;
            echo "<br>";

        }
    }

    public function get_ref_teacher_list(){
        $list = $this->t_teacher_lecture_appointment_info->get_ref_teacher_list();

        $ret_list=[];
        foreach($list as $val){
            $date    = date("Y-m",$val['answer_begin_time']);
            $ret_str = E\Eteacher_ref_type::get_desc($val['teacher_ref_type']);

            if(!isset($ret_list[$date])){
                $ret_list[$date]=[];
            }
            if(!isset($ret_list[$date][$ret_str])){
                $ret_list[$date][$ret_str]=[
                    "all_num"=>0,
                    "put_num"=>0,
                    "succ_num"=>0,
                ];
            }

            $ret_list[$date][$ret_str]["all_num"]++;
            if(isset($val['teacherid']) && $val['teacherid']>0){
                $ret_list[$date][$ret_str]["succ_num"]++;
            }
            if(isset($val['lid']) && $val['lid']>0){
                $ret_list[$date][$ret_str]["put_num"]++;
            }
        }

        foreach($ret_list as $key=>$val){
            echo "月份|工作室|所有人数|提交视频|通过人数";
            echo "<br>";

            foreach($val as $k=>$v){
                echo $key."|".$k."|".$v['all_num']."|".$v['put_num']."|".$v['succ_num'];
                echo "<br>";
            }
        }
    }


    /**
     * 工资分类统计数据
     */
    public function get_wage_data(){
        $begin_data = $this->get_in_str_val("begin_data","2017-1-1");
        $end_data   = $this->get_in_str_val("end_data","2017-5-1");
        $begin_time = strtotime($begin_data);
        $final_time = strtotime($end_data);

        $this->t_lesson_info->switch_tongji_database();

        $start_time = 0;
        $count_list = [];
        for($i=0;$start_time<$final_time;$i++){
            $start_time = strtotime("+$i month",$begin_time);
            $end_time   = strtotime("+1 month",$start_time);

            $tea_list   = $this->t_lesson_info->get_tea_month_list(
                $start_time,$end_time,-1,0,-1,-1
            );
            $full_start_time = strtotime("-1 month",$start_time);
            $full_tea_list   = $this->t_lesson_info->get_tea_month_list(
                $full_start_time,$start_time,-1,3,-1,-1
            );
            $list = array_merge($tea_list,$full_tea_list);

            $date_str = date("m-d",$start_time);
            $count_list[$date_str]["all"]["stu_num"]=$this->t_lesson_info->get_stu_total($start_time,$end_time,-1);
            $count_list[$date_str]["all"]["teacher_1v1"]   = 0;
            $count_list[$date_str]["all"]["teacher_trial"] = 0;
            foreach($list as &$val){
                $teacher_money_type = (string)$val['teacher_money_type'];
                if(!isset($count_list[$date_str][$teacher_money_type]["stu_num"])){
                    $stu_num = $this->t_lesson_info->get_stu_total($start_time,$end_time,$teacher_money_type);
                    $count_list[$date_str][$teacher_money_type]["stu_num"] = $stu_num;
                }

                $this->check_isset_data($count_list[$date_str][$teacher_money_type]["teacher_1v1"],0);
                $this->check_isset_data($count_list[$date_str][$teacher_money_type]["teacher_trial"],0);
                if($val['lesson_1v1']>0){
                    $this->check_isset_data($count_list[$date_str]["all"]["teacher_1v1"]);
                    $this->check_isset_data($count_list[$date_str][$teacher_money_type]["teacher_1v1"]);
                }else{
                    $this->check_isset_data($count_list[$date_str]["all"]["teacher_trial"]);
                    $this->check_isset_data($count_list[$date_str][$teacher_money_type]["teacher_trial"]);
                }

                $lesson_1v1   = $val['lesson_1v1']/100;
                $lesson_trial = $val['lesson_trial']/100;
                $lesson_total = $val['lesson_total']/100;
                $lesson_price = $val['lesson_price']/100;

                $this->check_isset_data($count_list[$date_str]["all"]["lesson_money"],$lesson_price);
                $this->check_isset_data($count_list[$date_str][$teacher_money_type]["lesson_money"],$lesson_price);
                $this->check_isset_data($count_list[$date_str]["all"]["lesson_1v1"],$lesson_1v1);
                $this->check_isset_data($count_list[$date_str][$teacher_money_type]["lesson_1v1"],$lesson_1v1);
                $this->check_isset_data($count_list[$date_str]["all"]["lesson_trial"],$lesson_trial);
                $this->check_isset_data($count_list[$date_str][$teacher_money_type]["lesson_trial"],$lesson_trial);
                $this->check_isset_data($count_list[$date_str]["all"]["lesson_total"],$lesson_total);
                $this->check_isset_data($count_list[$date_str][$teacher_money_type]["lesson_total"],$lesson_total);
                $this->check_isset_data($count_list[$date_str]["all"]["teacher_num"]);
                $this->check_isset_data($count_list[$date_str][$teacher_money_type]["teacher_num"]);
            }
        }

        foreach($count_list as $key=>$val){
            echo $key."|总收入|总课耗|1对1课耗|试听课耗|总老师|1对1老师|试听课老师|在读学员|师生比|月平均课耗|";
            echo "<br>";
            foreach($val as $k=>$v){
                if($k!="all" || $k=="0"){
                    $k = E\Eteacher_money_type::get_desc($k);
                }
                $shishengbi = round($v["stu_num"]/$v['teacher_num'],2);
                $per_total  = round($v['lesson_1v1']/$v["teacher_num"],2);
                echo $k."|".$v['lesson_money']."|".$v['lesson_total']."|".$v["lesson_1v1"]."|".$v["lesson_trial"]."|"
                       .$v['teacher_num']."|".$v['teacher_1v1']."|".$v["teacher_trial"]."|".$v['stu_num']."|"
                       .$shishengbi."|".$per_total;
                echo "<br>";
            }
            echo "<br>";
        }
    }

    public function check_isset_data(&$data,$add_data=1){
        if(!isset($data)){
            $data=$add_data;
        }else{
            $data+=$add_data;
        }
    }

    public function get_origin_stu(){
        $origin_list=[];

        $this->t_student_info->switch_tongji_database();
        $list = $this->t_student_info->get_origin_stu();
        foreach($list as &$val){
            E\Egrade::set_item_value_str($val);
            $val["cc"] = $this->t_manager_info->get_account($val['seller_adminid']);
            $val["cr"] = $this->t_assistant_info->get_nick($val['assistantid']);
            $val['lesson_count_left']      /= 100;
            $ret_list = $this->t_student_info->get_stu_list_by_origin($val['userid'],0);
            foreach($ret_list as $o_val){
                E\Egrade::set_item_value_str($o_val);
                $o_val["cc"] = $this->t_manager_info->get_account($o_val['seller_adminid']);
                $o_val["cr"] = $this->t_assistant_info->get_nick($o_val['assistantid']);
                $o_val['lesson_count_left'] /= 100;
            }
            $origin_list[$val['userid']][]=$ret_list;
        }

        echo "<pre>";
        var_dump($list);
        echo "</pre>";
        exit;
    }

    public function get_course_list(){
        $new_teacherid = [202135,202149,202152,202159,202157,202158];
        $old_teacherid = [158531,170356,170591,165795,173755,176412];
        
        exit;
        foreach($new_teacherid as $n_key => $n_val){
            $course_list = $this->t_course_order->get_course_list_by_teacherid($n_val);

            foreach($course_list as $c_val){
                $teacherid = $old_teacherid[$n_key];
                $this->t_course_order->row_insert([
                    "userid"               => $c_val['userid'],
                    "subject"              => $c_val['subject'],
                    "grade"                => $c_val['grade'],
                    "teacherid"            => $teacherid,
                    "assistantid"          => $c_val['assistantid'],
                    "default_lesson_count" => $c_val['default_lesson_count'],
                    "lesson_grade_type"    => $c_val['lesson_grade_type'],
                    "course_status"        => $c_val['course_status'],
                    "is_kk_flag"           => $c_val['is_kk_flag'],
                    "competition_flag"     => $c_val['competition_flag'],
                ]);
            }
        }
    }

    public function train_lesson_user(){
        $teacherid    = $this->get_in_int_val("teacherid",$this->teacherid);
        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);

        $grade    = $this->change_grade_end_to_grade($teacher_info);
        $courseid = $this->t_course_order->add_course_info_new(
            0,0,$grade,$teacher_info['subject'],0
            ,1100,1,0,0,0
            ,$teacher_info['teacherid']
        );
        $lessonid = $this->t_lesson_info->add_lesson(
            $courseid,1,0,0,1100,$teacher_info['teacherid'],0
            ,0,0,$grade,$teacher_info['subject'],100
            ,$teacher_info['teacher_money_type'],$teacher_info['level'],0,2,0
            ,0,1,4
        );
        $this->t_homework_info->add(0,0,0,$lessonid,$grade,$teacher_info['subject'],$teacher_info['teacherid']);
        $this->t_teacher_record_list->row_insert([
            "teacherid"      => $teacher_info['teacherid'],
            "type"           => 9,
            "add_time"       => time(),
            "train_lessonid" => $lessonid,
        ]);
        echo $lessonid;
    }

    public function change_grade_end_to_grade($teacher_info){
        $grade_end = $teacher_info['grade_end'];
        $subject   = $teacher_info['subject'];
        if($subject==10){
            $grade=200;
        }else{
            if($grade_end>=5){
                $grade=300;
            }elseif($grade_end>=3){
                $grade=200;
            }else{
                $grade=100;
            }
        }
        return $grade;
    }

    public function get_new_order(){
        $start_time = strtotime("2017-5-1");
        $end_time   = strtotime("2017-6-13");

        $ret_info=$this->t_order_info->get_new_order($start_time,$end_time);

        $order_list=[];
        foreach($ret_info as $val){
            $date_str=date("Y-m-d",$val['pay_time']);
            if($val['orderid']>0){
                \App\Helper\Utils::check_isset_data($order_list[$date_str]);
            }
            echo $val['nick']."|".date("Y-m-d",$val['pay_time'])."|".$val['default_lesson_count']*$val['lesson_total']/100;
            echo "<br>";
        }
    }

    public function get_lecture_list(){
        header("Content-type: text/html; charset=utf-8"); 
        $phone = $this->get_in_str_val("phone");
        if($phone==""){
            return $this->output_err("手机号为空！");
        }
        $teacher_info = $this->t_teacher_lecture_appointment_info->get_lecture_appointment_info($phone);
        if(empty($teacher_info)){
            return $this->output_err("您此手机号还未报名！请先报名才可录制试讲！");
        }
            
        $subject_list   = [];
        $grade_list     = [];
        $lecture_status = [];
        $ret_list       = [];
            
        $subject       = $teacher_info['subject_ex'];
        $grade         = $teacher_info['grade_ex'];
        $trans_subject = $teacher_info['trans_subject_ex'];
        $trans_grade   = $teacher_info['trans_grade_ex'];
        $grade_list[$subject] = E\Egrade::get_specify_select([100,200,300]);
        if(isset($trans_subject) && $trans_subject>0){
            $subject_list = E\Esubject::get_specify_select([$subject,$trans_subject]);
            $grade_list[$trans_subject] = $grade_list[$subject];
        }else{
            $subject_list = E\Esubject::$desc_map;
        }

        $ret_list = $this->get_teacher_lecture_list($teacher_info);
        $lecture_list = $this->t_teacher_lecture_info->get_lecture_list($phone);

        if(is_array($lecture_list) && !empty($lecture_list)){
            foreach($lecture_list as $l_val){
                $l_grade   = substr($l_val['grade'],0,1)."00";
                $l_subject = $l_val['subject'];
                $ret_key   = $l_subject."_".$l_grade;
                if(in_array($l_val['status'],[1,2])){
                    if(isset($grade_list[$l_subject])){
                        $grade_list[$l_subject] = array_diff_key($grade_list[$l_subject],[$l_grade]);
                    }
                }
                $ret_list[$ret_key]['status'] = $l_val['status'];
            }
        }

    }

    public function get_teacher_lecture_list($teacher_info){
        $ret_list = [];
        extract($teacher_info);
        $teacher_grade[$subject_ex]       = explode(",",$grade_ex);
        $teacher_grade[$trans_subject_ex] = explode(",",$trans_grade_ex);
        $identity = E\Eidentity::get_desc($teacher_type);
        foreach($teacher_grade as $s_key=>$s_val){
            $s_str = E\Esubject::get_desc($s_key);
            if($s_key>0){
                foreach($s_val as $g_val){
                    $g_str = E\Egrade::get_desc($g_val);
                    $data  = [
                        "title"        => $g_str.$s_str."试讲课",
                        "subject"      => $s_str,
                        "grade"        => $g_str,
                        "tea_nick"     => $name,
                        "identity_str" => $identity,
                        "status"       => -1,
                    ];
                    $ret_key = $s_key."_".$g_val;
                    $ret_list[$ret_key] = $data;
                }
            }
        }
        return $ret_list;
    }

    public function get_origin_user(){
        $num = $this->get_in_int_val("num",4);

        $start_time = strtotime("2017-".$num."-1");
        $end_time   = strtotime("+1 month",$start_time);

        $this->switch_tongji_database();
        $userid_list = $this->t_student_info->get_origin_user_list();
        $userid     = array_keys($userid_list);
        $userid_str = implode(",",$userid);

        $stu_list    = $this->t_student_info->get_origin_user($start_time,$end_time);
        // $origin_list = $this->t_student_info->get_origin_user_2($start_time,$end_time,$userid_str);

        echo "被转介绍";
        echo "<br>";
        echo "昵称|真实姓名|电话|邮箱|区域|年级|设备|签单人|合同类型|购买课时|剩余课时|退费情况|"
            ."介绍人昵称|介绍人真实姓名|介绍人电话|介绍人设备|介绍人CC|介绍人年级|介绍人地区";
        echo "<br>";
        foreach($stu_list as $val){
            $agent = json_decode($val['user_agent'],true);
            $origin_agent = json_decode($val['origin_user_agent'],true);
            E\Econtract_type::set_item_value_str($val);
            E\Egrade::set_item_value_str($val);
            E\Egrade::set_item_value_str($val,"origin_grade");
            $val['seller_nick'] = $this->cache_get_seller_nick($val['origin_seller']);
            echo $val['nick']."|".$val['realname']."|".$val['phone']."|".$val['email']
                             ."|".$val['phone_location']."|".$val['grade_str']
                             ."|".$agent['device_model']."|".$val['sys_operator']
                             ."|".$val['contract_type_str']."|".$val['lesson_total_all']
                             ."|".$val['lesson_left_all']."|".$val['refund_num']

                             ."|".$val['origin_nick']."|".$val['origin_realname']."|".$val['origin_phone']
                             ."|".$origin_agent['device_model']."|".$val['seller_nick']."|".$val['origin_grade_str']
                             ."|".$val['origin_phone_location'];
            echo "<br>";
        }

        // echo "介绍人";
        // echo "<br>";
        // echo "昵称|真实姓名|电话|邮箱|区域|年级|设备|签单人|合同类型|购买课时|剩余课时|退费情况";
        // echo "<br>";

        // foreach($origin_list as $val){
        //     $agent = json_decode($val['user_agent'],true);
        //     E\Econtract_type::set_item_value_str($val);
        //     E\Egrade::set_item_value_str($val);
        //     echo $val['nick']."|".$val['realname']."|".$val['phone']."|".$val['email']
        //                      ."|".$val['phone_location']."|".$val['grade_str']
        //                      ."|".$agent['device_model']."|".$val['sys_operator']
        //                      ."|".$val['contract_type_str']."|".$val['lesson_total_all']
        //                      ."|".$val['lesson_left_all']."|".$val['refund_num'];
        //     echo "<br>";
        // }
    }

    public function reset_teacher_name(){
        $list = $this->t_teacher_info->get_teacher_name_list();
        foreach($list as $val){
            echo $val['phone_spare']."|".$val['phone']."|".$val['origin_teacherid']."|".$val["teacherid"];
            echo "<br>";
             $this->t_teacher_info->field_update_list($val['teacherid'],[
                 "realname" => $val['realname'],
                 "nick"     => $val['realname'],
             ]);
        }
    }

    public function get_origin_list(){
        $arr = $this->get_b_txt();
        $phone_str = "";
        $this->switch_tongji_database();
        echo "姓名|手机号|版本|所在地|推荐人姓名|手机|版本|所在地";
        echo "<br>";

        foreach($arr as $val){
            if($val!=""){
                $info = $this->t_student_info->get_teacher_origin_info($val);
                echo $info["nick"]."|".$info['phone']."|".$info['user_agent']."|".$info['phone_location']
                                  ."|".$info["origin_nick"]."|".$info['origin_phone']."|".$info['origin_user_agent']
                                  ."|".$info['origin_location'];
                echo "<br>";
            }
        }
    }

    public function get_teacher_lesson_count(){
        $this->switch_tongji_database();
        $list = $this->t_lesson_info_b2->get_teacher_lesson_count();

        foreach($list as $val){
            $teacher_money_type = E\Eteacher_money_type::get_desc($val['teacher_money_type']);
            $level              = E\Elevel::get_desc($val['level']);
            echo $val['nick']."|".$val['phone']."|".$teacher_money_type."|".$level."|".$val['lesson_all_count'];
            echo "<br>";
        }
    }

    public function subject_teacher(){
        $list = $this->t_lesson_info_b2->get_subject_teacher();
        foreach($list as $val){
            $this->t_teacher_info->field_update_list($val['teacherid'],[
                "subject"       => $val['subject'],
                "grade_part_ex" => substr($val['grade'],0,1),
            ]);
            echo $val['grade'];
            echo "<br>";
            echo $val['subject'];
            echo "<br>";
            echo $val['phone'];
            exit;
        }
    }

    public function get_data(){
        //6月份新签合同的课时，原始价格，折扣价格，赠送课时，年级
        $start = strtotime("2017-6-1");
        $end   = strtotime("2017-7-1");

        $list=$this->t_order_info->get_new_order($start,$end);
        foreach($list as $val){
            $grade_str = E\Egrade::get_desc($val['grade']);
            echo $val['nick']."|".$grade_str."|".($val['lesson_total']/100)."|".($val['price']/100)."|".($val['discount_price']/100)
                             ."|".($val['free_lesson_total']/100);
            echo "<br>";
        }
    }

    public function get_not_through_user(){
        $start_date = $this->get_in_str_val("start_date","2017-6-17");
        $start_time = strtotime($start_date);
        $end_time   = time();
        $list = $this->t_train_lesson_user->get_not_through_user($start_time,$end_time);
        
        foreach($list as $val){
            echo $val['nick']."|".$val['phone']."|".$val['score']."|".date("Y-m-d H:i",$val['create_time']);
            echo "<br>";
        }
    }

    public function get_pingtai(){
        $num                = $this->get_in_int_val("num");
        $teacher_money_type = $this->get_in_str_val("teacher_money_type","5");
        $level              = $this->get_in_str_val("level","");

        $start_date = strtotime("2017-4-1");
        $start_time = strtotime("+$num month",$start_date);
        $end_time   = strtotime("+1 month",$start_time);
        $this->t_lesson_info_b2->switch_tongji_database();
        $list = $this->t_lesson_info_b2->get_lesson_total_list($start_time,$end_time,$teacher_money_type,$level);
        echo "姓名|工资类型|等级|创建时间|转化率|常规课时|试听课时|总课时|学生数";
        echo "<br>";

        foreach($list as $val){
            $ref_str            = E\Eteacher_ref_type::get_desc($val['teacher_ref_type']);
            $level_str          = E\Elevel::get_desc($val['level']);
            $teacher_money_type_str = E\Eteacher_money_type::get_desc($val['teacher_money_type']);
            $create_time        = date("Y-m-d",$val['create_time']);
            $trial_lesson_total = $val['trial_lesson_total']/100;
            $lesson_total       = $val['lesson_total']/100;
            $all_total          = $trial_lesson_total+$lesson_total;
            $stu_num            = $this->t_lesson_info_b2->get_stu_num($val['teacherid'],$start_time,$end_time);
            echo $val['nick']."|".$teacher_money_type_str."|".$level_str."|".$create_time
                             ."|".$val['test_transfor_per']."|".$lesson_total."|".$trial_lesson_total
                             ."|".$all_total."|".$stu_num;
            echo "<br>";
        }
    }

    public function get_stu_num(){
        $start = strtotime("2017-4-1");
        $end   = strtotime("2017-7-1");

        foreach($info as $val){
            $lesson_list = $this->t_lesson_info_b2->get_lesson_total();
            $teacherid = $this->t_teacher_info->get_teacherid_by_name($val);
            if($teacherid>0){
                $stu_num=$this->t_lesson_info_b2->get_stu_num($val,$start,$end);
                echo $val."|".$stu_num;
                echo "<br>";
            }
        }
    }


    public function get_wx_openid(){
        /**
         * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
         * 标题课程 : 待办事项提醒
         * {{first.DATA}}
         * 待办主题：{{keyword1.DATA}}
         * 待办内容：{{keyword2.DATA}}
         * 日期：{{keyword3.DATA}}
         * {{remark.DATA}}
         */
        $template_id = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
        $data = [
            "first"    => "老师您好，您的银行卡还未绑定。",
            "keyword1" => "银行卡绑定提醒",
            "keyword2" => "\n请您尽快绑定银行卡信息，上月的薪资将于本月10日左右发出，如果今天18:00前还未绑定，将和本月工资一起算在下月10号发放，请知悉",
            "keyword3" => date("Y-m-d H:i:s",time()),
            "remark" => "点击详情绑定银行卡",
        ];
        $url = "http://wx-teacher-web.leo1v1.com/bank_info.html";

        $arr = $this->get_b_txt();

        foreach($arr as $val){
            if($val!=""){
                $info = $this->t_teacher_info->get_teacher_info_by_phone($val);
                if(!empty($info) && $info['bankcard']==""){
                    echo $info['phone']."|".$info['realname']."|".$info['bankcard'];
                    echo "<br>";
                }
            }
        }
    }

    public function add_new_teacher(){
        $arr = $this->get_b_txt();
        $lesson_start = strtotime("2017-7-12 21:00");
        $lesson_end   = strtotime("2017-7-12 23:59");
        $grade        = 101;
        $subject      = 11;

        foreach($arr as $val){
            if($val!=""){
                $t_v = explode("|",$val);
                $phone = $t_v[2];
                $tea_info  = $this->t_teacher_info->get_teacher_info_by_phone($phone);
                $teacherid = $tea_info['teacherid'];
                $this->t_teacher_info->field_update_list($teacherid,[
                    "wx_use_flag"=>1,
                ]);
                // $user_info = $this->t_student_info->get_student_info_by_phone($t_v[2]);
                $this->cache_get_assistant_nick($assistantid);
            }
        }
    }

    public function reset_teacher_ref_type(){
        $list = $this->t_teacher_info->get_admin_teacher_list();
        foreach($list as $val){
            $this->t_teacher_info->field_update_list($val['teacherid'],[
                "teacher_ref_type" => 41
            ]);
        }
    }

    public function test_teacher_qr(){
        header("Content-type:image/jpeg");
        $phone = $this->get_in_str_val("phone","18790256265");

        $phone_qr_name  = $phone."_qr_summer.png";
        $text           = "http://wx-teacher-web.leo1v1.com/tea.html?".$phone;
        $bg_url         = "http://leowww.oss-cn-shanghai.aliyuncs.com/summer_pic_invitation.png";
        $qr_url         = "/tmp/".$phone.".png";
        $teacher_qr_url = "/tmp/".$phone_qr_name;
        \App\Helper\Utils::get_qr_code_png($text,$qr_url,5,4,3);
        
        $image_1 = imagecreatefrompng($bg_url);
        $image_2 = imagecreatefrompng($qr_url);
        $image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));
        imagecopyresampled($image_3,$image_1,0,0,0,0,imagesx($image_1),imagesy($image_1),imagesx($image_1),imagesy($image_1));
        imagecopymerge($image_3,$image_2, 455,875,0,0,imagesx($image_2),imagesy($image_2), 100);
        imagepng($image_3,$teacher_qr_url);
        imagedestroy($image_1);
        imagedestroy($image_2);
        imagedestroy($image_3);
    }

    public function test_lesson_total(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,3);
        $this->switch_tongji_database();
        $list = $this->t_lesson_info_b2->test_lesson_total($start_time,$end_time,0);
        echo count($list);
        echo "<br>";
        $full_start  = strtotime("-1 month",$start_time);
        $full_end    = strtotime("-1 month",$end_time);
        $full_list = $this->t_lesson_info_b2->test_lesson_total($full_start,$full_end,3);
        echo count($full_list);
    }

    public function add_lesson_for_11(){
        $arr=$this->get_b_txt();
        $today_date=date("Y-m-d",time());
        $stu_arr=[
            "肖芸"   => "241094",
            "董瑞雯" => "241096",
        ];

        foreach($arr as $val){
            if($val != ""){
                $lesson_info = explode("\t",$val);
                /* 0 上课老师 1 上课时间段 2 学生 3 上课老师手机号 */
                $lesson_time  = explode("-",$lesson_info[1]);
                
                $lesson_start  = strtotime($today_date." ".$lesson_time[0]); 
                $lesson_end    = strtotime($today_date." ".$lesson_time[1]); 
                $teacherid     = $this->t_teacher_info->get_teacherid_by_phone($lesson_info[3]);
                $check_teacher = $this->t_lesson_info->check_teacher_time_free($teacherid,0,$lesson_start,$lesson_end);
                if($check_teacher){
                    echo "老师".$lesson_info[0]."时间冲突！上课时间:".$lesson_info[1]."冲突id：".$check_teacher['lessonid'];
                    echo "<br>";
                    continue;
                }
                $userid     = $stu_arr[$lesson_info[2]];
                $check_user = $this->t_lesson_info->check_student_time_free($userid,0,$lesson_start,$lesson_end);
                if($check_user){
                    echo "学生".$lesson_info[2]."时间冲突！上课时间:".$lesson_info[1]."冲突id:".$check_user['lessonid'];
                    echo "<br>";
                    continue;
                }

                // $lessonid = $this->t_lesson_info->add_lesson(
                //     0,0,$userid,0,2,
                //     $teacherid,0,$lesson_start,$lesson_end,100,
                //     11,100
                // );
                echo $lesson_info[0]."|".$lesson_info[2];
                echo "<br>";
            }
        }
    }

    public function add_trial_lesson(){
        $userid    = $this->get_in_int_val("userid",241094);
        $teacherid = $this->get_in_int_val("teacherid",240469);

        $grade   = 100;
        $subject = 11;
        $lesson_start = strtotime("2017-7-15 21:00");
        $lesson_end = strtotime("2017-7-15 22:00");

        $ret_row1 = $this->t_lesson_info->check_student_time_free($userid,0,$lesson_start,$lesson_end);
        //检查时间是否冲突
        if ($ret_row1) {
            $error_lessonid=$ret_row1["lessonid"];
            return $this->output_err(
                "<div>有现存的学生课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
            );
        }
        $ret_row2 = $this->t_lesson_info->check_teacher_time_free($teacherid,0,$lesson_start,$lesson_end);
        if($ret_row2){
            $error_lessonid = $ret_row2["lessonid"];
            return $this->output_err(
                "<div>有现存的老师课程与该课程时间冲突！<a href='/tea_manage/lesson_list?lessonid=$error_lessonid/' target='_blank'>查看[lessonid=$error_lessonid]<a/><div> "
            );
        }

        $courseid = $this->t_course_order->add_course_info_new(1,$userid,$grade,$subject,100,2,0,1,1,0,$teacherid);
        $lessonid = $this->t_lesson_info->add_lesson(
            $courseid,0,$userid,0,2,
            $teacherid,0,$lesson_start,$lesson_end,$grade,
            $subject,100,4,1
        );
    }

    public function get_origin_order(){
        $origin     = $this->get_in_str_val("origin","问卷星");
        $start_time = strtotime("2017-5-1");
        $end_time   = time();

        $list = $this->t_order_info->get_origin_order($start_time,$end_time,$origin);
        echo "姓名|年级|手机|学生城市|签单课时|金额|下单人|合同生效时间|合同类型|渠道";
        echo "<br>";

        foreach($list as $val){
            $grade_str    = E\Egrade::get_desc($val['grade']);
            $lesson_total = $val['lesson_total']*$val['default_lesson_count']/100;
            $price        = $val['price']/100;
            $pay_time = date("Y-m-d H:i",$val['pay_time']);
            $contract_str = E\Econtract_type::get_desc($val['contract_type']);

            echo $val['nick']."|".$grade_str."|".$val['phone']."|".$val['phone_location']
                             ."|".$lesson_total
                             ."|".$price."|".$val['sys_operator']
                             ."|".$pay_time."|".$contract_str."|".$val['origin'];
            echo "<br>";
        }
    }

    public function get_origin_user_list(){
        $list = $this->t_order_info->get_origin_user_list();
        foreach($list as $val){
            echo $val['nick']."|".$val['phone']."|".$val['ass_nick']."|".$val['seller_nick'];
            echo "<br>";
        }
    }

    public function get_create_teacher(){
        $num = $this->get_in_int_val("num",0);
        $start_time = strtotime("2017-4-1");
        $end_time = strtotime("2017-7-1");

        $list = $this->t_teacher_info->get_trial_teacher_month($start_time,$end_time);
        echo "姓名|手机|入职日期|试听课时|成功数|常规课时|转化率";
        echo "<br>";

        foreach($list as &$val){
            $create_start = $val['create_time'];
            $create_end   = $val['create_time']+30*86400;
            $create_date = date("Y-m-d",$val['create_time']);
            $success = $this->t_lesson_info->get_teacher_test_person_num_list( $create_start,$create_end,-1,-1,[$val['teacherid']]);
            \App\Helper\Utils::check_isset_data($success[$val['teacherid']]['have_order'],0);
            $succ_trial = $success[$val['teacherid']]['have_order'];
            $trial_count  = $val['trial_count']/100;
            $normal_count = $val['normal_count']/100;
            if($trial_count==0){
                $succ_per="0%";
            }else{
                $succ_per = (round($succ_trial/$trial_count,2)*100)."%";
            }
            echo $val['nick']."|".$val['phone']."|".$create_date."|".$trial_count."|".$succ_trial."|".$normal_count."|".$succ_per;
            echo "<br>";
        }
    }

    public function get_amanda(){
        $start_time = strtotime("2015-1-1");
        $end_time   = strtotime("2017-8-1");
        $end_time   = time();
        $start_time = strtotime("-1 year",$end_time);
        $list = $this->t_student_info->get_has_lesson($start_time,$end_time);
        echo count($list);
        foreach($list as $val){
            $str=E\Estudent_type::get_desc($val['type']);
            echo $val['nick']."|".$val['phone']."|".$str;
            echo "<br>";
        }
    }

    /**
     * 有5个合同以上的名单
     */
    public function get_order_5(){
        $this->switch_tongji_database();
        $list = $this->t_order_info->get_order_5();

        foreach($list as $val){
            $ass_nick    = $this->cache_get_assistant_nick($val['assistantid']);
            $last_lesson = $this->t_lesson_info->get_last_lesson_time($val['userid']);
            $last_lesson = date("Y-m-d",$last_lesson);
            echo $val['nick']."|".$val['phone']."|".$val['have_order']."|".$ass_nick."|".$val['seller_name']."|".$last_lesson;
            echo "<br>";
        }

        $userid=$this->t_student_info->register($phone,$passwd,$reg_channel,$grade,$ip,$nick,$region);
    }

    public function test_push_wx_to_teacher(){
        $template_id = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
        $teacherid   = 58052;
        $openid = $this->t_teacher_info->get_wx_openid($teacherid);
        $nick   = $this->t_teacher_info->get_realname($teacherid);
        $data['first']    = $nick."老师您好！";
        $data['keyword1'] = "邀请参训通知";
        $data['keyword2'] = "经系统核查您试讲通过多日培训未通过，为方便老师参加，特将培训增设到每周4期：周中19点周末15点，老师可自由选择；若时间冲突，可登录教师端，在【我的培训】中观看回放后，点击【自我测评】回答问卷，考核通过后即收到【入职offer】开启您的线上教学之旅。";
        $data['keyword3'] = date("Y-m-d",time());
        $data['remark']   = "答题过程中有任何问题可私聊【师训】沈老师获得指导~课程多多，福利多多~期待老师的加入！";
        $list[0]['wx_openid'] = $openid;
        $job = new \App\Jobs\SendTeacherWx($list,$template_id,$data,"");
        dispatch($job);
    }

    /**
     * 获取学生第一次签约时间，科目明细
     */
    public function get_stu_order_list(){
        $start_time = strtotime("2016-5-1");
        $end_time   = strtotime("2017-6-30");
        $list       = $this->t_order_info->get_stu_order_list($start_time,$end_time);
        $stu_list   = [];
        foreach($list as $val){
            $userid     = $val['userid'];
            $phone      = $val['phone'];
            $realname   = $val['realname']!=""?$val['realname']:$val['nick'];
            $grade      = E\Egrade::get_desc($val['grade']);
            $first_time = date("Y-m-d H:i",$val['first_time']);
            $lesson_total = $val['lesson_total'];
            $subject    = E\Esubject::get_desc($val['subject']);
            \App\Helper\Utils::check_isset_data($stu_list[$userid]['phone'],$phone,0);
            \App\Helper\Utils::check_isset_data($stu_list[$userid]['realname'],$realname,0);
            \App\Helper\Utils::check_isset_data($stu_list[$userid]['grade'],$grade,0);
            \App\Helper\Utils::check_isset_data($stu_list[$userid]['first_time'],$first_time,0);
            \App\Helper\Utils::check_isset_data($stu_list[$userid]['subject'],$subject,0);
            \App\Helper\Utils::check_isset_data($stu_list[$userid]['lesson_total'],$lesson_total);
            if(isset($stu_list[$userid]['subject']) && !strstr($stu_list[$userid]['subject'],$subject)){
                $stu_list[$userid]['subject'] .= (",".$subject);
            }
        }
        echo "姓名|手机|首次签约时间|年级|总课时数|科目";
        echo "<br>";
        foreach($stu_list as $s_val){
            echo $s_val['realname']."|".$s_val['phone']."|".$s_val['first_time']."|".$s_val['grade']."|".($s_val['lesson_total']/100)
                                   ."|".$s_val['subject'];
            echo "<br>";
        }
    }

    /**
     * 今日头条渠道数据
     */
    public function get_jr() {
        $page_info  = $this->get_in_page_info();
        $start_time = strtotime("2017-5-1");
        $end_time   = time();
        $opt_type_str = $this->get_in_str_val("opt_type_str","all_count");
        $key1 = $this->get_in_str_val("key1","今日头条");
        $key2 = $this->get_in_str_val("key2");
        $key3 = $this->get_in_str_val("key3");
        $key4 = $this->get_in_str_val("key4");

        $origin_ex_arr= preg_split("/,/", session("ORIGIN_EX"));

        if (!$origin_ex_arr[0] ){
            if ($key1!="全部") {
                $origin_ex_arr[0]=$key1;
            }
        }

        if (!isset($origin_ex_arr[1] )){
            $origin_ex_arr[1]=$key2;
        }

        if (!isset($origin_ex_arr[2] )){
            $origin_ex_arr[2]=$key3;
        }

        if (!isset($origin_ex_arr[3] )){
            $origin_ex_arr[3]=$key4;
        }

        $origin_ex= join(",", $origin_ex_arr );


        $ret_info= $this->t_seller_student_new->get_origon_list( $page_info, $start_time, $end_time,$opt_type_str, $origin_ex) ;
        foreach($ret_info["list"] as &$item) {
            \App\Helper\Utils::unixtime2date_for_item($item,"add_time");
            echo $item['phone'];
            echo "<br>";
        }
    }

    /**
     * 重置田克平
     */
    public function get_tkp(){
        $lesson_start=strtotime("2017-8-1");
        $tea_list = $this->t_teacher_info->get_tkp();
        $this->switch_tongji_database(false);
        foreach($tea_list as $tea_val){
            $teacherid=$tea_val['teacherid'];
            $this->t_teacher_info->field_update_list($teacherid,[
                "teacher_money_type"=>4
            ]);
            $this->t_lesson_info_b2->reset_lesson_teacher_money_type($teacherid,$lesson_start);
        }
    }

    public function test_email(){
        $email = "wg392567893@163.com";
        $ret= \App\Helper\Common::send_paper_mail_new($email,"测试邮件","is test email");
        dd($ret);
    }

    public function reset_test_appointment(){
        if(\App\Helper\Utils::check_env_is_test()){
            $phone     = "99900020001";
            $id=$this->t_teacher_lecture_appointment_info->get_id_by_phone($phone);
        }else{
            $phone=$this->get_in_str_val("phone");
            if($phone==""){
                return $this->output_err("请输入手机号");
            }
            $info = $this->t_teacher_lecture_appointment_info->get_simple_info($phone);
            $id = $info['id'];
        }

        $this->t_teacher_lecture_appointment_info->field_update_list($id,[
            "subject_ex"       => "1",
            "grade_ex"         => "100",
            "trans_grade_ex"   => "",
            "trans_subject_ex" => "",
            "grade_1v1"        => "",
            "trans_grade_1v1"  => "",
        ]);

        $teacherid= $this->t_teacher_info->get_teacherid_by_phone($phone);
        $lessonid_list=$this->t_lesson_info_b2->get_lessonid_list_by_userid($teacherid);
        if(is_array($lessonid_list) && !empty($lessonid_list)){
            foreach($lessonid_list as $val){
                $this->t_lesson_info->row_delete($val['lessonid']);
            }
        }
        $id=$this->t_teacher_lecture_info->get_id_list_by_phone($phone);
        if(is_array($id) && !empty($id)){
            foreach($id as $val_l){
                $this->t_teacher_lecture_info->row_delete($val_l['id']);
            }
        }

    }

    public function add_teacher_money_type_new(){
        $arr = $this->get_b_txt();
        // 0 teacher_money_type | 1 level | 2 k1-k5 money |3 k6-k8 money |4 k9 money |5 k10-k11 money |6 k12 money |7 type

        foreach($arr as $val){
            if($val!=""){
                $add_arr = explode("|",$val);
                if(count($add_arr)==8){
                    $teacher_money_type = $add_arr[0];
                    $level              = $add_arr[1];
                    $type               = $add_arr[7];

                    $update_arr['teacher_money_type'] = $teacher_money_type;
                    $update_arr['level']              = $level;
                    $update_arr['type']               = $type;

                    $grade = 101;
                    for($i = 1;$i<=5;$i++){
                        $this->add_teacher_money_type($update_arr,$grade,$add_arr[2]);
                    }

                    for($i=1;$i<=3;$i++){
                        $this->add_teacher_money_type($update_arr,$grade,$add_arr[3]);
                    }

                    $this->add_teacher_money_type($update_arr,$grade,$add_arr[4]);

                    for($i=1;$i<=2;$i++){
                        $this->add_teacher_money_type($update_arr,$grade,$add_arr[5]);
                    }

                    $this->add_teacher_money_type($update_arr,$grade,$add_arr[6]);
                }
            }
        }
    }

    public function add_teacher_money_type($update_arr,&$grade,$money){
        $check_flag = $this->t_teacher_money_type->check_is_exists($update_arr['teacher_money_type'],$update_arr['level'],$grade);
        if(!$check_flag && ($grade>=101 || $grade<=303)){
            $update_arr['grade']=$grade;
            $update_arr['money']=$money;
            $this->t_teacher_money_type->row_insert($update_arr);
        }
        $grade = \App\Helper\Utils::get_next_grade($grade);
    }

    public function set_simulate_info(){
        $teacher_money_type = $this->get_in_int_val("teacher_money_type",4);
        $level              = $this->get_in_int_val("level",1);
        $level_simulate     = $this->get_in_int_val("level_simulate",1);

        $new_A = [
            "胡旭",
            "丁媛媛",
            "默建宾",
            "吉妍瑾",
            "李志伟",
            "邢平",
            "章一维",
            "付文",
            "董超",
            "马娇",
            "朱凯",
            "唐梦迪",
            "吴迎雪",
            "尹向烟",
            "李伟",
            "于艳",
            "张鑫",
            "王月",
            "李睿超",
            "卢雪瑞",
            "徐雪丽",
            "宁斯同",
            "李泽",
            "褚雪莲",
            "翁老师",
            "张习席",
            "周钦钦",
            "罗佳玲",
            "董昊",
            "桂莱樱",
            "任志琴",
            "刘学文",
            "毛好",
            "边水青",
            "张志敏",
            "毛志元",
            "赵凌云",
        ];

        $new_A_plus = [
            "张子琦",
            "矣子沁",
            "惠吉",
            "郭政一",
            "张礼海",
            "于梁梁",
            "张和清",
            "胡兴福",
            "汪小民",
            "杜琼",
            "成实",
            "孟德龙",
            "徐宁",
            "凌艺",
            "熊慧格",
            "孔春莉",
            "韩嘉亮",
            "黄志玲",
            "谢楠静",
            "卓媛容",
            "万里",
            "沙维娜",
            "田娇",
            "刘淼",
            "黄嵩婕",
            "王皓",
        ];

        $new_B_plus = [
            "魏晓晓",
            "许磊",
            "张杰-Johnny",
            "鞠东篱",
            "高歌",
            "闫佳",
            "税雄",
            "李凤喜",
            "韩涵（韩文君）",
        ];

        $new_B = [
            "陈桂琼",
            "陈懿",
            "房彩虹",
        ];

        $new_T = [
            "潘笑文",
            "刘平",
            "赵力红",
        ];
        //在职C->新版B
        // $this->set_simulate_info_by_list(0,0,1);
        //在职B->新版B+
        // $this->set_simulate_info_by_list(0,1,2);
        //高校C->新版B
        // $this->set_simulate_info_by_list(1,0,1);
        //外聘C->新版B
        // $this->set_simulate_info_by_list(2,0,1);
        //固定C->新版B
        // $this->set_simulate_info_by_list(3,0,1);
        $this->set_simulate_info_by_list($new_T,0,11);
        $this->set_simulate_info_by_list($new_B,0,1);
        $this->set_simulate_info_by_list($new_B_plus,0,2);
        $this->set_simulate_info_by_list($new_A,0,3);
        $this->set_simulate_info_by_list($new_A_plus,0,4);
    }

    public function set_simulate_info_by_list($list,$level,$level_simulate){
        if(!is_array($list)){
            $this->t_teacher_info->set_simulate_info($list,$level,$level_simulate);
        }else{
            foreach($list as $val){
                $count = $this->t_teacher_info->check_count_by_realname($val);
                if($count>1){
                    echo $this->red;
                    echo "more name:";
                    echo $val;
                    echo "more name end";
                    echo $this->div;
                }else{
                    $teacher_info = $this->t_teacher_info->get_teacher_info_by_realname_for_level_simulate($val,$level_simulate);
                    if(!empty($teacher_info)){
                        if($teacher_info['level_simulate'] != $level_simulate){
                            $ret = $this->t_teacher_info->field_update_list($teacher_info['teacherid'],[
                                "level_simulate" => $level_simulate
                            ]);
                            if($ret){
                                echo $this->blue;
                                echo $val;
                                echo "<br>";
                                echo "succ";
                                echo $this->div;
                            }else{
                                echo $this->red;
                                echo $val;
                                echo "<br>";
                                echo "fail";
                                echo $this->div;
                            }
                        }
                    }
                }
            }
        }
    }



    public function add_rule_type(){
        $rule_type = [
            1=>[
                0     => 0,
                1500  => 3,
                4500  => 6,
                7500  => 13,
                13500 => 16,
                18000 => 19
            ],2=>[
                0     => 0,
                15000 => 3,
                22500 => 7
            ],3=>[
                0     => 0,
                15000 => 4,
                22500 => 6
            ],4=>[
                0     => 0,
                1500  => 3,
                4500  => 6,
                10500 => 9,
                16500 => 12,
                22500 => 15,
                28500 => 18,
            ],5=>[
                0     => 0,
                1000  => 5,
                6000  => 10,
                12000 => 20,
            ],6=>[
                0     => 0,
                1000  => 4,
                4000  => 7,
                10000 => 10,
                16000 => 15,
                24000 => 20,
                33000 => 30,
            ]
        ];
        $reward_count_type = 1;
        foreach($rule_type as $key => $val){
            foreach($val as $k => $v){
                $check_flag = $this->t_teacher_reward_rule_list->check_reward_rule_is_exists($reward_count_type,$key,$k);
                if(!$check_flag){
                    $this->t_teacher_reward_rule_list->row_insert([
                        "reward_count_type" => $reward_count_type,
                        "rule_type"         => $key,
                        "num"               => $k,
                        "money"             => $v*100,
                    ]);
                }
            }
        }
    }

    /**
     * 刷新招师库李的年级和科目信息
     */
    public function refresh(){
        $subject_map = E\Esubject::$desc_map;
        $grade_map = E\Egrade::$desc_map;
        $list = $this->t_teacher_lecture_appointment_info->get_refresh_list();

        $num  = 0;
        foreach($list as $val){
            $num++;
            echo $val['id']."|".$val['phone']."|".$val['grade_ex']."|".$val['subject_ex'];

            // $grade_ex   = $this->check_str($grade_map,$val['grade_ex']);
            // $subject_ex = $this->check_str($subject_map,$val['subject_ex']);

            $grade_ex = $this->get_grade_ex_by_range($val['grade_start']);
            echo "|".$grade_ex;
            // if($grade_ex=="" && $subject_ex!=""){
            //     $grade_ex="100";
            // }
            // if($subject_ex=="" && $grade_ex!=""){
            //     $subject_ex="2";
            // }

            if($grade_ex!=""){
                // $this->t_teacher_lecture_appointment_info->field_update_list($val['id'],[
                //      "grade_ex" => $grade_ex,
                //      "subject_ex" => $subject_ex,
                // ]);

                echo "|update";
            }
            echo "<br>";
        }
    }

    public function check_str($map,$str){
        $ret = "";
        if($str!=""){
            foreach($map as $key=>$val){
                // $check_val = mb_substr($val,0,1,"utf-8");
                if(strstr($str,$val)){
                    $ret = $key;
                    break;
                }
            }
        }
        return $ret;
    }

    public function get_grade_ex_by_range($grade_range){
        switch($grade_range){
        case 1:case 2:
            $grade_ex="100";
            break;
        case 3:case 4:
            $grade_ex="200";
            break;
        case 5:case 6:
            $grade_ex="300";
            break;
        default:
            $grade_ex="";
            break;
        }
        return $grade_ex;
    }

    /**
     * 获取时间内的老师奖励薪资
     */
    public function get_reward_list(){
        $start = strtotime("2017-8-1");
        $end   = strtotime("2017-9-1");
        $list  = $this->t_teacher_money_list->get_reward_list($start,$end,5);
        foreach($list as $val){
            echo $val['teacherid']."|".$val['realname']."|".$val['phone']
                                  ."|银行卡:".$val['bankcard']."|".$val['bank_account']."|".$val['bank_type']
                                  ."|".($val['money_total']/100);
            echo "<br>";
        }
    }

    public function get_trial_teacher(){
        $num = $this->get_in_int_val("num");
        $start = strtotime("+$num month",strtotime("2017-7-1"));
        $end   = strtotime("+1 month",$start);
        $list = $this->t_lesson_info_b3->get_trial_teacher_list($start,$end);

        echo "姓名|手机|试听课次|成功课次|第一科目|第一科目年级|第二科目|第二科目年级";
        echo "<br>";
        foreach($list as $val){
            if($val['subject']>0){
                $subject_str = E\Esubject::get_desc($val['subject']);
                if($val['grade_start']>0){
                    $grade_str = E\Egrade_range::get_desc($val['grade_start'])."-".E\Egrade_range::get_desc($val['grade_end']);
                }else{
                    $grade_str = E\Egrade_part_ex::get_desc($val['grade_part_ex']);
                }
            }else{
                $subject_str = "无";
                $grade_str   = "无";
            }
            if($val['second_subject']){
                $second_subject_str = E\Esubject::get_desc($val['second_subject']);
                if($val['second_grade_start']>0){
                    $second_grade_str = E\Egrade_range::get_desc($val['second_grade_start'])."-".E\Egrade_range::get_desc($val['second_grade_end']);
                }else{
                    $second_grade_str = E\Egrade_part_ex::get_desc($val['second_grade']);
                }
            }else{
                $second_subject_str = "无";
                $second_grade_str   = "无";
            }


            $second_grade_str = E\Egrade_part_ex::get_desc($val['second_grade']);
            echo $val['realname']."|".$val['phone']."|".$val['lesson_num']."|".$val['succ_num']."|".$subject_str."|".$grade_str."|".$second_subject_str."|".$second_grade_str;
            echo "<br>";
        }
    }

    public function reset_teacher_info(){
        $time  = strtotime("2017-9-13 16:00");
        $arr = $this->t_lesson_info_b3->get_need_reset_list($time);
        dd($arr);
        foreach($arr as $val){
            if($val['new_teacher_money_type']!=$val['old_teacher_money_type'] || $val['new_level']!=$val['old_level']){
                echo $val['lessonid']."|new_teacher_money_type:".$val["new_teacher_money_type"]
                                     ."|old_teacher_money_type:".$val['old_teacher_money_type']
                                     ."|new_level:".$val['new_level']
                                     ."|old_level:".$val['old_level'];
                echo "<br>";
                // $this->t_lesson_info->field_update_list($val['lessonid'],[
                //     "teacher_money_type" => $val['new_teacher_money_type'],
                //     "level"              => $val['new_level'],
                // ]);
            }
        }
    }

    public function reset_teacher_money_type(){
        $batch = $this->get_in_int_val("batch",1);
        $list  = $this->t_teacher_info->get_need_reset_money_type_list($batch);
        dd($list);
        /**
         * 模板ID   : E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0
         * 标题课程 : 等级升级通知
         * {{first.DATA}}
         * 用户昵称：{{keyword1.DATA}}
         * 最新等级：{{keyword2.DATA}}
         * 生效时间：{{keyword3.DATA}}
         * {{remark.DATA}}
         */
        $template_id      = "E9JWlTQUKVWXmUUJq_hvXrGT3gUvFLN6CjYE1gzlSY0";
        $data['keyword3'] = "16:00";
        $data['remark']   = "感谢您长期以来对理优平台的辛劳付出与长久陪伴！";
        foreach($list as $val){
            $this->t_teacher_info->field_update_list($val['teacherid'],[
                "teacher_money_type" => $val['teacher_money_type_simulate'],
                "level"              => $val['level_simulate'],
            ]);

            if($val['wx_openid']!=""){
                $level_str = E\Enew_level::v2s($val['level_simulate']);
                $data['first'] = "恭喜您，您等级已经调整为".$level_str;
                $data['keyword1'] = mb_substr($val['realname'],0,1)."老师";
                $data['keyword2'] = $level_str;
                // \App\Helper\Utils::send_teacher_msg_for_wx($val['wx_openid'],$template_id,$data);
            }
        }
    }

    /**
     * 设置老师批次
     */
    public function reset_teacher_batch(){
        $list = $this->t_teacher_switch_money_type_list->get_teacher_switch_list(-1,-1,-1,0,8);
        $tea_list=[];
        foreach($list as $l_val){
            $lesson_total=(float)$l_val['lesson_total']/100;
            $y = (float)$l_val['all_money_different'];
            $x = (float)$l_val['base_money_different']/$lesson_total;
            $batch = 0;

            if($x>(float)0 && $y>(float)0){
                $batch = 1;
            }elseif($x<=(float)0 && $y>=(float)0){
                $batch = 2;
            }elseif($x>=(float)0 && $y<=(float)0){
                $batch = 3;
            }elseif($x>=(float)-2 && $y>=(float)-200){
                $batch = 4;
            }elseif($x<=(float)-2 && $y>=(float)-200){
                $batch = 5;
            }elseif($x<=(float)-2 && $y<=(float)-200){
                $batch = 6;
            }

            echo $l_val['realname']."|".$x."|".$y."|".$batch."|".$lesson_total."|";
            echo "<br>";
            // $this->t_teacher_switch_money_type_list->field_update_list($l_val['id'],[
            //     "batch"=>$batch
            // ]);
            $tea_list[$batch][]=$l_val['realname'];
        }
        dd($tea_list);
    }

    /**
     * 重置试讲通过,但科目未设置的老师科目和年级
     */
    public function reset_teacher_subject_info(){
        $list = $this->t_teacher_info->reset_teacher_subject_info();
        dd($list);
        foreach($list as $val){
            $grade_range = \App\Helper\Utils::change_grade_to_grade_range($val['grade']);
            $this->t_teacher_info->field_update_list($val['teacherid'],[
                "subject"     => $val['subject'],
                "grade_start" => $grade_range['gradde_start'],
                "grade_end"   => $grade_range['gradde_end'],
            ]);
        }
    }

    public function get_teacher_full_lesson(){
        $month = $this->get_in_int_val("month",6);
        $start_time = strtotime("2017-$month");
        $end_time = strtotime("+1 month",$start_time);
        $list = $this->t_lesson_info_b3->get_teacher_full_lesson_total($start_time,$end_time);
        echo "姓名|手机|课时|请假|迟到";
        echo "<br>";
        foreach($list as $val){
            $lesson_total=$val['lesson_total']/100;
            echo $val['realname']."|".$val['phone']."|".$lesson_total."|".$val['change_class']."|".$val['come_late'];
            echo "<br>";
        }
    }

    public function get_trial_lesson(){
        $month = $this->get_in_int_val("month");

        $start_time = strtotime("2017-$month");
        $end_time = strtotime("+1 month",$start_time);
    }

    public function reset_tea_already_lesson_count(){
        $tea_list = $this->t_lesson_info->get_teacherid_for_reset_lesson_count($start,$end);
        if(!empty($tea_list) && is_array($tea_list)){
            foreach($tea_list as $val){
                $stu_list = $this->t_lesson_info->get_student_list_by_teacher($val['teacherid'],$start,$end);
                if(!empty($stu_list) && is_array($stu_list)){
                    foreach($stu_list as $item){
                        $this->t_lesson_info->reset_teacher_student_already_lesson_count($val['teacherid'],$item['userid']);
                    }
                }
            }
        }
    }

    public function reset_already_lesson_count(){
        \App\Helper\Utils::redis(E\Eredis_type::V_DEL,$this->already_lesson_count_key);
        \App\Helper\Utils::redis(E\Eredis_type::V_DEL,$this->already_lesson_count_simulate_key);
    }

    public function reset_teacher_simulate_info(){
        $list =$this->t_teacher_switch_money_type_list->get_need_reset_list();
        dd($list);
        foreach($list as $val){
            $this->t_teacher_info->field_update_list($val['teacherid'],[
                "teacher_money_type_simulate" => $val['teacher_money_type'],
                "level_simulate"              => $val['level'],
            ]);
        }

    }

    /**
     * 获取之前第三版的等级和转换后的
     */
    public function get_teacher_list(){
        // $start = strtotime("2017-5-1");
        // $end   = strtotime("2017-9-1");
        // $list  = $this->t_teacher_info->get_old_teacher_money_type_list($start,$end);
        $info = $this->get_b_txt();
        dd($info);
        foreach($info as $val){
            if($val!=""){
                $teacher_info = $this->t_teacher_info->get_teacher_info($val);
                $teacher_money_type_str = E\Eteacher_money_type::get_desc($teacher_info['teacher_money_type']);
                $teacher_money_type_simulate_str = E\Eteacher_money_type::get_desc($teacher_info['teacher_money_type_simulate']);
                echo $teacher_money_type_str."|".$teacher_money_type_simulate_str;
                echo "<br>";
            }
        }
    }

    public function get_taobao_list(){
        $list = $this->t_taobao_item->get_all_item_list();

        $start_str = "<em class=\"tb-rmb-num\">";
        foreach($list as $val){
            if($val['product_id']!=""){
                $price="";
                $url  = "https://item.taobao.com/item.htm?id=".$val['product_id'];
                $html = file_get_contents($url);

                $left_str = strstr($html,$start_str);
                $left_str = str_replace($start_str,"",$left_str);
                $check_str = ".";
                $price = stristr($left_str,$check_str,true);

                echo $val['open_iid']."|".$val['product_id']."|".$val["price"]."|".$price;
                echo "<br>";
                if($price!=""){
                    $this->t_taobao_item->field_update_list($val['open_iid'],[
                        "price" => $price,
                    ]);
                }else{
                    $this->t_taobao_item->field_update_list($val['open_iid'],[
                        "status"=>0
                    ]);
                }
            }
        }
    }










}
