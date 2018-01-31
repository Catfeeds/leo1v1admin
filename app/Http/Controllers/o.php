<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;

//开关设备

class o extends Controller
{

    public function index() {
        //dd("线路修复中,请到前台拿空调遥控器");
        $id=$this->get_in_int_val("id");
        return $this->pageView(__METHOD__,null, [
            "id" => $id,
        ]);
    }
    public function d(){
        $sn=$this->get_in_str_val("sn");
        $info=$this->t_kaoqin_machine->get_info_by_sn( $sn );
        if (!$info) {
            return $this->error_view(["出错: sn=$sn"]);
        }
        $machine_id=$info["machine_id"];
        $info["last_post_time"] = \App\Helper\Utils::unixtime2date( $info["last_post_time"]);


        return $this->pageView(__METHOD__,null, [
            "info" => $info,
        ]);
    }
}
