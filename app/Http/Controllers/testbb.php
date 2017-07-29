<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

class testbb extends Controller
{
    var $check_login_flag = false;
    public function get_msg_num() {
        $bt_str=" ";
        $e=new \Exception();
        foreach( $e->getTrace() as &$bt_item ) {
            //$args=json_encode($bt_item["args"]);
            $bt_str.= @$bt_item["class"]. @$bt_item["type"]. @$bt_item["function"]."---".
                @$bt_item["file"].":".@$bt_item["line"].
                "<br/>";
        }
        echo $bt_str;

    }

    public function assistant_info_new2(){
        $today      = date('Y-m-d',time(null));
        $today      = '20170626';
        $start_time = strtotime($today.'00:00:00');
        $end_time   = $start_time+24*3600;
        $userid=-1;
        $lesson_arr = [];
        $phone = '456';
        $lesson_arr = $this->t_agent->get_agent_info_row_by_phone($phone);
    }


    public function test1() {

        $lesson_end_time = $this->t_lesson_info->get_lesson_end(2367);
        dd($lesson_end_time);
        dd(strtotime(date('Y-m-d',time(NULL))));
        chmod("/var/www/admin.yb1v1.com/public/wximg/l_3689y277y0ydraw.xml",0777);

        exit();
        // $lessonid  = $item['lessonid'];
        $lessonid = 190149;
        $ret_video = $this->t_lesson_info_b2->get_lesson_url($lessonid);

        if(isset($ret_video[0]['draw'])){
            $item['draw_url']  =  \App\Helper\Utils::gen_download_url($ret_video[0]['draw']);
            $savePathFile = public_path('wximg').'/'.$ret_video[0]['draw'];
            \App\Helper\Utils::savePicToServer($item['draw_url'],$savePathFile);

            $xml = file_get_contents($savePathFile);

            $xmlstring = simplexml_load_string($xml);

            $svgLists = json_decode(json_encode($xmlstring),true);


            $stroke_time = 0;

            foreach($svgLists['svg'] as $svg){
                if (array_key_exists('path',$svg)) {
                    $stroke_time = $svg['@attributes']['timestamp'];
                }
            }

            // dd($ret_video[0]['real_begin_time']<($stroke_time-30*60));

            if ($ret_video[0]['real_begin_time']<($stroke_time-30*60)) {
                $re = $this->t_lesson_info->field_update_list($lessonid,[
                    "lesson_user_online_status" =>  1
                ]);
                dd($re);

            }
            unlink($savePathFile);
        }


        // $this->t_manager_info->send_wx_todo_msg("tom","sdfa","dfadf");

    }





    public function test () {

        $arr = [
            []
        ];
        \App\Helper\Utils::sortArrByField();

        $c = $this->secsToStr(10000);
        dd($c);

    }

    public function lesson_send_msg(){
        $start_time = time(null);
        $this->t_teacher_info->get_lesson_info_by_time($start_time,$end_time);
    }







    public function set_teacher_free_time(){
        $free_time = $this->get_in_str_val('parent_modify_time');

        // 加一个时间的限制
    }


    public function get_nick_phone_by_account_type($account_type,&$item){
            $item["user_nick"]  = $this->cache_get_teacher_nick ($item["userid"] );
            $item['phone']      = $this->t_teacher_info->get_phone_by_nick($item['user_nick']);
    }










}