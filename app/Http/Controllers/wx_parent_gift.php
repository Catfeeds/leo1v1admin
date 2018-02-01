<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;
use Illuminate\Support\Facades\Input;
use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;



class wx_parent_gift extends Controller
{

    public function __construct(){
        $this->appid = "wx636f1058abca1bc1"; // 理由教育在线学习
        $this->secret = "756ca8483d61fa9582d9cdedf202e73e"; // 理由教育在线学习
    }


    public function get_parentid(){
        $parentid = $this->get_in_int_val("_parentid")?$this->get_in_int_val("_parentid"):session("parentid");
        if(!$parentid){
            $parentid = -1;
        }
        return $parentid;
    }



    private $appid ;
    private $secret ;
    public function get_gift_for_parent () {
        $p_appid     = \App\Helper\Config::get_wx_appid();
        $p_appsecret = \App\Helper\Config::get_wx_appsecret();

        $wx= new \App\Helper\Wx($p_appid,$p_appsecret);
        $redirect_url=urlencode("http://wx-parent.leo1v1.com/wx_parent_gift/check_parent_info" );
        $wx->goto_wx_login( $redirect_url );
    }

    public function check_parent_info(){
        $p_appid     = \App\Helper\Config::get_wx_appid();
        $p_appsecret = \App\Helper\Config::get_wx_appsecret();

        $code = $this->get_in_str_val('code');
        $wx   = new \App\Helper\Wx($p_appid,$p_appsecret);
        $token_info = $wx->get_token_from_code($code);
        $openid   = @$token_info["openid"];
        $token = $wx->get_wx_token($p_appid,$p_appsecret);
        $user_info = $wx->get_user_info($openid,$token);

        session(["wx_parent_openid" => $openid ] );

        $subscribe = @$user_info['subscribe'];
        $parentid = $this->t_parent_info->get_parentid_by_wx_openid($openid);
        $type = 0;

        if($parentid>0){
            $type = 1;
            session(["parentid" => $parentid ] );
        }else{
            session(["parentid" => "-1" ] );
        }


        header("location: http://wx-parent-web.leo1v1.com/m11/m11.html?type=".$type);
        return ;

        // if($is_parent_flag){
        //     // header("location: http://wx-parent-web.leo1v1.com/anniversary_day/index.html?parentid=".$is_parent_flag);//周年庆活动页面
        //     // header("Location: ");//双11活动页面
        //     return ;
        // }else{
        //     header("location: http://wx-parent-web.leo1v1.com/binding?goto_url=/index&type=1&openid=$openid");
        //     return ;
        // }
    }





    public function upload_excel(){
        $file = Input::file('file');
        // dd($file);
        if ($file->isValid()) {
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
            set_time_limit(90);
            ini_set("memory_limit", "1024M");
            $realPath = $file->getRealPath();
            $objPHPExcel = $objReader->load($realPath);
            $objPHPExcel->setActiveSheetIndex(0);
            $arr=$objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            foreach($arr as $item){
                if($item["A"] && $item["B"]){
                    $this->t_parent_luck_draw_in_wx->row_insert([
                        "price"  =>$item["A"],
                        "prize_code"  =>$item["B"],
                    ]);
                }
            }
            return outputjson_success();
        } else {
            return outputjson_ret(false);
        }
    }



    public function do_prize_draw(){
        // 获取 每个家长的等级
        $userid = $this->get_in_int_val('parentid');

        $parent_lesson_total = $this->t_parent_child->get_student_lesson_total_by_parentid($userid);
        $parent_num = $parent_lesson_total/100;

        // 8月6日 ~ 8日 // 每天的奖品数量多一点
        $six_date = strtotime('2017-08-06');
        $eig_date = strtotime('2017-08-09');
        $now = time();
        $start_time = strtotime(date("Y-m-d",time()));
        $end_time   = $start_time+86400;


        $is_need_change_limit_num = 0;
        if($now>$start_time && $now < $end_time){
            $is_need_change_limit_num = 1;
        }

        $price = 0;
        $limit_gift = 0;
        if($parent_num>30 && $parent_num<=90){
            $price = 20;

            if($is_need_change_limit_num){
                $limit_gift = 80;
            }else{
                $limit_gift = 60;
            }
        }elseif($parent_num>90 && $parent_num<=180){
            $price = 80;

            if($is_need_change_limit_num){
                $limit_gift = 65;
            }else{
                $limit_gift = 50;
            }

        }elseif($parent_num>180 && $parent_num<=250){
            $price = 120;

            if($is_need_change_limit_num){
                $limit_gift = 50;
            }else{
                $limit_gift = 35;
            }

        }elseif($parent_num>250 && $parent_num<=300){
            $price = 150;

            if($is_need_change_limit_num){
                $limit_gift = 35;
            }else{
                $limit_gift = 20;
            }

        }elseif($parent_num>300 && $parent_num<=350){
            $price = 200;

            if($is_need_change_limit_num){
                $limit_gift = 35;
            }else{
                $limit_gift = 20;
            }

        }elseif($parent_num>350 && $parent_num<=400){
            $price = 300;

            if($is_need_change_limit_num){
                $limit_gift = 18;
            }else{
                $limit_gift = 10;
            }

        }elseif($parent_num>400 && $parent_num<=450){
            $price = 400;

            if($is_need_change_limit_num){
                $limit_gift = 18;
            }else{
                $limit_gift = 10;
            }

        }elseif($parent_num>450){
            $price = 500;

            if($is_need_change_limit_num){
                $limit_gift = 14;
            }else{
                $limit_gift = 7;
            }
        }

        // 查看是否已抽奖
        $gift_info = $this->t_parent_luck_draw_in_wx->get_gift_info_by_userid($userid);

        if($gift_info['userid']){
            return $this->output_succ($gift_info);
        }else{
            // 首次参加抽奖 [将抽奖结果放入到数据表中]
            // $this->t_parent_luck_draw_in_wx->start_transaction();
            if($price >0){
                $all_gift_list  = $this->t_parent_luck_draw_in_wx->get_all_gift_list($price);
                $today_gift_num = $this->t_parent_luck_draw_in_wx->ger_today_gift_num($start_time,$end_time,$price);
                if($today_gift_num >=$limit_gift){
                    $prize_code = '';
                }else{
                    $rock_gift_num = count($all_gift_list);
                    $index = mt_rand(0,$rock_gift_num-1);
                    $prize_code = $all_gift_list[$index]['prize_code'];
                }
            }else{
                $prize_code = '';
            }

            if($prize_code){
                $id = $this->t_parent_luck_draw_in_wx->get_id_by_code($prize_code);
                if($id){
                    $ret_add = $this->t_parent_luck_draw_in_wx->field_update_list($id,[
                        "prize_code"   => $prize_code,
                        "userid"       => $userid,
                        "add_time"     => time(),
                        "receive_time" => time(),
                        "price"        => $price
                    ]);

                    if($ret_add){
                        $content = "恭喜您成功参与理优周年庆抽奖活动，获得奖学金现金券！您的使用码 $prize_code";
                        $value  = '';
                        $this->send_prize_info($userid,$content,$value);
                    }
                }
            }else{
                $receive_time = '';
                $ret_add = $this->t_parent_luck_draw_in_wx->row_insert([
                    "prize_code" => '',
                    "userid"     => $userid,
                    "add_time"   => time(),
                    "receive_time" =>$receive_time,
                    "price"      => $price
                ]);

                $gift_info = ['prize_code'=>'','userid'=>$userid,'price'=>$price];
                return $this->output_succ($gift_info);
            }

            // $this->t_parent_luck_draw_in_wx->commit();
            // if($ret_add){
            $gift_info = $this->t_parent_luck_draw_in_wx->get_gift_info_by_userid($userid);
            return $this->output_succ($gift_info);
            // }
        }
    }

    public function send_prize_info($parentid,$content,$value){
        $acc = $this->get_account();

        $userid = $this->t_parent_child->get_userid_by_parentid($parentid);
        $this->t_baidu_msg->start_transaction();
        $ret = $this->t_baidu_msg->baidu_push_msg($userid,$content,$value,1007,0);
        if(!$ret){
            $this->t_baidu_msg->rollback();
            return $this->output_err("添加失败！请重试！");
        }
        if($parentid>0){
            $ret = $this->t_baidu_msg->baidu_push_msg($parentid,$content,$value,4014,0);
            if(!$ret){
                $this->t_baidu_msg->rollback();
                return $this->output_err("添加失败！请重试！");
            }
            $wx_openid = $this->t_parent_info->get_wx_openid($parentid);
            if($wx_openid!=""){
                $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
                $data= [
                    "first"     => "您有一条未处理的\"理优周年庆\"活动奖励，请及时处理",
                    "keyword1"  => "理优周年庆活动",
                    "keyword2"  => "奖学金现金劵使用码",
                    "keyword3"  => date("Y-m-d"),
                    "remark"    => $content,
                ];
                \App\Helper\Utils::send_wx_to_parent($wx_openid,$template_id,$data);
            }
        }
        $this->t_baidu_msg->commit();
        return $this->output_succ();
    }



    /**

     *@ 市场部圣诞节活动
     *@
     *@
     *@
     **/



    /**
     * @ 微信图文[理优一段故事]
     * @
     **/
    public function christmasHistory(){ //微信推文 理优历史
        $p_appid     = \App\Helper\Config::get_wx_appid();
        $p_appsecret = \App\Helper\Config::get_wx_appsecret();
        $pid1 = $this->get_in_int_val('pid1');

        $wx= new \App\Helper\Wx($p_appid,$p_appsecret);
        $redirect_url=urlencode("http://wx-parent.leo1v1.com/wx_parent_gift/checkParInfo?pid1=".$pid1 );
        $wx->goto_wx_login( $redirect_url );
    }

    public function checkParInfo(){
        $p_appid     = \App\Helper\Config::get_wx_appid();
        $p_appsecret = \App\Helper\Config::get_wx_appsecret();
        $code = $this->get_in_str_val('code');
        $wx   = new \App\Helper\Wx($p_appid,$p_appsecret);
        $user_info = $wx->get_token_from_code($code);
        $openid    = @$user_info["openid"];
        $pid1 = $this->get_in_int_val('pid1');
        $parentid = $this->t_parent_info->get_parentid_by_wx_openid($openid);

        if($pid1 == 0 && $parentid == 0) { // 上级与本人都是未注册用户 则跳转至报名页
            header("Location: http://www.leo1v1.com/market/index.html?%E6%9C%8D%E5%8A%A1%E5%8F%B7%E2%80%94%E8%8F%9C%E5%8D%95%E6%A0%8F=");
            return;
        }elseif($pid1 == 0 && $parentid != 0){
            $pid1=$parentid;
        }

        header("Location: http://wx-parent-web.leo1v1.com/chrismas_day/index.html?pid1=".$pid1."&pid2=".$parentid);//链接待定
        return ;
    }

    /**
     * @ 点击抽奖
     **/
    public function dealLottery(){
        $parentid = $this->get_in_int_val("parentid");
        $orderid  = $this->t_order_info->getOrderByParentid($parentid);

        $prize_type = $this->t_activity_christmas->getPrizeType($parentid);
        if($prize_type==0){
            if($orderid>0){
                $rand = mt_rand(0,100);
                $prize_type = 0;
                if($rand<=60){
                    $prize_type = 1;
                }elseif(60<$rand && $rand<=90){
                    $prize_type = 2;
                }elseif(90<$rand && $rand<=100){
                    $prize_type = 3;
                }
            }else{
                $prize_type = 4;
            }

            $this->t_activity_christmas->row_insert([
                "christmas_price_type" => $prize_type,
                "parentid" => $parentid,
                "win_time" => time(),
            ]);
        }

        switch ($prize_type)
        {
        case 1:
            $prize_str = "抽中10元折扣券一张";
            break;
        case 2:
            $prize_str = "抽中20元折扣券一张";
            break;
        case 3:
            $prize_str = "抽中50元折扣券一张";
            break;
        case 4:
            $prize_str = "抽中价值200元的试听课一节";
            break;
        }

        $prize_result = [
            "prize_str"  => $prize_str,
            "prize_type" => $prize_type

        ];

        return $this->output_succ($prize_result);
    }

    /**
    *@ 检查是否是在读学员
    */
    public function checkIsRead(){
        $parentid = $this->get_in_int_val("pid");
        $orderid  = $this->t_order_info->getOrderByParentid($parentid);
        if($orderid>0){$orderid=1;}
        return $this->output_succ(["is_read"=>$orderid]);
    }


    /**
     * @ 展示当前个人信息
     */
    public function getAdminInfo(){ //
        $parentid = $this->get_in_int_val("parentid");
        $orderid  = $this->t_order_info->getOrderByParentid($parentid);



        return $this->output_succ();
    }

    public function getUserId(){

    }





    /**
     *市场部赠送图书活动
     *
     **/

    public function set_identity_for_book(){
        $_SESSION['check_flag']=1;
        return $this->output_succ(['share_num'=>1]);
    }

    public function check_identity_for_book(){
        $share_num = @$_SESSION['check_flag'];
        if($share_num>0){
            return $this->output_succ(['share_num'=>$share_num]);
        }else{
            return $this->output_succ(['share_num'=>0]);
        }
    }

    public function del_session(){
        $_SESSION['check_flag']=0;
        return $_SESSION['check_flag'];
    }


    /***
     *双11活动 理优在线
     **/

    public function get_prize_list(){ // 获取奖品列表
        $parentid   = $this->get_parentid();
        $prize_list = $this->t_ruffian_activity->get_prize_list($parentid);
        $has_buy    = $this->t_order_info->check_is_buy($parentid);

        foreach($prize_list as &$item){
            if($has_buy){
                $item['stu_type'] = 2;
            }else{
                $item['stu_type'] = 1;
            }

            if($item['prize_type'] != 8  ){
                $item['str'] = "购课满十课时即可使用，仅限".$item['phone']."使用。";
            }else{
                $item['str'] = "";
            }
            $item['prize_type_str'] = E\Eruffian_prize_type::get_desc($item['prize_type']);
        }
        return $this->output_succ(["data"=>$prize_list]);
    }

    public function get_draw_num($parentid){ //
        if($parentid<=0){
            $left_num = 0;
            return $left_num;
        }

        // 检查是否分享朋友圈 11.7-11.15[包含14号]
        $start_time = strtotime('2017-11-7'); // 2017-11-07 分享朋友圈有效时间
        $end_time   = strtotime('2017-11-15'); // 分享朋友圈有效时间
        $has_share  = $this->t_ruffian_share->get_share_num($parentid,$start_time, $end_time);

        // 检查是否在读学生
        $is_reading = $this->t_student_info->check_is_reading($parentid);

        //检查是否新签
        $order_start = strtotime('2017-11-11');
        $order_end   = strtotime('2017-11-15');
        $is_new_order = $this->t_order_info->check_is_new($parentid, $order_start, $order_end);

        $draw_num = 0; //抽奖次数

        if($has_share){ $draw_num++;}

        if($is_reading){$draw_num++;}

        if($is_new_order){$draw_num++;}

        $draw_num = ($draw_num>=2)?2:$draw_num; // 获取的最大次数

        $consume_num = $this->t_ruffian_activity->get_has_done($parentid); //已消耗抽奖次数

        $left_num = $draw_num-$consume_num;

        $left_num = $left_num<0?0:$left_num;

        return $left_num;
    }

    public function get_luck_parent_info(){ // 获取家长抽奖信息
        $parentid   = $this->get_parentid();
        $start_time = strtotime('2017-11-7'); // 2017-11-7 分享朋友圈有效时间
        $end_time   = strtotime('2017-11-15'); // 分享朋友圈有效时间

        if($parentid>0){
            $left_num = $this->get_draw_num($parentid);
            $has_share  = $this->t_ruffian_share->get_share_num($parentid,$start_time, $end_time);
        }else{
            $has_share = 0;
            $left_num  = 0;
        }
        return $this->output_succ(['left'=>$left_num,"is_share"=>$has_share]);
    }

    public function update_share_status(){ // 分享朋友圈
        $parentid = $this->get_parentid();
        $this->t_ruffian_share->delete_row_by_pid($parentid);
        $this->t_ruffian_share->row_insert([
            "is_share_flag" => 1,
            "share_time"    => time(),
            "parentid"      => $parentid
        ]);
        return $this->output_succ();
    }

    public function ruffian_activity(){ // 双11活动
        $parentid = $this->get_parentid();
        $has_buy  = $this->t_order_info->check_is_buy($parentid);
        $reg_time = $this->t_user_info->get_reg_time($parentid);
        $check_time = strtotime('2017-11-7');

        return $this->output_err("活动已结束,谢谢参加!");

        //检查是否可以抽奖
        $left_num = $this->get_draw_num($parentid);
        if($left_num <= 0){ return $this->output_err("您的抽奖次数已用完!"); }

        if($check_time>$reg_time && $has_buy>0){
            $stu_type = 2; // 老用户
        }else{
            $stu_type = 1; // 新用户
        }

        $prize_type = $this->get_win_rate($stu_type,$parentid);

        $this->t_ruffian_activity->start_transaction();

        if($prize_type>0){  //检测奖品是否抽完
            $has_prize_id = $this->t_ruffian_activity->check_has_left($prize_type,$stu_type);
        }else{
            $has_prize_id = '';
        }

        if(!$has_prize_id){
            if($stu_type == 1){
                $is_test = $this->t_lesson_info_b3->get_lessonid_by_pid($parentid);
                $is_has_test = $this->t_ruffian_activity->check_is_has_test($parentid);
                if($is_test>0 || $is_has_test){
                    $prize_type=2;
                }else{
                    $prize_type=8;
                }
            }elseif($stu_type ==2){
                $prize_type=2;
            }

            $this->t_ruffian_activity->row_insert([
                "parentid"   => $parentid,
                "prize_type" => $prize_type,
                "prize_time" => time(),
                "stu_type"   => $stu_type,
                "validity_time" => strtotime(date('Y-m-d'))
            ]);
        }else{
            if($stu_type == 1){
                $is_test = $this->t_lesson_info_b3->get_lessonid_by_pid($parentid);
                $is_has_test = $this->t_ruffian_activity->check_is_has_test($parentid);
                if($prize_type == 1 && $is_test ==0){ // 未试听过的人不能获得书包
                    $prize_type = 8;
                }
                if($prize_type == 8 && $is_has_test){
                    $prize_type = 2;
                }
            }

            $list = [2,8];
            if(in_array($prize_type,$list)){
                $this->t_ruffian_activity->row_insert([
                    "parentid"   => $parentid,
                    "prize_type" => $prize_type,
                    "prize_time" => time(),
                    "stu_type"   => $stu_type,
                    "validity_time" => strtotime(date('Y-m-d'))
                ]);
            }else{
                $this->t_ruffian_activity->field_update_list($has_prize_id,[
                    "parentid"   => $parentid,
                    "prize_time" => time(),
                ]);
            }
        }

        $this->t_ruffian_activity->commit();

        // 微信通知
        $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";//待处理通知
        $data_msg = [
            "first"     => "您好，您的双十一奖品券已存放进您的账户",
            "keyword1"  => "获奖详情",
            "keyword2"  => "点击 个人中心→奖品区 即可兑换奖券",
            "keyword3"  => date('Y-m-d H:i:s'),
        ];

        $url = "http://wx-parent-web.leo1v1.com/prizes";
        $wx=new \App\Helper\Wx();
        $p_openid = $this->t_parent_info->get_wx_openid($parentid);
        $wx->send_template_msg($p_openid,$template_id,$data_msg ,$url);


        // 检查是否分享朋友圈 11.7-11.15[包含14号]
        $start_time = strtotime('2017-11-7');
        $end_time   = strtotime('2017-11-15');
        $has_share  = $this->t_ruffian_share->get_share_num($parentid,$start_time, $end_time);

        //检查是否新签
        $order_start = strtotime('2017-11-11');
        $order_end   = strtotime('2017-11-15');
        $is_new_order = $this->t_order_info->check_is_new($parentid, $order_start, $order_end);

        $active_num = $this->t_ruffian_activity->get_active_num($parentid);

        if($active_num == 1){
            $data_info = [];
            if($stu_type == 1 && $is_new_order<=0){ // 新用户
                $data_info = [
                    "first"     => "您好，购课即可再次获得翻牌机会",
                    "keyword1"  => "购课赢翻牌机会",
                    "keyword2"  => "2017.11.11-2017.11.13期间，购课即可再次获得双十一翻牌机会",
                    "keyword3"  => date('Y-m-d H:i:s'),
                ];
            }elseif($stu_type == 2 && $has_share <= 0){ //老用户
                $data_info = [
                    "first"     => "您好，分享即可再次获得翻牌机会",
                    "keyword1"  => "分享赢翻牌机会",
                    "keyword2"  => "点击双十一活动页面右上角，分享到微信朋友圈即可获得翻牌机会",
                    "keyword3"  => date('Y-m-d H:i:s'),
                ];
            }

            if(!empty($data_info)){
                $wx->send_template_msg($p_openid,$template_id,$data_info ,"");
            }
        }

        return $this->output_succ(['prize'=>$prize_type]);
    }



    public function get_win_rate($stu_type,$parentid){ // 获取中奖概率
        $rate   = mt_rand(1,10000);
        $today  = time();
        $eleven = strtotime('2017-11-11');
        $prize_type = 0; // 奖品类型

        /**
           array(1,"","书包" ),
           array(2,"","10元折扣券" ),
           array(3,"","50元折扣券" ),
           array(4,"","100元折扣券" ),
           array(5,"","300元折扣券" ),
           array(6,"","500元折扣券" ),
           array(7,"","免费3次正式课" ),
           array(8,"","试听课" ),
        **/

        if($stu_type == 1){ // 新用户
            if($today < $eleven){
                if($rate>1000 && $rate<=2000){ // 书包 10
                    $prize_type=1;
                }elseif($rate>2000 && $rate<=3000){ // 50元折扣券  10
                    $prize_type=3;
                }elseif($rate>3000 && $rate<=3375){ // 100元折扣券 3.75
                    $prize_type=4;
                }elseif($rate>4000 && $rate<=4125){ // 300元折扣券 1.25
                    $prize_type=5;
                }elseif($rate>5000 && $rate<=5013){ // 3次免费课程 0.13
                    $prize_type=7;
                }
            }else{
                if($rate>1000 && $rate<=2500){ // 书包 12.5
                    $prize_type=1;
                }elseif($rate>3000 && $rate<=4250){ // 50元折扣券  12.5
                    $prize_type=3;
                }elseif($rate>100 && $rate<=725){ // 100元折扣券 6.25
                    $prize_type=4;
                }elseif($rate>5000 && $rate<=5250){ // 300元折扣券 2.5
                    $prize_type=5;
                }elseif($rate>6000 && $rate<=6013){ // 500元折扣券 0.13
                    $prize_type=6;
                }elseif($rate>7000 && $rate<=7025){ // 3次免费课程 0.25
                    $prize_type=7;
                }
            }
        }elseif($stu_type==2){ //老用户
            if($today < $eleven){
                if($rate>100 && $rate<=150){ // 书包 0.5
                    $prize_type=1;
                }elseif($rate>500 && $rate<=1000){ // 50元折扣券  5
                    $prize_type=3;
                }elseif($rate>1000 && $rate<=1100){ // 100元折扣券 1
                    $prize_type=4;
                }elseif($rate>1500 && $rate<=1530){ // 300元折扣券 0.3
                    $prize_type=5;
                }elseif($rate>5000 && $rate<=5010){ // 3次免费课程 0.1
                    $prize_type=7;
                }
            }else{
                if($rate>100 && $rate<=200){ // 书包 10
                    $prize_type=1;
                }elseif($rate>500 && $rate<=1000){ // 50元折扣券  5
                    $prize_type=3;
                }elseif($rate>1000 && $rate<=1100){ // 100元折扣券 1
                    $prize_type=4;
                }elseif($rate>5000 && $rate<=5030){ // 300元折扣券 0.3
                    $prize_type=5;
                }elseif($rate>6000 && $rate<=6010){ // 500元折扣券 0.10
                    $prize_type=6;
                }elseif($rate>7000 && $rate<=7020){ // 3次免费课程 0.2
                    $prize_type=7;
                }
            }
        }
        return $prize_type;
    }


    /**
       双11活动 理优在线
    **/




















    // 双11优学优享活动
    public function get_member_info_list(){ // 获取学员信息
        $openid = session('yxyx_openid');
        $start_time = strtotime('2017-11-3'); // 2017-11-03
        $agent_info = $this->t_agent->get_agent_id_by_openid($openid);

        if($agent_info){
            $parentid    = $agent_info['userid'];
            if(!$parentid){
                $parentid = -1;
            }
            $p_agent_id  = $agent_info['id'];
            $prize_num   = $this->t_luck_draw_yxyx_for_ruffian->get_prize_num($parentid);

            $invite_info = $this->t_agent->get_invite_num($start_time, $p_agent_id);
            $ret_info['invite_num'] = count($invite_info);
            $ret_info['light_num']  = floor(($ret_info['invite_num'] - 20*$prize_num)/5)>0?floor(($ret_info['invite_num'] - 20*$prize_num)/5):0;

            $ret_info['light_num'] =  $ret_info['light_num']>=4?4:$ret_info['light_num'];
            $ret_info['phone'] = $agent_info['phone'];
        }else{
            $ret_info = [
                "invite_num" => 0,
                "light_num"  => 0,
                "phone"      => 0
            ];
        }
        return $this->output_succ(["data"=>$ret_info]);
    }


    public function do_luck_draw_yxyx(){ // 抽奖

        return $this->output_err('我们的活动已结束啦!');

        $openid = session('yxyx_openid');
        $agent_info = $this->t_agent->get_agent_id_by_openid($openid);
        $userid = $agent_info['userid'];
        $today  = strtotime(date('Y-m-d'));

        // 获取已中奖的总金额
        $has_get_money = $this->t_luck_draw_yxyx_for_ruffian->get_total_money($today);

        // 检查是否可以抽奖
        $p_agent_id  = $agent_info['id'];
        $prize_num   = $this->t_luck_draw_yxyx_for_ruffian->get_prize_num($userid);
        $start_time  = strtotime('2017-11-3'); // 2017-11-03
        $invite_info = $this->t_agent->get_invite_num($start_time, $p_agent_id);
        $invite_num  = count($invite_info);
        $light_num   = floor(($invite_num - 20*$prize_num)/5)>0?floor(($invite_num - 20*$prize_num)/5):0;

        if($light_num<4){
            return $this->output_err("您未集齐四张卡片,请继续加油!");
        }

        $rate  = mt_rand(1,100);
        $prize = 0;
        /**
           金额分别为
           每日金额为1000元预算
           分为31.11—41.11—51.11—61.11—71.11—91.11—111.1挡位
           概率25%—25%—10%—10%—10%—10%—10%
        */

        if($rate>75 && $rate<=100){ //中奖金额 31.11  [25]
            $prize = 3111;
        }elseif($rate>50 && $rate<=75){ // 中奖金额 41.11 [25]
            $prize = 4111;
        }elseif($rate>40 && $rate<=50){ // 中奖金额 51.11  [40]
            $prize = 5111;
        }elseif($rate>30 && $rate<=40){ // 中奖金额 61.11 [20]
            $prize = 6111;
        }elseif($rate>20 && $rate<=30){ // 中奖金额 71.11 [10]
            $prize = 7111;
        }elseif($rate>10 && $rate<=20){ // 中奖金额 91.11 [10]
            $prize = 9111;
        }elseif($rate>0 && $rate<=10){ // 中奖金额 111.11  [10]
            $prize = 11111;
        }

        if($has_get_money >=1000){ // 每日金额1000元
            return $this->output_err('今日红包红包已被抢光，请明天再接再厉！');
        }
        // 中奖金额存入数据库
        $this->t_agent->update_money($userid, $prize);

        $this->t_luck_draw_yxyx_for_ruffian->row_insert([
            "luck_draw_adminid" => $userid,
            "luck_draw_time" => time(),
            "deposit_time" => 1,
            "is_deposit" => 1,
            "money"  => $prize,
            "agent_id" => $agent_info['id']
        ]);

        //发送微信推送
        /**
           {{first.DATA}}
           活动主题：{{keyword1.DATA}}
           活动时间：{{keyword2.DATA}}
           活动结果：{{keyword3.DATA}}
           {{remark.DATA}}
        **/

        $appid     = \App\Helper\Config::get_yxyx_wx_appid();
        $appsecret = \App\Helper\Config::get_yxyx_wx_appsecret();
        $wx = new \App\Helper\Wx($appid, $appsecret);

        $template_id = "-jlgaNShu8zuil5ST1Qo5hY6RzaNyujwZ0fAnh2Te40";//活动结束提醒
        $data_msg = [
            "first"     => "您好，此次活动已经结束，你已经成功参与",
            "keyword1"  => "双十一活动",
            "keyword2"  => "2017.11.03 - 2017.11.10",
            "keyword3"  => "活动结果：您获得了现金红包".($prize/100)."元，进入账号管理-个人中心-我的收入-实际收入即可查看",
            "remark"    => "感谢您的参与",
        ];
        $url = "http://wx-yxyx.leo1v1.com/wx_yxyx_web/index";
        $wx->send_template_msg($openid,$template_id,$data_msg ,$url);
        $prize = $prize/100;
        return $this->output_succ(["money"=>$prize]);
    }




    /**
     * @ 市场部 日常分享活动
     * @ 获取客户的openid
     * @ web_page_id=$web_page_id&from_adminid=$adminid"
     */

    public function marketing_department_activity () {
        $p_appid     = \App\Helper\Config::get_wx_appid();
        $p_appsecret = \App\Helper\Config::get_wx_appsecret();
        $type = $this->get_in_int_val('type');
        $web_page_id = $this->get_in_int_val('web_page_id');
        $from_adminid = $this->get_in_int_val('from_adminid');

        $wx= new \App\Helper\Wx($p_appid,$p_appsecret);
        $redirect_url=urlencode("http://wx-parent.leo1v1.com/wx_parent_gift/rewrite_url?type=$type&web_page_id=$web_page_id&from_adminid=$from_adminid");
        $wx->goto_wx_login( $redirect_url );
    }

    public function rewrite_url(){
        $p_appid     = \App\Helper\Config::get_wx_appid();
        $p_appsecret = \App\Helper\Config::get_wx_appsecret();

        $code = $this->get_in_str_val('code');
        $wx   = new \App\Helper\Wx($p_appid,$p_appsecret);
        $token_info = $wx->get_token_from_code($code);
        $openid     = @$token_info["openid"];
        $token      = $wx->get_wx_token($p_appid,$p_appsecret);
        $user_info  = $wx->get_user_info($openid,$token);

        $type = $this->get_in_int_val("type");
        $is_share = $this->t_market_department_activity->check_flag($openid,$type);

        $web_page_id  = $this->get_in_int_val('web_page_id');
        $from_adminid = $this->get_in_int_val('from_adminid');

        if($is_share){
            if($type == 1){
                header("location: http://wx-parent-web.leo1v1.com/wx-activity/shareSuc.html?openid=".$openid."&type=".$type."&web_page_id=$web_page_id&from_adminid=$from_adminid");
            }elseif($type == 2){
                header("location: http://wx-parent-web.leo1v1.com/wx-activity-book/shareSuc.html?openid=".$openid."&type=".$type."&web_page_id=$web_page_id&from_adminid=$from_adminid");
            }
        }else{
            if($type == 1){
                header("location: http://wx-parent-web.leo1v1.com/wx-activity/index.html?openid=".$openid."&type=".$type."&web_page_id=$web_page_id&from_adminid=$from_adminid");
            }elseif($type == 2){
                header("location: http://wx-parent-web.leo1v1.com/wx-activity-book/index.html?openid=".$openid."&type=".$type."&web_page_id=$web_page_id&from_adminid=$from_adminid");
            }
        }
        return ;
    }

    /**
     * @ 活动分享接口
     * @ 确保 数据库中一次活动只保存一条数据
     **/
    public function record_share(){ // 分享接口
        $openid = $this->get_in_str_val('openid');
        $type   = $this->get_in_int_val('type');

        $del_share = $this->t_market_department_activity->del_row($openid,$type);
        $this->t_market_department_activity->row_insert([
            "openid"  => $openid,
            "type"    => $type, //活动类型
            "share_time"  => time(),
            "create_time" => time()
        ]);

        return $this->output_succ();
    }

    /**
     * @ 市场部常规活动 获取图片链接
     **/
    public function getImgUrlInfo(){
        $id = $this->get_in_int_val('type');
        $id = $id-100; //[区分以前已存在的活动]

        $imgUrlInfo = $this->t_activity_usually->getImgUrlInfo($id);
        $domain = config('admin')['qiniu']['public']['url'];
        if($imgUrlInfo['shareImgUrl']){
            $imgUrlInfo['shareImgUrl'] = $domain."/".$imgUrlInfo['shareImgUrl'] ; //分享页面
        }

        if($imgUrlInfo['coverImgUrl']){
            $imgUrlInfo['coverImgUrl'] = $domain."/".$imgUrlInfo['coverImgUrl'] ; //封面页面
        }

        if($imgUrlInfo['activityImgUrl']){
            $imgUrlInfo['activityImgUrl'] = $domain."/".$imgUrlInfo['activityImgUrl'] ; //活动页面
        }

        if(time()<strtotime('2018-1-30') ){
            # 原有内容
            if($imgUrlInfo['followImgUrl']){
                $imgUrlInfo['followImgUrl'] = $domain."/".$imgUrlInfo['followImgUrl'] ; //关注页面
            }
        }else{
            # 为了分散每个微信群的压力,满97人时切换另一个微信群
            if($imgUrlInfo['followImgUrl']){
                $img_arr = explode(',',$imgUrlInfo['followImgUrl']);
                $index   = floor($imgUrlInfo['add_num']/97);
                $imgNum  = count($img_arr);
                // $AdminOpenid = 'orwGAs_IqKFcTuZcU1xwuEtV3Kek';//[james]
                $AdminOpenid = 'orwGAs9rPeoW665kCsrQD_rswjv4';//[罗艳]
                $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU"; //[待办事项]

                if($imgNum<$index+1){
                    $imgUrlInfo['followImgUrl'] = ""; //关注页面
                    $data= [
                        "first"     => "活动群满员通知 活动ID:".$id,
                        "keyword1"  => "活动标题:".$imgUrlInfo['title'],
                        "keyword2"  => '\n活动ID: '.$id.' \n活动标题: '.$imgUrlInfo['title'].'\n满员页面: 关注页'.($index+1).'\n满员时间: '.date("Y年m月d日 H:i:s"),
                        "keyword3"  => date("Y-m-d"),
                    ];
                    // \App\Helper\Utils::send_wx_to_parent($AdminOpenid,$template_id,$data);
                    \App\Helper\Utils::send_wx_to_parent('orwGAs_IqKFcTuZcU1xwuEtV3Kek',$template_id,$data);
                    $follow_str = $img_arr[0];
                    $imgUrlInfo['followImgUrl'] = $domain."/".$follow_str; //关注页面

                }else{
                    $follow_str = $img_arr[$index];
                    $imgUrlInfo['followImgUrl'] = $domain."/".$follow_str; //关注页面
                }

                # 检查人数 当人数超过 97 时 通知管理员活动页已切换 [罗艳] orwGAs9rPeoW665kCsrQD_rswjv4
                $add_num = $imgUrlInfo['add_num'];
                $noticeIndex = $add_num%97;
                if($noticeIndex == 0 && $add_num>0 ){
                    $data= [
                        "first"     => "市场推广活动 关注页切换通知 活动ID:".$id,
                        "keyword1"  => "活动标题:".$imgUrlInfo['title'],
                        "keyword2"  => "当前关注页编号:".($index+1).'即将切换至下一页',
                        "keyword3"  => date("Y-m-d"),
                    ];
                    // \App\Helper\Utils::send_wx_to_parent($AdminOpenid,$template_id,$data);
                    // \App\Helper\Utils::send_wx_to_parent('orwGAs_IqKFcTuZcU1xwuEtV3Kek',$template_id,$data);
                }
            }
        }

        return $this->output_succ(['data'=>$imgUrlInfo]);
    }

    # 记录添加人的数量
    public function recordAddNum(){
        $id = $this->get_in_int_val('type');
        $id = $id-100;
        $openid = $this->get_in_str_val('openid');
        $key = $id.'_'.$openid.'_market';
        $checkData = \App\Helper\Common::redis_get($key);
        \App\Helper\Common::redis_expire($key,86400);

        if($checkData != 1){
            \App\Helper\Common::redis_set($key,1);
            $this->t_activity_usually->updateAddNum($id);
        }

        return $this->output_succ();
    }


    public function marketingActivityUsually () { // 市场部常规分享活动
        $p_appid     = \App\Helper\Config::get_wx_appid();
        $p_appsecret = \App\Helper\Config::get_wx_appsecret();
        $type = $this->get_in_int_val('type');
        $web_page_id = $this->get_in_int_val('web_page_id');
        $from_adminid = $this->get_in_int_val('from_adminid');

        $wx= new \App\Helper\Wx($p_appid,$p_appsecret);
        $redirect_url=urlencode("http://wx-parent.leo1v1.com/wx_parent_gift/rewriteUrlUsually?type=$type&web_page_id=$web_page_id&from_adminid=$from_adminid");
        $wx->goto_wx_login( $redirect_url );
    }

    public function rewriteUrlUsually(){
        $p_appid     = \App\Helper\Config::get_wx_appid();
        $p_appsecret = \App\Helper\Config::get_wx_appsecret();

        $code = $this->get_in_str_val('code');
        $wx   = new \App\Helper\Wx($p_appid,$p_appsecret);
        $token_info = $wx->get_token_from_code($code);
        $openid     = @$token_info["openid"];
        $token      = $wx->get_wx_token($p_appid,$p_appsecret);
        $user_info  = $wx->get_user_info($openid,$token);

        $type = $this->get_in_int_val("type");
        $is_share = $this->t_market_department_activity->check_flag($openid,$type);

        $web_page_id  = $this->get_in_int_val('web_page_id');
        $from_adminid = $this->get_in_int_val('from_adminid');

        $id = $type-100;
        $checkStatus = $this->t_activity_usually->get_activity_status($id);
        if($checkStatus==2){
            header("location: http://wx-parent-web.leo1v1.com/wx-parent-activity/loginFail.html");
            return ;
        }

        if($is_share){
            header("location: http://wx-parent-web.leo1v1.com/wx-parent-activity/shareSuc.html?openid=".$openid."&type=".$type."&web_page_id=$web_page_id&from_adminid=$from_adminid");
        }else{
            header("location: http://wx-parent-web.leo1v1.com/wx-parent-activity/index.html?openid=".$openid."&type=".$type."&web_page_id=$web_page_id&from_adminid=$from_adminid");
        }
        return ;
    }



    /**
     * @ 市场部分享海报
     * @ James
     * @ 2018-01-24
     */
    # 获取分享链接打开次数
    public function linkOpenNum(){
        $uid = $this->get_in_int_val('uid');
        $checkHas = $this->t_personality_poster->checkHas($uid);
        if($checkHas>0){
            $this->t_personality_poster->updateClickNum($uid);
        }else{
            $this->t_personality_poster->row_insert([
                "uid" => $uid,
                "clickNum" => 1
            ]);
        }
        return $this->output_succ();
    }


}
