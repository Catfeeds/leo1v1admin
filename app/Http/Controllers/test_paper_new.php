<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class test_paper extends Controller
{
    use CacheNick;
    use TeaPower;
    var $check_login_flag=false;
    public function __construct() {
        parent::__construct();
    }
   

    
}
