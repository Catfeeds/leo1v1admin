<?php
namespace App\Helper;

class WxSendMsg{
    public function __construct() {
    }

    static public function get_app_id_secret($type='yxyx'){
        $arr = [];
        if ($type == 'yxyx'){
            $arr['appid']     = \App\Helper\Config::get_wx_appid();
            $arr['appsecret'] = \App\Helper\Config::get_wx_appsecret();
        } else if ($type == 'teacher') {
            $arr['appid']     = \App\Helper\Config::get_teacher_wx_appid();
            $arr['appsecret'] = \App\Helper\Config::get_teacher_wx_appsecret();
        } else if ($type == 'online'){
            // $arr['appid']     = \App\Helper\Config::get_teacher_wx_appid();
            // $arr['appsecret'] = \App\Helper\Config::get_teacher_wx_appsecret();
        }
        return $arr;
    }

    function get_wx_token($appid,$appsecret,$reset_flag=false) {
        \App\Helper\Utils::logger("XX :$appid,$appsecret, ");

        $key     = "wx_token_$appid";
        $ret_arr = \App\Helper\Common::redis_get_json($key);
        $now     = time(NULL);
        \App\Helper\Utils::logger('gettoken1');

        if (!$ret_arr || !isset($ret_arr["access_token"])  ||   $ret_arr["get_time"]+7000 <  $now  || $reset_flag ) {
            \App\Helper\Utils::logger('gettoken2');

            $json_data=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret"  );
            $ret_arr=\App\Helper\Utils::json_decode_as_array($json_data);
            $ret_arr["get_time"]=time(NULL);
            \App\Helper\Common::redis_set_json($key,$ret_arr );
        }
        \App\Helper\Utils::logger('gettoken4:' .json_encode( $ret_arr) );

        return @$ret_arr["access_token"];
    }

    /**
     * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
     * 标题课程 : 待办事项提醒
     * {{first.DATA}}
     * 待办主题：{{keyword1.DATA}}
     * 待办内容：{{keyword2.DATA}}
     * 日期：{{keyword3.DATA}}
     * {{remark.DATA}}
     */
    static public function template_tea_test_lesson_ok($teacherid,$nick, $lesson_time_str, $require_phone, $demand,$bgk_arr = []){
        //$bgk_arr备用数组
        $task = new  \App\Console\Tasks\TaskController();
        $wx_openid = $task->t_teacher_info->get_wx_openid($teacherid);

        if($wx_openid!='') {

            $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
            $data['first']    = $nick."同学的试听课已排好，请尽快完成课前准备工作";
            $data['keyword1'] = "备课通知";
            $data['keyword2'] = "\n上课时间：$lesson_time_str "
                              ."\n教务电话：$require_phone"
                              ."\n试听需求：$demand"
                              ."\n1、请及时确认试听需求并备课"
                              ."\n2、请尽快上传教师讲义、学生讲义（用于学生预习）和作业"
                              ."\n3、老师可提前15分钟进入课堂进行上课准备";
            $data['keyword3'] = date("Y-m-d H:i",time());
            $data['remark']   = "";
            $url = "http://www.leo1v1.com/login/teacher";

            self::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);

        }
    }

    static public function send_teacher_msg_for_wx($openid,$template_id,$data,$url=""){

        $app = self::get_app_id_secret('teacher');
        $teacher_wx = new \App\Helper\Wx($app['appid'],$app['appsecret']);

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

};
