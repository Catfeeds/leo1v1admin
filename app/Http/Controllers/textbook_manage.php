<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;

class textbook_manage extends Controller
{
    use CacheNick;
    use TeaPower;

    public function get_subject_grade_textbook_info(){
        $ret_info = \App\Helper\Utils::list_to_page_info([]);
        return $this->pageView(__METHOD__,$ret_info);

    }

   

}
