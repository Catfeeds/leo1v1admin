<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use LaneWeChat\Core\UserManage;
use \App\Enums as E;
use Illuminate\Support\Facades\Input ;

// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

//引入分词类
use Analysis\PhpAnalysis;

require_once  app_path("Libs/Pingpp/init.php");

class common extends Controller
{
    use TeaPower;
    use CacheNick;
    var $check_login_flag =false;

    function get_openim_info ( ) {
        global $_REQUEST;
        if (!isset ( $_REQUEST["q"])) {
            //dd (  $_REQUEST );
            return;
        }
        $arr= json_decode( $_REQUEST["q"],true );

        $user_list= $this->t_student_info-> get_user_list($arr["userids"]);

        foreach($arr ["userids" ] as $userid) {
            $out_user_list[] = [
                "userid"   => $userid,
                "nickname" => isset($user_list[$userid]) ?  $user_list[$userid]["nick"]. "-$userid" :"$userid" ,
                "avatar"   => isset($user_list[$userid]) ?  $user_list[$userid]["face"]:"" ,
                "trade"    => [
                    "status"   => "done", //订单状态，同一个订单状态对应的图片在一个登陆期间只下载一次
                    "text"     => "",//订单状态文案
                    "time"     => "2015-06-03 12:12:50",//订单时间
                    "tradepic" => "www.a.com/trade/b.jpg"//订单状态图片url 16*16
                ],"vip" => [//会员等级
                    "level"  => "v1", //会员等级
                    "text"   => "tttt",//会员等级文案
                    "vippic" => "www.a.com/vip/c.jpg"//会员等级图片url? 16*16
                ],
            ];
        }

        return json_encode( array(
            "users" => $out_user_list,
        ));
    }

    function send_mail() {
        $to      = $this->get_in_str_val("to");
        $title   = $this->get_in_str_val("title");
        $body    = trim($this->get_in_str_val("body"));
        $is_html = trim($this->get_in_str_val("is_html"));

        dispatch( new \App\Jobs\send_error_mail( $to,$title,$body ) );
        echo "new xxxx";
    }

    public function send_paper_email(){
        $to     = $this->get_in_str_val("to","");
        $body   = "";
        $header = "
        <meta charset='UTF-8'>
        <title>试卷下载</title>
        <style>
            body{
                text-align: center;
              font-family: 'Microsoft YaHei', 微软雅黑, 'Microsoft JhengHei', Helvetica, Arial, FreeSans, Arimo, 'Droid Sans','wenquanyi micro hei','Hiragino Sans GB', 'Hiragino Sans GB W3', Arial, sans-serif;
              -webkit-font-smoothing: antialiased;
              -moz-osx-font-smoothing: grayscale;
            }
            #main{
                padding: 10px 0px 50px 0px;
                width: 80%;
                margin: 0 auto;
            }
            .header{
                color: #333333;
                font-size: 20px;
                margin: 40px 0 10px 0;
                text-align: left;
            }
            .header img{
                margin-bottom: 3px;
                vertical-align: middle;
            }
            .description{
                color: #999999;
                font-size: 15px;
                margin: 3px;
                text-align: left;
            }
            .description_important{
                color: #000000;
                font-size: 15px;
                margin: 3px 3px 8px 3px;
                text-align: left;
            }
            a{
                color: #0bceff;
                text-decoration: none;
                text-decoration: underline;
            }
            table{
                width: 100%;
            }
            td{
                vertical-align: top;
            }
            td div.description_important{
                margin-top: 15px;
            }
            td img{
                margin-right: 5px;
                padding-right: 5px;
            }
            .tdwrap{
                width: 150px;
            }
            ul{
                font-size: 15px;
                color: #0bceff;
                text-align: left;
            }
            li {
                font-size: 15px;
                margin: 3px;
                padding: 4px;
            }
            li a{
                font-size: 15px;
                margin: 3px;
            }
            .highlight{
                color: #f26173;
            }
        </style>
    </head>
    <body>
        <div id='email_exam_papers'>
            <img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/email_exam_papers/header.png' style='width:100%'/>
            <div id='main'>
                <div class='header'>亲爱的家长你好：</div>
                <div class='description'>以下是您选择下载的试卷，希望对您有所帮助</div>
                <div class='description'>本邮件是由“<span class='highlight'>上海理优教研室</span>”发送，若想了解更多，请您通过以下方式了解我们。</div>

                <div class='header'><img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/email_exam_papers/title_start.png' height='18px'/>&nbsp;&nbsp;&nbsp;网盘下载地址</div>";

        $url    = "https://pan.baidu.com/s/1o8roPcu";
        $passwd = "ecie";

        $body ="<ul>";
        $body.="<li><a href=".$url.">链接:".$url."</a>&nbsp;&nbsp;<span>密码:".$passwd."</span></li>";
        $body.="</ul>";

        $footer="<div class='description'>复制密码并单击链接获取文件，一份文档中包含试卷及答案。</div>
                <div class='description'>(如果下载中遇到任何问题，请关注下方订阅号“上海升学帮”，并回复相关问题)</div>

                <div class='header'><img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/email_exam_papers/title_start.png' height='18px'/>&nbsp;&nbsp;&nbsp;关于我们</div>
                <div class='description_important'>理优1对1致力于为初高中学生提供专业、专注、有效的教学，帮助更多的家庭打破师资、时间、地域、费用的局限，获得四维一体的专业学习体验。作为在线教育行业内首家专注于移动Pad端研发的公司，理优1对1在1年内成功获得GGV数千万元A轮投资（GGV风投曾投资阿里巴巴集团、优酷土豆、去哪儿、小红书等知名企业）。</div>
                <div class='description'>咨询电话：400-680-6180</div>
                <div class='description'>官方店铺：<a href='https://shop123923740.taobao.com/'>https://shop123923740.taobao.com/</a></div>
                <div class='description'>理优官网：<a href='http://www.leo1v1.com/'>http://www.leo1v1.com/</a></div>
                <div class='description'>联系地址：上海市闵行区宜山路2016号合川大厦13楼F室</div>

                <div class='header'><img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/email_exam_papers/title_start.png' height='18px'/>&nbsp;&nbsp;&nbsp;APP下载</div>
                <table>
                    <tr><td class='tdwrap'><img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/email_exam_papers/qr_parent.png' width='150px' height='150px' /></td>
                        <td>
                            <div class='description_padding'></div>
                            <div class='description_important'>订阅号(升学版)</div>
                            <div class='description'>请用微信扫码关注</div>
                        </td>
                    </tr>
                    <tr><td class='tdwrap'><img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/email_exam_papers/qr_stu.png' width='150px' height='150px' /></td>
                        <td>
                            <div class='description_padding'></div>
                            <div class='description_important'>学生端下载</div>
                            <div class='description'>请用Pad的二维码扫描工具扫描下载</div>
                        </td>
                    </tr>
                    <tr><td class='tdwrap'><img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/email_exam_papers/qr_index.png' width='150px' height='150px'/></td>
                        <td>
                            <div class='description_padding'></div>
                            <div class='description_important'>手机官网</div>
                            <div class='description'>请用手机的二维码扫描工具领取课程</div>
                        </td>
                    </tr>
                </table>
            </div>
            <img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/email_exam_papers/footer.png' style='width:100%'/>
        </div>
    </body>";

        $html  = $header.$body.$footer;
        $title = "上海理优教研室";
        // $ret   = \App\Helper\Common::send_paper_mail($to,$title,$html);
        $ret   = \App\Helper\Email::SendMailEmd($to,$title,$html);

        $name   = $this->get_in_str_val("name","");
        $phone  = $this->get_in_str_val("phone","");
        $origin = "官网试卷用户";

        $check_flag = \App\Helper\Utils::check_phone($phone);
        if(!$check_flag && $phone != ""){
            return $this->output_err("请填写正确的手机号!");
        }

        $this->add_user_origin_info($name,$phone);
        $this->t_seller_student_info->add_or_add_to_sub($name,$phone,0,$origin,0,0,0,0);
        $this->t_seller_student_new->book_free_lesson_new($name,$phone,0,$origin,0,0);

        if($ret){
            return outputjson_success();
        }else{
            return outputjson_error("发送失败");
        }
    }

    public function add_user_origin_info($name,$phone){
        $check_flag=$this->t_user_origin_info->check_phone($phone);
        if($check_flag==0){
            $this->t_user_origin_info->row_insert([
                "name"     => $name,
                "phone"    => $phone,
                "origin"   => "send_paper",
                "add_time" => time(),
            ]);
        }
    }

    public function email_open_address(){
        $url=$this->get_in_str_val("url");
        $url_ex = \App\Helper\Common::get_url_ex($url);
        return redirect($url_ex);
    }

    public function teacher_record_detail_info(){
        $this->t_teacher_record_list->switch_tongji_database();
        $id        = $this->get_in_int_val("id",0);
        $teacherid = $this->get_in_int_val("teacherid",50158);
        $type      = $this->get_in_int_val("type",1);
        $add_time  = $this->get_in_int_val("add_time",1484116899);

        if($id==0){
            $ret_info  = $this->t_teacher_record_list->get_all_info($teacherid,$type,$add_time);
        }else{
            $ret_info = $this->t_teacher_record_list->field_get_list($id,"*");
        }

        if($type==1){
            $ret_info['title'] = "教师课程质量反馈报告";
        }elseif($type==9){
            $ret_info['title'] = "模拟试听反馈报告";
        }

        return $this->pageView(__METHOD__,null,[
            "ret_info" => $ret_info
        ]);
    }

    /**
     * 培训课程问卷星接口
     * userid=dXNlcmlkPTUwNzI4Jmxlc3NvbmlkPTExMTQxMw==&sojumpindex=5&totalvalue=10
     * sojumpindex累计提交次数
     * @param userid base64编码加密的字符串 解析后如:userid=123&lessonid=123
     * @param totalvalue 用户答题分数
     */
    public function train_user_answer(){
        $userid     = $this->get_in_str_val("userid");
        $totalvalue = $this->get_in_str_val("totalvalue");
        \App\Helper\Utils::logger("train_answer".$userid." score".$totalvalue);

        $today_date  = date("Y年m月d日",time());
        $lesson_info = base64_decode($userid);
        $lesson_arr  = explode("&",$lesson_info);
        $answer      = [];
        foreach($lesson_arr as $lesson_val){
            $user_info = explode("=",$lesson_val);
            $answer[$user_info[0]]=$user_info[1];
        }

        $str = "提交成功！您的分数为".$totalvalue;
        if(isset($answer) && !empty($answer)){
            $teacher_info = $this->t_teacher_info->get_teacher_info($answer['userid']);
            $old_score    = $this->t_train_lesson_user->get_score($answer['lessonid'],$answer['userid']);
            $level_str    = E\Elevel::get_desc($teacher_info['level']);
            \App\Helper\Utils::logger("train user info :".json_encode($teacher_info)
                                      ."answer info :".json_encode($answer)
                                      ." score is ".$totalvalue." time is ".time());

            if(isset($teacher_info) && !empty($teacher_info)){
                if($old_score=="" || $totalvalue>$old_score){
                    $this->t_train_lesson_user->field_update_list_2($answer['lessonid'],$answer['userid'],[
                        "score" => $totalvalue
                    ]);
                }

                $train_time = $this->t_train_lesson_user->get_max_lesson_time($answer['userid']);

                //培训通过
                if($totalvalue>=90 ){
                    if($teacher_info['train_through_new']==0){
                        $this->teacher_train_through_deal_2018_1_25($answer['userid']);
                    }

                    //发送微信通知进行模拟课堂
                    $check_flag = $this->t_lesson_info->check_train_lesson_new($answer['userid']);
                    if(empty($check_flag)){
                        $this->add_trial_train_lesson($teacher_info,1);
                    }
                }
            }
        }
        echo $str;
    }

    /**
     * 通过老师id发送入职信息
     */
    public function send_offer_info_by_teacherid(){
        $teacherid = $this->get_in_int_val("teacherid");
        if($teacherid==0){
            return $this->output_err("老师id不能为0!");
        }

        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);
        $this->send_offer_info($teacher_info);

        return $this->output_succ();
    }

    public function add_trial_train_lesson_by_admin(){
        $teacherid    = $this->get_in_int_val("teacherid");
        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);

        $this->add_trial_train_lesson($teacher_info);

        return $this->output_succ();
    }

    public function show_offer_html(){
        $teacherid = $this->get_in_int_val("teacherid");
        $is_test   = $this->get_in_int_val("is_test");

        if($teacherid==0){
            return $this->output_err("老师id出错！");
        }

        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);
        $html = $this->get_offer_html($teacher_info);
        if($is_test){
            $ret = \App\Helper\Common::send_paper_mail($teacher_info['email'],"上海理优教研室",$html);
            if($teacher_info['wx_openid']!=""){
                $template_id      = "1FahTQqlGwCu1caY9wHCuBQXPOPKETuG_EGRNYU89II";
                $data["first"]    = "老师您好，恭喜你已经通过理优入职培训，成为理优正式授课老师，等级为：B等";
                $data["keyword1"] = "教职老师";
                $data["keyword2"] = "理优教育";
                $data["keyword3"] = date("Y-m-d",time());
                $data["remark"]   = "愿老师您与我们一起以春风化雨的精神,打造高品质教学服务,助我们理优学子更上一层楼。";
                $offer_url        = "http://admin.leo1v1.com/common/show_offer_html?teacherid=".$teacherid;
                \App\Helper\Utils::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id,$data,$offer_url);
            }
        }

        return $html;
    }

    public function show_level_up_html(){
        $teacherid = $this->get_in_int_val("teacherid");
        $is_test   = $this->get_in_int_val("is_test");

        if($teacherid==0){
            return $this->output_err("老师id出错！");
        }

        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);
        $html = $this->teacher_level_up_html($teacher_info);
        if($is_test){
            $teacher_info['email']   = "wg392567893@163.com";
            $teacher_info['subject'] = E\Esubject::V_4;
            $ret = \App\Helper\Common::send_paper_mail($teacher_info['email'],"上海理优教研室",$html);
            if($teacher_info['wx_openid']!=""){
                $template_id      = "1FahTQqlGwCu1caY9wHCuBQXPOPKETuG_EGRNYU89II";
                $data["first"]    = "老师您好，恭喜你已经通过理优入职培训，成为理优正式授课老师，等级为：B等";
                $data["keyword1"] = "教职老师";
                $data["keyword2"] = "理优教育";
                $data["keyword3"] = date("Y-m-d",time());
                $data["remark"]   = "愿老师您与我们一起以春风化雨的精神,打造高品质教学服务,助我们理优学子更上一层楼。";
                $offer_url        = "http://admin.leo1v1.com/common/show_level_up_html?teacherid=".$teacherid;
                \App\Helper\Utils::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id,$data,$offer_url);
            }
        }

        return $html;
    }

    public function show_ruzhi_html(){
        $teacherid = $this->get_in_int_val("teacherid");
        $is_test   = $this->get_in_int_val("is_test");
        if($teacherid==0){
            return $this->output_err("老师id出错！");
        }

        $teacher_info = $this->t_teacher_info->get_teacher_info($teacherid);
        $html = $this->get_offer_html($teacher_info);

        if($is_test){
            $teacher_info['email']   = "wg392567893@163.com";
            $ret = \App\Helper\Common::send_paper_mail($teacher_info['email'],"上海理优教研室",$html);
            if($teacher_info['wx_openid']!=""){
                $template_id      = "1FahTQqlGwCu1caY9wHCuBQXPOPKETuG_EGRNYU89II";
                $data["first"]    = "老师您好，恭喜你已经通过理优入职培训，成为理优正式授课老师，等级为：B等";
                $data["keyword1"] = "教职老师";
                $data["keyword2"] = "理优教育";
                $data["keyword3"] = date("Y-m-d",time());
                $data["remark"]   = "愿老师您与我们一起以春风化雨的精神,打造高品质教学服务,助我们理优学子更上一层楼。";
                $offer_url        = "http://admin.leo1v1.com/common/show_ruzhi_html?teacherid=".$teacherid;
                \App\Helper\Utils::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id,$data,$offer_url);
            }
        }
        return $html;
    }

    function get_ytx_record_list() {
        list($start_time,$end_time) =  $this->get_in_date_range(-2,0);
    }

    public function base64(){
        $text = $this->get_in_str_val("text");
        $type = $this->get_in_str_val("type");

        if($type=="encode"){
            $ret_info = base64_encode($text);
        }elseif($type=="decode"){
            $ret_info = base64_decode($text);
        }else{
            $ret_info = "格式错误";
        }

        return $this->output_succ(["data"=>$ret_info]);
    }

    public function check_answer_time($lessonid){
        $now          = time();
        $lesson_start = $this->t_lesson_info->get_lesson_start($lessonid);

        $lesson_day  = date("w",$lesson_start);
        if($lesson_day==5){
            $finish=strtotime(date("Y-m-d 10:00",$lesson_start+86400));
        }elseif($lesson_day==6){
            $finish=strtotime(date("Y-m-d 21:00",$lesson_start));
        }else{
            $finish=0;
        }

        $ret = $now>$finish?0:1;
        return $ret;
    }

    // 从老师后台接受的发送推送请求
    public function send_wx_todo_msg(){
        $data    = $this->get_in_str_val("data");
        $msg_arr = json_decode(base64_decode($data),true);
        \App\Helper\Utils::logger("ebai_main_page wx_push".json_encode($msg_arr));

        if(is_array($msg_arr) && !empty($msg_arr)){
            extract($msg_arr);
            // $desc = '';
            $this->t_manager_info->send_wx_todo_msg(urldecode($account),urldecode($from_user),urldecode($header_msg),$msg,$url,$desc='点击进入管理系统操作');
        }
    }

    // 从老师后台接受的发送推送请求
    public function send_teacher_wx_msg(){
        $data    = $this->get_in_str_val("data");
        $msg_arr = json_decode(base64_decode($data),true);

        if(is_array($msg_arr) && !empty($msg_arr)){
            extract($msg_arr);
            $this->t_manager_info->send_wx_todo_msg(urldecode($account),urldecode($from_user),urldecode($header_msg),$msg,$url);
        }
    }

    public function set_upload_info()
    {
        $draw          = $this->get_in_str_val('draw',"");
        $audio         = $this->get_in_str_val('audio',"");
        $real_end_time = $this->get_in_str_val('real_end_time',"");
        $courseid      = $this->get_in_int_val('courseid', 0);
        $lesson_num    = $this->get_in_int_val('lesson_num',0);

        $pcm_file_count    = $this->get_in_int_val("pcm_file_count");
        $pcm_file_all_size = $this->get_in_int_val("pcm_file_all_size");

        $this->notice_lesson_video($courseid,$lesson_num);
        $this->t_lesson_info_b2->set_lesson_upload_info(
            $draw, $audio, $real_end_time,
            $courseid, $lesson_num, $pcm_file_all_size,$pcm_file_count );

        if(($pcm_file_count >10 || $pcm_file_all_size < 10000 ) ) {
            $lessonid=$this->t_lesson_info_b2->get_lessonid_by_courseid_num($courseid,$lesson_num);
            \App\Helper\Utils::logger(" ERROR, lessonid = $lessonid pcm_file_count=$pcm_file_count pcm_file_all_size  = $pcm_file_all_size    ");
            if(\App\Helper\Utils::check_env_is_release() ) {
                dispatch( new \App\Jobs\send_error_mail(
                    "xcwenn@qq.com","报错: lessonid = $lessonid pcm_file_count=$pcm_file_count pcm_file_all_size  = $pcm_file_all_size   " ,
                    " lessonid = $lessonid pcm_file_count=$pcm_file_count pcm_file_all_size  = $pcm_file_all_size  "
                ));
            }
        }
        return $this->output_succ();
    }

    public function notice_lesson_video($courseid,$lesson_num){
        $lesson_info = $this->t_lesson_info->get_lesson_info($courseid,$lesson_num);
        $lesson_type = $lesson_info['lesson_type'];
        $lessonid    = $lesson_info['lessonid'];

        $check_time = time()-86400*2;
        if($lesson_info['lesson_start']>$check_time){
            $this->t_baidu_msg->change_lesson_start_message_status($lessonid);
            if($lesson_type<1000){
                $userid=$this->t_lesson_info->get_userid($lessonid);
                $this->baidu_push_video($userid,$lesson_info);
            }elseif($lesson_type>3000 && $lesson_type<4001){
                $user_list=$this->t_small_lesson_info->get_all_user($lessonid);
                $this->baidu_push_video($user_list,$lesson_info);
            }elseif($lesson_type>1000 && $lesson_type<3000){
                $user_list=$this->t_open_lesson_user->get_all_user($lessonid);
                $this->baidu_push_video($user_list,$lesson_info);
            }
        }
    }

    public function baidu_push_video($userid,$lesson_info){
        $date_str = date('m月d日 H:i', $lesson_info['lesson_start'])."-".date('H:i', $lesson_info['lesson_end']);
        $message  = $date_str."的课程视频已经录制成功，立即查看视频回放。";
        if(is_array($userid)){
            foreach($userid as $val){
                $this->t_baidu_msg->baidu_push_msg($val['userid'],$message,$lesson_info['lessonid'],1015,4);
            }
        }else{
            $parentid = $this->t_student_info->get_parentid($userid);
            $stu_nick = $this->t_student_info->get_nick($userid);
            $this->t_baidu_msg->baidu_push_msg($userid,$message,$lesson_info['lessonid'],1015,4);
            $this->t_baidu_msg->baidu_push_msg($parentid,$message,$lesson_info['lessonid'],4013,304);
        }
    }

    public function get_finish_lessons() {
        $ret = $this->t_lesson_info_b2->get_finish_lessons();
        foreach($ret as &$item){
            $teacherid       = $item['teacherid'];
            $lessonid        = $item['lessonid'];
            $lesson_type     = $item['lesson_type'];
            $real_begin_time = $item['real_begin_time'];

            if ($real_begin_time > $item["lesson_start"]  || $real_begin_time < $item["lesson_start"]-30*60 ) {
                $item["real_begin_time"] = $item["lesson_start"];
                $this->t_lesson_info->field_update_list($lessonid,[
                    "real_begin_time" => $item["real_begin_time"]
                ]);
            }

            $item['room_id'] = ($lesson_type >= 1000?"p_":"l_").$item['courseid']."y".$item['lesson_num']."y".$item['lesson_type'];
            if ($lesson_type >=1000 && $lesson_type <2000 ){
                $item["teacher_server_list"] = array(
                    array(
                        "start_time" => $item['real_begin_time'],
                        "program_id" => "1",
                        "opt_type"   => "1",
                        "end_time"   => 0xFFFFFFFF
                    )
                );
            }
        }

        return $this->output_succ([
            'data'                    => $ret,
            "webrtc_xmpp_server_list" => \App\Helper\Config::get_config("audio_server_list"),
            "audio_server_list"       => \App\Helper\Config::get_config("audio_server_list"),
            "xmpp_server_list"        => \App\Helper\Config::get_config("xmpp_server_list"),
        ]);
    }

    /**
     * 老师微信帮 邀请有礼生成的二维码图片
     */
    public function get_teacher_qr(){
        $wx_openid = $this->get_in_str_val("wx_openid");
        $activity_flag = 0;
        \App\Helper\Utils::logger("get_teacher_info wx_openid:".$wx_openid);

        $phone = $this->t_teacher_info->get_phone_by_wx_openid($wx_openid);
        if(!$phone || $wx_openid==""){
            return "";
        }

        $check_time = strtotime("2017-10-8");
        if(time()<$check_time){
            $activity_flag=1;
            $phone_qr_name = $phone."_guoqing_pic_qr.png";
        }else{
            $phone_qr_name = $phone."_qr.png";
        }

        $qiniu     = \App\Helper\Config::get_config("qiniu");
        $qiniu_url = $qiniu['public']['url'];
        $is_exists = \App\Helper\Utils::qiniu_file_stat($qiniu_url,$phone_qr_name);
        if(!$is_exists ){
            //text待转化为二维码的内容
            $text           = "http://wx-teacher-web.leo1v1.com/tea.html?".$phone;
            $qr_url         = "/tmp/".$phone.".png";
            $teacher_qr_url = "/tmp/".$phone_qr_name;

            if($activity_flag){
                //教师节背景图
                $bg_url = "http://leowww.oss-cn-shanghai.aliyuncs.com/guoqing_pic_invitation.png";
                \App\Helper\Utils::get_qr_code_png($text,$qr_url,5,4,3);

                list($qr_width, $qr_height)=getimagesize($qr_url);
                //缩放比例
                $per = round(157/$qr_width,3);
                $n_w = $qr_width*$per;
                $n_h = $qr_height*$per;
                $new = imagecreatetruecolor($n_w, $n_h);
                $img = imagecreatefrompng($qr_url);
                //copy部分图像并调整
                imagecopyresized($new,$img,0,0,0,0,$n_w,$n_h,$qr_width,$qr_height);
                //图像输出新图片、另存为
                imagepng($new, $qr_url);
                imagedestroy($new);
                imagedestroy($img);
            }else{
                //原始邀请有奖背景图
                $bg_url = "http://leowww.oss-cn-shanghai.aliyuncs.com/pic_invitation.png";
                \App\Helper\Utils::get_qr_code_png($text,$qr_url,10,5,4);
            }

            //高温邀请有奖背景图
            // $bg_url = "http://leowww.oss-cn-shanghai.aliyuncs.com/summer_pic_invitation_8.png";
            // \App\Helper\Utils::get_qr_code_png($text,$qr_url,5,4,3);
            $image_bg  = imagecreatefrompng($bg_url);
            $image_qr  = imagecreatefrompng($qr_url);
            $image_ret = imageCreatetruecolor(imagesx($image_bg),imagesy($image_bg));
            imagecopyresampled($image_ret,$image_bg,0,0,0,0,imagesx($image_bg),imagesy($image_bg),imagesx($image_bg),imagesy($image_bg));
            if($activity_flag){
                imagecopymerge($image_ret,$image_qr,532,1038,0,0,157,157,100);
            }else{
                imagecopymerge($image_ret,$image_qr,287,580,0,0,imagesx($image_qr),imagesy($image_qr),100);
            }
            //高温
            // imagecopymerge($image_ret,$image_qr,455,875,0,0,imagesx($image_qr),imagesy($image_qr),100);
            imagepng($image_ret,$teacher_qr_url);

            $file_name = \App\Helper\Utils::qiniu_upload($teacher_qr_url);

            if($file_name!=''){
                $cmd_rm = "rm /tmp/".$phone."*.png";
                \App\Helper\Utils::exec_cmd($cmd_rm);
            }

            imagedestroy($image_bg);
            imagedestroy($image_qr);
            imagedestroy($image_ret);
        }else{
            $file_name=$phone_qr_name;
        }

        $file_url = $qiniu_url."/".$file_name;
        return $file_url;
    }



    /**
     * 优学优享 邀请学员生成二维码图片
     */
    public function get_agent_qr(){
        $wx_openid = $this->get_in_str_val("wx_openid");
        $row = $this->t_agent->get_agent_info_by_openid($wx_openid);
        $phone = '';
        if(isset($row['phone'])){
            $phone = $row['phone'];
        }
        if(!$phone || $wx_openid==""){
            return "";
        }
        $qiniu         = \App\Helper\Config::get_config("qiniu");
        if ( \App\Helper\Utils::check_env_is_test() ) {
            $phone_qr_name = $phone."_qr_agent_test_new.png";
        }else{
            $phone_qr_name = $phone."_qr_agent_gkk_new.png";
        }
        $qiniu_url     = $qiniu['public']['url'];
        \App\Helper\Utils::logger("CHECK is_exists start");

        $is_exists     = \App\Helper\Utils::qiniu_file_stat($qiniu_url,$phone_qr_name);

        \App\Helper\Utils::logger("CHECK is_exists end");
        if(!$is_exists){
            if (\App\Helper\Utils::check_env_is_test() ) {
                $www_url="test.www.leo1v1.com";
            }else{
                $www_url="www.leo1v1.com";
            }


            $text         = "http://$www_url/market-invite/index.html?p_phone=".$phone."&type=1";
            $bg_url       = "http://7u2f5q.com2.z0.glb.qiniucdn.com/d8563e7ad928cf9535fc5c90e17bb2521503108001175.jpg";

            $qr_url       = "/tmp/".$phone.".png";
            $agent_qr_url = "/tmp/".$phone_qr_name;
            \App\Helper\Utils::logger("QR_URL $text ");
            \App\Helper\Utils::get_qr_code_png($text,$qr_url,5,4,3);

            $image_1 = imagecreatefromjpeg($bg_url);     //背景图
            $image_2 = imagecreatefrompng($qr_url);     //二维码
            $image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));     //新建背景图

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
            $headimgurl = $data['headimgurl'];

            $image_4 = imagecreatefromjpeg($headimgurl);
            $image_5 = imageCreatetruecolor(190,190);     //新建微信头像图
            $color = imagecolorallocate($image_5, 255, 255, 255);
            imagefill($image_5, 0, 0, $color);
            imageColorTransparent($image_5, $color);

            imagecopyresampled($image_3,$image_1,0,0,0,0,imagesx($image_1),imagesy($image_1),imagesx($image_1),imagesy($image_1));
            imagecopyresampled($image_5,$image_4,0,0,0,0,imagesx($image_5),imagesy($image_5),imagesx($image_4),imagesy($image_4));
            imagecopymerge($image_3,$image_2,372,1346,0,0,imagesx($image_2),imagesx($image_2),100);
            $r = 95; //圆半径
            for ($x = 0; $x < 190; $x++) {
                for ($y = 0; $y < 190; $y++) {
                    $rgbColor = imagecolorat($image_5, $x, $y);
                    $a = $x-$r;
                    $b = $y-$r;
                    if ( ( ( $a*$a + $b*$b) <= ($r * $r) ) ) {
                        $n_x = $x+354;
                        $n_y = $y+34;
                        imagesetpixel($image_3, $n_x, $n_y, $rgbColor);
                    }
                }
            }
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
        }else{
            $file_name=$phone_qr_name;
        }

        $file_url = $qiniu_url."/".$file_name;
        return $file_url;
    }


    /**
     * 优学优享 邀请会员生成二维码图片
     */
    public function get_agent_qr_new(){
        $wx_openid = $this->get_in_str_val("wx_openid");
        $row = $this->t_agent->get_agent_info_by_openid($wx_openid);
        $phone = '';
        if(isset($row['phone'])){
            $phone = $row['phone'];
        }
        if(!$phone || $wx_openid==""){
            return "";
        }

        $qiniu         = \App\Helper\Config::get_config("qiniu");

        if ( \App\Helper\Utils::check_env_is_test() ) {
            $phone_qr_name = $phone."_qr_agent_merber1.png";
        }else{
            $phone_qr_name = $phone."_qr_agent_merber.png";
        }
        $qiniu_url     = $qiniu['public']['url'];
        $is_exists     = \App\Helper\Utils::qiniu_file_stat($qiniu_url,$phone_qr_name);
        //判断是否更新微信头像
        // if ($old_headimgurl != $headimgurl) {
        //     $this->t_agent->field_update_list($row['id'],['headimgurl' => $headimgurl]);
        //     if($is_exists) {
        //         //删除七牛图片
        //         \App\Helper\Utils::qiniu_del_file($qiniu_url,$phone_qr_name);
        //     }
        //     $is_exists = false;
        // }

        if(!$is_exists){
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
        }else{
            $file_name=$phone_qr_name;
        }

        $file_url = $qiniu_url."/".$file_name;
        return $file_url;
    }

    public function resize_img($url,$path='/tmp/'){
        $imgname = $path.uniqid().'.jpg';
        $file = $url;
        list($width, $height) = getimagesize($file); //获取原图尺寸
        $percent = (110/$width);
        //缩放尺寸
        $newwidth = 190;
        $newheight = 190;
        $src_im = imagecreatefromjpeg($file);
        $dst_im = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresized($dst_im, $src_im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        imagejpeg($dst_im, $imgname); //输出压缩后的图片
        imagedestroy($dst_im);
        imagedestroy($src_im);
        return $imgname;
    }

    //第一步生成圆角图片
    public function test($url,$path='/tmp/'){
        $w = 190;  $h=190; // original size
        $original_path= $url;
        $dest_path = $path.uniqid().'.png';
        $src = imagecreatefromjpeg($original_path);
        $newpic = imagecreatetruecolor($w,$h);
        imagealphablending($newpic,false);
        $transparent = imagecolorallocatealpha($newpic, 0, 0, 0, 127);
        $r=$w/2;
        for($x=0;$x<$w;$x++)
            for($y=0;$y<$h;$y++){
                $c = imagecolorat($src,$x,$y);
                $_x = $x - $w/2;
                $_y = $y - $h/2;
                if((($_x*$_x) + ($_y*$_y)) < ($r*$r)){
                    imagesetpixel($newpic,$x,$y,$c);
                }else{
                    imagesetpixel($newpic,$x,$y,$transparent);
                }
            }
        imagesavealpha($newpic, true);
        imagepng($newpic, $dest_path);
        imagedestroy($newpic);
        imagedestroy($src);
        unlink($url);
        return $dest_path;
    }



    public function send_charge_info(){
        $orderid = $this->get_in_int_val("orderid");
        $channel = $this->get_in_str_val("channel");
        // $channel = "wx";
        $orderid = 29598;
        $order_info = $this->t_order_info->field_get_list($orderid,"price,userid,lesson_total,default_lesson_count");
        $amount = $order_info["price"];
        /*  if (empty($channel) || empty($amount)) {
            return $this->output_err("channel or amount is empty");
            /* echo 'channel or amount is empty';
               exit();*/
        /* }*/
        $channel = strtolower($channel);


        // $orderNo = $orderid+1000000000;
        if($channel=="cmb_wallet"){
            $orderNo = substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 10);
        }else{
            $orderNo = $orderid.substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        }
        //  dd($orderNo);
        /**
         * 设置请求签名密钥，密钥对需要你自己用 openssl 工具生成，如何生成可以参考帮助中心：https://help.pingxx.com/article/123161；
         * 生成密钥后，需要在代码中设置请求签名的私钥(rsa_private_key.pem)；
         * 然后登录 [Dashboard](https://dashboard.pingxx.com)->点击右上角公司名称->开发信息->商户公钥（用于商户身份验证）
         * 将你的公钥复制粘贴进去并且保存->先启用 Test 模式进行测试->测试通过后启用 Live 模式
         */

        \Pingpp\Pingpp::setApiKey(APP_KEY);  // 设置 API Key
        \Pingpp\Pingpp::setPrivateKeyPath(app_path("Libs/Pingpp/your_rsa_private_key.pem"));   // 设置私钥地址
        \Pingpp\Pingpp::setPrivateKey(file_get_contents(app_path("Libs/Pingpp/your_rsa_private_key.pem"))); //设置私钥内容

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
                'token' => 'dsafadsfasdfadsjuyhfnhujkijunhaf', // 选填
                'user_id'=>'110337689002'
                  );
            break;
        case 'cmb_wallet':
            $extra = array(
                'result_url' => 'http://example.com/success',// 支付成功页面跳转路径
                'p_no'=> '111111111111111111111',//客户协议号，不超过 30 位的纯数字字符串。
                'seq'  =>'111111111',//协议开通请求流水号，不超过 20 位的纯数字字符串，请保证系统内唯一。
                'm_uid' =>'222222',//协议用户 ID，不超过 20 位的纯数字字符串。
                'mobile'=>'13817759346'//协议手机号，11 位数字
                // 'discount_amount'=>'2000'// 立减金额，支付完成的返回参数，单位为分。
            );
            break;
        case 'mmdpay_wap':
            $extra = array(
                'phone' => '13817759346',// 手机号
                'id_no'=>'331081198904151212',//身份证号。
                'name'  =>'tt',//真实姓名
            );
            break;
        case 'alipay_pc_direct':
            $extra = array(
                'success_url' => 'http://admin.leo1v1.com/common/get_webhooks_notice'
            );
            break;
        case 'wx_pub':
            $extra = array(
                'open_id' => 'wx636f1058abca1bc1'
            );
            break;
        case 'wx_pub_qr':
            $extra = array(
                'product_id' => $orderNo,
                'success_url' => 'http://admin.leo1v1.com/common/get_webhooks_notice'
            );
            break;





        }

        $nick = $this->cache_get_student_nick($order_info["userid"]);
        $lesson_count = $order_info["lesson_total"]*$order_info["default_lesson_count"]/100;

        try {
            $ch = \Pingpp\Charge::create(
                array(
                    //请求参数字段规则，请参考 API 文档：https://www.pingxx.com/api#api-c-new
                    'subject'   => '合同付款',
                    'body'      => '学生:'.$nick.",总课时:".$lesson_count,
                    'amount'    => $amount,//订单总金额, 人民币单位：分（如订单总金额为 1 元，此处请填 100）
                    'order_no'  => $orderNo,// 推荐使用 8-20 位，要求数字或字母，不允许其他字符
                    'currency'  => 'cny',
                    'extra'     => $extra,
                    'channel'   => $channel,// 支付使用的第三方支付渠道取值，请参考：https://www.pingxx.com/api#api-c-new
                    'client_ip' => $_SERVER['REMOTE_ADDR'],// 发起支付请求客户端的 IP 地址，格式为 IPV4，如: 127.0.0.1
                    'app'       => array('id' => APP_ID)
                )
            );
            if($ch){
                $this->t_orderid_orderno_list->row_insert([
                    "order_no"  =>$orderNo,
                    "orderid"   =>$orderid
                ]);
            }
            //echo $ch;// 输出 Ping++ 返回的支付凭据 Charge
            //dd(json_decode($ch,true));
            return $this->output_succ(["charge"=>$ch]);
            // return $this->output_succ(json_decode($ch,true));
        } catch (\Pingpp\Error\Base $e) {
            // 捕获报错信息
            if ($e->getHttpStatus() != null) {
                header('Status: ' . $e->getHttpStatus());
                \App\Helper\Utils::logger($e->getHttpBody());
                echo $e->getHttpBody();
            } else {
                \App\Helper\Utils::logger($e->getMessage());
                echo $e->getMessage();
            }
            return $this->output_err("系统异常");

        }

    }

    public function get_webhooks_notice(){
        return $this->get_webhooks_notice_test();
    }
    public function get_webhooks_notice_test(){
        $event = json_decode(file_get_contents("php://input"));

        // 对异步通知做处理
        if (!isset($event->type)) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
            exit("fail");
        }
        switch ($event->type) {
        case "charge.succeeded":
            // 开发者在此处加入对支付异步通知的处理代码
            header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
            $orderNo = $event->data->object->order_no;
            $channel = $event->data->object->channel;
            $aa = E\Eorder_channel::s2v($channel);
            $channel_name = E\Eorder_channel::get_desc($aa);
            if(empty($channel_name)){
                $channel_name = $channel;
            }

            $orderid=  $this->t_orderid_orderno_list->get_orderid($orderNo);
            $order_type = $this->t_orderid_orderno_list->get_order_type($orderNo);
            if($order_type==0){
                $pre_price = $this->t_order_info->get_pre_price($orderid);
                $pre_pay_time = $this->t_order_info->get_pre_pay_time($orderid);
                $userid = $this->t_order_info->get_userid($orderid);
                $sys_operator = $this->t_order_info->get_sys_operator($orderid);
                $nick = $this->t_student_info->get_nick($userid);
                if($pre_price>0 && empty($pre_pay_time)){
                    $this->t_order_info->field_update_list($orderid,[
                        "pre_pay_time"   =>time(),
                        "channel"        =>$channel,
                        "pre_from_orderno"=>$orderNo
                    ]);
                    $this->t_manager_info->send_wx_todo_msg(
                        "echo",
                        "合同定金付款通知",
                        "合同定金付款通知",
                        "学生:".$nick." 合同定金付款成功,支付方式".$channel_name.",订单号:".$orderNo,
                        "/user_manage_new/money_contract_list?studentid=$userid");
                   

                    $this->t_manager_info->send_wx_todo_msg(
                        $sys_operator,
                        "合同定金付款通知",
                        "合同定金付款通知",
                        "学生:".$nick." 合同定金付款成功,支付方式".$channel_name.",订单号:".$orderNo,
                        "");
                    $this->t_manager_info->send_wx_todo_msg(
                        "jack",
                        "合同定金付款通知",
                        "合同定金付款通知",
                        "学生:".$nick." 合同定金付款成功,支付方式:".$channel_name.",订单号:".$orderNo,
                        "");
                    $child_orderid = $this->t_child_order_info->get_child_orderid($orderid,1);
                    if($child_orderid>0){
                        $this->t_child_order_info->field_update_list($child_orderid,[
                            "pay_status" =>1,
                            "pay_time"   =>time(),
                            "channel"    =>$channel,
                            "from_orderno"=>$orderNo
                        ]);
                    }

                }elseif($pre_price>0 && $pre_pay_time>0){
                    $this->t_order_info->field_update_list($orderid,[
                        "order_status" =>1,
                        "contract_status"=>1,
                        "pay_time"    =>time(),
                        "channel"     =>$channel,
                        "from_orderno"=>$orderNo
                    ]);
                    $this->t_manager_info->send_wx_todo_msg(
                        "echo",
                        "合同尾款付款通知",
                        "合同尾款付款通知",
                        "学生:".$nick." 合同尾款付款成功,支付方式".$channel_name.",订单号:".$orderNo,
                        "/user_manage_new/money_contract_list?studentid=$userid");
                    $this->t_manager_info->send_wx_todo_msg(
                        $sys_operator,
                        "合同尾款付款通知",
                        "合同尾款付款通知",
                        "学生:".$nick." 合同尾款付款成功,支付方式".$channel_name.",订单号:".$orderNo,
                        "");
                    $this->t_manager_info->send_wx_todo_msg(
                        "jack",
                        "合同尾款付款通知",
                        "合同尾款付款通知",
                        "学生:".$nick." 合同尾款付款成功,支付方式:".$channel_name.",订单号:".$orderNo,
                        "");
                    $child_orderid = $this->t_child_order_info->get_child_orderid($orderid,0);
                    if($child_orderid>0){
                        $this->t_child_order_info->field_update_list($child_orderid,[
                            "pay_status" =>1,
                            "pay_time"   =>time(),
                            "channel"    =>$channel,
                            "from_orderno"=>$orderNo
                        ]);
                    }


                }else{

                    $this->t_order_info->field_update_list($orderid,[
                        "order_status" =>1,
                        "contract_status"=>1,
                        "pay_time"       =>time(),
                        "channel"        =>$channel,
                        "from_orderno"=>$orderNo
                    ]);
                    $this->t_manager_info->send_wx_todo_msg(
                        "echo",
                        "合同付款通知",
                        "合同付款通知",
                        "学生:".$nick." 合同付款成功,支付方式".$channel_name.",订单号:".$orderNo,
                        "/user_manage_new/money_contract_list?studentid=$userid");
                    $this->t_manager_info->send_wx_todo_msg(
                        $sys_operator,
                        "合同付款通知",
                        "合同付款通知",
                        "学生:".$nick." 合同付款成功,支付方式".$channel_name.",订单号:".$orderNo,
                        "");
                    $this->t_manager_info->send_wx_todo_msg(
                        "jack",
                        "合同付款通知",
                        "合同付款通知",
                        "学生:".$nick." 合同付款成功,支付方式:".$channel_name.",订单号:".$orderNo,
                        "");
                    $child_orderid = $this->t_child_order_info->get_child_orderid($orderid,0);
                    if($child_orderid>0){
                        $this->t_child_order_info->field_update_list($child_orderid,[
                            "pay_status" =>1,
                            "pay_time"   =>time(),
                            "channel"    =>$channel,
                            "from_orderno"=>$orderNo
                        ]);
                    }


                }
            }elseif($order_type==1){
                $parent_orderid = $this->t_child_order_info->get_parent_orderid($orderid);
                $userid = $this->t_order_info->get_userid($parent_orderid);
                $sys_operator = $this->t_order_info->get_sys_operator($parent_orderid);
                $nick = $this->t_student_info->get_nick($userid);
                $this->t_child_order_info->field_update_list($orderid,[
                    "pay_status" =>1,
                    "pay_time"   =>time(),
                    "channel"    =>$channel,
                    "from_orderno"=>$orderNo
                ]);
                $this->t_manager_info->send_wx_todo_msg(
                    "echo",
                    "合同付款通知",
                    "合同付款通知",
                    "学生:".$nick." 合同付款成功,支付方式".$channel_name.",订单号:".$orderNo,
                    "/user_manage_new/money_contract_list?studentid=$userid");
                $this->t_manager_info->send_wx_todo_msg(
                    "zero",
                    "合同付款通知",
                    "合同付款通知",
                    "学生:".$nick." 合同付款成功,支付方式".$channel_name.",订单号:".$orderNo,
                    "/user_manage_new/money_contract_list?studentid=$userid");

                $this->t_manager_info->send_wx_todo_msg(
                    $sys_operator,
                    "合同付款通知",
                    "合同付款通知",
                    "学生:".$nick." 合同付款成功,支付方式".$channel_name.",订单号:".$orderNo,
                    "");
                $this->t_manager_info->send_wx_todo_msg(
                    "jack",
                    "合同付款通知",
                    "合同付款通知",
                    "学生:".$nick." 合同付款成功,支付方式:".$channel_name.",订单号:".$orderNo,
                    "");
                $all_order_pay = $this->t_child_order_info->chick_all_order_have_pay($parent_orderid);
                if(empty($all_order_pay)){
                    $this->t_order_info->field_update_list($parent_orderid,[
                        "order_status" =>1,
                        "contract_status"=>1,
                        "pay_time"       =>time()
                    ]);
                    $this->t_manager_info->send_wx_todo_msg(
                        "echo",
                        "合同付款通知",
                        "合同已支付全款",
                        "学生:".$nick." 合同已支付全款",
                        "/user_manage_new/money_contract_list?studentid=$userid");
                    $this->t_manager_info->send_wx_todo_msg(
                        "zero",
                        "合同付款通知",
                        "合同已支付全款",
                        "学生:".$nick." 合同已支付全款",
                        "/user_manage_new/money_contract_list?studentid=$userid");

                    $this->t_manager_info->send_wx_todo_msg(
                        $sys_operator,
                        "合同付款通知",
                        "合同已支付全款",
                        "学生:".$nick." 合同已支付全款",
                        "");
                    $this->t_manager_info->send_wx_todo_msg(
                        "jack",
                        "合同付款通知",
                        "合同已支付全款",
                        "学生:".$nick." 合同已支付全款",
                        "");


                }

            }

            break;
        case "refund.succeeded":
            // 开发者在此处加入对退款异步通知的处理代码
            header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
            break;
        default:
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
            break;
        }
    }


    //微信老师帮绑定老师账号
    public function bind(){
        $phone      = $this->get_in_str_val("phone");
        $code       = $this->get_in_str_val("code");
        $wx_openid  = $this->get_in_str_val("wx_openid");
        $check_code = \App\Helper\Common::redis_get("JOIN_USER_PHONE_$phone" );
        \App\Helper\Utils::logger("nange:".$wx_openid);

        if($phone==""){
            return $this->output_err("手机号不能为空！");
        }

        if($code==$check_code){
            $teacher_info = $this->t_teacher_info->get_teacher_info_by_phone($phone);

            if($teacher_info['subject']==E\Esubject::V_11){ // 教育学老师无法绑定老师帮
                return $this->output_err("此账号无法绑定！");
            }

            if(!isset($teacher_info['teacherid'])){
                $teacher_info['phone']            = $phone;
                $teacher_info['acc']              = "wx_reference";
                $teacher_info['teacher_type']     = 32;
                $teacher_info['teacher_ref_type'] = 32;
                $teacher_info['send_sms_flag']    = 0;
                $teacher_info['wx_use_flag']      = 0;
                $teacher_info['use_easy_pass']    = 2;

                $data = $this->add_teacher_common($teacher_info);
                if(!$data || !is_int($data)){
                    if($data===false){
                        $data="生成失败！请退出重试！";
                    }

                    if($data == '该手机号已存在'){
                        $teacherid_old = $this->t_teacher_info->get_teacherid_by_phone($phone);
                        $teacher_info['teacherid'] = $teacherid_old;
                        $ret = $this->t_teacher_info->field_update_list($teacherid_old, [
                            "wx_openid" => $wx_openid
                        ]);
                    }else{
                        return $this->output_err($data);
                    }
                }else{
                    $teacher_info['teacherid'] = $data;
                }

            }

            if($wx_openid){
                $teacherid = $this->t_teacher_info->get_teacherid_by_openid($wx_openid);
                if($teacherid>0 && $teacherid!=$teacher_info['teacherid']){
                    $ret = $this->t_teacher_info->field_update_list($teacherid,[
                        "wx_openid" => null,
                    ]);
                }
                $ret = $this->t_teacher_info->field_update_list($teacher_info['teacherid'], [
                    "wx_openid" => $wx_openid
                ]);
            }else{
                return $this->output_err("微信绑定失败!请重新登录后绑定");
            }

            session(["login_userid"=>$teacher_info['teacherid']]);
            session(["login_user_role"=>2]);
            session(["teacher_wx_use_flag"=>$teacher_info['wx_use_flag']]);
            return $this->output_succ(["wx_use_flag"=>$teacher_info['wx_use_flag']]);
        }else{
            return $this->output_err("验证码不对");
        }
    }

    //wx- teacher
    public function send_phone_code () { //999
        \App\Helper\Utils::logger("send_er_wei");

        return (new common_ex )->send_phone_code();
    }

    public function get_teacher_hornor_list(){ //1016
        $url = "http://admin.leo1v1.com/teacher_money/get_teacher_lesson_total_list";
        $post_data = [];
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);

        $ret_arr = json_decode($output,true);

        if($ret_arr == null){
            return $this->output_err("老师荣誉榜获取失败!");
        }else if($ret_arr!=null && !empty($ret_arr['data'])){
            return $this->output_succ(['data'=>$ret_arr['data']]);
        }else{
            return $this->output_err('老师荣誉榜不存在!');
        }
    }

    public function get_comment_tags(){ //1013
        \App\Helper\Utils::logger("12333-tages");
        $lessonid = $this->get_in_int_val('lessonid');
        \App\Helper\Utils::logger("lessonid11:".$lessonid);

        if(!$lessonid){
            return $this->output_err('lessonid not exist');
        }
        $homework_situation = ["格式好","格式差", "正确率高", "正确率低"];
        $content_grasp      = ["理解快"," 理解慢","  掌握扎实 "," 基础不牢"];
        $lesson_interact    = ["积极主动","参与度低"];

        $lesson_intro_str = $this->t_lesson_info_b2->get_lesson_intro($lessonid);
        \App\Helper\Utils::logger("lesson_intro_str1:".$lesson_intro_str);
        $lesson_intro_arr = [];
        if($lesson_intro_str){
            $lesson_intro_arr = explode('|',$lesson_intro_str);
            if (count($lesson_intro_arr) == 0 && strlen($lesson_intro_str)> 0) {
                $lesson_intro[]=$lesson_intro_str;
            }

            $default_comment_tags = [
                "homework_situation"=> $homework_situation,
                "content_grasp"=> $content_grasp,
                "lesson_interact"=> $lesson_interact,
                "lesson_intro"=>$lesson_intro_arr
            ];
        }else{
            $default_comment_tags = [
                "homework_situation"=> $homework_situation,
                "content_grasp"=> $content_grasp,
                "lesson_interact"=> $lesson_interact,
            ];
        }
        \App\Helper\Utils::logger("default_comment_tags:".json_encode($default_comment_tags));

        return $this->output_succ(['default_comment_tags'=>$default_comment_tags]);
    }
    public function get_token($bucket){
        $qiniu = \App\Helper\Config::get_config("qiniu");

        $accessKey = $qiniu['access_key'];
        $secretKey = $qiniu['secret_key'];

        // 构建鉴权对象
        $auth = new \Qiniu\Auth ($accessKey, $secretKey);

        // 上传到七牛后保存的文件名
         return $auth->uploadToken($bucket);
    }

    public function get_bucket_info() {
        $is_public = $this->get_in_int_val( "is_public", 0 );
        $qiniu_config = \App\Helper\Config::get_config("qiniu");;

        $public_bucket = $qiniu_config["public"] ['bucket'];
        $private_bucket = $qiniu_config["private_url"] ['bucket'];

        if ($is_public) {
            $ret_arr = [
                "bucket" => $public_bucket,
                "domain" =>  $qiniu_config["public"] ["url"],
                "token"  => $this->get_token($public_bucket),
            ];
        }else{
            $ret_arr = [
                "domain" => $qiniu_config["private_url"] ["url"],
                "token"  => $this->get_token($private_bucket),
                "bucket" => $private_bucket,
            ];
        }
        return $this->output_succ($ret_arr);
    }


    public function get_new_bucket_info() {
        $qiniu_config = \App\Helper\Config::get_config("qiniu");;

        $ret_arr = [
            "bucket" => "teacher-doc",
            "domain" =>  "http://teacher-doc.qiniudn.com",
            "token"  => $this->get_token("teacher-doc"),
        ];
        \App\Helper\Utils::logger("menu_str_show: ".json_encode($ret_arr));
        return $this->output_succ($ret_arr);
    }



    public function upload_qiniu() {
        $file = Input::file('file');
        $file_name_fix=$this->get_in_str_val("file_name_fix");

        $qiniu_config=\App\Helper\Config::get_config("qiniu");;

        $accessKey = $qiniu_config['access_key'];
        $secretKey = $qiniu_config['secret_key'];

        // 构建鉴权对象

        $private_bucket = $qiniu_config["private_url"] ['bucket'];

        if($file->isValid()){
            //处理列
            $tmpName       = $file ->getFileName();
            $realPath      = $file ->getRealPath();
            $original_name = $file->getClientOriginalName();
            //$objPHPExcel = $objReader->load( $realPath );
            preg_match('/.*\.([^.]*)$/',$original_name , $matches);
            $qiniu_file_name = $file_name_fix.".".$matches[1];
            // 构建鉴权对象
            $auth = new Auth( $accessKey, $secretKey );

            // 要上传的空间
            $bucket = $private_bucket ;
            //delete old file
            $bucketMgr = new BucketManager($auth);
            $bucketMgr->delete($bucket, $qiniu_file_name);

            // 生成上传 Token
            $token = $auth->uploadToken($bucket);

            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            \App\Helper\Utils::logger("start...:$bucket");
            list($ret, $err) = $uploadMgr->putFile($token, $qiniu_file_name,  $realPath );
            \App\Helper\Utils::logger( "error:" . json_encode($err) );

            \App\Helper\Utils::logger("end ...");
            return $this->output_succ(["file_name" => $qiniu_file_name]);
        }
        return $this->output_err("上传失败");
    }

    //中文分词预处理
    public function get_ppl_data(){
        $pa=new \Analysis\PhpAnalysis();
        $demand = $this->get_in_str_val("demand","哈哈哈");

        $pa->SetSource($demand);
        $pa->resultType=2;
        $pa->differMax=true;
        $pa->StartAnalysis();
        $arr=$pa->GetFinallyIndex();
        dd($arr);
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }

    //百度有钱花接口
    public function send_baidu_money_charge(){
        $orderid = $this->get_in_int_val("orderid",159);


        //期待贷款额度(分单位)
        $money = $this->t_child_order_info->get_price($orderid);

        //分期期数
        $period = $this->t_child_order_info->get_period_num($orderid);
        //成交价格
        $parent_orderid = $this->t_child_order_info->get_parent_orderid($orderid);
        $dealmoney = $this->t_order_info->get_price($parent_orderid);

        //订单id
        $orderNo = $orderid.substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);




        $url = 'https://umoney.baidu.com/edu/openapi/post';
        // $url = 'http://vipabc.umoney.baidu.com/edu/openapi/post';
        // $url="http://test.umoney.baidu.com/edu/openapi/post";
        // $url="http://umoney.umoney.baidu.com/edu/openapi/post";

        $userid = $this->t_order_info->get_userid($parent_orderid);
        $user_info = $this->t_student_info->field_get_list($userid,"nick,phone,email");

        // RSA加密数据
        $endata = array(
            'username' => $user_info["nick"],
            'mobile' => $user_info["phone"],
            'email' => $user_info["email"],
        );

        $rsaData = $this->enrsa($endata);


        $arrParams = array(
            'action' => 'sync_order_info',
            'tpl' => 'leoedu',// 分配的tpl
            'corpid' => 'leoedu',// 分配的corpid
            'orderid' => $orderNo,// 机构订单号
            'money' => $money,// 期望贷款额度（分单位）
            'dealmoney' => $dealmoney,// 成交价格（分单位）>= 期望额度+首付额度
            'period' => $period,// 期数
            'courseid' => 'SHLEOZ3101001',// 课程id（会分配）
            'coursename' => '小学在线课程',// 课程名称
            'oauthid' => $userid,// 用户id 机构方提供
            'data' => $rsaData,
        );

        $strSecretKey = '9v4DvTxOz3';// 分配的key
        $arrParams['sign'] = $this->createBaseSign($arrParams, $strSecretKey);


        // 发送请求post(form)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $ret = curl_exec($ch);

        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result = json_decode($ret, true);
        // dd($result);

        // print_r($result);


        //返回信息成功后处理
        if($result["status"]==0){
            $this->t_orderid_orderno_list->row_insert([
                "order_no"  =>$orderNo,
                "orderid"   =>$orderid
            ]);
        }

        print_r($orderNo);
        echo "<br>";
        print_r($arrParams['sign']);
        echo "<br>";
        return $this->output_succ(["result"=>$result]);







    }

    //查询百度有钱花订单信息
    public function get_baidu_money_charge(){
        $url = 'https://umoney.baidu.com/edu/openapi/post';
        $orderid = $this->get_in_int_val("orderid");
        $orderNo = $this->t_order_info->get_from_orderno($orderid);
        if(empty($orderNo)){
            $orderNo=123456789;
        }

        // $url = 'http://vipabc.umoney.baidu.com/edu/openapi/post';

        $userid = $this->t_order_info->get_userid($orderid);
        $user_info = $this->t_student_info->field_get_list($userid,"nick,phone,email,grade");

        // RSA加密数据
        $endata = array(
            'username' => $user_info["nick"],
            'mobile' => $user_info["phone"],
            'email' => $user_info["email"],
        );

        $rsaData = $this->enrsa($endata);


        $arrParams = array(
            'action' => 'get_order_status',
            'tpl' => 'tpl',// 分配的tpl
            'corpid' => 'corpid',// 分配的corpid
            'orderid' => $orderNo,// 机构订单号
        );

        $strSecretKey = 'key';// 分配的key
        $arrParams['sign'] = $this->createBaseSign($arrParams, $strSecretKey);


        // 发送请求post(form)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $ret = curl_exec($ch);

        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result = json_decode($ret, true);
        dd($result);

    }

    /**
     * @param $data
     * @return string
     * rsa 加密(百度有钱花)
     */
    public function enrsa($data){
        $public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC3//sR2tXw0wrC2DySx8vNGlqt
3Y7ldU9+LBLI6e1KS5lfc5jlTGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2kl
Bd6h4wrbbHA2XE1sq21ykja/Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o
2n1vP1D+tD3amHsK7QIDAQAB
-----END PUBLIC KEY-----';
        $pu_key = openssl_pkey_get_public($public_key);
        $str = json_encode($data);
        $encrypted = "";
        // 公钥加密  padding使用OPENSSL_PKCS1_PADDING这个
        if (openssl_public_encrypt($str, $encrypted, $pu_key, OPENSSL_PKCS1_PADDING)){
            $encrypted = base64_encode($encrypted);
        }
        return $encrypted;
    }


    /**
     * @param $param
     * @param string $strSecretKey
     * @return bool|string
     * 生成签名(百度有钱花)
     */
    public function createBaseSign($param, $strSecretKey){
        if (!is_array($param) || empty($param)){
            return false;
        }
        ksort($param);
        $concatStr = '';
        foreach ($param as $k=>$v) {
            $concatStr .= $k.'='.$v.'&';
        }
        $concatStr .= 'key='.$strSecretKey;
        return strtoupper(md5($concatStr));
    }

    //百度有钱花回调地址(测试)
    public function baidu_callback_return_info_test(){
        $orderNo = $this->get_in_str_val("orderid");
        $status = $this->get_in_int_val("status");
        $period_new = $this->get_in_int_val("period");
        $sign = $this->get_in_str_val("sign");
        $data = $_REQUEST;
        foreach($data as $k=>$v){
            if($k=="_url" || $k=="_ctl" || $k =="_act" || $k=="_role" || $k=="_userid" || $k=="sign"){
                unset($data[$k]);
            }
        }
        $orderid=  $this->t_orderid_orderno_list->get_orderid($orderNo);
        $check_exist = $this->t_child_order_info->get_parent_orderid($orderid);
        if(empty($check_exist)){
            return $this->output_succ(["status"=>1,"msg"=>"订单不存在"]);
        }else{
            //期待贷款额度(分单位)
            $money = $this->t_child_order_info->get_price($orderid);

            //分期期数
            $period = $this->t_child_order_info->get_period_num($orderid);
            //成交价格
            $parent_orderid = $this->t_child_order_info->get_parent_orderid($orderid);
            $dealmoney = $this->t_order_info->get_price($parent_orderid);

            //订单id
            // $orderNo = $orderid.substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);




            //$url = 'https://umoney.baidu.com/edu/openapi/post';
            // $url = 'http://vipabc.umoney.baidu.com/edu/openapi/post';
            $url="http://test.umoney.baidu.com/edu/openapi/post";

            $userid = $this->t_order_info->get_userid($parent_orderid);
            $user_info = $this->t_student_info->field_get_list($userid,"nick,phone,email");

            // RSA加密数据
            $endata = array(
                'username' => $user_info["nick"],
                'mobile' => $user_info["phone"],
                'email' => $user_info["email"],
            );

            $rsaData = $this->enrsa($endata);


            $arrParams = array(
                'action' => 'sync_order_info',
                'tpl' => 'leoedu',// 分配的tpl
                'corpid' => 'leoedu',// 分配的corpid
                'orderid' => $orderNo,// 机构订单号
                'money' => $money,// 期望贷款额度（分单位）
                'dealmoney' => $dealmoney,// 成交价格（分单位）>= 期望额度+首付额度
                'period' => $period,// 期数
                'courseid' => 'HXSD0101003',// 课程id（会分配）
                'coursename' => '理优分期课程',// 课程名称
                'oauthid' => $userid,// 用户id 机构方提供
                'data' => $rsaData,
            );

            $strSecretKey = '9v4DvTxOz3';// 分配的key
            $arrParams['sign'] = $this->createBaseSign($data, $strSecretKey);
            if($arrParams['sign'] != $sign){
                return $this->output_succ(["status"=>2,"msg"=>"参数错误"]);
            }else{
                if($status==8){
                    $this->t_child_order_info->field_update_list($orderid,[
                        "pay_status"  =>1,
                        "pay_time"    =>time(),
                        "channel"     =>"baidu",
                        "from_orderno"=>$orderNo,
                        "period_num"  =>$period_new
                    ]);
                    $this->t_manager_info->send_wx_todo_msg(
                        "jack",
                        "百度分期付款通知",
                        "百度分期付款通知",
                        "学生:".$user_info["nick"]." 百度分期付款成功,支付方式:百度有钱花,订单号:".$orderNo,
                        "");

                }
                return $this->output_succ(["status"=>0,"msg"=>"success"]);
            }

        }
        // dd(111);
    }

    //百度有钱花回调地址
    public function baidu_callback_return_info(){
        dd(111);
    }


    //建行支付测试接口
    public function send_ccb_order_charge(){
        $orderid = $this->get_in_int_val("orderid");
        $orderid = 974;
        //成交价格
        $payment = $this->t_order_info->get_price($orderid);
        //订单id
        $orderNo = $orderid.substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        //MAC校验域
        // $mac = md5($orderNo);
        $merchantid= 105290000009104;

        //信用卡分期柜台代码
        $posid = '002171923';
        $branchid = 310000000;
        $curcode = "01";
        $txcode = 520100;
        $remark1="";
        $remark2 ="";
        $type=1;
        $gateway=0;
        $clientip = "";
        $reginfo = "学生:".$this->t_order_info->get_userid($orderid);
        $proinfo="课程";
        $referer="";
        $thirdappinfo="comccbpay105290000009104leoedu";
        $calljs="";
        $installnum = "";
        $sec = "30819d300d06092a864886f70d010101050003818b0030818702818100da1faeffe420660abea650bea7d21afff96881c1fc0c0b06e6edb9f7bdd4e617da53dd6fe375321cfed3653a6cf09a8e13166a4f5bb15af5122701f0f2bcc7f5d86e8a7b870a4ddf82a68a9a8b2f5a2b963e955b63e7aebb19f282e2194b1dd3d5654a63d05c5a7471088b728616cd1f67726c6629ba39c17b8df73e5ca8b42d020111";
        $pub = substr($sec,-30);
        // dd($sect);

        $mac = md5("MERCHANTID=".$merchantid."@&POSID=".$posid."@&BRANCHID=".$branchid."@&ORDERID=".$orderNo."@&PAYMENT=".$payment."@&CURCODE=".$curcode."@&TXCODE=".$txcode."@&REMARK1=".$remark1."@&REMARK2=".$remark2."@&TYPE=".$type."@&PUB=".$pub."%@&GATEWAY=".$gateway."@&CLIENTIP=".$clientip."@&REGINFO=".$reginfo."@&PROINFO=".$proinfo."@&REFERER=".$referer."@&THIRDAPPINFO=".$thirdappinfo."@");
        $url = "https://ibsbjstar.ccb.com.cn/app/ccbMain?MERCHANTID=".$merchantid."&POSID=".urlencode($posid)."&BRANCHID=".$branchid."&ORDERID=".$orderNo."&PAYMENT=".$payment."&CURCODE=".urlencode($curcode)."&TXCODE=".$txcode."&REMARK1=".$remark1."&REMARK2=".$remark2."&MAC=".urlencode($mac)."&TYPE=".$type."&GATEWAY=".$gateway."&CLIENTIP=".$clientip."&REGINFO=".urlencode($reginfo)."&PROINFO=".urlencode($proinfo)."&REFERER=".$referer."&THIRDAPPINFO=".urlencode($thirdappinfo)."&CALLJS=".$calljs."&INSTALLNUM=".$installnum;
        dd($url);

        $data = [
            "payment" =>$payment,
            "orderid" =>$orderid,
            "mac"     =>$mac,
            "merchantid" =>$merchantid,
            "posid"      =>$posid,
            "branchid"   =>$branchid,
            "curcode"    =>$curcode,
            "txcode"     =>$txcode,
            "remark2"    =>$remark2,
            "remark1"    =>$remark1,
            "type"       =>$type,
            "gateway"    =>$gateway,
            "clientip"   =>$clientip,
            "reginfo"    =>$reginfo,
            "proinfo"    =>$proinfo,
            "referer"    =>$referer,
            "thirdappinfo"=>$thirdappinfo,
            "calljs"      =>$calljs,
            "installnum"  =>$installnum
        ];
        return $this->output_succ(["data"=>$data]);


    }

    function parse_roomid($roomid)
    {
        $tmp_arr                   = explode("y", strtolower(substr($roomid,2)));

        if (count( $tmp_arr) ==3 )  {
            $lesson_arr['courseid']    = $tmp_arr[0];
            $lesson_arr['lesson_num']  = $tmp_arr[1];
            $lesson_arr['lesson_type'] = $tmp_arr[2];
            return $lesson_arr;
        }else{
            return false;
        }
    }

    private function parse_userid($userid)
    {
        $pos = strpos($userid, "_");
        if($pos != false){
            $type   = substr($userid, 0, $pos);
            if (strlen($type)==4 && $type[0]=="p"){
                $type=substr($type,1);
            }
        }else{
            $item=$this->t_phone_to_user-> get_info_by_userid($userid);
            $role=$item["role"];
            $utype_to_prefix_config = array(
                1 => 'stu',
                2 => 'tea',
                3 => 'ad',
                4 => 'par'
            );

            $type   = @$utype_to_prefix_config[$role];
        }
        return $type;
    }


    /**
     * functions handle xmpp and webrtc notifies
     */
    public function notify(){
        $roomid   = $this->get_in_str_val("roomid"); //l_11Y21Y1
        $userid   = $this->get_in_str_val("userid"); //par_111, tea_112341 , 1341241
        $opt_type = $this->get_in_str_val("opt_type"); //login logout stop restart
        $server_type = $this->get_in_str_val("server_type");
        $online_userlist = $this->get_in_str_val('online_userlist');
        $program_id = $this->get_in_int_val('program_id');

        if (preg_match('/_chat$/',$roomid, $matches)) {
            \App\Helper\Utils::logger(" $roomid no  log");
            return $this->output_succ();
        }
        if ($userid== "supervisor" ){
            return $this->output_succ();
        }

        $lesson_arr = $this->parse_roomid($roomid);
        if (!$lesson_arr) {
            return $this->output_succ();
        }
        $user_type_arr = array();
        if($online_userlist != ''){
            $online_user_arr = explode(',',$online_userlist);
            foreach($online_user_arr as $key => $value){
                if($value != '')
                    $user_type_arr[] = $this->parse_userid($value);
            }
        }
        $utype = $this->parse_userid($userid);

        $lessonid=0;
        if ( $opt_type == "login" || $opt_type == "logout"    ){
            $ret_arr= $this->t_lesson_info_b3->get_lesson_condition_info($lesson_arr['courseid'], $lesson_arr['lesson_num']);
            $condition =$ret_arr["lesson_condition"];
            $lessonid=$ret_arr["lessonid"];

            if ($utype=="tea" && $ret_arr["teacherid"] != $userid ) { //不是老师,是cc,不处理

            }else{
                $condition_new = $this->update_condition($condition, $utype, $user_type_arr, $opt_type, $server_type);
                $this->t_lesson_info->field_update_list($lessonid,[
                    "lesson_condition" => $condition_new
                ]);
            }
        }

        if ( ($lessonid && ($utype=="stu"|| $utype=="par"  || $utype=="tea") )
             || $opt_type =="register" ){
            //get_userid
            $tmp_userid=@preg_split("/_/" ,$userid)[1];
            if ($tmp_userid){
                $userid= $tmp_userid;
            }

            $server_type_conf = array("webrtc" =>1 , "xmpp" =>2 );
            $log_type_conf    = array("login" =>1 , "logout" =>2 ,"register"=>3,"no_recv_data"=>4);
            $server_type      = $server_type_conf[ $server_type];
            $opt_type         = $log_type_conf[ $opt_type];

            $this->t_lesson_opt_log->row_insert([
                'lessonid'    => $lessonid,
                'opt_time'    => time(),
                'opt_type'    => $opt_type,
                'userid'      => $userid,
                'server_type' => $server_type,
                'server_ip'   => ip2long($this->get_in_client_ip() ),
                'program_id'  => $program_id,
            ],false, true);
            if($utype=="tea" && $opt_type==1){
                $this->t_lesson_info_b3->set_real_begin_time($lessonid,time(NULL));
            }
        }
        return $this->output_succ();
    }


    private function update_condition($condition, $utype, $user_type_arr, $opt_type, $server_type)
    {
        $condition_arr;
        $opt_type_config = array(
            'login'   => 1,
            'logout'  => 0,
            'stop'    => 0,
            'restart' => 1
        );
        if($condition == ""){
            //xxxx_dis 表明相关服务器的断线次数
            $condition_arr = array(
                'stu' => array(
                    'xmpp'       => 0,
                    'webrtc'     => 0,
                    'xmpp_dis'   => 0,
                    'webrtc_dis' => 0
                ),
                'tea' => array(
                    'xmpp'       => 0,
                    'webrtc'     => 0,
                    'xmpp_dis'   => 0,
                    'webrtc_dis' => 0
                ),
                'ad' => array(
                    'xmpp'       => 0,
                    'webrtc'     => 0,
                    'xmpp_dis'   => 0,
                    'webrtc_dis' => 0
                ),
                'par' => array(
                    'xmpp'       => 0,
                    'webrtc'     => 0,
                    'xmpp_dis'   => 0,
                    'webrtc_dis' => 0
                ),
                'suspend' => 0
            );

         }else{
            $condition_arr = json_decode($condition, true);
         }

        if($opt_type == 'logout') {
            $condition_arr[$utype][$server_type . "_dis"] = @$condition_arr[$utype][$server_type . "_dis"]+ 1 ;
            $condition_arr[$utype][$server_type] = 0;
        }else if ( $opt_type =="login") {
            $condition_arr[$utype][$server_type] = 1;
        }

        return json_encode($condition_arr);
    }

    /**
     * 家长端发送试卷邮件
     * @param string paper_str 加密的卷子信息
     */
    public function send_papers_email(){
        $paper_str = $this->get_in_str_val("paper_str");

        $paper_data   = json_decode(\App\Helper\Utils::decode_str($paper_str),true);
        $to           = $paper_data['to'];
        $paperid_list = $paper_data['paperid_list'];

        $ret_info = $this->t_paper_info->get_paper_list_by_id_str($paperid_list);

        $title  = "理优升学帮试卷下载";
        $header = "
  		<meta charset='UTF-8'>
		<title>试卷下载</title>
		<style>
			body{
				text-align: center;
			  font-family: 'Microsoft YaHei', 微软雅黑, 'Microsoft JhengHei', Helvetica, Arial, FreeSans, Arimo, 'Droid Sans','wenquanyi micro hei','Hiragino Sans GB', 'Hiragino Sans GB W3', Arial, sans-serif;
			  -webkit-font-smoothing: antialiased;
			  -moz-osx-font-smoothing: grayscale;
			}
			#main{
				padding: 10px 0px 50px 0px;
				width: 80%;
				margin: 0 auto;
			}
			.header{
				color: #333333;
				font-size: 20px;
				margin: 40px 0 10px 0;
				text-align: left;
			}
			.header img{
				margin-bottom: 3px;
				vertical-align: middle;
			}
			.description{
				color: #999999;
				font-size: 15px;
				margin: 3px;
				text-align: left;
			}
			.description_important{
				color: #000000;
				font-size: 15px;
				margin: 3px 3px 8px 3px;
				text-align: left;
			}
			a{
				color: #0bceff;
				text-decoration: none;
				text-decoration: underline;
			}
			table{
				width: 100%;
			}
			td{
				vertical-align: top;
			}
			td div.description_important{
				margin-top: 15px;
			}
			td img{
				margin-right: 5px;
				padding-right: 5px;
			}
			.tdwrap{
				width: 150px;
			}
			ul{
				font-size: 15px;
				color: #0bceff;
				text-align: left;
			}
			li {
				font-size: 15px;
				margin: 3px;
				padding: 4px;
			}
			li a{
				font-size: 15px;
				margin: 3px;
			}
			.highlight{
				color: #f26173;
			}
		</style>
	</head>
	<body>
		<div id='email_exam_papers'>
			<img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/email_exam_papers/header.png' style='width:100%'/>
			<div id='main'>
				<div class='header'>亲爱的家长你好：</div>
				<div class='description'>以下是您选择下载的试卷，希望对您有所帮助</div>
				<div class='description'>本邮件是由“<span class='highlight'>理优升学帮</span>”发送，若想了解更多，请您通过以下方式了解我们。</div>
				<div class='header'><img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/email_exam_papers/title_start.png' height='18px'/>&nbsp;&nbsp;&nbsp;试卷下载</div>";
        $i = 1;
        $body ="<ul>";
        foreach ($ret_info as $item){
            $body.="<li><a href=".$item["paper_url"] ." >$i :  ".$item["paper_name"] ." </a>";
            $i++;
        }
        $body .= "</ul>";
        $footer= "<div class='description'>单击试卷名称下载，一份文档中包含试卷及答案。</div>
				<div class='description'>(如果下载中遇到任何问题，请关注下方订阅号“上海升学帮”，并回复相关问题)</div>
				<div class='header'><img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/email_exam_papers/title_start.png' height='18px'/>&nbsp;&nbsp;&nbsp;关于我们</div>
				<div class='description_important'>理优1对1致力于为初高中学生提供专业、专注、有效的教学，帮助更多的家庭打破师资、时间、地域、费用的局限，获得四维一体的专业学习体验。作为在线教育行业内首家专注于移动Pad端研发的公司，理优1对1在1年内成功获得GGV数千万元A轮投资（GGV风投曾投资阿里巴巴集团、优酷土豆、去哪儿、小红书等知名企业）。</div>
				<div class='description'>咨询电话：400-680-6180</div>
				<div class='description'>官方店铺：<a href='https://shop123923740.taobao.com/'>https://shop123923740.taobao.com/</a></div>
				<div class='description'>理优官网：<a href='http://www.leo1v1.com/'>http://www.leo1v1.com/</a></div>
				<div class='description'>联系地址：上海市闵行区宜山路2016号合川大厦13楼F室</div>
				<div class='header'><img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/email_exam_papers/title_start.png' height='18px'/>&nbsp;&nbsp;&nbsp;APP下载</div>

				<table>
					<tr><td class='tdwrap'><img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/email_exam_papers/qr_parent.png' width='150px' height='150px' /></td>
						<td>
							<div class='description_padding'></div>
							<div class='description_important'>订阅号(升学版)</div>
							<div class='description'>请用微信扫码关注</div>
						</td>
					</tr>
					<tr><td class='tdwrap'><img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/email_exam_papers/qr_stu.png' width='150px' height='150px' /></td>
						<td>
							<div class='description_padding'></div>
							<div class='description_important'>学生端下载</div>
							<div class='description'>请用Pad的二维码扫描工具扫描下载</div>
						</td>
					</tr>
					<tr><td class='tdwrap'><img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/email_exam_papers/qr_index.png' width='150px' height='150px'/></td>
						<td>
							<div class='description_padding'></div>
							<div class='description_important'>手机官网</div>
							<div class='description'>请用手机的二维码扫描工具领取课程</div>
						</td>
					</tr>
				</table>
			</div>
			<img src='http://7u2f5q.com2.z0.glb.qiniucdn.com/email_exam_papers/footer.png' style='width:100%'/>
		</div>
	</body>";

        $html = $header.$body.$footer;
        $mail_ret = \App\Helper\Common::send_paper_mail_new($to,$title,$html);

        if ($mail_ret ) {
            $this->t_paper_info->paper_grow_down($paperid_list);
            return $this->output_succ();
        }else{
            return $this->output_err("发送失败");
        }
    }

    public function check_change_flag(){
        $lessonid = $this->get_in_int_val('lessonid');
        $check_info = $this->t_lesson_info_b3->get_tea_info($lessonid);
        $list = [];


        if($check_info['tea_cw_pic']){
            $pic_num = explode(',',$check_info['tea_cw_pic']);
            $list['tea_cw_pic'] = ' 已平铺 页数:'.count($pic_num);
        }else{
            $list['tea_cw_pic'] = ' 无';
        }

        if($check_info['create_time']){
            $list['create_time'] = date('Y-m-d H:i:s',$check_info['create_time']);
        }else{
            $list['create_time'] = ' 无';
        }

        return $this->output_succ(['data'=>$list]);
    }


}
