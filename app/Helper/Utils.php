<?php
namespace App\Helper;
use Illuminate\Support\Facades\Log ;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Artisan;
use App\Enums as  E;
use \App\Libs;


// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

require_once(app_path("/Libs/OSS/autoload.php"));
use OSS\OssClient;

use OSS\Core\OssException;


require_once app_path('/Libs/TCPDF/tcpdf.php');
require_once app_path('/Libs/TCPDF/config/tcpdf_config.php');

class Utils  {

    static function init_hour_list(&$list, $init_item=[]) {
        for ($i=0;$i<24;$i++) {
            $init_item["hour"]=$i;
            \App\Helper\Utils::array_item_init_if_nofind($list,$i,$init_item);
        }
    }

    static function get_start_index_from_ret_info($ret_info) {
        return ($ret_info["page_info"]["page_num"]-1)*$ret_info["page_info"]["per_page_count"]+1;
    }

    /**
     *  得到 roomid
     */
    static function gen_roomid_name ( $lesson_type,$courseid,$lesson_num ){
        return ($lesson_type>=1000?"p_":"l_") . $courseid . "y" . $lesson_num . "y" . $lesson_type;
    }

    /*
     * 得到房间里所有的用户
     */
    static function get_room_users ( $roomid,$config ) {
        $xmpp_server = new \XMPPOperator($config['ip'], $config['xmpp_port'],
                                    "sys_user", "xx",
                                    $config['ip']);
        return $xmpp_server->get_room_user($roomid);
    }


    /*
     * 得到房间里所有的用户
     */
    static function del_room ($userid, $roomid,$config ) {
        $xmpp_server = new \XMPPOperator($config['ip'], $config['xmpp_port'],
                                          $userid , "xx",
                                         $config['ip']);
        return $xmpp_server->del_room ($roomid);
    }



    static function get_lesson_server_type ( $lesson_type,$server_type )  {
        if($server_type ==0  ) { //default
            if ($lesson_type<1000) {
                return  1; //1v1->理优
            }else{
                return  2;
            }
        } else if ( $server_type >0  ) {
            return $server_type ;
        }
    }

    static function debug_to_html($data ){
        if ( is_string($data ) || is_numeric($data )){
            echo "<pre>";
            echo $data;
            echo "</pre>";
        } else {
            echo json_encode($data);
        }
        exit;
    }

    static function encode($tex,$key,$type="encode"){
        $chrArr=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
                      'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                      '0','1','2','3','4','5','6','7','8','9');
        if($type=="decode"){
            if(strlen($tex)<14)return false;
            $verity_str=substr($tex, 0,8);
            $tex=substr($tex, 8);
            if($verity_str!=substr(md5($tex),0,8)){
                return false;
            }
        }
        $key_b=$type=="decode"?substr($tex,0,6):$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62];
        $rand_key=$key_b.$key;
        $rand_key=md5($rand_key);
        $tex=$type=="decode"?base64_decode(substr($tex, 6)):$tex;
        $texlen=strlen($tex);
        $reslutstr="";
        for($i=0;$i<$texlen;$i++){
            $reslutstr.=$tex{$i}^$rand_key{$i%32};
        }
        if($type!="decode"){
            $reslutstr=trim($key_b.base64_encode($reslutstr),"==");
            $reslutstr=substr(md5($reslutstr), 0,8).$reslutstr;
        }
        return $reslutstr;
    }

    static function encode_str($str){
        $key="xcwen142857";
        return self::encode($str, $key, "encode" );
    }

    static function decode_str($str){
        $key="xcwen142857";
        return self::encode($str, $key, "decode" );
    }

    static function  ios_get_item_info($type, $str )  {
        if ($type==0) {
            return [$type,$str] ;
        }else{
            $replace_in= ["　", "×","（","）","、","，","－" ];
            $replace_out=[" ",  " \\times ","(", ")", ",", ",", "-" ];
            $str=str_replace($replace_in,$replace_out, $str );

            //处理 确认是否需要tex
            if(preg_match("/^[(){}\[\]A-Za-z0-9,°.\/*=+ \t\n\r-]*\$/",$str,$matches)){
                //return [0,$str] ;
                return [$type,$str] ;
            }else{
                return [$type,$str];
            }
        }
    }

    static  function  ios_clean_data_question_data($str) {
        $str=str_replace(
            array("考点：",     "分析：",        "答题：",     "点评："  ,"解答：", "专题：" ),
            array("【考点】", "【分析】","【答题】","【点评】",  "【解答】"  ,"【专题】" ),
            $str  );

        // $ 分割
        $arr=split("\\$",$str );
        $cur_doular1_flag=false;
        $cur_doular2_flag=false;
        $new_arr=[];
        foreach ($arr as $item ){
            if ($cur_doular1_flag ) {
                if ($item=="") {
                    $cur_doular1_flag=false;
                    $cur_doular2_flag=true;
                }else{
                    $new_arr[]=   self::ios_get_item_info(1, $item) ;
                    $cur_doular1_flag=false;
                }
            }else if ($cur_doular2_flag ) {
                if ( $item=="" ) {
                    $cur_doular2_flag=false;
                }else{
                    $new_arr[]= self::ios_get_item_info(2, $item) ;
                }
            }else{
                $new_arr[]= self::ios_get_item_info(0, $item) ;
                $cur_doular1_flag=true;
            }
        }
        //gen obj str
        $obj_str="";
        $type_config=["","\$", "\$\$"];
        foreach( $new_arr as $item )  {
            $type     = $item[0];
            $item_str = $item[1];
            $type_str = $type_config[$type];
            //for
            if(preg_match("/[\\\\^_]/", $item_str))  {
                $obj_str .= $type_str.$item[1].$type_str;
            }else{
                $obj_str .= $item[1];
            }
        }
        return $obj_str;
    }

    static  function  split_question_q($str) {
        //处理
        $q="";
        $select_list=[];
        $arr=split("\n\n\n\\\$A\\.\\\$", $str  ) ;
        if (count( $arr) ==2) {
            $q=$arr[0];
            $select_list=split("\n\n\\\$[A-Z]\\.\\\$", $arr[1]) ;
        }

        //去掉空项
        $a_list=[];
        foreach ($select_list as $item ) {
            $item=trim($item);
            if ($item==""){
                break;
            }
            $a_list[]=$item;
        }
        return  [ "q" => $q, "select_list" =>$a_list];
    }

    static  function  split_question_a($str) {
        //处理
        $a=substr($str,0,1);
        $analysis=substr($str,2);
        return  [ "a" => $a, "analysis" =>$analysis];
    }
    static function json_decode_as_array( $str, $need_array=false) {
        if ( $need_array && !$str) {
            $str="{}";
        }
        return json_decode($str,true) ;
    }

    static function json_decode_as_int_array( $str) {
        $ret_arr=self::json_decode_as_array($str);
        foreach($ret_arr as &$item ) {
            $item=intval($item);
        }
        return $ret_arr;
    }


    public static function convert_question_info($question_type, $q,$a)  {

        $analysis="";
        if ($question_type==1) { //选择题
            //
            $q_info=Utils::split_question_q($q);
            $q=Utils::ios_clean_data_question_data( $q_info["q"]);
            $select_list=$q_info["select_list"];
            foreach($select_list  as &$select_item ) {
                $select_item =Utils::ios_clean_data_question_data($select_item );
            }

            $a_info=Utils::split_question_a($a);
            $a=$a_info["a"];
            $analysis= Utils::ios_clean_data_question_data($a_info["analysis"]);

        }else{ //其他
            $q=Utils::ios_clean_data_question_data( $q);
            $a=Utils::ios_clean_data_question_data( $a);
        }
        return array( $q, $a, $select_list,$analysis );
    }

    public static function get_user_agent_info($user_agent) {
        $data=Utils::json_decode_as_array($user_agent);

        if(is_array($data)){
            $ret=$data["device_model"]."-".$data["system_version"].",".$data["version"];
            return $ret;
        }else{
            return  $user_agent;
        }
    }

    public static function get_pad_type( $user_agent ) {

        $data=Utils::json_decode_as_array($user_agent);

        if(is_array($data)){
            if (preg_match("/iPad/",  $data["device_model"]  ,$matches))  {
                return E\Epad_type::V_1 ;
            }else{
                return E\Epad_type::V_2 ;
            }
        }else{
            return E\Epad_type::V_2 ;
        }

    }

    static function fmt_lesson_name($grade,$subject, $lesson_num) {
        return E\Egrade::get_simple_desc($grade).E\Esubject::get_simple_desc($subject)."-$lesson_num" ;
    }

    static function fmt_lesson_time($lesson_start,$lesson_end){
        if ($lesson_start>0){
            $m_count=($lesson_end-$lesson_start)/60;
            return date('m-d H:i', $lesson_start) . date('~H:i', $lesson_end) ."-[" .$m_count ."]" ;
        }else{
            return "无";
        }
    }

    static function fmt_lesson_time_new($lesson_start,$lesson_end){
        if ($lesson_start>0){
            return date('m-d H:i', $lesson_start) . date('~H:i', $lesson_end);
        }else{
            return "无";
        }
    }

    static function get_lesson_time($lesson_start,$lesson_end,$type=0){
        $date_str = date('m-d H:i', $lesson_start) . date('~H:i', $lesson_end);
        if($type===0){
            $count_str = "";
        }elseif($type==1){
            $count_str="-[".(($lesson_end-$lesson_start)/60)."]";
        }
        $lesson_time_str = $date_str.$count_str;
        return $lesson_time_str;
    }

    static function output_html($str) {
        echo "<head> <meta charset=\"UTF-8\"> <head> <body>$str" ;
    }

    static function unixtime2date($timestamp ,$fmt_str= 'Y-m-d H:i:s' ){
        if ($timestamp) {
            return date($fmt_str, $timestamp);
        }else{
            return "无";
        }
    }

    static function unixtime2date_for_item(&$item, $field_name,$fix_str="", $fmt_str="Y-m-d H:i:s"){
        $item[$field_name.$fix_str] = static::unixtime2date($item[$field_name],$fmt_str);
    }

    static function unixtime2date_range(&$item,$range_name="lesson_time",$start_name="lesson_start",$end_name="lesson_end",
                                        $fmt_start_str="Y-m-d H:i",$fmt_end_str="H:i"
    ){
        $item[$range_name] = static::unixtime2date($item[$start_name],$fmt_start_str)."-"
                                                     .static::unixtime2date($item[$end_name],$fmt_end_str);
    }

    static function get_day_range( $timestamp){
        $ret=array();
        $ret['sdate'] = strtotime( date('Y-m-d 00:00:00', $timestamp));
        $ret['edate'] =  $ret['sdate'] + 86400;
        return $ret;
    }

    // 获取指定日期所在星期的开始时间与结束时间 ,
    static function get_week_range( $timestamp,$start_fix=0){
        $ret = array();
        //%w Numeric representation of the day of the week  0 (for Sunday) through 6 (for Saturday)
        $w   = strftime('%w',$timestamp);
        if ($start_fix==0){//周日
            $start= $timestamp-($w-$start_fix)*86400;
        }else{ //周1 ==1
            if ($w==0){
                $w=7;
            }
            $start = $timestamp-($w-$start_fix)*86400;
        }

        $ret['sdate'] = strtotime( date('Y-m-d 00:00:00',$start));
        $ret['edate'] = $ret['sdate'] + 86400*7-1;
        return $ret;
    }

    /**
     * 获取指定日期所在月的开始日期与结束日期
     * @param int timestamp 当前时间戳
     * @param int is_full_month 是否拉取整个月
     * @return array
     */
    static function get_month_range($timestamp,$is_full_month=false ){
        $ret = array();
        $mdays        = date('t',$timestamp);
        $ret['sdate'] = strtotime( date('Y-m-1 00:00:00',$timestamp));
        if($is_full_month){
            $ret['edate'] = strtotime("+1 month",$ret['sdate']);
        }else{
            $ret['edate'] = strtotime( date('Y-m-'.$mdays.' 23:59:59',$timestamp));
        }
        return $ret;
    }

    //检测当前环境
    static function   check_env_is_local(){
        return \Illuminate\Support\Facades\App::environment( E\Eenv::S_LOCAL );
    }

    static function   check_env_is_test(){
        return \Illuminate\Support\Facades\App::environment( E\Eenv::S_TEST );
    }

    static function   check_env_is_testing(){
        return \Illuminate\Support\Facades\App::environment( E\Eenv::S_TESTING );
    }

    static function   check_env_is_release(){
        return \Illuminate\Support\Facades\App::environment( E\Eenv::S_RELEASE );
    }
    static function get_full_url($url) {
        $arr=explode("/", $url);
        if (!isset($arr[0]) )  {
            $arr[0]="index";
        }

        if (!isset($arr[1]) or  trim($arr[1])=="" )  {
            $arr[1]="index";
        }
        $url="/".$arr[0]."/".$arr[1];
        return $url;
    }

    public static function logger( $message ) {
        global $g_request;

        if (  !self::check_env_is_testing()
             && ( $g_request  instanceof \Illuminate\Http\Request  )
        ) {
            if (!class_exists('ChromePhp', false)) {
                include_once( app_path("Libs/ChromePhp.php") );
            }
            $time_str=date('H:i:s', time(NULL));
            \ChromePhp::log($time_str, substr( $message,0,4000 ));
            /*
            if (!class_exists('FB', false)) {
                include_once( app_path("Libs/FirePHPCore/fb.php") );
            }
            $time_str=date('H:i:s', time(NULL));
            \FB::log(  " $time_str: $message", "NEW" );
            */
        }
        logger("P_".getmypid().":". $message );
    }

    public static function comment_field($field,$comment) {
        $field->comment(bin2hex($comment) );
    }

    // 15601830297-1 => 15601830297
    static public function get_phone($phone){
        $arr=explode("-",$phone);
        return $arr[0];
    }

    static public function list_to_page_info($list){
        $ret_arr=[];
        $ret_arr["page_info"] = array(
            "total_num"      => 1,
            "per_page_count" => 100000,
            "page_num"       => 1,
        );
        $ret_arr["list"]=$list;
        return $ret_arr;
    }

    static function get_publish_version() {
        return \App\Config\publish_version::version;
    }

    static function wx_get_token() {
        $wx=new \App\Helper\Wx();
        return $wx->wx_get_token();
    }

    static function gen_jquery_data($item, $field_list=null ) {
        $str="";
        if ($field_list ) {
            foreach   ($field_list as $field_name) {
                $str.="data-$field_name=\"".htmlspecialchars($item[$field_name])."\" ";
            }
        }else{
            foreach   ($item as  $field_name=>$value) {
                if (!is_int( $field_name)) {
                    $str.="data-$field_name=\"".htmlspecialchars($value)."\" ";
                }
            }
        }
        return $str;
    }

    static function get_course_name($lesson_type) {
        if($lesson_type<1000){
            $course_name="1对1";
        }elseif($lesson_type<3000){
            $course_name="公开课";
        }elseif($lesson_type==3001){
            $course_name="小班课";
        }else{
            $course_name="课程";
        }
        return $course_name;
    }

    static function get_next_grade( $grade){
        switch ( $grade ) {
        case 101 : return 102 ;
        case 102 : return 103 ;
        case 103 : return 104 ;
        case 104 : return 105 ;
        case 105 : return 106 ;
        case 106 : return 201 ;
        case 201 : return 202 ;
        case 202 : return 203 ;
        case 203 : return 301 ;
        case 301 : return 302 ;
        case 302 : return 303 ;
        case 303 : return 401 ;
        default: return $grade;
        }
    }

    static function get_start_index($page_num,$page_per_count ) {
        if ($page_num>100000000) {
            $page_num=1;
        }
        return ($page_num-1 )* $page_per_count +1;
    }

    static function hide_item_phone( &$item, $phone_field_name="phone") {
        $phone=$item[$phone_field_name];
        $item[$phone_field_name."_hide"]=substr($phone,0,3)."****".substr($phone,7);
    }

    static function th_order_gen( $title, $field_name ="" ) {
        if ( is_array($title) ) {
            $arr=$title;
            $str="";
            foreach( $arr as $item ) {
                $str.=' <td > '.$item[0]
                    .'<a href="javascript:;" class=" fa fa-sort td-sort-item  " data-field-name="'.$item[1]
                    .'"  > </a> </td>';
            }
            return $str;
        }else{
            return ' <td > '.$title
                            .'<a href="javascript:;" class=" fa fa-sort td-sort-item  " data-field-name="'
                            .$field_name.'"  > </a> </td>';
        }
    }

    static function order_list( &$list, $order_field_name,$is_asc_flag) {
        if ($is_asc_flag) {
            usort( $list , function ($a,$b) use ($order_field_name)
            {
                $a_v=@$a[$order_field_name] ;
                $b_v=@$b[$order_field_name] ;
                if ($a_v==$b_v) return 0;
                return $a_v>$b_v? 1:-1;
            });
        }else{
            usort($list, function ($a,$b) use ($order_field_name)
            {
                $a_v=@$a[$order_field_name] ;
                $b_v=@$b[$order_field_name] ;
                if ($a_v==$b_v) return 0;
                return $a_v>$b_v? -1:1;
            });
        }
    }

    static function date_list_set_value( &$date_list , &$from_list , $date_key, $field_name, $from_field_name ) {

        foreach ($from_list as $item) {
            $opt_date  = $item[$date_key];
            $date_item = &$date_list[$opt_date];
            $date_item[$field_name]=$item[$from_field_name];
        }

    }
    static function all_item_add(&$all_item,$item, $field_name  ) {
        if (is_array( $field_name)) {
            $field_arr=$field_name;
            foreach ($field_arr as $field_name) {
                $all_item[$field_name]=@$all_item[$field_name]+@$item[$field_name];
            }
        }else{
            $all_item[$field_name]=@$all_item[$field_name]+@$item[$field_name];
        }
    }

    static function list_add_sum_item( &$data_list,  $all_item, $sum_field_list) {
        foreach ($data_list as $item ) {
            \App\Helper\Utils::all_item_add($all_item,$item, $sum_field_list );
        }
        array_unshift($data_list,$all_item);
    }


    /**
     * 获取指定当月的开始和结束时间
     * @param time 默认当前时间
     * @return array
     */
    static function get_month_date($time=0){
        if($time==0){
            $time = time();
        }
        $date['start'] = strtotime(date("Y-m-01",$time));
        $date['end']   = strtotime("+1 month",$date['start']);
        return $date;
    }

    static function array_item_add_value(&$arr,$field_name,$value ) {
        $arr[$field_name]=@$arr[$field_name]+$value;
    }

    static function array_item_init_if_nofind(&$arr,$field_name, $init_value=array() ) {
        if (!isset( $arr[$field_name]  ) ) {
            $arr[$field_name]  =$init_value;
        }
    }

    static function get_up_month_day($time) {
        $day=date("d", $time);
        $last_month_time= $time-$day*86400 ;
        $pre_time=strtotime( date("Y-m-$day", $last_month_time ));
        if ($pre_time > $last_month_time ) {
            $pre_time= $last_month_time;
        }
        return $pre_time;
    }

    static function gen_diff_persent($v_base, $v) {
        if (!$v_base) {
            return 0;
        }else{
            return intval(($v-$v_base)*100/$v_base);
        }
    }

    static function get_diff_color_str($val,$ge_0_ok_flag=true ) {
        $color_congfig=[
            0=> "green",
            1=>"red",
        ];
        if (!$ge_0_ok_flag) {
            $color_congfig=[
                0=>"red",
                1=> "green",
            ];
        }
        if ($val==0) {
            return "$val%";
        }else{
            $config_index=0;
            if ($val<0) {
                $config_index=1;
            }
            $color_str= $color_congfig[$config_index];
            return "<font color=$color_str>$val%</font>" ;
        }
    }

    static public function get_online_line($time_list,$list) {
        foreach ($list as $item) {
            $start_time = $item["lesson_start"];
            $end_time   = $item["lesson_end"]+300;
            $start_id   = floor((($start_time+28800)%86400)/300);
            $end_id     = ceil((($end_time+28800)%86400)/300);
            for( ; $start_id<=$end_id; $start_id++ ) {
                $time_list[$start_id]++;
            }
        }
        return $time_list;
    }

    static public function get_online_line_timestramp($time_list,$list) {
        foreach ($list as $item) {
            $start_time = $item["lesson_start"]- $item["lesson_start"]%300 ;
            $end_time   = $item["lesson_end"]+300;
            for( ; $start_time<=$end_time; $start_time+=300 ) {
                $time_list[$start_time]++;
            }
        }
        return $time_list;
    }


    static public function exec_cmd($cmd){
        \App\Helper\Utils::logger("EXEC: $cmd");

        $fp  = popen("$cmd", "r");
        $ret = "";
        while(!feof($fp)) {
            $ret .=fread($fp, 1024);
        }
        fclose($fp);
        return $ret;
    }

    static public function qiniu_upload($file){
        $qiniu     = \App\Helper\Config::get_config("qiniu");

        $bucket    = $qiniu['public']['bucket'];
        $accessKey = $qiniu['access_key'];
        $secretKey = $qiniu['secret_key'];

        // 构建鉴权对象
        $auth = new \Qiniu\Auth ($accessKey, $secretKey);

        // 上传到七牛后保存的文件名
        $key = basename($file);

        // 生成上传 Token
        $token = $auth->uploadToken($bucket,$key);

        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new \Qiniu\Storage\UploadManager ();

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $file);
        if ($err !== null) {
            return false;
        } else {
            return  $ret["key"];
        }
    }

    /**
     * 获取七牛文件状态
     * @param bucket 需要测试的七牛空间
     * @param key    需要测试的文件名
     * @return boolean
     */
    static public function qiniu_file_stat($bucket,$key){
        $qiniu     = \App\Helper\Config::get_config("qiniu");
        $bucket    = $qiniu['public']['bucket'];
        $accessKey = $qiniu['access_key'];
        $secretKey = $qiniu['secret_key'];

        $auth      = new \Qiniu\Auth ($accessKey, $secretKey);
        $bucketMgr = new \Qiniu\Storage\BucketManager($auth);

        list($ret, $err) = $bucketMgr->stat($bucket, $key);

        if($err !== null) {
            return false;
        } else {
            return true;
        }
    }

    static public function send_reference_msg_for_wx($openid,$record_info,$status_str){
        $template_id         = "kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc";
        $wx_data["first"]    = $record_info;
        $wx_data["keyword1"] = $status_str;
        $wx_data["keyword2"] = "\n 1、填写报名信息"
                             ."\n 2、录制试讲视频"
                             ."\n 3、进行入职培训"
                             ."\n 4、成功入职";
        $wx_data["remark"] = "好友成功入职后，即可获得伯乐奖，"
                           ."伯乐奖将于每月10日结算（如遇节假日，会延后到之后的工作日），"
                           ."请及时绑定银行卡号，如未绑定将无法发放。";
        self::send_teacher_msg_for_wx($openid,$template_id,$wx_data);
    }

    static public function send_teacher_msg_for_wx($openid,$template_id,$data,$url=""){
        $appId      = \App\Helper\Config::get_teacher_wx_appid();
        $appSecret  = \App\Helper\Config::get_teacher_wx_appsecret();
        $teacher_wx = new \App\Helper\Wx($appId,$appSecret);

        $is_success = $teacher_wx->send_template_msg($openid,$template_id,$data,$url);

        $task = new  \App\Console\Tasks\TaskController();
        $task->t_weixin_msg->row_insert([
            "userid"      => 0,
            "openid"      => $openid,
            "send_time"   => time(),
            "templateid"  => $template_id,
            "title"       => "",
            "notify_data" => json_encode($data),
            "notify_url"  => $url,
            "is_success"  => $is_success?1:0,
        ]);
    }

    static public function send_agent_msg_for_wx($openid,$template_id,$data,$url=""){
        echo " send wx $openid \n  ";
        $wx_config  = \App\Helper\Config::get_config("yxyx_wx");
        $wx         = new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );
        $xy_openid ="oAJiDwNulct06mAlmTTO97zKp_24";
        $wx->send_template_msg($xy_openid,$template_id,$data,$url);
        $jim_openid="oAJiDwMAO47ma8cUpCNKcRumg5KU";
        $wx->send_template_msg($jim_openid,$template_id,$data,$url);

        $is_success = $wx->send_template_msg($openid,$template_id,$data,$url);
        $task= new \App\Console\Tasks\TaskController();
        $task->t_weixin_msg->row_insert([
            "userid"      => 0,
            "openid"      => $openid,
            "send_time"   => time(),
            "templateid"  => $template_id,
            "title"       => "",
            "notify_data" => json_encode($data),
            "notify_url"  => $url,
            "is_success"  => $is_success?1:0,
        ]);
    }


    static public function sms_common($phone,$type,$data,$user_ip=0,$sign_name="理优教育")
    {
        $phone = (string)$phone;

        $is_success=0;
        if ($user_ip) {
            //每个ip 最多 10个
            if (!\App\Helper\Common::redis_day_add_with_max_limit("sms_ip_$user_ip",1, 20)){
                $send_flag_code=3;
            }
        }
        $receive_content="";

        //$test_flag=true;
        $test_flag=false;

        if ($is_success==0) {
            if ( \App\Helper\Utils::check_env_is_release()  || $test_flag) {
                $ret = \App\Helper\Common::send_sms_with_taobao($phone,"SMS_".$type,$data,$sign_name);
                $receive_content = json_encode($ret );
                if(property_exists($ret,"result") && $ret->result->err_code==="0") {
                    $is_success = 1;
                }else{
                    $send_email = false;
                    if ( $ret->code=="15" ) {
                        $sub_code = $ret->sub_code;
                        if ( $sub_code=="isv.BUSINESS_LIMIT_CONTROL"
                             || $sub_code=="isv.MOBILE_NUMBER_ILLEGAL"
                        ) {

                        }else{
                            $send_email=true;
                        }
                    }else{
                        $send_email=true;
                    }

                    if ( $send_email ) {
                        \App\Helper\Utils::logger("SEND MAIL " );
                        \App\Helper\Common::send_mail("xcwenn@qq.com","发短信出问题",
                                                      E\Esms_type::v2s($type).":".$phone .":".$receive_content."|||");
                    }
                    $is_success=0;
                }
            }
        }

        $task = new  \App\Console\Tasks\TaskController();
        $task->t_sms_msg->row_insert([
            "phone"           => $phone,
            "message"         => json_encode($data),
            "send_time"       => time(NULL),
            "receive_content" => $receive_content,
            "is_success"      => $is_success,
            "type"            => $type,
            "user_ip"         => $user_ip,
        ]);
        return $is_success;
    }

    /**
     * 获取老师课程的课时奖励
     * @param type 课时奖励类型
     * @param already_lesson_count 累计课时
     */
    static public function get_teacher_lesson_money($type,$already_lesson_count){
        $rule_type = \App\Config\teacher_rule::$rule_type;
        $reward    = 0;

        if(isset($rule_type[$type])){
            if($type == 7){ //武汉全职老师课时累计
                foreach($rule_type[$type] as $key=>$val){
                    if($already_lesson_count>$key){
                        $reward = $val;
                    }elseif($already_lesson_count<=$key){
                        break;
                    }
                }
            }else{
                foreach($rule_type[$type] as $key=>$val){
                    if($already_lesson_count>=$key){
                        $reward = $val;
                    }elseif($already_lesson_count<$key){
                        break;
                    }
                }
            }
        }
        return $reward;
    }

    /**
     * 供模拟工资使用
     */
    static public function get_teacher_lesson_money_simulate($type,$already_lesson_count){
        $rule_type = \App\Config\teacher_rule::reward_count_type_list(E\Ereward_count_type::V_1);
        $reward    = 0;
        if(isset($rule_type[$type])){
            foreach($rule_type[$type] as $key=>&$val){
                $val/=100;
                if($already_lesson_count>=$key){
                    $reward = $val;
                }elseif($already_lesson_count<$key){
                    break;
                }
            }
        }
        return $reward;
    }


    /**
     * @param identity 推荐的老师身份
     * @param num 已推荐的老师数量
     */
    static public function get_reference_money($identity,$num){
        $rule_type = \App\Config\teacher_rule::get_teacher_reference_rule($identity);
        $reward    = $rule_type[0];
        if(isset($rule_type) && !empty($rule_type)){
            foreach($rule_type as $key=>$val){
                if($num>$key){
                    $reward = $val;
                }elseif($num<=$key){
                    break;
                }
            }
        }
        return $reward;
    }

    static public function get_lesson_full_reward($lesson_full_num){
        $teacher_money = \App\Helper\Config::get_config("teacher_money");
        $full_num      = $teacher_money['lesson_full_num'];
        if($lesson_full_num%$full_num==0 && $lesson_full_num!=0){
            $lesson_full_reward=$teacher_money['lesson_full_reward']/100;
        }else{
            $lesson_full_reward=0;
        }
        return $lesson_full_reward;
    }


    /**
     * 通用的扣款金额,扣款金额可能会更改,不适用于某些特殊得扣款
     * @param lesson_info 课程信息
     * @param type        扣款类型
     * @return int        扣款金额
     */
    static function get_lesson_deduct_price($lesson_info,$type){
        //$price=2*$lesson_info['lesson_count']/100;
        $price = 5;
        return $price;
    }

    /**
     * 根据老师类型获取每堂试听课的价格
     * @param teacher_money_type 老师工资分类
     * @param teacher_type       老师全职类型
     * @param check_time         公司全职老师检测课程时间
     * @return int
     */
    static function get_trial_base_price($teacher_money_type,$teacher_type=0,$lesson_time=0){
        $teacher_money = \App\Helper\Config::get_config("teacher_money");
        $check_time    = strtotime("2017-4-1");
        $check_type    = self::check_teacher_money_type($teacher_money_type,$teacher_type);
        if(!$check_type){
            return 0;
        }

        if($teacher_type==3 && $lesson_time<$check_time){
            $trial_base = 50;
        }else{
            $trial_base = $teacher_money['trial_base_price'][$check_type]/100;
        }

        return $trial_base;
    }

    /**
     * 2017年09月09日18:29:48
     * 检测老师工资体系
     * type 1 课时累计由学生决定,旧版工资体系,试听课价格为50
     * type 3 课时累计由学生决定,公司全职老师,试听课价格为0
     * type 2 课时累计由上月常规+试听课时决定,试听课价格为30
     * type 4 课时累计由上月常规时决定,试听课价格为30
     * @param  teacher_money_type 老师工资分类
     * @param  teacher_type 老师类型
     * @return integer
     */
    static function check_teacher_money_type($teacher_money_type,$teacher_type=0){
        $type = 0;
        if(in_array($teacher_money_type,[0,1,2,3,7])){
            if($teacher_type == E\Eteacher_type::V_3){
                $type = 3;
            }else{
                $type = 1;
            }
        }elseif(in_array($teacher_money_type,[4,5])){
            $type = 2;
        }elseif(in_array($teacher_money_type,[6])){
            $type = 4;
        }
        return $type;
    }

    /**
     * 获取短信签名
     */
    static function get_sms_sign_name($sign_key=0){
        $sign_name = \App\Helper\Config::get_config("sms_sign_name");
        return $sign_name[$sign_key];
    }

    static function check_phone($phone)
    {
        return preg_match('/[0-9]{11}$/', $phone);
    }

    static function gen_duration_list( $data_list, $key_str, $duration_list=[],  $duration_str="duration" )
    {
        $ret_map = [];
        $init_ret_item=[
            $key_str => 0
        ];
        foreach ( $duration_list as $duration_val ) {
            $init_ret_item["f_$duration_val" ]=0;
        }
        $end_value=0xFFFFFFFF;
        $duration_list[]=$end_value;
        $init_ret_item["f_end" ]=$end_value;
        foreach ($data_list as $item) {
            $key = $item[$key_str];
            if (!isset($ret_map[$key])) {
                $tmp_init_item=$init_ret_item;
                $tmp_init_item[$key_str ] = $key;
                $ret_map[$key] = $tmp_init_item ;
            }
            $duration=$item[$duration_str];
            foreach ( $duration_list as $check_duration_val ) {
                if ($check_duration_val > $duration ) {
                    $ret_map[$key]["_f_$check_duration_val" ]++;
                    break;
                }
            }
        }

        return  $ret_map;
    }

    static function get_server_type_str($item){
        if(!isset($item['server_type'])){
            $item['server_type']=0;
        }

        if($item['server_type']==0){
            if(isset($item['lesson_type']) && $item['lesson_type']<1000){
                $server_type_str="默认:理优";
            }else{
                $server_type_str="默认:声网";
            }
        }elseif($item['server_type']==1){
            $server_type_str="理优";
        }else{
            $server_type_str="声网";
        }
        return $server_type_str;
    }


    static function gen_download_url($file_url)
    {
        //Qiniu_SetKeys($this->g_config['qiniu']['access_key'], $this->g_config['qiniu']['secret_key']);


        $qiniu     = \App\Helper\Config::get_config("qiniu");
        $base_url = $qiniu['private_url']['url'];
        $accessKey = $qiniu['access_key'];
        $secretKey = $qiniu['secret_key'];


        // 构建鉴权对象
        $auth = new \Qiniu\Auth ($accessKey, $secretKey);
        return   $auth->privateDownloadUrl($base_url."/".$file_url );
    }

    static public  function wx_send_todo_msg( $openid, $from_user, $header_msg,$msg="",$url="",$desc="点击进入管理系统操作") {
        if (substr($url,0,7 )!="http://") {
            $url="http://admin.leo1v1.com/".trim($url,"/ \t");
        }

        $template_id=" SqAHV3G2UM71LmLFRYeE0ub1-lDU0_JgrDNhdDd-FTA";
        $data = [
            "first"    => $header_msg,
            "keyword1" => $msg,
            "keyword2" => $from_user,
            "keyword3" => date("Y-m-d H:i:s"),
            "remark"   => $desc,
        ];
        $wx=new \App\Helper\Wx();
        $ret = $wx->send_template_msg($openid,$template_id,$data ,$url);
    }

    static public function get_qr_code_png($text,$outfile=false,$level=0,$size=100,$margin=4){
        include_once(app_path("Libs/phpqrcode.php"));
        return \QRcode::png($text,$outfile,$level,$size,$margin);
    }

    static public function get_teacher_lecture_file($subject,$grade_start,$grade_end,$not_grade=""){
        $subject_str     = E\Esubject::get_desc($subject);
        $file_url        = "http://leowww.oss-cn-shanghai.aliyuncs.com/TeacherLecturePPT/".$subject_str."/试讲内容——";
        $grade_all_str   = "";
        $grade_range_str = [
            1 => "101,102,103",
            2 => "104,105,106",
            3 => "201,202",
            4 => "203",
            5 => "301,302",
            6 => "303",
        ];

        if(in_array($subject,[1,2,3,5])){
            $grade_arr = [];
            for($i=$grade_start;$i<=$grade_end;$i++){
                $check_grade = $grade_range_str[$i];
                if(!strstr($not_grade,$check_grade)){
                    if(empty($grade_arr)){
                        if($subject==5 && $i==3){
                            $grade_all_str .= "初二";
                        }else{
                            $grade_all_str .= E\Egrade_range::get_desc($i);
                        }
                    }else{
                        $grade_arr[] = E\Egrade_range::get_desc($i);
                    }
                }else{
                    if($grade_all_str != "" && empty($grade_arr)){
                        $grade_arr[]=$grade_all_str;
                    }
                }
            }

            if(is_array($grade_arr) && !empty($grade_arr)){
                $grade_all_str = $grade_arr;
            }
        }else{
            if($grade_start>4){
                $grade_all_str .= "高中";
            }elseif($grade_start>2){
                $grade_all_str .= "初中";
                if($grade_end>4){
                    $grade_all_str .= "高中";
                }
            }
            if($grade_all_str!=""){
                $grade_all_str .= $subject_str;
            }
        }

        $html = "";
        if(is_array($grade_all_str)){
            foreach($grade_all_str as $val){
                $ppt_url=$file_url.$val.".pptx";
                $html.="<br><span class='red'>试讲内容</span>"
                     ."<a href='$ppt_url'>"
                     ."点击下载"
                     ."</a>";
            }
        }else{
            $ppt_url=$file_url.$grade_all_str.".pptx";
            $html.="<br><span class='red'>试讲内容</span>"
                ."<a href='$ppt_url'>"
                ."点击下载"
                ."</a>";
        }

        return $html;
    }

    static public function get_teacher_lecture_file_by_grade($subject,$grade){
        $subject_str  = E\Esubject::get_desc($subject);
        $grade_str    = E\Egrade::get_desc($grade);
        $file_url     = "http://leowww.oss-cn-shanghai.aliyuncs.com/TeacherLecturePPT/".$subject_str."/试讲内容——";
        $file_url    .= $grade_str.$subject_str.".pptx";

        return $file_url;
    }

    static function get_teacher_ref_rate($num){
        $teacher_ref_rate = \App\Helper\Config::get_config("teacher_ref_rate");
        foreach($teacher_ref_rate[2] as $rate_key=>$rate_val){
            if(!isset($last_rate)){
                $last_rate=$rate_val;
            }
            if($num<$rate_key){
                $rate=$last_rate;
                break;
            }
            $last_rate = $rate_val;
        }
        return $last_rate;
    }

    static function send_error_email($to,$title,$content){
        dispatch( new \App\Jobs\send_error_mail($to,$title,$content));
    }

    static public  function savePicToServer($pic_url,$savePathFile) {
        $targetName   = $savePathFile;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        // chmod($targetName,0777);
        $fp = fopen($targetName,'wb');



        curl_setopt($ch,CURLOPT_URL,$pic_url);
        curl_setopt($ch,CURLOPT_FILE,$fp);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $msg['state'] = curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        $msg['savePathFile'] = $savePathFile;

        \App\Helper\Utils::logger("savePathFile_msg:".json_encode($msg));

        return $msg;
    }

    static public function change_grade_to_grade_range($grade){
        $grade_range = [];
        switch($grade){
        case 100:
            $grade_range['grade_start']=1;$grade_range['grade_end']=2;
            break;
        case 200:
            $grade_range['grade_start']=3;$grade_range['grade_end']=4;
            break;
        case 300:
            $grade_range['grade_start']=5;$grade_range['grade_end']=6;
            break;
        default:
            $grade_range['grade_start']=0;$grade_range['grade_end']=0;
            break;
        }
        return $grade_range;
    }


    

    //黄嵩婕 71743 在2017-9-20之前所有都是60元/课时
    //张珍颖奥数 58812 所有都是75元/课时
    static public function get_teacher_base_money($teacherid,$lesson_info){
        $money            = $lesson_info['money'];
        //黄嵩婕切换新版工资版本时间,之前的课程计算工资不变,之后的工资变成新版工资
        $huang_check_time = strtotime("2017-9-20");
        $zhang_check_time = strtotime("2017-9-22");

        if($teacherid==71743 && $lesson_info['lesson_start']<$huang_check_time){
            $money=60;
        }elseif($teacherid==58812 && $lesson_info['competition_flag']==1 && $lesson_info['lesson_start']<$zhang_check_time){
            $money=75;
        }
        return $money;
    }

    /**
     * 检测某个参数是否存在并 赋值/增加
     * @param data 需检测的参数
     * @param add_data 检测后所增加的值
     * @param type 0 检测不存在后不执行增加操作 1 检测不存在后增加add_data的值
     */
    static public function check_isset_data(&$data,$add_data=1,$type=1){
        if(!isset($data)){
            $data = $add_data;
        }elseif($type==1){
            $data += $add_data;
        }
    }

    /**
     * 为 set_value 设置一个默认值
     * @param set_value 需要设置默认值的参数
     * @param check_data 需要检测的 参数/数组
     * @param default_value 如果 check_data 不存在，则给 set_value 设置的默认值
     * @param check_key 如果 check_data 为数组，则需要检测 check_data[check_key] 是否存在
     */
    static public function set_default_value(&$set_value,$check_data,$default_value=1,$check_key=0){
        self::check_isset_data($set_value,"");
        if(is_array($check_data)){
            $set_value = !isset($check_data[$check_key])?$default_value:$check_data[$check_key];
        }else{
            $set_value = !isset($check_data)?$default_value:$check_data;
        }
    }

    //计算百分比
    static public function get_rate($child,$mother,$point=2){
        return round($mother>0?($child/$mother):0,$point);
    }

    static public function pingapp(){
        include_once(app_path("Libs/Pingpp/Pingpp.php"));
        /**
         * 设置请求签名密钥，密钥对需要你自己用 openssl 工具生成，如何生成可以参考帮助中心：https://help.pingxx.com/article/123161；
         * 生成密钥后，需要在代码中设置请求签名的私钥(rsa_private_key.pem)；
         * 然后登录 [Dashboard](https://dashboard.pingxx.com)->点击右上角公司名称->开发信息->商户公钥（用于商户身份验证）
         * 将你的公钥复制粘贴进去并且保存->先启用 Test 模式进行测试->测试通过后启用 Live 模式
         */
        echo 11;
        \Pingpp::setApiKey("sk_test_ibbTe5jLGCi5rzfH4OqPW9KC");  // 设置 API Key
        \Pingpp::setPrivateKeyPath(__DIR__ . '/your_rsa_private_key.pem');   // 设置私钥


        // 设置私钥内容方式2
        \Pingpp\Pingpp::setPrivateKey(file_get_contents(__DIR__ . '/your_rsa_private_key.pem'));

        /**
         * $extra 在使用某些渠道的时候，需要填入相应的参数，其它渠道则是 array()。
         * 以下 channel 仅为部分示例，未列出的 channel 请查看文档 https://pingxx.com/document/api#api-c-new；
         * 或直接查看开发者中心：https://www.pingxx.com/docs/server；包含了所有渠道的 extra 参数的示例；
         */
        $extra = array();
        switch ($channel) {
        case 'alipay_wap':
            $extra = array(
                // success_url 和 cancel_url 在本地测试不要写 localhost ，请写 127.0.0.1。URL 后面不要加自定义参数
                'success_url' => 'http://example.com/success',
                'cancel_url' => 'http://example.com/cancel'
            );
            break;
        case 'bfb_wap':
            $extra = array(
                'result_url' => 'http://example.com/result',// 百度钱包同步回调地址
                'bfb_login' => true// 是否需要登录百度钱包来进行支付
            );
            break;
        case 'upacp_wap':
            $extra = array(
                'result_url' => 'http://example.com/result'// 银联同步回调地址
            );
            break;
        case 'wx_pub':
            $extra = array(
                'open_id' => 'openidxxxxxxxxxxxx'// 用户在商户微信公众号下的唯一标识，获取方式可参考 pingpp-php/lib/WxpubOAuth.php
            );
            break;
        case 'wx_pub_qr':
            $extra = array(
                'product_id' => 'Productid'// 为二维码中包含的商品 ID，1-32 位字符串，商户可自定义
            );
            break;
        case 'yeepay_wap':
            $extra = array(
                'product_category' => '1',// 商品类别码参考链接 ：https://www.pingxx.com/api#api-appendix-2
                'identity_id'=> 'your identity_id',// 商户生成的用户账号唯一标识，最长 50 位字符串
                'identity_type' => 1,// 用户标识类型参考链接：https://www.pingxx.com/api#yeepay_identity_type
                'terminal_type' => 1,// 终端类型，对应取值 0:IMEI, 1:MAC, 2:UUID, 3:other
                'terminal_id'=>'your terminal_id',// 终端 ID
                'user_ua'=>'your user_ua',// 用户使用的移动终端的 UserAgent 信息
                'result_url'=>'http://example.com/result'// 前台通知地址
            );
            break;
        case 'jdpay_wap':
            $extra = array(
                'success_url' => 'http://example.com/success',// 支付成功页面跳转路径
                'fail_url'=> 'http://example.com/fail',// 支付失败页面跳转路径
                /**
                 *token 为用户交易令牌，用于识别用户信息，支付成功后会调用 success_url 返回给商户。
                 *商户可以记录这个 token 值，当用户再次支付的时候传入该 token，用户无需再次输入银行卡信息
                 */
                'token' => 'dsafadsfasdfadsjuyhfnhujkijunhaf' // 选填
            );
            break;
        }


        try {
            $ch = \Pingpp\Charge::create(
                array(
                    //请求参数字段规则，请参考 API 文档：https://www.pingxx.com/api#api-c-new
                    'subject'   => 'Your Subject',
                    'body'      => 'Your Body',
                    'amount'    => $amount,//订单总金额, 人民币单位：分（如订单总金额为 1 元，此处请填 100）
                    'order_no'  => $orderNo,// 推荐使用 8-20 位，要求数字或字母，不允许其他字符
                    'currency'  => 'cny',
                    'extra'     => $extra,
                    'channel'   => $channel,// 支付使用的第三方支付渠道取值，请参考：https://www.pingxx.com/api#api-c-new
                    'client_ip' => $_SERVER['REMOTE_ADDR'],// 发起支付请求客户端的 IP 地址，格式为 IPV4，如: 127.0.0.1
                    'app'       => array('id' => APP_ID)
                )
            );
            echo $ch;// 输出 Ping++ 返回的支付凭据 Charge
        } catch (\Pingpp\Error\Base $e) {
            // 捕获报错信息
            if ($e->getHttpStatus() != null) {
                header('Status: ' . $e->getHttpStatus());
                echo $e->getHttpBody();
            } else {
                echo $e->getMessage();
            }
        }
        echo 22;
    }

    static public function change_grade_end_to_grade($teacher_info){
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

    static public function send_wx_to_parent($wx_openid,$template_id,$data,$url=""){
        $wx  = new \App\Helper\Wx();
        $ret = $wx->send_template_msg($wx_openid,$template_id,$data,$url);
    }

    static public function gen_url($base_url, $args=null ) {
        $arr=[];
        if ($args) {
            foreach (  $args as  $key=>$value ) {
                $arr[]="$key=" . urlencode($value);
            }
            return $base_url."?". join("&",$arr);
        }else{
            return $base_url;
        }
    }

    static public function gen_wx_teacher_url( $path_name, $args ) {
        $config=\App\Helper\Config::get_config("teacher_wx");
        $url=$config["url"];
        $url=trim($url, "/");
        $path_name=trim($path_name, "/");
        return \App\Helper\Utils::gen_url($url."/".$path_name, $args);
    }

    static public function get_up_grade($grade){
        if(in_array($grade,[106,203])){
            $up_grade = (int)substr($grade,0,1)+1;
            $grade    = $up_grade."01";
        }elseif($grade==303){
            $grade = (int)$grade;
        }else{
            $grade = (int)$grade+1;
        }
        return (int)$grade;
    }

    /**
     * 获取老师等级描述
     * 所有第四版都是星级等级描述
     * 旧版老师使用初,中,高等级描述
     * 非平台老师都为招师代理
     */
    static public function get_teacher_level_str($teacher_info){
        self::set_default_value($teacher_type,$teacher_info,E\Eteacher_type::V_0,"teacher_type");
        self::set_default_value($teacher_money_type,$teacher_info,E\Eteacher_money_type::V_6,"teacher_money_type");
        self::set_default_value($level,$teacher_info,E\Elevel::V_0,"level");

        if($teacher_type>20){
            $level_str="招师代理";
        }else{
            if($teacher_money_type==E\Eteacher_money_type::V_0){
                if($level<3){
                    $level_str = E\Elevel::$v2s_map[$level+1];
                }elseif($level==E\Elevel::V_3){
                    $level_str = "明星";
                }else{
                    $level_str = "";
                }
            }elseif(in_array($teacher_money_type,[E\Eteacher_money_type::V_2,E\Eteacher_money_type::V_3])){
                $level_str = "高级";
            }elseif($teacher_money_type==E\Eteacher_money_type::V_6){
                $level_str = E\Enew_level::$v2s_map[$level];
            }else{
                $level_str = E\Elevel::$v2s_map[$level];
            }
            $level_str.="教师";
        }
        return $level_str;
    }

    /**
     * 检测老师工资类型获取老师等级(字母形式:C,B,A...)
     */
    static public function get_teacher_letter_level($teacher_money_type,$level){
        if($teacher_money_type==E\Eteacher_money_type::V_6){
            $letter_level = E\Enew_level::get_desc($level);
        }else{
            $letter_level = E\Elevel::get_desc($level);
        }
        return $letter_level;
    }

    static public function seconds_to_string($secs=0){
        $r="";
        if($secs>=86400){
            $days=floor($secs/86400);
            $secs=$secs%86400;
            $r=$days.' day';
            if($days<>1){$r.='s';}
            if($secs>0){$r.=', ';}
        }
        if($secs>=3600){
            $hours=floor($secs/3600);
            $secs=$secs%3600;
            $r.=$hours.' hour';
            if($hours<>1){$r.='s';}
            if($secs>0){$r.=', ';}
        }
        if($secs>=60){
            $minutes=floor($secs/60);
            $secs=$secs%60;
            $r.=$minutes.' minute';
            if($minutes<>1){$r.='s';}
            if($secs>0){$r.=', ';}
        }
        $r.=$secs.' second';
        if($secs<>1){
            $r.='s';
        }
        return $r;
    }

    static public function change_key_value_arr($array){
        foreach($array as $key => $val){
            $ret_arr[] = [
                "cid"  => $key,
                "name" => $val,
            ];
        }
        return $ret_arr;
    }

    static public function get_specify_select($array=[]){
        $map_array = static::$desc_map;
        if(!empty($array)){
            $array     = array_flip($array);
            $map_array = array_intersect_key($map_array,$array);
        }
        return $map_array;
    }

    static public function get_teacher_contact_way($teacher_info){
        if(isset($teacher_info['phone_spare']) && $teacher_info['phone_spare']!=""){
            $phone=$teacher_info['phone_spare'];
        }else{
            $phone=$teacher_info['phone'];
        }

        return $phone;
    }

    static public function array_keys_to_string($array,$point=","){
        $keys_string = "";
        if(is_array($array) && !empty($array)){
            $keys_array = array_keys($array);
            $keys_string = implode($point,$keys_array);
        }
        return $keys_string;
    }





        //  处理反馈图片上传
    /**
       老师帮 微信
       只有一张图片时 直接将图片放入数据库 不需要压缩
    **/
   static public function deal_feedback_img($serverId_str,$sever_name)
    {
        $serverIdLists = explode(',',$serverId_str);
        $alibaba_url   = [];
        $alibaba_url_origi = [];

        $ret = [];


        foreach($serverIdLists as $serverId){
            $imgStateInfo = self::savePicToServer_for_img($serverId);
            $savePathFile = $imgStateInfo['savePathFile'];
            $file_name = self::put_img_to_alibaba($savePathFile);
            $alibaba_url_origi[] = $savePathFile;
            $alibaba_url[] = $file_name ;
            unlink($savePathFile);
        }

        $ret['alibaba_url_str'] = implode(',',$alibaba_url);

        // 原始图片处理压缩包
        if ( count($serverIdLists)>1) {
            $alibaba_url_str_compress = implode(' ',$alibaba_url_origi);
            $tar_name  = "/tmp/".md5(date('YmdHis').rand()).".tar.gz";
            $cmd       = "tar -cvzf $tar_name $alibaba_url_str_compress ";
            $ret_tar   = \App\Helper\Utils::exec_cmd($cmd);
            $ret['file_name_origi'] = self::put_img_to_alibaba($tar_name);
            @unlink($tar_name);
            foreach($alibaba_url_origi as $item_orgi){
                @unlink($item_orgi);
            }
        }

        return $ret;
    }


    static public function savePicToServer_for_img($serverId ,$appid_tec= 'wxa99d0de03f407627', $appscript_tec= '61bbf741a09300f7f2fd0a861803f920' ) {
        $accessToken = self::get_wx_token_jssdk( $appid_tec, $appscript_tec);
        // 要存在你服务器哪个位置？
        $route = md5(date('YmdHis').rand());
        $savePathFile = '/tmp/'.$route.'.jpg';
        $targetName   = $savePathFile;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $fp = fopen($targetName,'wb');
        curl_setopt($ch,CURLOPT_URL,"http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$accessToken&media_id=$serverId");
        curl_setopt($ch,CURLOPT_FILE,$fp);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $msg['state'] = curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        $msg['savePathFile'] = $savePathFile;
        return $msg;
    }

    static public function put_img_to_alibaba($target){
        try {
            $config=\App\Helper\Config::get_config("ali_oss");
            $file_name=basename($target);

            $ossClient = new OssClient(
                $config["oss_access_id"],
                $config["oss_access_key"],
                $config["oss_endpoint"],
                false
            );
            $bucket=$config["public"]["bucket"];
            $ossClient->uploadFile($bucket, $file_name, $target  );

            return $config["public"]["url"]."/".$file_name;
        } catch (OssException $e) {
            return "" ;
        }
    }

   static public function get_wx_token_jssdk($appid_tec= 'wxa99d0de03f407627', $appscript_tec= '61bbf741a09300f7f2fd0a861803f920' ){
        $wx        = new \App\Helper\Wx();
        return $wx->get_wx_token($appid_tec,$appscript_tec);
    }



    static public function img_to_pdf($filesnames){
        ini_set("memory_limit",'-1');

        header("Content-type:text/html;charset=utf-8");

        $hostdir = public_path('wximg');

        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


        foreach ($filesnames as $name) {
            if(strstr($name,'jpg') || (strstr($name,'png') )){//如果是图片则添加到pdf中
                // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
                $pdf->AddPage();//添加一个页面
                $filename = $hostdir.'/'.$name;//拼接文件路径

                //gd库操作  读取图片
                if(strstr($name,'jpg')){
                    $source = imagecreatefromjpeg($filename);
                }elseif(strstr($name,'png')){
                    $source = imagecreatefrompng($filename);
                }
                //gd库操作  旋转90度
                $rotate = imagerotate($source, 0, 0);
                //gd库操作  生成旋转后的文件放入别的目录中
                $tmp_name = time().'_'.rand();

                if(strstr($name,'jpg')){
                    imagejpeg($rotate,$hostdir."/$tmp_name.jpg");
                }elseif(strstr($name,'png')){
                    imagepng($rotate,$hostdir."/$tmp_name.png");
                }

                //tcpdf操作  添加图片到pdf中
                if(strstr($name,'jpg')){
                    $pdf->Image($hostdir."/$tmp_name.jpg", 15, 26, 210, 297, 'JPG', '', 'center', true, 1000);
                }elseif(strstr($name,'png')){
                    $pdf->Image($hostdir."/$tmp_name.png", 15, 26, 210, 297, 'PNG', '', 'center', true, 1000);
                }

            }
        }

        $pdf_name_tmp =$hostdir.'/'.time().'_'.rand().'.pdf';
        $pdf_info = $pdf->Output("$pdf_name_tmp", 'FD');

        $pdf_url = $this->qiniu_upload($pdf_name_tmp);

        unlink($pdf_name_tmp);
        return $pdf_url;
    }

    static public function send_curl_post($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        $ret = curl_multi_getcontent($ch);
        curl_close($ch);
        return $ret;
    }

    /**
     * @param type redis操作类型
     * @param key   redis存储的键
     * @param value  redis存储的值
     * @param json_decode 进行get操作时是否进行json处理
     */
    static public function redis($type,$key,$value=[],$json_flag=false){
        if($type==E\Eredis_type::V_GET){
            $value = Redis::get($key);
            if($json_flag){
                $value = json_decode($value,true);
            }
        }elseif($type==E\Eredis_type::V_SET){
            if(is_array($value)){
                $value=json_encode($value);
            }
            Redis::set($key,$value);
        }elseif($type==E\Eredis_type::V_DEL){
            Redis::del($key);
        }
        return $value;
    }

    static public function effective_lesson_sql(&$where_arr,$prefix=""){
        if($prefix!=""){
            $prefix = $prefix.".";
        }
        $where_arr[] = $prefix."lesson_del_flag=0";
        $where_arr[] = $prefix."confirm_flag!=2";
    }

    static public function transform_1tg_0tr(&$item,$field){
        $new_field = $field.'_str';
        if( $item[$field]  === '0' ){
            $item[$new_field] = '<span style="color:red">否</span>';
        } else if($item[$field]  === '1' ){
            $item[$new_field] = '<span style="color:green">是</span>';
        } else {
            $item[$new_field] = '';
        }
    }

    static public function revisit_warning_type_count($item, &$warning_type_count){
        $one    = time();
        $two    = $one - 86400*5;
        $three  = $one - 86400*7;
        $retime = $item['revisit_time'];
        if ($retime >= $two) {
            $warning_type_count['warning_type_one'] = @$warning_type_count['warning_type_one'] + 1;
        } else if ($retime < $two & $retime >= $three) {
            $warning_type_count['warning_type_two'] = @$warning_type_count['warning_type_two'] + 1;
        }
    }

    static public function format_teacher_birth(&$item){
        $birth = $item['birth'];
        if(strlen($birth) != 0) {
            $year = substr($birth,0,4);
            $month = substr($birth,4,2);
            $day = substr($birth,6);
            $item['birth'] = $year.'-'.$month.'-'.$day;
        }
    }


};
