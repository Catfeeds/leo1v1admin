<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

class custom extends Controller
{
    public function index()  {

        global $g_request;
        $path=$g_request->path();
        $arr=explode("/", $path);
        $act="index";
        if (isset($arr[1]) ) {
            $act=$arr[1] ;
        }
        if ($act=="custom_list") {
            $this->$act();
        }
    }

    public function custom_list() {
    }
}
