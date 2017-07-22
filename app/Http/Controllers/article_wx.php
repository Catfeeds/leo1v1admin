<?php
namespace App\Http\Controllers;
use LaneWeChat\Core\ResponsePassive;
use Teacher\Core\WeChatOAuth;
// use LaneWeChat\Core\WeChatOAuth;
use Teacher\Core\UserManage;
use Teacher\Core\Media;

include(app_path("Libs/LaneWeChat/lanewechat.php"));
include(app_path("Wx/Teacher/lanewechat_teacher.php"));


class  article_wx extends Controller
{
    var $check_login_flag =false;
    public function index() {

    }

    public function parent_side_manual () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function student_side_manual () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function activity () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_manual () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_software () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_ipad () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_pc () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_whiteboard () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_video_math () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_video_english () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_video_chinese () {
        return $this->Pageview(__METHOD__,'' );
    }


    public function leo_teacher_video_physics () {
        return $this->Pageview(__METHOD__,'' );
    }



    public function leo_teacher_deal_question () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_new_teacher_deal_question () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_student_question () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_consult_question () {
        return $this->Pageview(__METHOD__,'' );
    }


    public function leo_teacher_wages () {
        return $this->Pageview(__METHOD__,'' );
    }



    public function leo_teacher_train() {
        return $this->Pageview(__METHOD__,'' );
    }


    public function leo_teacher_lesson_before() {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_lessoning () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_lesson_after () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_lesson_equipment () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_lesson_universal () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_lesson_software_download () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_about_me () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function student_side_manual_ipad () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function student_side_manual_pc () {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_interview() {
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_agent() { // 招师代理
        return $this->Pageview(__METHOD__,'' );
    }

    public function leo_teacher_recruit() { // 招师大奖
        return $this->Pageview(__METHOD__,'' );
    }

    public function yxyx_leo_about() { // 优学优享-理优简介
        return $this->Pageview(__METHOD__,'' );
    }







    /**
    *
    * 获取微信用户的openid和用户信息
    *
    */
    public function get_openid () {

        // exit;
        $openid = $_GET['openid'];

        // var_dump($openid);
        //第二步，获取access_token网页版

        // $openId = WeChatOAuth::getAccessTokenAndOpenId($code);

        //第三步，获取用户信息

        // $userInfo = UserManage::getUserInfo($openId['openid']);
        $userInfo = UserManage::getUserInfo($openid);

        // dd($userInfo);
        $url = "http://admin.yb1v1.com/common/get_teacher_qr?wx_openid=".$openid;
        $img_url = $this->get_img_url($url);
        $type = 'image';

        $num = rand();
        $img_Long = file_get_contents($img_url);

        file_put_contents( public_path().'/wximg/'.$num.'.png',$img_Long);

        $img_url = public_path().'/wximg/'.$num.'.png';
        $img_url = realpath($img_url);

        $mediaId = Media::upload($img_url, $type);
        unlink($img_url);

        exit();
        // var_dump($mediaId);
        // var_dump($img_url);

        // return $mediaId['media_id'];


        $wechat = new \App\Wx\Teacher\wechat (WECHAT_TOKEN_TEC, TRUE);
        $wechat->mediaId = $mediaId['media_id'];

        return $mediaId['media_id'];

        // $ret = $wechat->run();
        // if (is_bool($ret)) {
        //     return "";
        // }else{
        //     return $ret;
        // }

        // $ret =  $wechat->send_img($mediaId);
        // return $ret;

    }


    public function get_img_url($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}