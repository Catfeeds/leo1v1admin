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
        $grade = $this->get_in_int_val("grade",-1);
        $subject = $this->get_in_int_val("subject",-1);
        $address  = trim($this->get_in_str_val('address',''));
        $ret_info =  $this->t_location_subject_grade_textbook_info->get_all_info($page_info,$grade,$subject,$address);
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
        $province = $this->get_in_str_val("province","河南");
        $list = $this->t_location_subject_grade_textbook_info->get_all_list($province);
        $data=[];
        foreach($list as $item){
            $data[$item["city"]]["educational_system"] =$item["educational_system"]; 
            $data[$item["city"]]["area"] =$item["city"]; 
            $data[$item["city"]]["province"] =$item["province"]; 
            $subject_str    = E\Esubject::get_desc($item["subject"]);
            $grade_str    = E\Egrade::get_desc($item["grade"]);

            $arr_text= explode(",",$item["teacher_textbook"]);
            $textbook="";
            foreach($arr_text as $vall){
                @$textbook .=  E\Eregion_version::get_desc ($vall).",";
            }
            $textbook = trim($textbook,",");
            $str = $this->get_textbook_str($item["subject"],$item["grade"]);
            $data[$item["city"]][$str] =$grade_str."".$subject_str.":".$textbook;
            

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
