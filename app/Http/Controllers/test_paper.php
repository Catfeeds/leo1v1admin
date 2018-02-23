<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;


class test_paper extends Controller
{

    function __construct( ) {
        parent::__construct();
    }
   
    public function input_paper() {
     
        $tag_four      = $this->get_in_int_val('tag_four', -1);
        $tag_five      = $this->get_in_int_val('tag_five', -1);
        $file_title    = trim( $this->get_in_str_val('file_title', '') );
        $file_id       = intval($this->get_in_str_val("file_id",-1));
        $page_info     = $this->get_in_page_info();
        $ret_info = [];
       
        return $this->pageView( __METHOD__,$ret_info,[
            '_publish_version'    => 20180204111439,
        ]);
    } 

}
