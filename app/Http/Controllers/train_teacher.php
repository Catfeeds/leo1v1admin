<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;
use Illuminate\Support\Facades\Cookie ;

class train_teacher extends Controller
{

    use CacheNick;
    use TeaPower;

    public function train_lecture_lesson_list(){
        list($start_time,$end_time) = $this->get_in_date_range(0,0,0,null,1);
        $teacherid       = $this->get_in_int_val("teacherid",-1);
        $lesson_status   = $this->get_in_int_val("lesson_status",-1);
        $lesson_type     = $this->get_in_int_val("lesson_type",1100);
        $lessonid        = $this->get_in_int_val("lessonid",-1);
        $lesson_sub_type = $this->get_in_int_val("lesson_sub_type",-1);
        $train_type      = $this->get_in_int_val("train_type",-1);
        $acc             = $this->get_account();
        $page_num        = $this->get_in_page_num();

        $this->t_lesson_info->switch_tongji_database();
        $ret_info = $this->t_lesson_info->get_train_lesson(
            $page_num,$start_time,$end_time,$teacherid,$lesson_status,
            $lessonid,$lesson_sub_type,5
        );

        $n = 1;
        foreach($ret_info['list'] as &$val){
            \App\Helper\Utils::unixtime2date_range($val);
            E\Esubject::set_item_value_str($val);
            E\Egrade::set_item_value_str($val);
            E\Elesson_status::set_item_value_str($val);
            E\Econtract_type::set_item_value_str($val,"lesson_type");
            if($val['tea_cw_url']==""){
                $val['cw_status']="未上传";
            }else{
                $val['cw_status']="已上传";
            }

            $val['index']  = $n;
            $n++;
            $server_info   = @$server_map[$val['courseid']];
            $val['region'] = @$server_info['region'];
            $val['ip']     = @$server_info['ip'];
            $val['port']   = @$server_info['webrtc_port'];
            $val['server_type_str'] = \App\Helper\Utils::get_server_type_str($val);
        }
        return $this->pageView(__METHOD__,$ret_info,[
            "acc" => $acc
        ]);
    }

}