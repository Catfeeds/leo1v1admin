<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use \App\Libs;
use \App\Config as C;
use \App\Enums as E;
use App\Helper\Utils;

class test_sam  extends Controller
{
    use CacheNick;
    use TeaPower;
    
    public function lesson_list()
    {
      
    }
    
    private function get_lesson_quiz_cfg($lesson_quiz_status, $lesson_type)
    {
    }
    public function manager_list()
    {
    }
    public function test(){
        
        $ret_info = $this->t_cr_week_month_info->get_tongji();
        echo "统计时间:2016.10.1~2017.10.1"."<br/>";
        echo "总注册学生数:".$ret_info['total_student']."<br/>";
        echo "总签单数:".$ret_info['total_order']."<br/>";
        echo "第二次购买的学生数".$ret_info['total_renew_order'].'<br/>';
        echo "电话接通数:".$ret_info['total_call']."<br/>";
     
    }



    public function  tt(){
        $ret_info = $this->t_cr_week_month_info->get_tongji2();
        foreach ($ret_info as $key => $value) {
            # code...
            $phone=trim($value['phone']);
            if ($phone =="" ) {
                $phone_location = "" ;
            }else{
                $url= "https://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=$phone";

                $data= preg_replace("/__GetZoneResult_ = /","", \App\Helper\Net::send_post_data($url,[] )
                );
                $data= preg_replace("/([A-Za-z]*):/","\"\\1\":", $data);
                $data= preg_replace("/'/","\"", $data);

                $data = iconv("GBK","utf-8",$data);
                $arr  = json_decode($data,true);

                if(isset($arr['province']) && isset($arr['carrier'])){
                    if(strpos($arr['carrier'],'移动') ||strpos($arr['carrier'],'联通')||strpos($arr['carrier'],'电信')){
                        $phone_location =  $arr["carrier"];
                    }else{
                        $phone_location =  $arr["province"]."其它";
                    }
                }else{
                    $phone_location =  "";
                }
            }
            echo $phone_location.'<br/>';
            /*$this->t_student_info->field_update_list($value['userid'],[
                "email" =>$email,
            ]);*/

        }

    }
}

