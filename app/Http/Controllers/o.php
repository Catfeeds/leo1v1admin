<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;

//开关设备

class o extends Controller
{

    public function index() {
	dd("线路修复中,请到前台拿空调遥控器");
        $id=$this->get_in_int_val("id");
        return $this->pageView(__METHOD__,null, [
            "id" => $id,
        ]);
    }
}
