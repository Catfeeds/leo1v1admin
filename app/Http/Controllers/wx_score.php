<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;


class wx_score extends Controller
{
    var $check_login_flag =false;
    

    public function gen_user_school_info() {
        $openid=$this->get_in_str_val("openid");
        $m1_score=$this->get_in_float_val("m1_score");
        $m2_score=$this->get_in_float_val("m2_score");
        $areaid=$this->get_in_float_val("areaid");
        //
        $score=($m1_score+$m2_score+30)/2;
        
        /*
          "school_name" => "视觉艺术附中"
          "scores_school" => "475"
          "scores_area" => "102"
          "percent" => 50
        */
        
        if ($score>=590) {
            $max_config=[
                ["school_name" => "上海中学", "scores_school" => "", "scores_area" => "", "percent" => 20],
                ["school_name" => "交大附中", "scores_school" => "", "scores_area" => "", "percent" => 30],
                ["school_name" => "华师大附二中", "scores_school" => "", "scores_area" => "", "percent" => 20],
                ["school_name" => "复旦附中", "scores_school" => "", "scores_area" => "", "percent" => 30,],
            ];
            if ($areaid==E\Esh_area::V_102) {
                $max_config[0]["percent"]=70;
                $max_config[1]["percent"]=10;
                $max_config[2]["percent"]=10;
                $max_config[3]["percent"]=10;
            }
            
            if ($areaid==E\Esh_area::V_112) {
                $max_config[0]["percent"]=10;
                $max_config[1]["percent"]=10;
                $max_config[2]["percent"]=70;
                $max_config[3]["percent"]=10;
            }

            if ($areaid==E\Esh_area::V_107) {
                $max_config[0]["percent"]=20;
                $max_config[1]["percent"]=30;
                $max_config[2]["percent"]=20;
                $max_config[3]["percent"]=30;
            }
            $school_list=$max_config;
        }else{
            $school_list=$this->t_scores_info->get_find_school_list($areaid,$score );
        }
        


        //E\Esh_area
        $user_info=$this->t_wx_user_info->field_get_list($openid,"*");

        //level
        if ($score>=590) {
            $level=1;
        }else if ( $score >= $this->t_scores_info->min_score($areaid) ) {
            $level=2;
        }else{
            $level=3;
        }
        if ($score>590) {
            $score=590; 
        }
        //all_percent
        if ($score>=430) {
            $all_percent=0.25*($score-430)+60;
        }else if($score>=400){
            $all_percent=60;
        }else {
            $all_percent=60-0.15*(400-$score);
        }
        if ($all_percent<=0) {
            $all_percent=1;
        }
        if ($all_percent>=100) {
            $all_percent=99;
        }
        

        

        $data=[
            "user_info" => $user_info,
            "level" => $level,
            "percent" => $all_percent,
            "school_list"=>$school_list,
        ];

        $this->t_wx_key_value->row_insert([
            "data"  => json_encode($data),
        ]);
        $id=$this->t_wx_key_value->get_last_insertid();
        return $this->output_succ(["id" =>$id]);
    }

    public function get_user_school_info() {
        $id=$this->get_in_str_val("id");
        $openid=$this->get_in_str_val("openid");

        $data=\App\Helper\Utils::json_decode_as_array($this->t_wx_key_value->field_get_value($id,"data"));
        if ($data) {
            if ($openid==@$data["user_info"]["openid"]){
                usort($data["school_list"],function($item1,$item2)
                {
                    $percent1=$item1["percent"];
                    $percent2=$item2["percent"];
                    if ($percent1==$percent2) return 0;
                    return ($percent1<$percent2 ) ? 1:-1;
                });

                foreach ($data["school_list"]  as &$item) {
                    $school_name=$item["school_name"];
                    if (!(strpos($school_name, "分" ) >0 || strpos($school_name, "中" ) >0  )) {
                        $item["school_name"]=$school_name."中学";
                    }
                }
                return $this->output_succ(["data" =>$data]);
            }
        }

        return $this->output_err("参数出错");

    }
}