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

        // dd($openid);
        $is_parent_flag = $this->t_parent_info->get_parentid_by_wx_openid($openid);
        // echo $is_parent_flag; //orwGAs_IqKFcTuZcU1xwuEtV3Kek 271968
        if($is_parent_flag){
            // return $is_parent_flag;
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
            //处理列

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
            //return 111;
            //dd(222);
            return outputjson_ret(false);
        }

    }



    public function do_prize_draw(){
        // 获取 每个家长的等级
        $userid = $this->get_in_int_val('userid');
        $parent_lesson_total = $this->t_parent_child->get_student_lesson_total_by_parentid($userid);
        $parent_num = $parent_lesson_total/100;

        $price = 0;
        $limit_gift = 0;
        if($parent_num>30 && $parent_num<=90){
            $price = 20;
            $limit_gift = 71;
        }elseif($parent_num>90 && $parent_num<=180){
            $price = 80;
            $limit_gift = 57;
        }elseif($parent_num>180 && $parent_num<=250){
            $price = 120;
            $limit_gift = 42;
        }elseif($parent_num>250 && $parent_num<=300){
            $price = 150;
            $limit_gift = 28;
        }elseif($parent_num>300 && $parent_num<=350){
            $price = 200;
            $limit_gift = 28;
        }elseif($parent_num>350 && $parent_num<=400){
            $price = 300;
            $limit_gift = 14;
        }elseif($parent_num>400 && $parent_num<=450){
            $price = 400;
            $limit_gift = 14;
        }elseif($parent_num>450){
            $price = 500;
            $limit_gift = 10;
        }


        // 查看是否已抽奖

        $gift_info = $this->t_parent_luck_draw_in_wx->get_gift_info_by_userid($userid);

        if($gift_info['userid']>0){
            return $this->output_succ($gift_info);
        }else{
            // 首次参加抽奖 [将抽奖结果放入到数据表中]
            $start_time = strtotime(date("Y-m-d",time()));
            $end_time   = $start_time+86400;
            $all_gift_list  = $this->t_parent_luck_draw_in_wx->get_all_gift_list($price);
            $today_gift_num = $this->t_parent_luck_draw_in_wx->ger_today_gift_num($start_time,$end_time,$price);

            // 8月6日 ~ 8日 // 每天的奖品数量
            if($price == 20){
                
            }


            if($today_gift_num >=$limit_gift){
                return $this->output_err("未中奖!");
            }

            $rock_gift_num = count($all_gift_list);

            $index = mt_rand(0,$rock_gift_num-1);

            $prize_code = $all_gift_list[$index]['prize_code'];

            $ret_add = $this->t_parent_luck_draw_in_wx->row_insert([
                "prize_code" => $prize_code,
                "userid"     => $userid,
                "add_time"   => time()
            ]);


        }


    }



}