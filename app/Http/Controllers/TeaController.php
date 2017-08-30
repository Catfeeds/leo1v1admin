<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log ;
use \App\Enums as E;


class TeaController extends Controller
{

    var $check_login_flag = true;

    function __construct()  {
        parent::__construct();
        if (!$this->get_login_teacher ()) {
            header("Location: /login/teacher");
            exit;
        }
    }


    public function get_login_teacher() {
        $role      = $this->get_in_int_val("_role",2 );
        $teacherid = $this->get_in_int_val("_userid",60008);

        if (!$role) {
            $role = session("login_user_role" );
        }

        if (!$teacherid) {
            $teacherid = session("login_userid" );
        }

        if ($role==2 &&  $teacherid ) {
            return $teacherid;
        }else{
             echo $this->output_err("未登录");
             exit;
        }
    }

}
