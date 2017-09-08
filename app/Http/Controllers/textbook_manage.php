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
      return $this->pageView(__METHOD__,null);
    }

    public function get_city_textbook_info(){
        $list = $this->t_location_subject_grade_textbook_info->get_all_list();
        $data=[];
        foreach($list as $item){
            $data[$item["city"]]["educational_system"] =$item["educational_system"]; 
            $data[$item["city"]]["area"] =$item["city"]; 
            $data[$item["city"]]["province"] =$item["province"]; 
            $subject_str    = E\Esubject::get_desc($item["subject"]);
            $grade_str    = E\Esubject::get_desc($item["grade"]);

            $arr_text= explode(",",$item["teacher_textbook"]);
            $textbook="";
            foreach($arr_text as $vall){
                @$textbook .=  E\Eregion_version::get_desc ($vall).",";
            }
            $textbook = trim($textbook,",");
            

        }
        $arr=[];
        foreach($data as $v){
            $arr[] = $v;
        }
        return $this->output_succ([
            "data"=>$arr
        ]);

        //dd($data);
    }


   

}
