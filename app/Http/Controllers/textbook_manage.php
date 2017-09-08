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
        $page_info = $this->get_in_page_info();
        $ret_info =  $this->t_location_subject_grade_textbook_info->get_all_info($page_info);
        foreach($ret_info["list"] as &$item){
            E\Esubject::set_item_value_str($item,"subject");
            E\Egrade::set_item_value_str($item,"grade");
            $arr= explode(",",$item["teacher_textbook"]);
            foreach($arr as $val){
                @$item["textbook_str"] .=  E\Eregion_version::get_desc ($val).",";
            }
            $item["textbook_str"] = trim($item["textbook_str"],",");
 
        }
        return $this->pageView(__METHOD__,$ret_info);

    }

    public function show_textbook_map(){
        $url  =  "http://bbs.lampbrother.net" ; 
        echo " <   script   language = 'javascript' 
type = 'text/javascript' > "; 
        echo " window.location.href = '$url' "; 
        echo " <  /script > ";  
      return $this->pageView(__METHOD__,null);
    }

   

}
