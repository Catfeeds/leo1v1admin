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
            $realPath = $file -> getRealPath();
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');

            $objPHPExcel = $objReader->load($realPath);
            $objPHPExcel->setActiveSheetIndex(0);
            $arr=$objPHPExcel->getActiveSheet()->toArray();

            // foreach($arr as $k=>&$val){
            //     if(empty($val[0]) || $k==0){
            //         unset($arr[$k]);
            //     }
            //     // $val[-1] = strlen($val[1]);
            //     if(strlen($val[1])==4){
            //         $val[1]="0".$val[1];
            //     }
            //     if(strlen($val[2])==4){
            //         $val[2]="0".$val[2];
            //     }

            // }

            // foreach($arr as $item){
            //     $day = strtotime($item[0]);
            //     $this->t_psychological_teacher_time_list->row_insert([
            //         "day"  =>$day,
            //         "start"=>$item[1],
            //         "end"  =>$item[2],
            //         "teacher_phone_list"=>$item[3]
            //     ]);
            // }

            // dd($arr);
            // (new common_new()) ->upload_from_xls_data( $realPath);

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
        $parent_lesson_total_lists = $this->t_parent_child->get_student_lesson_total_by_parentid($userid);

        dd($parent_lesson_total_lists);
    }







}