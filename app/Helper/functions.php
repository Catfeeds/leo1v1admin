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
    \App\Helper\Utils::logger("JSON DATA :". substr( $json_data,0, 200)  );


    if( isset ($_GET['callback']) ) {
        $content  = htmlspecialchars($_GET['callback']) . '(' . $json_data . ')';
        $response = \Illuminate\Support\Facades\Response::make($content, 200 );
        $response->header('Content-Type', "text/javascript");
        return $response;
    }else{
        $content= $json_data ;
        $response=\Illuminate\Support\Facades\Response::make ($content, 200 );
        $response->header('Access-Control-Allow-Origin',"*");
        $response->header('Content-Type', "application/json");
        return $response;
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
            $array["info"]= "出错";
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

