<?php
function check_utf8_encode($str){
    if (strlen($str)>0) {
        $tmp_data=json_encode(["v" => $str ]);
        if (!$tmp_data) {
            return mb_substr($str ,0, mb_strlen($str,"utf8")-1, "utf8" ) ; //去掉最后一个无效字
        }else{
            return $str;
        }
    }else{
        return $str;
    }
}


function deal_json_utf8_encode(&$arr )   {
    foreach ($arr as &$value) {
        if (is_string($value)) {
            $value= check_utf8_encode($value);
        }else if ( is_array($value ) ){
            deal_json_utf8_encode( $value);
        }else{ //不处理

        }
    }
}
/**
 * 输出json 格式 并且程序终止
 * @return string
 */
function outputJson($array){
    global $_GET;
    $json_data=json_encode($array ,JSON_UNESCAPED_UNICODE) ;
    if ( !$json_data) { //数据出问题了
        deal_json_utf8_encode( $array );
        $json_data=json_encode($array ,JSON_UNESCAPED_UNICODE) ;

    }
    if (!$json_data) {
        $json_data=json_encode(["ret" => 6002,  "info"=> "JSON　出错"]  ,JSON_UNESCAPED_UNICODE);
        logger("ERROR  JSON ..");
    }

    if( isset ($_GET['callback']) ) {
        return htmlspecialchars($_GET['callback']) . '(' . $json_data . ')';
    }else{
        return  $json_data ;
    }
}

function outputjson_ret($ret_flag, $err_msg =null ){
    if ($ret_flag ) {
        return outputjson_success();
    }else{
        if ( !$err_msg) {
            $err_msg="失败";
        }
        return outputjson_error( $err_msg  );
    }
}

function outputjson_success($array=null){
    if (!is_array( $array) ){
        $array=array();
    }

    $array['ret']  = 0;
    $array['info'] = '成功';

    return outputJson( $array );
}

function outputjson_error($errno, $array=null){
    if (is_string ($errno) ){
        if(!is_array ($array)){
            $array=array();
        }

        $array["ret"]=-1;
        $array["info"]=$errno;
    }else{
        if(!is_array ($array)){
            $array=array();
        }

        $array["ret"]=$errno;
        if (!isset( $array["info"] )) {
            $array["info"]=app\Enums\Eerror::get_desc($errno) ;
        }

        if (!is_array( $array) ){
            $array=array();
        }
    }
    return outputJson( $array);
}

function get_machine_info_from_user_agent( $user_agent ){
    $ret = preg_replace("/istudent\/(.*) \\((.*); scale\/.*/","\\1;\\2",$user_agent  );
    if (strlen($ret) == strlen($user_agent)){
        $ret = preg_replace("/.*android astudent (v.*) -.*- \\((.*)\\)/","安卓:\\1;\\2",$user_agent );
    }
    $arr = App\Helper\Utils::json_decode_as_array( $user_agent) ;
    if (is_array($arr )) {
        //{"device_model":"","system_version":"","version":"3.1.0"};
        $ret=$arr["device_model"]."-".$arr["system_version"]  ."-". $arr["version"];
    }
    return $ret;
}

function unixtime2date($timestamp ){
    return date('Y-m-d H:i:s', $timestamp);
}

function check_room_user($user, $roomid,  $config )
{
    /*

                    [ip] => 192.168.0.6
                    [xmpp_port] => 5222
                    [webrtc_port] => 5061
                    [websocket_port] => 20061
                    [region] => 杭州
                    [courseid] => 464
    */

    $xmpp_server = new XMPPOperator($ret_server['ip'], $ret_server['xmpp_port'],
                                    $g_config['xmpp_lesson']['sys_id'], $g_config['xmpp_lesson']['passwd'],
                                    $ret_server['ip'] );
    //$roomid = "l_104y4y0";
    echo "get_end_lesson_teachers start ...\n";
    $ret_room_user = $xmpp_server->get_room_user($roomid);
    echo "get_end_lesson_teachers end ...\n";
    SeasLog::info("check room user user: {user}, roomid: {roomid}, result:{ret_room_user}",
                  array('{user}' => $user, '{roomid}' => $roomid, '{ret_room_user}' => json_encode(array($ret_room_user))));
    if ($ret_room_user === false) {
        $ret['code'] = false;
        return $ret['code'];
    }

    $ret['code'] = 0;
    $ret['result'] = in_array($user, $ret_room_user);
    return $ret;
}

function css_display($flag ) {
    if (!$flag) {
        return "display:none;"  ;
    }
    return "";
}

function get_agent_qr_new($wx_openid){

    if (\App\Helper\Utils::check_env_is_test() ) {
        $www_url="test.www.leo1v1.com";
    }else{
        $www_url="www.leo1v1.com";
    }

    $text         = "http://$www_url/market-invite/index.html?p_phone=".$phone."&type=2";
    $qr_url       = "/tmp/".$phone.".png";
    $bg_url       = "http://7u2f5q.com2.z0.glb.qiniucdn.com/4fa4f2970f6df4cf69bc37f0391b14751506672309999.png";
    \App\Helper\Utils::get_qr_code_png($text,$qr_url,5,4,3);

    //请求微信头像
    $wx_config    = \App\Helper\Config::get_config("yxyx_wx");
    $wx           = new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );
    $access_token = $wx->get_wx_token($wx_config["appid"],$wx_config["appsecret"]);
    $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$wx_openid."&lang=zh_cn";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $output = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($output,true);
    //        $old_headimgurl = $row['headimgurl'];
    $headimgurl = $data['headimgurl'];


    $image_5 = imagecreatefromjpeg($headimgurl);
    $image_6 = imageCreatetruecolor(160,160);     //新建微信头像图
    $color = imagecolorallocate($image_6, 255, 255, 255);
    imagefill($image_6, 0, 0, $color);
    imageColorTransparent($image_6, $color);
    imagecopyresampled($image_6,$image_5,0,0,0,0,imagesx($image_6),imagesy($image_6),imagesx($image_5),imagesy($image_5));

    $image_1 = imagecreatefrompng($bg_url);     //背景图
    $image_2 = imagecreatefrompng($qr_url);     //二维码
    $image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));     //新建图
    $image_4 = imageCreatetruecolor(176,176);     //新建二维码图
    imagecopyresampled($image_3,$image_1,0,0,0,0,imagesx($image_1),imagesy($image_1),imagesx($image_1),imagesy($image_1));
    imagecopyresampled($image_4,$image_2,0,0,0,0,imagesx($image_4),imagesy($image_4),imagesx($image_2),imagesy($image_2));
    imagecopymerge($image_3,$image_4,287,1100,0,0,imagesx($image_4),imagesy($image_4),100);
    // imagecopymerge($image_3,$image_6,295,29,0,0,160,160,100);

    $r = 80; //圆半径
    for ($x = 0; $x < 160; $x++) {
        for ($y = 0; $y < 160; $y++) {
            $rgbColor = imagecolorat($image_6, $x, $y);
            $a = $x-$r;
            $b = $y-$r;
            if ( ( ( $a*$a + $b*$b) <= ($r * $r) ) ) {
                $n_x = $x+295;
                $n_y = $y+28;
                imagesetpixel($image_3, $n_x, $n_y, $rgbColor);
            }
        }
    }

    $agent_qr_url = "/tmp/".$phone_qr_name;
    imagepng($image_3,$agent_qr_url);


    $file_name = \App\Helper\Utils::qiniu_upload($agent_qr_url);
    if($file_name!=''){
        $cmd_rm = "rm /tmp/".$phone."*.png";
        \App\Helper\Utils::exec_cmd($cmd_rm);
    }

    imagedestroy($image_1);
    imagedestroy($image_2);
    imagedestroy($image_3);
    imagedestroy($image_4);
    imagedestroy($image_5);
        imagedestroy($image_6);

    $file_url = $qiniu_url."/".$file_name;
    return $file_url;
}
