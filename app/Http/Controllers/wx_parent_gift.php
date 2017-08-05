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

        if($parent_num>30 && $parent_num<=90){
            $price = 20;
        }elseif($parent_num>90 && $parent_num<=180){
            $price = 80;
        }elseif($parent_num>180 && $parent_num<=250){
            $price = 120;
        }elseif($parent_num>250 && $parent_num<=300){
            $price = 150;
        }elseif($parent_num>90 && $parent_num<=180){
            $price = 200;
        }


        // 查看是否已抽奖

        $gift_info = $this->t_parent_luck_draw_in_wx->get_gift_info_by_userid($userid);

        if($gift_info['userid']){
            if($gift_info['prize_code']){
                return $this->output_succ($gift_info);
            }else{
                return $this->output_succ();
            }
        }else{

            // 首次参加抽奖 [将抽奖结果放入到数据表中]
            $now = time();
            $all_gift_list = $this->t_parent_luck_draw_in_wx->get_all_gift_list($now);

            $index = mt_rand(0,1870);

            if(!$all_gift_list[$index]['receive_time']){
                return $this->output_err("未中奖!");
            }else{
                // $all_gift_list[$index]['price'] = 1;

                $ret_add = $this->t_parent_luck_draw_in_wx->row_insert([
                    "prize_code" => "",
                    "userid"     => $userid,

                ]);
            }

        }


    }







}