<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use \App\Enums as E;


class teacher_common extends Controller
{
    use TeaPower;
    use CacheNick;
    var $check_login_flag =false;

    function login() {
        
    }

    public function full_to_part() {
        return $this->output_err("wel");
    }
}