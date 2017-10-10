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








    public function get_share_num_for_book () {
        $wx= new \App\Helper\Wx("wx636f1058abca1bc1","756ca8483d61fa9582d9cdedf202e73e");
        $redirect_url=urlencode("http://wx-parent.leo1v1.com/wx_parent_gift/check_identity_for_book" );

        $wx->goto_wx_login( $redirect_url );
    }



    public function check_identity_for_book(){
        $code = $this->get_in_str_val('code');
        $wx= new \App\Helper\Wx("wx636f1058abca1bc1","756ca8483d61fa9582d9cdedf202e73e");
        $token_info = $wx->get_token_from_code($code);
        $openid   = @$token_info["openid"];

        $share_num = @$this->t_wx_give_book->check_share_flag($openid);
        if($share_num>0){
            return $this->output_succ(['share_num'=>$share_num]);
        }else{
            $ret = $this->t_wx_give_book->row_insert([
                "openid"    => $openid,
                "create_time" => time(),
                "share_num"   => 1
            ]);

            return $this->output_succ(['share_num'=>0]);
        }

    }



    // public function get_share_num(){
    //     $parentid = $this->get_parentid();
    //     $share_num = $this->t_wx_give_book->get_share_num_by_parentid($parentid);

    //     return $this->output_succ(['share_num'=>$share_num]);
    // }

    // public function set_share_num(){ //记录分享朋友圈次数
    //     $parentid = $this->get_parentid();
    //     $this->t_wx_give_book->row_delete_by_parentid($parentid);

    //     $ret = $this->t_wx_give_book->row_insert([
    //         "parentid"    => $parentid,
    //         "create_time" => time(),
    //         "share_num"   => 1
    //     ]);

    //     return $this->output_succ();
    // }

    // public function get_parentid(){
    //     $parentid= $this->get_in_int_val("_parentid")?$this->get_in_int_val("_parentid") : session("parentid");
    //     return $parentid;
    // }

}
