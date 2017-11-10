<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

class ceshi extends Controller
{
    var $check_login_flag =false;
    public function ceshi1() {
        list($start_time,$end_time)=$this->get_in_date_range(0,-1);
        $ret_info=$this->t_student_info->get_closest_list($start_time,$end_time );

        return $this->pageView(__METHOD__,\App\Helper\Utils::list_to_page_info( $ret_info));
    }
}
