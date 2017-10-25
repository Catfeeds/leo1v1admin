<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class month_student extends Controller
{

    use CacheNick;
    var $check_login_flag=true;

    function __construct( )  {
        parent::__construct();
    }

    public function get_month_stu_info(){

    }
}
