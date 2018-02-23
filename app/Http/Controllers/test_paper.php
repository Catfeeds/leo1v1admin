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
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],3);
        $subject      = $this->get_in_int_val('subject', -1);
        $grade        = $this->get_in_int_val('grade', -1);
        $paper_id     = trim($this->get_in_int_val('paper_id', -1) );
        $book         = $this->get_in_int_val("book",-1);
        $volume       = $this->get_in_int_val("volume",-1);
        $page_info    = $this->get_in_page_info();
        $ret_info = [];
       
        return $this->pageView( __METHOD__,$ret_info,[
            '_publish_version'    => 20180223134439,
        ]);
    } 

}
