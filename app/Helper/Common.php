<?php
namespace App\Helper;
use Illuminate\Support\Facades\Log ;
use Illuminate\Support\Facades\Redis;
use \App\Enums as  E;

class Common {
    static function env_obj( $key, $def =null ) {
        $str=env($key,"");
        if(!$str) {
            return $def;
        }
        $obj=json_decode($str,true);
        if ($obj===null) {
            //$str= "  env json_decode err, key= $key, value = $str  ";
            //throw new \Exception( $str );
        }
        return $obj;
    }

    static public function  merge_row_data( &$row_list_1,$row_list_2,$field_name ) {
        foreach ($row_list_2 as  $item ) {
            $k= $item[$field_name];
            if  (! isset( $row_list_1[$k] ) ) {
                $row_list_1[$k] = $item;
            }else{
                $row_list_1[$k]=array_merge($row_list_1[$k], $item   );
            }
        }
    }

    static function  get_enum_color_str($value ,$desc , $config_arr) {
        if (isset( $config_arr[$value])) {
            return "<font color=\"{$config_arr[$value]}\">$desc</font>" ;
        }
    }
    static function set_item_enum_str( &$item, $field_name ,$enum_class ,$config_arr  ) {
        $v= $item[$field_name];
        $item["$field_name"."_str"] = static::get_enum_color_str( $v, $enum_class::get_desc($v),$config_arr  );
    }
    static function set_item_enum_flow_status( &$item, $field_name="flow_status" ) {
        static::set_item_enum_str( $item, $field_name,  E\Eflow_status::class, [
            1=> "blue",
            2=> "green",
            3=> "red",
        ]  );
    }

    static function get_wx_token(  $appid, $appsecret  ) {
        $key="wx_token_$appid";
        $ret_arr=\App\Helper\Common::redis_get_json($key);
        $now=time(NULL);
        if (!$ret_arr ||   $ret_arr["get_time"]+7000 <  $now ) {
            \App\Helper\Utils::logger ( "SEND_QQAPI 2222" );
            $json_data=file_get_contents( "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret"  );
            $ret_arr=\App\Helper\Utils::json_decode_as_array($json_data);
            $ret_arr["get_time"]=time(NULL);
            \App\Helper\Common::redis_set_json($key,$ret_arr );
        }
        return $ret_arr["access_token"];
    }

    /**
     * 得到房间里所有的用户
     */
    static function get_room_users ( $roomid,$config ) {
        $xmpp_server = new \XMPPOperator($config['ip'], $config['xmpp_port'],
                                         "sys_user", "xx",
                                         $config['ip']);
        return $xmpp_server->get_room_user($roomid);
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

    static function div_safe( $v1, $v2 ){
        if  ($v2==0) {
            return 0;
        }else{
            return $v1/$v2 ;
        }
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
                //完整性验证失败
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

    static function json_decode_as_array( $str) {
        return json_decode($str,true) ;
    }

    static function json_decode_as_int_array( $str) {
        $ret_arr=self::json_decode_as_array($str);
        foreach($ret_arr as &$item ) {
            $item=intval($item);
        }
        return $ret_arr;
    }

    /**
     * 模板名称: 通用验证
     * 模板ID: SMS_7771547
     * 模板内容: 您的手机验证码为：${code} ，请尽快完成验证 编号为： ${index}
     * 不要直接使用 , 要用 \App\Helper\Common::sms_common($phone,$sms_id,$arr);
     */
    public static function send_sms_with_taobao($phone,$template_code,$data,$sign_name="理优教育"){
        include_once( app_path("Libs/taobao_sms/TopSdk.php") );

        // foreach ($data as   &$value) {
        //     $value=strval($value);
        // }
        $c = new \TopClient();

        /**
         * 原账号的短信被限制,将 10671030,10671029 两个验证码短信切换到另一个账号上发送
         */
        if($template_code == "SMS_10671030"){
            $template_code = "SMS_7795923";
        }elseif($template_code == "SMS_10671029"){
            $template_code = "SMS_7771547";
        }

        /**
         * array( 7795923 ,'register','用户注册验证码',),
         * array( 7786570,'','通知家长预约成功',),
         * array( 7771547,'','通用验证',),
         * array( 8295424 ,'','课程当天早上通知',),
         */
        $template_value = substr($template_code,4);

        if ( $template_value==7795923
            ||$template_value==7786570
            ||$template_value==7771547
            ||$template_value==8295424
        ){
            $c->appkey ="23287514" ;
            $c->secretKey = "232fee572ba45d17cbe6fed8d39678ab";
        }else{
            $c->appkey ="23388285" ;
            $c->secretKey = "cf52133f47748ac2330e9a22fa423d8e";
        }

        $c->format="json";
        $req = new \AlibabaAliqinFcSmsNumSendRequest();

        $req->setSmsType("normal");
        $req->setSmsFreeSignName($sign_name);

        $req->setSmsParam(json_encode($data));
        $req->setRecNum( $phone);
        $req->setSmsTemplateCode($template_code);
        try {
            $resp = $c->execute($req);
        }catch(\Exception $e ) {

        }
        return $resp;
    }

    // public static function send_voice_with_taobao($phone,$template_code,$data,$sign_name="理优教育"){
    //     include_once( app_path("Libs/taobao_sms/TopSdk.php") );

    //     $c = new \TopClient();

    //     /**
    //      * array( 7795923 ,'register','用户注册验证码',),
    //      * array( 7786570,'','通知家长预约成功',),
    //      * array( 7771547,'','通用验证',),
    //      * array( 8295424 ,'','课程当天早上通知',),
    //      */
    //     $template_value = substr($template_code,4);
    //     $c->appkey ="23388285" ;
    //     $c->secretKey = "cf52133f47748ac2330e9a22fa423d8e";

    //     $c->format="json";
    //     $req = new AlibabaAliqinFcVoiceNumSinglecallRequest;

    //     $req->setExtend("12345");
    //     $req->setCalledNum($phone);
    //     // $req->setCalledShowNum("4001112222");
    //     // $req->setVoiceCode("c2e99ebc-2d4c-4e78-8d2a-afbb06cf6216.wav");

    //     $req->setSmsParam(json_encode($data));
    //     $req->setSmsTemplateCode($template_code);
    //     try {
    //         $resp = $c->execute($req);
    //     }catch(\Exception $e ) {

    //     }
    //     return $resp;
    // }

    static function output_html($str) {
        echo "<head> <meta charset=\"UTF-8\"> <head> <body>$str" ;
    }

    static function unixtime2date($timestamp ){
        return date('Y-m-d H:i:s', $timestamp);
    }

    // 获取指定日期所在星期的开始时间与结束时间 ,
    function get_week_range( $timestamp,$start_fix=0){
        $ret = array();
        //%w Numeric representation of the day of the week   0 (for Sunday) through 6 (for Saturday)
        $w = strftime('%w',$timestamp);
        if ($start_fix==0){//周日
            $start= $timestamp-($w-$start_fix)*86400;
        }else{ //周1 ==1
            if ($w==0){
                $w=7;
            }
            $start= $timestamp-($w-$start_fix)*86400;
        }

        $ret['sdate'] =  strtotime( date('Y-m-d 00:00:00',$start));
        $ret['edate'] =   $ret['sdate'] + 86400*7-1;
        return $ret;
    }

    // 获取指定日期所在月的开始日期与结束日期
    static function get_month_range($timestamp ){
        $ret = array();
        $mdays        = date('t',$timestamp);
        $ret['sdate'] = strtotime( date('Y-m-1 00:00:00',$timestamp));
        $ret['edate'] =strtotime( date('Y-m-'.$mdays.' 23:59:59',$timestamp));
        return $ret;
    }
    static function get_phone_location($phone) {
        $phone=trim($phone);
        if ($phone =="" ) {
            return "" ;
        }
        $url= "https://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=$phone";

        $data= preg_replace("/__GetZoneResult_ = /","",
                            Net::send_post_data($url,[] )
        );
        $data= preg_replace("/([A-Za-z]*):/","\"\\1\":", $data);
        $data= preg_replace("/'/","\"", $data);

        $data = iconv("GBK","utf-8",$data);
        $arr  = json_decode($data,true);
        return  isset($arr["carrier"])?$arr["carrier"]:"";

        // if(isset($arr['province']) && isset($arr['catName'])){
        //     $phone_location = $arr['province'].$arr['catName'];
        // }else{
        //     $phone_location = "";
        // }
        // return  $phone_location;
    }

    static function dispache_mail(  $address ,$title ,$message  ) {
        $job=new \App\Jobs\SendEmail( $address,
                                      $title,
                                      $message );
        dispatch($job);
    }

    static function send_paper_mail ( $address ,$title ,$message ,$is_html=true) {
        require_once( app_path("Libs/mail/class.phpmailer.php"));
        require_once( app_path("Libs/mail/class.smtp.php"));
        date_default_timezone_set('Asia/Shanghai');//设定时区东八区

        /**  @var  $mail PHPMailer  */

        $mail = new \PHPMailer(); //建立邮件发送类

        $mail->IsSMTP(); // 使用SMTP方式发送
        $mail->CharSet ="UTF-8";//设置编码，否则发送中文乱码
        $mail->Host = "smtp.leoedu.cn"; // 您的企业邮局域名
        // $mail->Host = "mail.leoedu.com"; // 您的企业邮局域名
        $mail->SMTPAuth = true; // 启用SMTP验证功能
        $mail->Username = "trc@leoedu.cn"; // 邮局用户名(请填写完整的email地址)
        $mail->Password = "xcwen@142857"; // 邮局密码

        $mail->From = "emd@leoedu.com"; //邮件发送者email地址
        $mail->FromName = "理优教学管理部";

        if (is_array(  $address)) {
            foreach ( $address as $item ){
                $mail->AddAddress($item, $item);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
            }
        }else{
            $mail->AddAddress($address, $address);
        }
        $mail->IsHTML($is_html); // set email format to HTML //是否使用HTML格式
        //$mail->AddReplyTo("", "");

        //$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件

        $mail->Subject = $title;
        $mail->Body = $message;
        //$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
        $ret=$mail->Send();
        if(!$ret) {
            \App\Helper\Utils::logger("email err2:". $mail->ErrorInfo);
        }
        return $ret;
    }

    static function send_mail( $address ,$title ,$message ,$is_html=true) {

        require_once( app_path("Libs/mail/class.phpmailer.php"));
        require_once( app_path("Libs/mail/class.smtp.php"));
        date_default_timezone_set('Asia/Shanghai');//设定时区东八区

        /**  @var  $mail PHPMailer  */

        $mail = new \PHPMailer(); //建立邮件发送类

        $mail->IsSMTP(); // 使用SMTP方式发送
        $mail->CharSet ="UTF-8";//设置编码，否则发送中文乱码
        $mail->Host = "smtp.leoedu.cn"; // 您的企业邮局域名
        //$mail->Host = "webmail.euchost.com"; // 您的企业邮局域名
        //$mail->SMTPSecure = 'tls';
        //$mail->Port = 465;


        $mail->SMTPAuth = true; // 启用SMTP验证功能
        $mail->Username = "jim@leoedu.cn"; // 邮局用户名(请填写完整的email地址)
        $mail->Password = "xcwen@142857"; // 邮局密码

        $mail->From = "jim@leoedu.cn"; //邮件发送者email地址
        $mail->FromName = "jim同学  ";

        if (is_array(  $address)) {
            foreach ( $address as $item ){
                $mail->AddAddress($item, $item);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
            }
        }else{
            $mail->AddAddress($address, $address);
        }
        //$mail->AddReplyTo("", "");

        //$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
        $mail->IsHTML($is_html); // set email format to HTML //是否使用HTML格式

        $mail->Subject = $title;
        $mail->Body = $message;
        //$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
        $ret= $mail->Send();
        if(!$ret) {
            \App\Helper\Utils::logger("email err:". $mail->ErrorInfo);
        }
        return  $ret;
    }

    static function send_mail_admin( $address ,$title ,$message ,$is_html=true) {
        require_once( app_path("Libs/mail/class.phpmailer.php"));
        require_once( app_path("Libs/mail/class.smtp.php"));
        date_default_timezone_set('Asia/Shanghai');//设定时区东八区

        /**  @var  $mail PHPMailer  */

        $mail = new \PHPMailer(); //建立邮件发送类

        $mail->IsSMTP(); // 使用SMTP方式发送
        $mail->CharSet ="UTF-8";//设置编码，否则发送中文乱码
        $mail->Host = "smtp.leoedu.cn"; // 您的企业邮局域名
        //$mail->Host = "webmail.euchost.com"; // 您的企业邮局域名
        //$mail->SMTPSecure = 'tls';
        //$mail->Port = 465;

        $mail->SMTPAuth = true; // 启用SMTP验证功能
        $mail->Username = "jim@leoedu.com"; // 邮局用户名(请填写完整的email地址)
        $mail->Password = "xcwen@142857"; // 邮局密码

        $mail->From = "jim@leoedu.com"; //邮件发送者email地址
        $mail->FromName = "理优监课组";

        if (is_array(  $address)) {
            foreach ( $address as $i=> $item ){
                if ($i==0) {
                    $mail->AddAddress($item,$item);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
                }else{
                    $mail->addCC($item,$item);
                }
            }
        }else{
            $mail->AddAddress($address, $address);
        }
        //$mail->AddReplyTo("", "");

        //$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
        $mail->IsHTML($is_html); // set email format to HTML //是否使用HTML格式

        $mail->Subject = $title;
        $mail->Body = $message;
        //$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
        $ret= $mail->Send();
        if(!$ret) {
            \App\Helper\Utils::logger("ADMIN email ".json_encode($address)." err: ". $mail->ErrorInfo);
        }else{
            \App\Helper\Utils::logger("ADMIN email ".json_encode($address)." SUCC");
        }
        return $ret;
    }


    static function send_mail_leo_com ( $address ,$title ,$message ,$is_html=true) {
        require_once( app_path("Libs/mail/class.phpmailer.php"));
        require_once( app_path("Libs/mail/class.smtp.php"));
        date_default_timezone_set('Asia/Shanghai');//设定时区东八区

        /**  @var  $mail PHPMailer  */

        $mail = new \PHPMailer(); //建立邮件发送类

        $mail->IsSMTP(); // 使用SMTP方式发送
        $mail->CharSet ="UTF-8";//设置编码，否则发送中文乱码
        $mail->Host = "smtp.leoedu.com"; // 您的企业邮局域名


        $mail->SMTPAuth = true; // 启用SMTP验证功能
        $mail->SMTPSecure="tls";
        $mail->Username = "jim@leoedu.com"; // 邮局用户名(请填写完整的email地址)
        $mail->Password = "xcwen142857"; // 邮局密码

        $mail->From = "jim@leoedu.com"; //邮件发送者email地址
        $mail->FromName = "理优教研组";

        if (is_array($address)) {
            foreach ( $address as $i => $item ){
                if ($i==0) {
                    $mail->AddAddress($item,$item);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
                }else{
                    $mail->addCC($item,$item);
                }
            }
        }else{
            $mail->AddAddress($address, $address);
        }
        //$mail->AddReplyTo("", "");

        //$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
        $mail->IsHTML($is_html); // set email format to HTML //是否使用HTML格式

        $mail->Subject = $title;
        $mail->Body = $message;
        //$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
        $ret = $mail->Send();
        if(!$ret) {
            \App\Helper\Utils::logger(" leo_com:email err: ".json_encode($address)." :$title  ". $mail->ErrorInfo);
        }else{
            \App\Helper\Utils::logger(" leo_com:email succ: ".json_encode($address)." :$title " );
        }
        return  $ret;
    }

    static function send_paper_mail_new ( $address ,$title ,$message ,$is_html=true) {

        require_once( app_path("Libs/mail/class.phpmailer.php"));
        require_once( app_path("Libs/mail/class.smtp.php"));
        date_default_timezone_set('Asia/Shanghai');//设定时区东八区

        /**  @var  $mail PHPMailer  */

        $mail = new \PHPMailer(); //建立邮件发送类

        $mail->IsSMTP(); // 使用SMTP方式发送
        $mail->CharSet ="UTF-8";//设置编码，否则发送中文乱码
        $mail->Host = "smtp.leoedu.com"; // 您的企业邮局域名


        $mail->SMTPAuth = true; // 启用SMTP验证功能
        $mail->SMTPSecure="tls";
        $mail->Username = "jim@leoedu.com"; // 邮局用户名(请填写完整的email地址)
        $mail->Password = "xcwen142857"; // 邮局密码

        $mail->From = "jim@leoedu.com"; //邮件发送者email地址
        $mail->FromName = "理优教学管理部";

        if (is_array($address)) {
            foreach ( $address as $item ){
                $mail->AddAddress($item, $item);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
            }
        }else{
            $mail->AddAddress($address, $address);
        }
        //$mail->AddReplyTo("", "");

        //$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
        $mail->IsHTML($is_html); // set email format to HTML //是否使用HTML格式

        $mail->Subject = $title;
        $mail->Body = $message;
        //$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
        $ret= $mail->Send();
        if(!$ret) {
            \App\Helper\Utils::logger(" leo_com:email err: $address :$title  ". $mail->ErrorInfo);
        }else{
            \App\Helper\Utils::logger(" leo_com:email succ: $address :$title " );
        }
        return  $ret;
    }

    static function send_mail_leo_com_new ( $address ,$title ,$message ,$is_html=true) {

        return $this->send_mail_admin($address,$title,$message,$is_html);

        require_once( app_path("Libs/mail/class.phpmailer.php"));
        require_once( app_path("Libs/mail/class.smtp.php"));
        date_default_timezone_set('Asia/Shanghai');//设定时区东八区

        /**  @var  $mail PHPMailer  */

        $mail = new \PHPMailer(); //建立邮件发送类

        $mail->IsSMTP(); // 使用SMTP方式发送
        $mail->CharSet ="UTF-8";//设置编码，否则发送中文乱码
        $mail->Host = "smtp.leoedu.com"; // 您的企业邮局域名


        $mail->SMTPAuth = true; // 启用SMTP验证功能
        $mail->SMTPSecure="tls";
        $mail->Username = "jim@leoedu.com"; // 邮局用户名(请填写完整的email地址)
        $mail->Password = "xcwen142857"; // 邮局密码

        $mail->From = "jim@leoedu.com"; //邮件发送者email地址
        $mail->FromName = "理优1对1";

        if (is_array($address)) {
            foreach ( $address as $item ){
                $mail->AddAddress($item, $item);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
            }
        }else{
            $mail->AddAddress($address, $address);
        }
        //$mail->AddReplyTo("", "");
        $mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
        $mail->IsHTML($is_html); // set email format to HTML //是否使用HTML格式

        $mail->Subject = $title;
        $mail->Body = $message;
        //$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
        $ret= $mail->Send();
        if(!$ret) {
            \App\Helper\Utils::logger(" leo_com:email err: $address :$title  ". $mail->ErrorInfo);
        }else{
            \App\Helper\Utils::logger(" leo_com:email succ: $address :$title " );
        }
        return  $ret;
    }


    static function send_mail_qq ( $address ,$title ,$message ,$is_html=true) {

        require_once( app_path("Libs/mail/class.phpmailer.php"));
        require_once( app_path("Libs/mail/class.smtp.php"));
        date_default_timezone_set('Asia/Shanghai');//设定时区东八区

        /**  @var  $mail PHPMailer  */

        $mail = new \PHPMailer(); //建立邮件发送类

        //$mail->IsSMTP(); // 使用SMTP方式发送
        $mail->Mailer = 'SMTP';
        $mail->CharSet ="UTF-8";//设置编码，否则发送中文乱码
        $mail->Host = "smtp.qq.com";
        $mail->SMTPAuth = true; // 启用SMTP验证功能
        $mail->Port = 25;
        $mail->Username = "329732001"; // 邮局用户名(请填写完整的email地址)
        $mail->Password = "message"; // 邮局密码

        $mail->From = "xcwenn@qq.com"; //邮件发送者email地址
        $mail->FromName = "jim qq ";

        if (is_array(  $address)) {
            foreach ( $address as $item ){
                $mail->AddAddress($item, $item);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
            }
        }else{
            $mail->AddAddress($address, $address);
        }
        //$mail->AddReplyTo("", "");

        //$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
        $mail->IsHTML($is_html); // set email format to HTML //是否使用HTML格式

        $mail->Subject = $title;
        $mail->Body = $message;
        //$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
        $ret= $mail->Send();
        if(!$ret) {
            \App\Helper\Utils::logger("QQ email err:". $mail->ErrorInfo);
        }
        return  $ret;

    }

    static public function  redis_get_json_date($key) {
        $date_key=date("Ymd");
        $json_ret=\App\Helper\Common::redis_get_json($key);
        if (!$json_ret || $json_ret["opt_date"] != $date_key ) {
            $json_ret=[
                "opt_date" => $date_key ,
                "opt_count" => 0,
            ];
            \App\Helper\Common::redis_set_json($key, $json_ret);
        }
        return $json_ret["opt_count"];
    }

    static public function  redis_set_json_date_add($key,$max_value,$add_value=1 ) {
        $date_key=date("Ymd");
        $json_ret=\App\Helper\Common::redis_get_json($key);
        if (!$json_ret || $json_ret["opt_date"] != $date_key ) {
            $json_ret=[
                "opt_date" => $date_key ,
                "opt_count" => 0,
            ];
        }
        $opt_count=$json_ret["opt_count"];
        $opt_count+=$add_value;
        if ($opt_count>$max_value)  {
            return false;
        }
        $json_ret["opt_count"]=$opt_count;
        \App\Helper\Common::redis_set_json($key, $json_ret);
        return  $opt_count;
    }

    static public function  redis_get_json($key) {
        return json_decode( Redis::get($key),true );
    }

    static public function  redis_set_json($key,$data) {
        return Redis::set($key,json_encode($data)) ;
    }

    static public function  redis_get($key,$dbid=null) {
        if ($dbid) {
            Redis::select($dbid);
        }
        return  Redis::get($key);
    }

    static public function  redis_del($key,$dbid=null) {
        if ($dbid) {
            Redis::select($dbid);
        }
        return  Redis::del($key);
    }

    static public function  redis_set($key,$data,$dbid=null) {
        if ($dbid) {
            Redis::select($dbid);
        }
        return Redis::set($key,$data) ;
    }

    /**
     * 设置key的有效时间
     * @param string key redis中存放的键
     * @param int    ttl 该键值的有效时间（单位：秒）
     * @return boolean
     */
    static public function redis_expire($key,$ttl){
        return Redis::expire($key,$ttl);
    }

    /**
     * 设置key的过期时间
     * @param string key redis中存放的键
     * @param int    timestamp 键值过期时间戳
     * @return boolean
     */
    static public function redis_expireat($key,$timestamp){
        return Redis::expireat($key,$timestamp);
    }

    static public function redis_set_expire_value($key,$data,$ttl){
        $ret = self::redis_set_json($key,$data);
        if($ret){
            $ret = self::redis_expire($key,$ttl);
        }
        return $ret;
    }

    static public function redis_day_add_with_max_limit( $key , $add_count ,$max_value) {
        $data = json_decode( Redis::get($key) ,true);
        $opt_date=date("Y-m-d" );
        if ($data["opt_date"] !=$opt_date ) {
            $data["opt_date"]= $opt_date ;
            $data["count"]= $add_count;
        }else{
            $data["count"]+= $add_count;
        }

        if ($data["count"]>$max_value ) {
            return false;
        }else{
            Redis::set($key,json_encode($data));
            return true;
        }
    }

    /**
     * 生成随机code
     */
    static public function gen_rand_code($length=4) {
        $ret="";
        for($i=0;$i<$length;$i++) {
            $ret.=rand()%10;
        }
        return $ret;
    }
    static public  function get_local_ip(){

    }
    static public function http_post_json_str($url, $json_str ) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$json_str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json_str))
        );
        $result = curl_exec($ch);
        return $result;
    }

    static public function http_post_json($url, $arr) {
        $data_string = json_encode($arr);
        static::http_post_json_str($url,$data_string);
    }

    static public function json_encode_zh($data) {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    static public function get_item_from_priority_list( $priority_list, $exclude_map )
    {
        $len=count($priority_list);
        $i=0;
        for ($i= 0; $i < 100; $i++) {
            $rand_index=rand(0,$len-1);
            $item=$priority_list[$rand_index];
            if (!isset($exclude_map[$item] )) {
                return $item;
            }
        }
        return false;
    }

    static function xml2array($contents, $get_attributes=1, $priority = 'tag')
    {
        if(!$contents) return array();

        if(!function_exists('xml_parser_create')) {
            //print "'xml_parser_create()' function not found!";
            return array();
        }

        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        @xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);

        if(!$xml_values) return;//Hmm...

        //Initializations
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();

        $current = &$xml_array; //Refference

        //Go through the tags.
        $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
        foreach($xml_values as $data) {
            unset($attributes,$value);//Remove existing values, or there will be trouble

            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data);//We could use the array by itself, but this cooler.

            $result = array();
            $attributes_data = array();

            if(isset($value)) {
                if($priority == 'tag') $result = $value;
                else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
            }

            //Set the attributes too.
            if(isset($attributes) and $get_attributes) {
                foreach($attributes as $attr => $val) {
                    if($priority == 'tag') $attributes_data[$attr] = $val;
                    else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                }
            }

            //See tag status and do the needed.
            if($type == "open") {//The starting of the tag '<tag>'
                $parent[$level-1] = &$current;
                if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag.'_'.$level] = 1;

                    $current = &$current[$tag];

                } else { //There was another element with the same tag name

                    if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                        $repeated_tag_index[$tag.'_'.$level]++;
                    } else {//This section will make the value an array if multiple tags with the same name appear together
                        $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                        $repeated_tag_index[$tag.'_'.$level] = 2;

                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }

                    }
                    $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                    $current = &$current[$tag][$last_item_index];
                }

            } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if(!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

                } else { //If taken, put all things inside a list(array)
                    if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                        // ...push the new element into that array.
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

                        if($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag.'_'.$level]++;

                    } else { //If it is not an array...
                        $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                        $repeated_tag_index[$tag.'_'.$level] = 1;
                        if($priority == 'tag' and $get_attributes) {
                            if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

                                $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                                unset($current[$tag.'_attr']);
                            }

                            if($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                    }
                }

            } elseif($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level-1];
            }
        }

        return($xml_array);
    }
    static function gen_date_time_list($start_time,$end_time, $date_data_list )  {
        $date_list=\App\Helper\Common::get_date_time_list($start_time,$end_time-1);
        \App\Helper\Common::merge_row_data($date_list, $date_data_list ,"date");
        return $date_list;
    }

    static function get_date_time_list($start_time,$end_time) {
        $ret=[];
        $year=date("Y",$start_time);
        $same_year_flag=true;
        for($i=$start_time;$i<=$end_time;$i+=86400 ) {
            if($year != date("Y",$i)  ) {
                $same_year_flag=false;
                break;
            }
        }


        for($i=$start_time;$i<=$end_time;$i+=86400 ) {
            $date=date("Y-m-d",$i);
            if($same_year_flag){
                $title=date("m-d",$i);
            }else{
                $title= substr($date,2);
            }
            $ret["$date"]=[
                "title" => $title
            ];
        }
        return $ret;
    }
    static function get_time_format($seconds) {
        if (!$seconds ) {
            return "无";
        }
        $t=$seconds;
        $s=$t%60;
        $t=($t-$s)/60;
        $m=$t%60;
        $h=($t-$m)/60;
        return sprintf("%02d:%02d:%02d", $h,$m, $s);
    }

    static function get_time_format_minute($seconds) {
        if (!$seconds ) {
            return "无";
        }
        $t=$seconds;
        $s=$t%60;
        $m=($t-$s)/60;
        return sprintf("%02d:%02d", $m, $s);
    }


    static function get_url_ex($url) {
        $domain = config('admin')['qiniu']['private_url']['url'];
        $secret = config('admin')['qiniu']['secret_key'];
        $access = config('admin')['qiniu']['access_key'];
        $keyEsc = str_replace("%2F", "/", rawurlencode($url));
        $baseUrl =  $domain."/".$keyEsc;
        #$deadline = $this->Expires;
        if (!isset($deadline)) {
            $deadline = 3600;
        }
        $deadline += time();

        $pos = strpos($baseUrl, '?');
        if ($pos !== false) {
            $baseUrl .= '&e=';
        } else {
            $baseUrl .= '?e=';
        }
        $baseUrl .= $deadline;

        #$token = Qiniu_Sign($mac, $baseUrl);
        $sign = hash_hmac('sha1', $baseUrl, $secret, true);
        $find = array('+', '/');
        $replace = array('-', '_');
        $str_Encode =  str_replace($find, $replace, base64_encode($sign));

        $token =  $access.':'.$str_Encode;

        return $baseUrl."&token=".$token;
    }

    static function get_boolean_color_str($value) {
        if ($value) {
            $color = "green";
            $str="是";
        }else{
            $color = "red";
            $str="否";
        }
        return "<font color=".$color.">".$str."</font> ";
    }

    static function get_set_boolean_color_str($val) {
        $str=E\Eset_boolean::get_desc($val);
        switch ( $val) {
        case 1 :
            $color="green";
            break;
        case 2 :
            $color="red";
            break;
        case 0 :
            $color="blue";
            break;

        default:
            $color="red";
            break;
        }
        return "<font color=".$color.">".$str."</font> ";
    }


    static function get_set_state_color_str($val) {
        $str=E\Ecomplaint_state::get_desc($val);
        switch ( $val) {
        case 1 :
            $color="green";
            break;
        case 2 :
            $color="red";
            break;
        case 0 :
            $color="blue";
            break;

        default:
            $color="red";
            break;
        }
        return "<font color=".$color.">".$str."</font> ";
    }



    static function get_test_pager_boolean_color_str($value,$time) {
        if ($value) {
            $color = "green";
            if($time>0){
                $str="老师已下载<br>".date("Y-m-d H:i",$time);
            }else{
                $str="老师未下载";
            }
        }else{
            $color = "red";
            $str   = "无试卷";
        }
        return "<font color=".$color.">".$str."</font> ";
    }

    static function get_test_pager_boolean_color_str_new($value,$time) {
        if ($value) {
            if($time>0){
                $str="老师已下载";
            }else{
                $str="老师未下载";
            }
        }else{
            $str   = "无试卷";
        }
        return $str;
    }


    static function gen_admin_member_data($old_list,$no_need_sum_list=[],$monthtime_flag=1,$month=0)
    {
        /**  @var  $t_manager_info \App\Models\t_manager_info  */
        $t_manager_info=new  \App\Models\t_manager_info ();
        $task=new \App\Console\Tasks\TongjiTask() ;

        if($monthtime_flag==1 || strtotime( date("Y-m-01")) == $month ){
            $admin_list = $t_manager_info->get_admin_member_list();
        }else{
            $admin_list = $t_manager_info->get_admin_member_list_new($month);
        }

        $admin_list=$admin_list["list"] ;
        $cur_key_index=1;
        $check_init_map_item=function (&$item, $key, $key_class, $adminid = "",$groupid="",$become_member_time=0,$leave_member_time=0,$create_time=0,$del_flag=0,$seller_level=0) {
            global $cur_key_index;
            if (!isset($item [$key])) {
                $item[$key] = [
                    "groupid"=>$groupid,
                    "adminid" => $adminid,
                    "key_class" => $key_class."-".$cur_key_index,
                    "sub_list"=>[] ,
                    "data" => array(),
                    "become_member_time"=>$become_member_time,
                    "leave_member_time" =>$leave_member_time,
                    "create_time"  =>$create_time,
                    "del_flag"  =>$del_flag,
                    "seller_level"  =>$seller_level,
                ];
                $cur_key_index++;
            }
        };

        $add_data=function (&$item, $add_item , $self_flag=false)  use (&$no_need_sum_list) {
            $arr=&$item["data"];
            if ($self_flag) {
                //dd( $item);
            }

            foreach ($add_item as $k => $v) {
                if (!is_int($k) && $k!="main_type" && $k!="up_group_name" && $k!="group_name" && $k!="account"   && $k!="adminid" && $k!= "groupid" && $k!= "become_member_time" && $k!= "leave_member_time" && $k!= "create_time" && $k!= "del_flag" && $k!= "seller_level"
                    && ($self_flag || !in_array( $k,$no_need_sum_list ) ) ) {
                    if ($self_flag) {
                        $arr[$k]=$v;
                    }else{
                        if (!isset($arr[$k])) {
                            $arr[$k]=0;
                        }
                        $arr[$k]+=$v;
                    }
                }
            }
        };

        $check_init_map_item($data_map,"","");
        foreach ($old_list as &$item) {
            $adminid=$item["adminid"];
            //g.main_type,g.group_name group_name,g.groupid groupid,m.group_name up_group_name,am.uid adminid
            // "am.create_time,am.become_member_time,am.leave_member_time,am.del_flag ".
            if (isset($admin_list[ $adminid])) {
                $admin_item= $admin_list[ $adminid] ;
                $item['main_type']=$admin_item["main_type"];
                $item['up_group_name']=$admin_item["up_group_name"];
                $item['group_name']=$admin_item["group_name"];
                $item['groupid']=$admin_item["groupid"];
                $item['account']=$admin_item["account"];
                $item['become_member_time']=$admin_item["become_member_time"];
                $item['leave_member_time']=$admin_item["leave_member_time"];
                $item['create_time']=$admin_item["create_time"];
                $item['del_flag']=$admin_item["del_flag"];
                $item['seller_level']=$admin_item["seller_level"];
            }else{

            }


            if (empty($item['main_type'])) {
                $item['main_type']="未定义";
                $item['up_group_name']="未定义";
                $item['group_name']="未定义";
                $item['account']= $task->cache_get_account_nick($adminid);
                $item['groupid']= 0;
                $item['become_member_time']=0;
                $item['leave_member_time']=0;
                $item['create_time']=0;
                $item['del_flag']=0;
                $item['seller_level']=0;
            }


            $main_type          = $item['main_type'];
            $up_group_name      = $item["up_group_name"];
            $group_name         = $item["group_name"];
            $account            = $item["account"];
            $groupid            = $item['groupid'];
            $become_member_time = $item['become_member_time'];
            $leave_member_time  = isset($item['leave_member_time'])?$item['leave_member_time']:0;
            $create_time        = isset($item['create_time'])?$item['create_time']:0;
            $del_flag           = isset($item['del_flag'])?$item['del_flag']:0;
            $seller_level       = isset($item['seller_level'])?$item['seller_level']:0;
            $key0_map           = &$data_map[""];
            $add_data($key0_map, $item );

            $check_init_map_item($key0_map["sub_list"] , $main_type,"main_type" );
            $key1_map=&$key0_map["sub_list"][$main_type];
            $add_data($key1_map, $item );

            $check_init_map_item($key1_map["sub_list"] , $up_group_name ,"up_group_name");
            $key2_map=&$key1_map["sub_list"][$up_group_name];
            $add_data($key2_map, $item );

            $check_init_map_item($key2_map["sub_list"] , $group_name ,"group_name","",$groupid);
            $key3_map=&$key2_map["sub_list"][$group_name];
            $add_data($key3_map, $item );

            $check_init_map_item($key3_map["sub_list"] , $account,"account",$adminid,$groupid,$become_member_time,$leave_member_time,$create_time,$del_flag,$seller_level);
            $key4_map=&$key3_map["sub_list"][$account];
            $add_data($key4_map, $item,true );

        }
        $list=[];
        foreach ($data_map as $key0 => $item0) {
            $data=$item0["data"];
            $data["main_type"]="全部";
            $data["up_group_name"]="";
            $data["group_name"]="";
            $data["account"]="";
            $data["main_type_class"]="";
            $data["up_group_name_class"]="";
            $data["group_name_class"]="";
            $data["account_class"]="";
            $data["level"]="l-0";

            $list[]=$data;
            foreach ($item0["sub_list"] as $key1 => $item1) {
                $data=$item1["data"];
                $data["main_type"]=$key1;
                $data["up_group_name"]="";
                $data["group_name"]="";
                $data["account"]="";
                $data["main_type_class"]=$item1["key_class"];
                $data["up_group_name_class"]="";
                $data["group_name_class"]="";
                $data["account_class"]="";
                $data["level"]="l-1";


                $list[]=$data;

                foreach ($item1["sub_list"] as $key2 => $item2) {
                    $data=$item2["data"];
                    $data["main_type"]=$key1;
                    $data["up_group_name"]=$key2;
                    $data["group_name"]="";
                    $data["account"]="";
                    $data["main_type_class"]=$item1["key_class"];
                    $data["up_group_name_class"]=$item2["key_class"];
                    $data["group_name_class"]="";
                    $data["account_class"]="";
                    $data["level"]="l-2";

                    $list[]=$data;
                    foreach ($item2["sub_list"] as $key3 => $item3) {
                        $data=$item3["data"];
                        $data["main_type"]=$key1;
                        $data["up_group_name"]=$key2;
                        $data["group_name"]=$key3;
                        $data["account"]="";
                        $data["main_type_class"]=$item1["key_class"];
                        $data["up_group_name_class"]=$item2["key_class"];
                        $data["group_name_class"]=$item3["key_class"];
                        $data["account_class"]="";
                        $data['groupid'] = $item3['groupid'];
                        $data["level"]="l-3";

                        $list[]=$data;
                        foreach ($item3["sub_list"] as $key4 => $item4) {
                            $data=$item4["data"];
                            $data["main_type"]=$key1;
                            $data["up_group_name"]=$key2;
                            $data["group_name"]=$key3;
                            $data["account"]=$key4;
                            $data["main_type_class"]=$item1["key_class"];
                            $data["up_group_name_class"]=$item2["key_class"];
                            $data["group_name_class"]=$item3["key_class"];
                            $data["account_class"]=$item4["key_class"];
                            $data['adminid'] = $item4['adminid'];
                            $data['groupid'] = $item4['groupid'];
                            $data["level"]="l-4";
                            $data['become_member_time']=$item4["become_member_time"];
                            $data['leave_member_time']=$item4["leave_member_time"];
                            $data['create_time']=$item4["create_time"];
                            $data['del_flag']=$item4["del_flag"];
                            $data['seller_level']=$item4["seller_level"];

                            $list[]=$data;
                        }
                    }
                }
            }
        }
        return $list;
    }

    static function gen_admin_member_data_new($old_list,$no_need_sum_list=[],$monthtime_flag=1,$month=0)
    {
        /**  @var  $t_manager_info \App\Models\t_manager_info  */
        $t_manager_info=new  \App\Models\t_manager_info ();
        $task=new \App\Console\Tasks\TongjiTask() ;
        if($monthtime_flag==1 || strtotime( date("Y-m-01")) == $month ){//非历史组织架构
            // $admin_list = $t_manager_info->get_admin_member_list();
            $admin_list = $t_manager_info->get_admin_member_list_tmp(); // test
        }else{//月组织架构
            $admin_list = $t_manager_info->get_admin_member_list_new($month);
        }

        $admin_list=$admin_list["list"] ;
        $cur_key_index=1;
        $check_init_map_item=function (&$item, $key, $key_class, $adminid = "",$groupid="",$become_member_time=0,$leave_member_time=0,$create_time=0,$del_flag=0,$seller_level=0) {
            global $cur_key_index;
            if (!isset($item [$key])) {
                $item[$key] = [
                    "groupid"=>$groupid,
                    "adminid" => $adminid,
                    "key_class" => $key_class."-".$cur_key_index,
                    "sub_list"=>[] ,
                    "data" => array(),
                    "become_member_time"=>$become_member_time,
                    "leave_member_time" =>$leave_member_time,
                    "create_time"  =>$create_time,
                    "del_flag"  =>$del_flag,
                    "seller_level"  =>$seller_level,
                ];
                $cur_key_index++;
            }
        };

        $add_data=function (&$item, $add_item , $self_flag=false)  use (&$no_need_sum_list) {
            $arr=&$item["data"];
            if ($self_flag) {
                //dd( $item);
            }

            foreach ($add_item as $k => $v) {
                if (!is_int($k) && $k!="main_type" && $k!="up_group_name" && $k!="group_name" && $k!="account"   && $k!="adminid" && $k!= "groupid" && $k!= "become_member_time" && $k!= "leave_member_time" && $k!= "create_time" && $k!= "del_flag" && $k!= "seller_level" && $k!= "first_group_name"
                    && ($self_flag || !in_array( $k,$no_need_sum_list ) ) ) {
                    if ($self_flag) {
                        $arr[$k]=$v;
                    }else{
                        if (!isset($arr[$k])) {
                            $arr[$k]=0;
                        }
                        $arr[$k]+=$v;
                    }
                }
            }
        };

        $check_init_map_item($data_map,"","");
        foreach ($old_list as &$item) {
            $adminid=$item["adminid"];
            //g.main_type,g.group_name group_name,g.groupid groupid,m.group_name up_group_name,am.uid adminid
            // "am.create_time,am.become_member_time,am.leave_member_time,am.del_flag ".
            if (isset($admin_list[ $adminid])) {
                $admin_item= $admin_list[ $adminid] ;
                $item['main_type']=$admin_item["main_type"];
                $item['first_group_name']=$admin_item["first_group_name"];
                $item['up_group_name']=$admin_item["up_group_name"];
                $item['group_name']=$admin_item["group_name"];
                $item['groupid']=$admin_item["groupid"];
                $item['account']=$admin_item["account"];
                $item['become_member_time']=$admin_item["become_member_time"];
                $item['leave_member_time']=$admin_item["leave_member_time"];
                $item['create_time']=$admin_item["create_time"];
                $item['del_flag']=$admin_item["del_flag"];
                $item['seller_level']=$admin_item["seller_level"];
            }else{

            }


            if (empty($item['main_type'])) {
                $item['main_type']="未定义";
                $item['first_group_name']="未定义";
                $item['up_group_name']="未定义";
                $item['group_name']="未定义";
                $item['account']= $task->cache_get_account_nick($adminid);
                $item['groupid']= 0;
                $item['become_member_time']=0;
                $item['leave_member_time']=0;
                $item['create_time']=0;
                $item['del_flag']=0;
                $item['seller_level']=0;
            }


            $main_type          = $item['main_type'];
            $first_group_name   = $item["first_group_name"];
            $up_group_name      = $item["up_group_name"];
            $group_name         = $item["group_name"];
            $account            = $item["account"];
            $groupid            = $item['groupid'];
            $become_member_time = $item['become_member_time'];
            $leave_member_time  = isset($item['leave_member_time'])?$item['leave_member_time']:0;
            $create_time        = isset($item['create_time'])?$item['create_time']:0;
            $del_flag           = isset($item['del_flag'])?$item['del_flag']:0;
            $seller_level       = isset($item['seller_level'])?$item['seller_level']:0;
            $key0_map           = &$data_map[""];
            $add_data($key0_map, $item );

            $check_init_map_item($key0_map["sub_list"] , $main_type,"main_type" );
            $key1_map=&$key0_map["sub_list"][$main_type];
            $add_data($key1_map, $item );

            $check_init_map_item($key1_map["sub_list"] , $first_group_name ,"first_group_name");
            $key2_map=&$key1_map["sub_list"][$first_group_name];
            $add_data($key2_map, $item );

            $check_init_map_item($key2_map["sub_list"] , $up_group_name ,"up_group_name");
            $key3_map=&$key2_map["sub_list"][$up_group_name];
            $add_data($key3_map, $item );

            $check_init_map_item($key3_map["sub_list"] , $group_name ,"group_name","",$groupid);
            $key4_map=&$key3_map["sub_list"][$group_name];
            $add_data($key4_map, $item );

            $check_init_map_item($key4_map["sub_list"] , $account,"account",$adminid,$groupid,$become_member_time,$leave_member_time,$create_time,$del_flag,$seller_level);
            $key5_map=&$key4_map["sub_list"][$account];
            $add_data($key5_map, $item,true );

        }
        $list=[];
        foreach ($data_map as $key0 => $item0) {
            $data=$item0["data"];
            $data["main_type"]="全部";
            $data["first_group_name"]="";
            $data["up_group_name"]="";
            $data["group_name"]="";
            $data["account"]="";
            $data["main_type_class"]="";
            $data["first_group_name_class"]="";
            $data["up_group_name_class"]="";
            $data["group_name_class"]="";
            $data["account_class"]="";
            $data["level"]="l-0";

            $list[]=$data;
            foreach ($item0["sub_list"] as $key1 => $item1) {
                $data=$item1["data"];
                $data["main_type"]=$key1;
                $data["first_group_name"]="";
                $data["up_group_name"]="";
                $data["group_name"]="";
                $data["account"]="";
                $data["main_type_class"]=$item1["key_class"];
                $data["first_group_name_class"]="";
                $data["up_group_name_class"]="";
                $data["group_name_class"]="";
                $data["account_class"]="";
                $data["level"]="l-1";


                $list[]=$data;
                foreach ($item1["sub_list"] as $key2 => $item2) {
                    $data=$item2["data"];
                    $data["main_type"]=$key1;
                    $data["first_group_name"]=$key2;
                    $data["up_group_name"]="";
                    $data["group_name"]="";
                    $data["account"]="";
                    $data["main_type_class"]=$item1["key_class"];
                    $data["first_group_name_class"]=$item2["key_class"];
                    $data["up_group_name_class"]="";
                    $data["group_name_class"]="";
                    $data["account_class"]="";
                    $data["level"]="l-2";

                    $list[]=$data;
                    foreach ($item2["sub_list"] as $key3 => $item3) {
                        $data=$item3["data"];
                        $data["main_type"]=$key1;
                        $data["first_group_name"]=$key2;
                        $data["up_group_name"]=$key3;
                        $data["group_name"]="";
                        $data["account"]="";
                        $data["main_type_class"]=$item1["key_class"];
                        $data["first_group_name_class"]=$item2["key_class"];
                        $data["up_group_name_class"]=$item3["key_class"];
                        $data["group_name_class"]="";
                        $data["account_class"]="";
                        $data["level"]="l-3";

                        $list[]=$data;
                        foreach ($item3["sub_list"] as $key4 => $item4) {
                            $data=$item4["data"];
                            $data["main_type"]=$key1;
                            $data["first_group_name"]=$key2;
                            $data["up_group_name"]=$key3;
                            $data["group_name"]=$key4;
                            $data["account"]="";
                            $data["main_type_class"]=$item1["key_class"];
                            $data["first_group_name_class"]=$item2["key_class"];
                            $data["up_group_name_class"]=$item3["key_class"];
                            $data["group_name_class"]=$item4["key_class"];
                            $data['groupid'] = $item4['groupid'];
                            $data["account_class"]="";
                            $data["level"]="l-4";

                            $list[]=$data;
                            foreach ($item4["sub_list"] as $key5 => $item5) {
                                $data=$item5["data"];
                                $data["main_type"]=$key1;
                                $data["first_group_name"]=$key2;
                                $data["up_group_name"]=$key3;
                                $data["group_name"]=$key4;
                                $data["account"]=$key5;
                                $data["main_type_class"]=$item1["key_class"];
                                $data["first_group_name_class"]=$item2["key_class"];
                                $data["up_group_name_class"]=$item3["key_class"];
                                $data["group_name_class"]=$item4["key_class"];
                                $data['groupid'] = $item4['groupid'];
                                $data["account_class"]=$item5["key_class"];
                                $data["level"]="l-5";
                                $data['adminid'] = $item5['adminid'];
                                $data['become_member_time']=$item5["become_member_time"];
                                $data['leave_member_time']=$item5["leave_member_time"];
                                $data['create_time']=$item5["create_time"];
                                $data['del_flag']=$item5["del_flag"];
                                $data['seller_level']=$item5["seller_level"];
                                $list[]=$data;
                            }
                        }
                    }
                }
            }
        }
        return $list;
    }


    //得到ip 信息
    static function get_ip_addr_str( $ip ) {
        $ip_info =  json_decode( @file_get_contents("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=$ip"), true);
        return  $ip_info["province"] ."-" .$ip_info["city"]  ;
    }
    static function get_ip_addr_str_new( $ip ) {
        $ip_info =  json_decode( @file_get_contents("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=$ip"), true);
        return  @$ip_info["city"];
    }

    static function sort_value_asc_func( $a ,$b ) {
        if ($a<$b) {
            return -1;
        }
        if  ($a==$b) {
            return 0;
        }
        return 1;
    }
    static function sort_value_desc_func( $a ,$b ) {
        if ($a>$b) {
            return -1;
        }
        if  ($a==$b) {
            return 0;
        }
        return 1;
    }


    static function httpcopy($url, $file="", $timeout=60) {
        $file = empty($file) ? pathinfo($url,PATHINFO_BASENAME) : $file;
        $dir = pathinfo($file,PATHINFO_DIRNAME);
        !is_dir($dir) && @mkdir($dir,0755,true);
        $url = str_replace(" ","%20",$url);

        if(function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $temp = curl_exec($ch);
            if(@file_put_contents($file, $temp) && !curl_error($ch)) {
                return $file;
            } else {
                return false;
            }
        } else {
            $opts = array(
                "http"=>array(
                    "method"=>"GET",
                    "header"=>"",
                    "timeout"=>$timeout)
            );
            $context = stream_context_create($opts);
            if(@copy($url, $file, $context)) {
                //$http_response_header
                return $file;
            } else {
                return false;
            }
        }
    }


    static  function check_in_phone(){
        // 先检查是否为wap代理，准确度高
        if(stristr(@$_SERVER['HTTP_VIA'],"wap")){
            return true;
        }
        // 检查浏览器是否接受 WML.
        elseif(strpos(strtoupper(@$_SERVER['HTTP_ACCEPT']),"VND.WAP.WML") > 0){
            return true;
        }
        //检查USER_AGENT
        elseif(preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', @$_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
        else{
            return false;
        }
    }

    static function sort_pinyin ($list , $field_name ) {
        foreach ($list as &$item ) {
            $item["_gb_k"]= @iconv('UTF-8', 'GBK', $item[$field_name ]);
        }
        usort($list,
              function($a, $b){
                  $a_v=@$a["_gb_k"] ;
                  $b_v=@$b["_gb_k"] ;
                  return strcasecmp($a_v ,$b_v );
              });

        foreach ($list as &$item2 ) {
            unset($item2["_gb_k"]);
        }

        return $list;
    }


    public static function encrypt($input, $key) {
        $size = \mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = static::pkcs5_pad($input, $size);
        $td = \mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = \mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        \mcrypt_generic_init($td, $key, $iv);
        $data = \mcrypt_generic($td, $input);
        \mcrypt_generic_deinit($td);
        \mcrypt_module_close($td);
        $data = \base64_encode($data);
        return $data;
    }

    private static function pkcs5_pad ($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    public static function decrypt($sStr, $sKey) {
        $decrypted = mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            $sKey,
            base64_decode($sStr),
            MCRYPT_MODE_ECB
        );

        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s-1]);
        $decrypted = substr($decrypted, 0, -$padding);
        return $decrypted;
    }

    static public function gen_order_pdf($orderid,$username,$grade,$competition_flag,$lesson_count,$price,$one_lesson_count,$per_lesson_interval,$order_start_time,$order_end_time,$gong_zhang_flag,$flag_str,$type_2_lesson_count,$phone,$parent_name){
        $work_dir   = app_path("OrderPdf");
        $order_temp = file_get_contents("$work_dir/order_temp.tex");

        $search=[
            "#UserName#",
            "#LessonInfo#",
            "#PriceInfo#",
            "#OrderStartTime#",
            "#OrderEndTime#",
            "#PerLessonCount#",
            "#OneLessonTime#",
            "#GongZhang#",
            "#UserPhone#",
            "#ParentName#",
            "#ParentPhone#",
            "#FreeLessonCount#",
        ];

        $lesson_info=E\Egrade::get_desc($grade)."($lesson_count)课时" . E\Ecompetition_flag::get_desc($competition_flag) ;
        if ($type_2_lesson_count ) {
            //$lesson_info.=",赠送($type_2_lesson_count)课时 ";
        }
        $replace=[
            $username,
            $lesson_info,
            static::cny($price)."整($price)",
            \App\Helper\Utils::unixtime2date($order_start_time, "Y年m月d日" ),
            \App\Helper\Utils::unixtime2date($order_end_time , "Y年m月d日" ),
            $one_lesson_count,
            $per_lesson_interval,
            $gong_zhang_flag?"gz.png": "gz_null.png",
            $phone,
            $parent_name,
            $phone,
            $type_2_lesson_count ,
        ];
        $order_sex= str_replace($search,$replace, $order_temp );
        $base_file_name= "order_{$orderid}_$flag_str" ;
        if( $gong_zhang_flag ) {
            $base_file_name.="_gz";
        }
        $pdf_file="/tmp/$base_file_name.pdf" ;
        $sex_file="/tmp/$base_file_name.tex";
        file_put_contents($sex_file  , $order_sex );
        $ret=\App\Helper\Utils::exec_cmd(" $work_dir/mktex.sh $sex_file  ");
        $qiniu_file_name=\App\Helper\Utils::qiniu_upload($pdf_file);

        //$ret=\App\Helper\Utils::exec_cmd("rm -rf /tmp/$base_file_name.*");
        return Config::get_qiniu_public_url()."/". $qiniu_file_name;
    }

        static public function gen_order_pdf_empty() {
            $work_dir=app_path("OrderPdf");
        $order_temp= file_get_contents("$work_dir/order_temp.tex");

        $search=[
            "#UserName#",
            "#LessonInfo#",
            "#PriceInfo#",
            "#OrderStartTime#",
            "#OrderEndTime#",
            "#PerLessonCount#",
            "#OneLessonTime#",
            "#GongZhang#",
            "#UserPhone#",
            "#ParentName#",
            "#ParentPhone#",
            "#FreeLessonCount#",
        ];

        // $lesson_info=E\Egrade::get_desc($grade)."($lesson_count)课时" . E\Ecompetition_flag::get_desc($competition_flag) ;
        // if ($type_2_lesson_count ) {
        //     //$lesson_info.=",赠送($type_2_lesson_count)课时 ";
        // }
        $replace=[
            "　　　",
            "　　　",
            "　　　",
            "　　　",
            "　　　",
            "　　　",
            "　　　",
            "gz_null.png",
            "　　　",
            "　　　",
            "　　　",
            "　　　"
        ];
        $order_sex= str_replace($search,$replace, $order_temp );
        $base_file_name= "order_empty_unique_leo123" ;
        // if( $gong_zhang_flag ) {
        //     $base_file_name.="_gz";
        // }
        $pdf_file="/tmp/$base_file_name.pdf" ;
        $sex_file="/tmp/$base_file_name.tex";
        file_put_contents($sex_file  , $order_sex );
        $ret=\App\Helper\Utils::exec_cmd(" $work_dir/mktex.sh $sex_file  ");
        $qiniu_file_name=\App\Helper\Utils::qiniu_upload($pdf_file);

        //$ret=\App\Helper\Utils::exec_cmd("rm -rf /tmp/$base_file_name.*");
        return Config::get_qiniu_public_url()."/". $qiniu_file_name;
        }


    static   function cny($ns) {

        static $cnums=array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"),
                     $cnyunits=array("圆","角","分"),
                     $grees=array("拾","佰","仟","万","拾","佰","仟","亿");
        @list($ns1,$ns2)=@explode(".",$ns,2);
        $ns2=@array_filter(array($ns2[1],$ns2[0]));
        $ret=@array_merge($ns2,array(implode("",static::_cny_map_unit(str_split($ns1),$grees)),""));
        $ret=@implode("",array_reverse( static::_cny_map_unit($ret,$cnyunits)));
        return @str_replace(array_keys($cnums),$cnums,$ret);
    }

    static function _cny_map_unit($list,$units) {
        $ul=count($units);
        $xs=array();
        foreach (array_reverse($list) as $x) {
            $l=count($xs);
            if ($x!="0" || !($l%4)) $n=($x=='0'?'':$x).($units[($l-1)%$ul]);
            else $n=is_numeric($xs[0][0])?$x:'';
            array_unshift($xs,$n);
        }
        return $xs;
    }

    static function secsToStr($secs) {// 将秒数转变 小时数
        $r = '';
        if($secs>=86400){$days=floor($secs/86400);
            $secs=$secs%86400;
            $r=$days.'天';
            // if($days<>1){$r.='s';}
            // if($secs>0){$r.=', ';}
        }

        if($secs>=3600){$hours=floor($secs/3600);
            $secs=$secs%3600;
            $r.=$hours.'小时';
            // if($hours<>1){$r.='s';}
            // if($secs>0){$r.=', ';}
        }

        if($secs>=60){
            $minutes=floor($secs/60);
            $secs=$secs%60;
            $r.=$minutes.'分钟';
            // if($minutes<>1){$r.='s';}
            // if($secs>0){$r.=', ';}}
            $r.=$secs.'秒';
            // if($secs<>1)
            // {$r.='s';}
            return $r;
        }
    }

    static function sortArrByField(&$array, $field, $desc = false){
        $fieldArr = array();
        foreach ($array as $k => $v) {
            $fieldArr[$k] = $v[$field];
        }
        $sort = $desc == false ? SORT_ASC : SORT_DESC;
        array_multisort($fieldArr, $sort, $array);
    }
    static function size_str($size) {
        if ( $size> 1024*1024*1024)  {
            return  sprintf("%.2fGB", $size/(1024*1024*1024));
        } else if ( $size> 1024*1024)  {
            return  sprintf("%.2fMB", $size/(1024*1024));
        } else if ( $size> 1024)  {
            return  sprintf("%.2fKB", $size/(1024));
        }else{
            return  $size;
        }
    }


    static public function gen_echarts_time_data($data_list, $field_time="logtime", $field_value="value" ) {
        $time_list = [];
        foreach  ( $data_list  as $item  ) {
            $time_list  [] =  [
                "value" => [ date("Y-m-d H:i:s" ,$item[ $field_time ]), $item[$field_value]   ]
            ];
        }
        return $time_list;
    }

    static public function gen_day_time_list($time_list,$start_time,$end_time, $field_time="logtime", $field_value="value" ) {
        if (count($time_list) != 1440 ) {
            $t=$start_time;
            $tmp_list=[];
            foreach ( $time_list as $item ) {
                $c_time=$item[ $field_time];
                while ( $t < $c_time -60  ) {
                    $tmp_list[]= [$field_value =>null];
                    $t+=60;
                }
                $tmp_list[]= $item;
                $t+=60;
            }
            for ( ; $t<$end_time; $t+=60  ) {
                $tmp_list[]= [ $field_value =>null];
            }
            $time_list=$tmp_list;
        }
        return $time_list;
    }

};