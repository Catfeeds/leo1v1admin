<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie;

class teacher_money extends Controller
{
    use CacheNick;
    var $teacher_money;
    var $late_num   = 0;
    var $change_num = 0;

    public function __construct(){
        $this->teacher_money = \App\Helper\Config::get_config("teacher_money");
    }

    public function get_teacher_money_total_list(){
        $teacherid = $this->get_login_teacher();

    }




}