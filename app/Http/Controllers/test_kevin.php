<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;
class test_kevin extends Controller
{
    public function p_list(){
        $page_info= $this->get_in_page_info();
        $nick_phone= $this->get_in_str_val("nick_phone");
        $account_role= $this->get_in_el_account_role();

        $ret_info=$this->t_manager_info->get_list_test($page_info,$nick_phone);
        return $this->pageView( __METHOD__,$ret_info);
    }


    // public function test_one(){
    //     $page_info= $this->get_in_page_info();
    //     $nick_phone= $this->get_in_str_val("nick_phone");
    //     $account_role= $this->get_in_el_account_role();
    //     $ret_info=$this->t_manager_info->get_list_test($page_info,$nick_phone);

    //     return $this->pageView( __METHOD__,$ret_info);

    // }
    

}
