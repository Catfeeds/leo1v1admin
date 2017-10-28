<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;
use Illuminate\Support\Facades\Input;


class wx_parent_gift extends Controller
{

    public function __construct(){
        $this->appid = "wx636f1058abca1bc1"; // 理由教育在线学习
        $this->secret = "756ca8483d61fa9582d9cdedf202e73e"; // 理由教育在线学习
    }

    private $appid ;
    private $secret ;
    public function get_gift_for_parent () {
        $wx= new \App\Helper\Wx("wx636f1058abca1bc1","756ca8483d61fa9582d9cdedf202e73e");
        $redirect_url=urlencode("http://wx-parent.leo1v1.com/wx_parent_gift/check_parent_info" );
        $wx->goto_wx_login( $redirect_url );
    }

    public function check_parent_info(){
        $code = $this->get_in_str_val('code');
        $wx= new \App\Helper\Wx("wx636f1058abca1bc1","756ca8483d61fa9582d9cdedf202e73e");
        $token_info = $wx->get_token_from_code($code);
        $openid   = @$token_info["openid"];

        $is_parent_flag = $this->t_parent_info->get_parentid_by_wx_openid($openid);
        if($is_parent_flag){
            header("location: http://wx-parent-web.leo1v1.com/anniversary_day/index.html?parentid=".$is_parent_flag);
            return ;
        }else{
            header("location: http://wx-parent-web.leo1v1.com/binding?goto_url=/index&type=1&openid=$openid");
            return ;
        }
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







    // public function get_share_num_for_book () {

    //     return @$_SESSION['check_flag'];
    //     $wx= new \App\Helper\Wx("wx636f1058abca1bc1","756ca8483d61fa9582d9cdedf202e73e");
    //     $redirect_url=urlencode("http://wx-parent.leo1v1.com/wx_parent_gift/check_identity_for_book" );
    //     $wx->goto_wx_login( $redirect_url );


    // }

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


    // 双11活动 理优在线

    public function get_luck_parent_info(){ // 获取家长抽奖信息
        $parentid = $this->get_in_int_val('parentid');

        // 检查是否分享朋友圈
        $start_time = strtotime('2017-11-06'); // 分享朋友圈有效时间
        $end_time   = strtotime('2017-11-13'); // 分享朋友圈有效时间
        $has_share  = $this->t_ruffian_share->get_share_num($parentid,$start_time, $end_time);

        // 检查是否在读学生
        $is_reading = $this->t_student_info->check_is_reading($parentid);

        //检查是否新签
        $order_start = strtotime('2017-11-11');
        $order_end   = strtotime('2017-11-13');
        $is_new_order = $this->t_order_info->check_is_new($parentid, $order_start, $order_end);

        $draw_num = 0; //抽奖次数

        if($has_share){ $draw_num++;}

        if($is_reading){$draw_num++;}

        if($is_new_order){$draw_num++;}

        $draw_num = ($draw_num>=2)?2:$draw_num;


        /** 抽奖活动
        // 1.统计
        **/

    }

    public function update_share_status(){ // check是否分享
        $parentid = $this->get_in_int_val('parentid');

        $this->t_ruffian_share->delete_row_by_pid($parentid);

        $this->t_ruffian_share->row_insert([
            "is_share_flag" => 1,
            "share_time"    => time(),
            "parentid"      => $parentid
        ]);

    }

    public function ruffian_activity(){ // 双11活动




    }



    // 双11优学优享活动
    public function get_member_info_list(){ // 获取会员信息
        $start_time = 1509638400; // 2017-11-03
        $parentid = $this->get_in_int_val('pid');

        $prize_num   = $this->t_luck_draw_yxyx_for_ruffian->get_prize_num($parentid);
        $invite_info = $this->t_agent->get_invite_num($start_time, $pid);

        $ret_info['invite_num'] = count($invite_info);
        $ret_info['light_num']  = floor(($ret_info['invite_num'] - 20*$prize_num)/5);

        return $this->output_succ(["data"=>$ret_info]);
    }


    public function do_luck_draw_yxyx(){ // 抽奖
        $parentid = $this->get_in_int_val('parentid');

        // 获取已中奖的总金额
        $has_get_money = $this->t_luck_draw_yxyx_for_ruffian->get_total_money();
        if($has_get_money >1000){
            return $this->output_err("谢谢参加!");
        }

        $rate  = mt_rand(1,100);
        $prize = 0;
        /**
           金额分别为
           1.11（80%）
           11.11（11%）
           31.11（4%）
           51.11（2%）
           71.11（1%）
           91.11（1%）
           111.1（1%）
           每天优先小金额
           每日金额为1000元预算
        */

        if($rate>=10 && $rate<90){ //中奖金额 1.11
            $prize = 111;
        }elseif($rate>30 & $rate<=41){ // 中奖金额 11.11
            $prize = 1111;
        }elseif($rate>30 & $rate<=34){ // 中奖金额 31.11
            $prize = 3111;
        }elseif($rate>50 & $rate<=52){ // 中奖金额 51.11
            $prize = 5111;
        }elseif($rate>60 & $rate<=61){ // 中奖金额 71.11
            $prize = 7111;
        }elseif($rate>70 & $rate<=71){ // 中奖金额 91.11
            $prize = 9111;
        }elseif($rate>80 & $rate<=81){ // 中奖金额 111.11
            $prize = 11111;
        }

        // 中奖金额存入数据库
        $ret = $this->t_agent->update_money($parentid, $prize);
        $is_save   = 0;
        $save_tiem = 0;
        if($ret){
            $is_save = 1;
            $save_time = time();
        }
        $this->t_luck_draw_yxyx_for_ruffian->row_insert([
            "luck_draw_adminid" => $parentid,
            "luck_draw_time" => time(),
            "deposit_time" => $save_time,
            "is_deposit" => $is_save,
            "money"  => $prize,
        ]);

        //发送微信推送
        /**
           {{first.DATA}}
           活动主题：{{keyword1.DATA}}
           活动时间：{{keyword2.DATA}}
           活动结果：{{keyword3.DATA}}
           {{remark.DATA}}
         **/
        $wx = new \App\Helper\Wx();

        $template_id = "lFGrDb_bPXPNJjS33WfmG4XVlVLoCWKLoAPGB5v9mP0";//活动结束提醒
        $data_msg = [
            "first"     => "您好，此次活动已经结束，你已经成功参与",
            "keyword1"  => "双十一活动",
            "keyword2"  => "2017.11.03 - 2017.11.10",
            "keyword3"  => "活动结果：您获得了现金红包".($prize/100)."元，进入账号管理-个人中心-我的收入-实际收入即可查看",
            "remark"    => "感谢您的参与",
        ];
        $url = "";
        $send_openid = $this->t_parent_info->get_wx_openid($parenrid);
        $wx->send_template_msg($send_openid,$template_id,$data_msg ,$url);

        return $this->output_succ(["money"=>$prize]);
    }




}
