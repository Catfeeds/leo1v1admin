<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;

class campus_manage extends Controller
{
    use CacheNick;
    use TeaPower;

   
    public function admin_campus_manage(){
        $ret_info = $this->t_admin_campus_list->get_admin_campus_info(); 
        return $this->pageView(__METHOD__,$ret_info);

        dd($ret_info);
    }

    public function add_admin_campus(){
        $campus_name =trim($this->get_in_str_val("campus_name"));
        $this->t_admin_campus_list->row_insert([
            "campus_name" =>$campus_name
        ]);
        return $this->output_succ();
    }


}