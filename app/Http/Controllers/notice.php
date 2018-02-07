<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

class notice extends Controller
{
    var $check_login_flag = false;

    public function sms_common($phone,$user_ip,$type,$data){
        if( in_array( $phone,[
            "13545096512",
            "13264833258",
            "13617133016",
            "13476679763",
            "15927348753",
            "18674012082",
            "13545096512",
            "18601735549",
            "13770868207",
            "13545299625",
            "13918599496",
            "15994209765",
            "15921544767",
            "18017008608",
            "18019739090",
            "13816297801",
            "13371812569",
            "18688792764",
            "13848216130",
            "15044941007",
            "15754725910",
            "13171286842",
            "15384729195",
            "13273853275",
            "13273853275",
            "15821208225",
            "13818373894",
            "13918009152",
            "18279808219",
            "13524296040",
            "13564678676",
            "13705759995",
            "18019399542",
            "15958258032",
            "18916068160",
            "15901681505",
            "13361908997",
            "13818903771",
            "18817415265",
            "13788911242",
            "14782323288",
            "13916612415",
        ] )) {
            return;
        }

        $is_success = 0;
        if ($user_ip) {
            //每个ip 最多 10个
            if (!\App\Helper\Common::redis_day_add_with_max_limit("sms_ip_$user_ip",1, 20)){
                $send_flag_code=3;
            }
        }
        $receive_content = "";
        $test_flag       = false;

        if ($is_success==0) {
            if ( \App\Helper\Utils::check_env_is_release()  || $test_flag) {
                $ret=\App\Helper\Common::send_sms_with_taobao(
                    $phone,"SMS_".$type,$data
                );

                $receive_content= json_encode($ret );
                \App\Helper\Utils::logger("sms_ret:".$receive_content."sms_phone:".$phone);
                if ( property_exists($ret,"result") && $ret->result->err_code==="0") {
                    $is_success=1;
                }else{
                    $send_email=false;

                    if (   $ret->code=="15" ) {
                        $sub_code= $ret->sub_code;
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

        $this->t_sms_msg->row_insert([
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

    public function get_in_user_ip()  {
        return  $this->get_in_str_val("user_ip");
    }

    public function sms_stu_register()
    {
        $phone   = $this->get_in_phone();
        $user_ip = $this->get_in_user_ip();
        $code    = $this->get_in_str_val("code");
        $index   = $this->get_in_str_val("index");

        $type  = E\Esms_type::V_REGISTER;
        $this->sms_common($phone,$user_ip,$type,[
            "code" => $code,
            "index" => $index
        ]);
        return outputjson_success();
    }

    public function sms() {
        $phone   = $this->get_in_phone();
        $user_ip = $this->get_in_user_ip();
        $type    = $this->get_in_type();
        $args    = json_decode($this->get_in_str_val("args"),true);
        if (!is_array ($args) ) {
            return $this->output_err("xx");
        }
        $this->sms_common($phone,$user_ip,$type, $args);

        return $this->output_succ();
    }

    public function sms_register() {
        $phone   = $this->get_in_phone();
        $user_ip = $this->get_in_user_ip();
        $type    = $this->get_in_type();
        $args    = json_decode($this->get_in_str_val("args"),true);
        if (!is_array ($args) ) {
            return $this->output_err("xx");
        }
        \App\Helper\Utils::logger("111111");
        $this->sms_common_regiter($phone,$user_ip,$type, $args);

        return $this->output_succ();
    }



    public function sms_common_regiter($phone , $user_ip , $type, $data  )
    {
        if( in_array( $phone, [
            "13545096512",
            "13264833258",
            "13617133016",
            "13476679763",
            "15927348753",
            "18674012082",
            "13545096512",
            "18601735549",
            "13770868207",
            "13545299625",
            "13918599496",
            "15994209765",
            "15921544767",
            "18017008608",
            "18019739090",
            "13816297801",
            "13371812569",
            "18688792764",
            "13848216130",
            "15044941007",
            "15754725910",
            "13171286842",
            "15384729195",
            "13273853275",
            "13273853275",
            "15821208225",
            "13818373894",
            "13918009152",
            "18279808219",
            "13524296040",
            "13564678676",
            "13705759995",
            "18019399542",
            "15958258032",
            "18916068160",
            "15901681505",
            "13361908997",
            "13818903771",
            "18817415265",
            "13788911242",
            "14782323288",
            "13916612415",
        ] )) {
            return;
        }


        \App\Helper\Utils::logger("111111");

        $is_success=0;
        if ($user_ip) {
            //每个ip 最多 10个
            if (!\App\Helper\Common::redis_day_add_with_max_limit("sms_ip_$user_ip",1, 10) && !in_array($phone,[15821272506])){
                return;
            }
        }else{
            return;
        }
        \App\Helper\Utils::logger("111112");

        if($phone ){
            //每个手机 最多 3个
            if (!\App\Helper\Common::redis_day_add_with_max_limit("sms_phone_$phone",1, 3) && !in_array($phone,[15821272506]) ){
                return;
            }

        }else{
            return;
        }
        \App\Helper\Utils::logger("111113");

        $receive_content="";

        //$test_flag=true;
        $test_flag=false;

        //测试
        // $this->t_manager_info->send_wx_todo_msg_by_adminid(349,"sms","sms_ceshi","sms","");


        if ($is_success==0) {
            if ( \App\Helper\Utils::check_env_is_release()  || $test_flag) {
                $ret=\App\Helper\Common::send_sms_with_taobao($phone,
                                                              "SMS_".$type,
                                                              $data);

                $receive_content= json_encode($ret );
                if ( property_exists($ret,"result") && $ret->result->err_code==="0") {
                    $is_success=1;
                }else{
                    $send_email=false;

                    if (   $ret->code=="15" ) {
                        $sub_code= $ret->sub_code;
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

        $this->t_sms_msg->row_insert([
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

}
