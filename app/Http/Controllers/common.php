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

        $html=$header.$body.$footer;
        $title="上海理优教研室";
        //$mail_ret=send_mail($to,$title,$html,true);
        $ret=\App\Helper\Common::send_paper_mail($to,$title,$html);

        $name   = $this->get_in_str_val("name","");
        $phone  = $this->get_in_str_val("phone","");
        $origin = "官网试卷用户";

        $this->add_user_origin_info($name,$phone);
        $this->t_seller_student_info->add_or_add_to_sub($name,$phone,0,$origin,0,0,0,0);
        $this->t_seller_student_new->book_free_lesson_new($name,$phone,0,$origin,0,0);

        if ($ret ) {
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
     * @param userid base64编码加密的字符串 userid=123&lessonid=123
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
            // $ret = $this->check_answer_time($answer["lessonid"]);
            // if(!$ret){
            //     header("Content-type: text/html; charset=utf-8");
            //     echo "超出答题时间!请参加下次培训!";
            //     return ;
            // }

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
                if($totalvalue>=90 && $teacher_info['train_through_new']==0){
                    $ret = $this->t_teacher_info->field_update_list($answer['userid'],[
                        "train_through_new"      => 1,
                        "train_through_new_time" => time(),
                    ]);

                    if(isset($teacher_info['email']) && !empty($teacher_info['email']) && strlen($teacher_info['email'])>3){
                        $title = "上海理优教研室";
                        $html  = $this->get_offer_html($teacher_info);
                        $ret   = \App\Helper\Common::send_paper_mail($teacher_info['email'],$title,$html);
                    }

                    if(isset($teacher_info['wx_openid']) && !empty($teacher_info['wx_openid'])){
                        /**
                         * 模板ID : 1FahTQqlGwCu1caY9wHCuBQXPOPKETuG_EGRNYU89II
                         * 标题   : 入职邀请通知
                         * {{first.DATA}}
                         * 职位名称：{{keyword1.DATA}}
                         * 公司名称：{{keyword2.DATA}}
                         * 入职时间：{{keyword3.DATA}}
                         * {{remark.DATA}}
                         */
                        $template_id      = "1FahTQqlGwCu1caY9wHCuBQXPOPKETuG_EGRNYU89II";
                        $data["first"]    = "老师您好，恭喜你已经通过理优入职培训，成为理优正式授课老师，等级为：".$level_str."等";
                        $data["keyword1"] = "教职老师";
                        $data["keyword2"] = "理优教育";
                        $data["keyword3"] = $today_date;
                        $data["remark"]   = "愿老师您与我们一起以春风化雨的精神,打造高品质教学服务,助我们理优学子更上一层楼。";
                        $offer_url        = "http://admin.yb1v1.com/common/show_offer_html?teacherid=".$answer['userid'];
                        \App\Helper\Utils::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id,$data,$offer_url);
                    }

                    // $this->add_trial_train_lesson($teacher_info);
                    //$str .= "<br>您培训已通过，请登陆老师后台进行模拟试听课程。";

                    $reference_info = $this->t_teacher_info->get_reference_info_by_phone($teacher_info['phone']);
                    $check_flag     = $this->t_teacher_money_list->check_is_exists($teacher_info['teacherid'],6);
                    if(!empty($reference_info['teacherid']) && !$check_flag){
                        $wx_openid      = $reference_info['wx_openid'];
                        $teacher_type   = $reference_info['teacher_type'];
                        if(!in_array($teacher_type,[21,22,31])){
                            if(in_array($teacher_info['identity'],[5,6])){
                                $type = 1;
                            }else{
                                $type = 2;
                            }
                            $begin_date = \App\Helper\Config::get_config("teacher_ref_start_time");
                            $begin_time = strtotime($begin_date);
                            $ref_num = $this->t_teacher_lecture_appointment_info->get_reference_num(
                                $reference_info['phone'],$type,$begin_time
                            );
                            $ref_price = \App\Helper\Utils::get_reference_money($teacher_info['identity'],$ref_num);
                            $this->t_teacher_money_list->row_insert([
                                "teacherid"  => $reference_info['teacherid'],
                                "money"      => $ref_price*100,
                                "money_info" => $teacher_info['teacherid'],
                                "add_time"   => time(),
                                "type"       => 6,
                            ]);
                            if($wx_openid!=""){
                                $template_id         = "kvkJPCc9t5LDc8sl0ll0imEWK7IGD1NrFKAiVSMwGwc";
                                $wx_data["first"]    = $teacher_info['nick']."已成功入职";
                                $wx_data["keyword1"] = "已入职";
                                $wx_data["keyword2"] = "";
                                $wx_data["remark"]   = "您已获得".$ref_price."元伯乐奖，请在个人中心-我的收入中查看详情，"
                                                   ."伯乐奖将于每月10日结算（如遇节假日，会延后到之后的工作日），"
                                                   ."请及时绑定银行卡号，如未绑定将无法发放。";
                                \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$wx_data);
                            }
                        }
                    }
                }
            }
        }
        echo $str;
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
            // $teacher_info['email']   = "wg392567893@163.com";
            // $teacher_info['subject'] = 4;
            $ret = \App\Helper\Common::send_paper_mail($teacher_info['email'],"上海理优教研室",$html);
            if($teacher_info['wx_openid']!=""){
                $template_id      = "1FahTQqlGwCu1caY9wHCuBQXPOPKETuG_EGRNYU89II";
                $data["first"]    = "老师您好，恭喜你已经通过理优入职培训，成为理优正式授课老师，等级为：B等";
                $data["keyword1"] = "教职老师";
                $data["keyword2"] = "理优教育";
                $data["keyword3"] = date("Y-m-d",time());
                $data["remark"]   = "愿老师您与我们一起以春风化雨的精神,打造高品质教学服务,助我们理优学子更上一层楼。";
                $offer_url        = "http://admin.yb1v1.com/common/show_offer_html?teacherid=".$teacherid;
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
            $teacher_info['subject'] = 4;
            $ret = \App\Helper\Common::send_paper_mail($teacher_info['email'],"上海理优教研室",$html);
            if($teacher_info['wx_openid']!=""){
                $template_id      = "1FahTQqlGwCu1caY9wHCuBQXPOPKETuG_EGRNYU89II";
                $data["first"]    = "老师您好，恭喜你已经通过理优入职培训，成为理优正式授课老师，等级为：B等";
                $data["keyword1"] = "教职老师";
                $data["keyword2"] = "理优教育";
                $data["keyword3"] = date("Y-m-d",time());
                $data["remark"]   = "愿老师您与我们一起以春风化雨的精神,打造高品质教学服务,助我们理优学子更上一层楼。";
                $offer_url        = "http://admin.yb1v1.com/common/show_level_up_html?teacherid=".$teacherid;
                \App\Helper\Utils::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id,$data,$offer_url);
            }
        }

        return $html;
    }

    public function get_offer_html($teacher_info){
        $name       = $teacher_info['nick'];
        $level_str  = E\Elevel::get_desc($teacher_info['level']);
        $date_str   = \App\Helper\Utils::unixtime2date(time(),"Y.m.d");
        $group_html = $this->get_qq_group_html($teacher_info['subject']);
        $html       = "
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf8'>
        <meta name='viewport' content='width=device-width, initial-scale=0.8, maximum-scale=1,user-scalable=true'>
        <style>
         *{margin:0 auto;padding:0 auto;}
         body{opacity:100%;color:#666;}
         html{font-size:10px;}
         .color333{color:#333;}
         .fl{float:left;}
         .fr{float:right;}

         .top-line{margin-top:24px;}
         .tea_name{position:relative;z-index:1;top:321px;}
         .tea_level{position:relative;z-index:1;top:410px;}
         .date{position:relative;z-index:1;top:-215px;left:165px;}

         .todo{margin:20px 0 10px 0;}
         .todo li{margin:10px 0;}

         .about_us{margin:30px 0 0;}
         .us_title{margin:0 0 10px;}
         .ul_title{margin:10px 0 0;color:#333;font-size;28px;}

         .join-us{margin:40px 0;}
         .join-us-content{width:44%;}
         .middle-line{
             width:28%;
             height:4rem;
             background:url(http://7u2f5q.com2.z0.glb.qiniucdn.com/7854b16d86652ff547354f84b119d7a51496676904532.png) repeat-x;
             background-position:0 50%;
         }

         .size12{font-size:2.4rem;}
         .size14{font-size:2.8rem;}
         .size18{font-size:3.6rem;}
         .size20{font-size:4rem;}
         .size24{font-size:4.8rem;}
         .content{width:700px;}
         .img_position{position:relative;width:700px;}

         @media screen and (max-width: 720px) {
             .size12{font-size:1.5rem;}
             .size14{font-size:1.75rem;}
             .size18{font-size:2.25rem;}
             .size20{font-size:2.5rem;}
             .size24{font-size:3rem;}
             .content{width:400px;}
             .img_position{width:400px;}
             .tea_name{top:199px;}
             .tea_level{top:241px;}
             .date{top:-135px;left:90px;}
             .middle-line{height:2.5rem;}
         }
        </style>
    </head>
<body>
    <div style='width:100%' align='center'>
        <div class='content'>
            <div class='logo top-line' align='center'>
                <img height='50px' src='http://7u2f5q.com2.z0.glb.qiniucdn.com/ff214d6936c8911f83b5ed28eba692481496717820241.png'/>
            </div>
            <div>
                <div class='size24 top-line color:#333'>
                    您的加入,我们期待已久
                </div>
                <div class='size14' style='margin:20px 0 0'>
                    以下是您的理优教育兼职讲师入职通知
                    <br/>
                    请仔细阅读通知书下方待办事项
                </div>
            </div>
            <div>
                <div class='size12' style='line-height:24px'>
                    <name class='tea_name'>".$name."</name>
                    <br/>
                    <level class='tea_level'>老师等级:".$level_str."</level>
                    <img class='img_position' src='http://7u2f5q.com2.z0.glb.qiniucdn.com/ae57036b08deb686fc7d52b8463a075e1496669999943.png'>
                     <date class='date'>&nbsp;&nbsp;".$date_str."</date>
                </div>
            </div>
            <div class='todo size12' align='left'>
                <div class='size20 color333'>待办事项</div>
                <div class='ul_title size14 color333'>
                    -加入相关QQ群(请备注 科目-年级-姓名)
                </div>
                <ul>".$group_html."</ul>
                <div class='ul_title size14 color333'>
                    -理优老师后台链接
                </div>
                <ul>
                    <li>
                        后台连接:<br>
                        http://www.leo1v1.com/login/teacher
                    </li>
                </ul>
            </div>
            <div class='about_us' align='left'>
                <div class='us_title size20 color333'>关于我们</div>
                <div class='size14' style='text-indent:2em'>理优1对1致力于为初高中学生提供专业、专注、有效的教学，帮助更多的家庭打破师资、时间、地域、费用的局限，
                    获得四维一体的专业学习体验。作为在线教育行业内首家专注于移动Pad端研发的公司，理优1对1在1年内成功获得
                    GGV数千万元A轮投资（GGV风投曾经投资阿里巴巴集团、优酷土豆、去哪儿、小红书等知名企业）。
                </div>
                <div class='join-us'>
                    <div class='middle-line fl'></div>
                    <div class='join-us-content size14 color333 fl' align='center'>我们欢迎您的加入</div>
                    <div class='middle-line fr'></div>
                    <div style='clear:both'></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
";
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
                $offer_url        = "http://admin.yb1v1.com/common/show_ruzhi_html?teacherid=".$teacherid;
                \App\Helper\Utils::send_teacher_msg_for_wx($teacher_info['wx_openid'],$template_id,$data,$offer_url);
            }
        }
        return $html;
    }

    public function get_qq_group_html($subject){
        $qq_common = ["问题答疑","528851744","用于薪资，软件等综合问题"];
        $qq_group  = [
            1=>[
                ["教研-语文","126321887","处理教学相关事务"],
                ["排课-语文","103229898","用于抢课"]
            ],2=>[
                ["教研-数学","29759286","处理教学相关事务"],
                ["排课-数学","132041242","用于排课"],
            ],3=>[
                ["教研-英语","451786901","处理教学相关事务"],
                ["排课-英语","41874330","用于排课"],
            ],4=>[
                ["教研-综合","513683916","处理教学相关事务"],
                ["排课-理化","129811086","用于排课"],
            ],5=>[
                ["教研-综合","513683916","处理教学相关事务"],
                ["排课-文理综合","538808064","用于排课"],
            ],
        ];
        if($subject<=3){
            $key=$subject;
        }elseif(in_array($subject,[4,5])){
            $key=4;
        }else{
            $key=5;
        }
        $qq_group[$key][]=$qq_common;
        $html="";
        foreach($qq_group[$key] as $qq_val){
            $html .= "<li>【LEO】".$qq_val[0]."<br>群号：".$qq_val[1]."<br>群介绍：".$qq_val[2]."</li>";
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

            dispatch( new \App\Jobs\send_error_mail(
                "xcwenn@qq.com","报错: lessonid = $lessonid pcm_file_count=$pcm_file_count pcm_file_all_size  = $pcm_file_all_size   " ,
                " lessonid = $lessonid pcm_file_count=$pcm_file_count pcm_file_all_size  = $pcm_file_all_size  "
            ));
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

            if ($real_begin_time > $item["lesson_start"]) {
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
            'data' => $ret,
            "webrtc_xmpp_server_list" => \App\Helper\Config::get_config("audio_server_list")
        ]);
    }

    /**
     * 老师微信帮 邀请有礼生成的二维码图片
     */
    public function get_teacher_qr(){
        $wx_openid = $this->get_in_str_val("wx_openid");

        \App\Helper\Utils::logger("get_teacher_info wx_openid:".$wx_openid);

        $phone = $this->t_teacher_info->get_phone_by_wx_openid($wx_openid);
        if(!$phone || $wx_openid==""){
            return "";
        }

        $qiniu         = \App\Helper\Config::get_config("qiniu");
        $phone_qr_name = $phone."_qr_summer.png";
        $qiniu_url     = $qiniu['public']['url'];
        $is_exists     = \App\Helper\Utils::qiniu_file_stat($qiniu_url,$phone_qr_name);
        if(!$is_exists){
            //text待转化为二维码的内容
            $text           = "http://wx-teacher-web.leo1v1.com/tea.html?".$phone;
            $qr_url         = "/tmp/".$phone.".png";
            $bg_url         = "http://leowww.oss-cn-shanghai.aliyuncs.com/summer_pic_invitation.png";
            $teacher_qr_url = "/tmp/".$phone_qr_name;
            \App\Helper\Utils::get_qr_code_png($text,$qr_url,5,4,3);

            $image_1 = imagecreatefrompng($bg_url);
            $image_2 = imagecreatefrompng($qr_url);
            $image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));
            imagecopyresampled($image_3,$image_1,0,0,0,0,imagesx($image_1),imagesy($image_1),imagesx($image_1),imagesy($image_1));
            imagecopymerge($image_3,$image_2, 455,875,0,0,imagesx($image_2),imagesy($image_2), 100);
            imagepng($image_3,$teacher_qr_url);

            $file_name = \App\Helper\Utils::qiniu_upload($teacher_qr_url);
            if($file_name!=''){
                $cmd_rm = "rm /tmp/".$phone."*.png";
                \App\Helper\Utils::exec_cmd($cmd_rm);
            }

            imagedestroy($image_1);
            imagedestroy($image_2);
            imagedestroy($image_3);
        }else{
            $file_name=$phone_qr_name;
        }

        $file_url = $qiniu_url."/".$file_name;
        return $file_url;
    }

    /**
     * 优学优享 我要邀请生成二维码图片
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
        $phone_qr_name = $phone."_qr_agent_bb.png";
        $qiniu_url     = $qiniu['public']['url'];
        $is_exists     = \App\Helper\Utils::qiniu_file_stat($qiniu_url,$phone_qr_name);
        if(!$is_exists){
            // $text         = "http://wx-yxyx-web.leo1v1.com/#/student-form?p_phone=".$phone;
            $text         = "http://www.leo1v1.com/market-invite/index.html?p_phone=".$phone;
            $qr_url       = "/tmp/".$phone.".png";
            $bg_url       = "http://7u2f5q.com2.z0.glb.qiniucdn.com/e1e96219645d2c0658973305cfc640ec1500451878002.png";
            $agent_qr_url = "/tmp/".$phone_qr_name;
            $headimgurl = "http://7u2f5q.com2.z0.glb.qiniucdn.com/9b4c10cff422a9d0ca9ca60025604e6c1498550175839.png";
            $image_4 = imagecreatefrompng($headimgurl);     //微信头像
            if($row['headimgurl']){
               $headimgurl = $row['headimgurl'];
               $datapath ="/tmp/".$phone."_headimg.png";
               $wgetshell ='wget -O '.$datapath.' "'.$row['headimgurl'].'" ';
               shell_exec($wgetshell);
               $image_4 = imagecreatefromjpeg($datapath);     //微信头像
            }           
            \App\Helper\Utils::logger('img4:'.$image_4);
            \App\Helper\Utils::get_qr_code_png($text,$qr_url,5,4,3);

            $image_1 = imagecreatefrompng($bg_url);     //背景图
            $image_2 = imagecreatefrompng($qr_url);     //二维码
            $image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));     //新建图
            $image_5 = imageCreatetruecolor(160,160);     //新建图
            imagecopyresampled($image_3,$image_1,0,0,0,0,imagesx($image_1),imagesy($image_1),imagesx($image_1),imagesy($image_1));
            imagecopyresampled($image_5,$image_4,0,0,0,0,imagesx($image_5),imagesy($image_5),imagesx($image_4),imagesy($image_4));
            imagecopymerge($image_3,$image_2,80,1080,0,0,180,180,100);
            imagecopymerge($image_3,$image_5,297,209,0,0,160,160,100);
            imagepng($image_3,$agent_qr_url);

            $file_name = \App\Helper\Utils::qiniu_upload($agent_qr_url);
            \App\Helper\Utils::logger('yxyx_file_name:'.$file_name);

            if($file_name!=''){
                $cmd_rm = "rm /tmp/".$phone."*.png";
                \App\Helper\Utils::exec_cmd($cmd_rm);
            }

            imagedestroy($image_1);
            imagedestroy($image_2);
            imagedestroy($image_3);
            imagedestroy($image_4);
        }else{
            $file_name=$phone_qr_name;
        }

        $file_url = $qiniu_url."/".$file_name;
        return $file_url;
    }

    public function send_charge_info(){
        $orderid = $this->get_in_int_val("orderid");
        $channel = $this->get_in_str_val("channel");
        $channel = "jdpay_wap";
        $orderid = 17819;
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

            $orderid=  $this->t_orderid_orderno_list->get_orderid($orderNo);
            $this->t_order_info->field_update_list($orderid,[
                "order_status" =>1,
                "contract_status"=>1,
                "pay_time"       =>time(),
                "channel"        =>$channel
            ]);
            $userid = $this->t_order_info->get_userid($orderid);
            $sys_operator = $this->t_order_info->get_sys_operator($orderid);
            $nick = $this->t_student_info->get_nick($userid);
            $this->t_manager_info->send_wx_todo_msg(
                "echo",
                "合同付款通知",
                "合同付款通知",
                "学生:".$nick." 合同付款成功,支付方式".$channel_name,
                "/user_manage_new/money_contract_list?studentid=$userid");
            $this->t_manager_info->send_wx_todo_msg(
                $sys_operator,
                "合同付款通知",
                "合同付款通知",
                "学生:".$nick." 合同付款成功,支付方式".$channel_name,
                "");
            $this->t_manager_info->send_wx_todo_msg(
                "jack",
                "合同付款通知",
                "合同付款通知",
                "学生:".$nick." 合同付款成功,支付方式:".$channel_name,
                "");
            $this->t_manager_info->send_wx_todo_msg(
                "alan",
                "合同付款通知",
                "合同付款通知",
                "学生:".$nick." 合同付款成功,支付方式:".$channel_name,
                "");



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


    //wx - teacher bind
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
            if($teacher_info['subject']==11){ // 临时测试!
                return $this->output_err("此账号无法绑定！");
            }
            if(!isset($teacher_info['teacherid'])  ){
                $teacher_info['phone']            = $phone;
                $teacher_info['acc']              = "wx_reference";
                $teacher_info['teacher_type']     = 32;
                $teacher_info['teacher_ref_type'] = 32;
                $teacher_info['send_sms_flag']    = 0;
                $teacher_info['wx_use_flag']      = 0;

                $data = $this->add_teacher_common($teacher_info);
                if(!$data || !is_int($data)){
                    if($data===false){
                        $data="生成失败！请退出重试！";
                    }
                    return $this->output_err($data);
                }
                $teacher_info['teacherid'] = $data;
            }
            \App\Helper\Utils::logger("wx_openid189:$wx_openid,phone:$phone,teacherid:".$teacher_info['teacherid']);

            if($wx_openid){
                $teacherid = $this->t_teacher_info->get_teacherid_by_openid($wx_openid);
                if($teacherid>0 && $teacherid!=$teacher_info['teacherid']){
                    $ret = $this->t_teacher_info->field_update_list($teacherid,[
                        "wx_openid" => null,
                    ]);
                }
                $re = $this->t_teacher_info->field_update_list($teacher_info['teacherid'], [
                    "wx_openid" => $wx_openid
                ]);
            }else{
                return $this->output_err("微信绑定失败!请重新登录后绑定!");
            }

            session(["login_userid"=>$teacher_info['teacherid']]);
            session(["login_user_role"=>2]);
            session(["teacher_wx_use_flag"=>$teacher_info['wx_use_flag']]);
            return $this->output_succ(["wx_use_flag"=>$teacher_info['wx_use_flag']]);
        }else{
            return $this->output_err ("验证码不对");
        }
    }

    //wx- teacher
    public function send_phone_code () { //999
        \App\Helper\Utils::logger("send_er_wei");

        return (new common_ex )->send_phone_code();
    }

    public function get_teacher_hornor_list(){ //1016
        $url = "http://admin.yb1v1.com/teacher_money/get_teacher_lesson_total_list";
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

        $qiniu     = \App\Helper\Config::get_config("qiniu");

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
            \App\Helper\Utils::logger("start...");
            list($ret, $err) = $uploadMgr->putFile($token, $qiniu_file_name,  $realPath );
            \App\Helper\Utils::logger( "error:" . json_encode($err) );

            \App\Helper\Utils::logger("end ...");
            return $this->output_succ(["file_name" => $qiniu_file_name]);
        }
        return $this->output_err("上传失败");
    }

}
