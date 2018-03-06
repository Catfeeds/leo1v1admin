<?php

namespace App\Http\Controllers;
use \App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class info_support extends Controller
{
    use CacheNick;
    var $check_login_flag=true;

    function __construct( ) {
        parent::__construct();
    }

    public function get_books()
    {
        $subject      = $this->get_in_int_val('subject', 1);       
        $province = $this->get_in_int_val('province', -1);
        $city       = $this->get_in_int_val('city', -1);


        $province_range = "";
        if($province > 0){
                   
        }

        $ret_info = $this->t_info_resource_book->get_books($subject,$province,$city,$province_range);

        $city_have = 0;
        $list = [];
        $i = -1;
        foreach($ret_info as $k => $item) {           
            if($item['city'] != $city_have){
                $i ++;
                $city_have = $item['city'];
                $list[$i] = $item;
                $list[$i]['subject_str'] = E\Esubject::get_desc($item['subject']);         
                $list[$i]['book_arr'][$item['grade']][$item['book']] = E\Eregion_version::get_desc($item['book']);                                
            }else{
                $list[$i]['book_arr'][$item['grade']][$item['book']] = E\Eregion_version::get_desc($item['book']);
            }
        }
        //dd($list);
        return $this->pageView(__METHOD__,null,[
            '_publish_version'    => 20180306161440,
            'list' => $list
        ]);
    }

    public function save_books(){
        $subject         = $this->get_in_int_val('subject', 1);       
        $province        = $this->get_in_int_val('province', -1);
        $city            = $this->get_in_int_val('city', -1);
        $province_name   = $this->get_in_str_val('province_name');
        $city_name       = $this->get_in_str_val('city_name');
        $low             = $this->get_in_str_val('low');       
        $middle          = $this->get_in_str_val('middle');
        $high            = $this->get_in_str_val('high');
        $data = [];
        $public_data = [
            "subject" => $subject,
            "province" => $province,
            "province_name" => $province_name,
            "city" => $city,
            "city_name" => $city_name
        ];
        if($low){
            $low_arr = explode(",",$low);
            if($low_arr){
                foreach($low_arr as $book_1){
                    $public_data["grade"] = 100;
                    $public_data["book"] = $book_1;
                    $this->t_info_resource_book->row_insert($public_data);
                }
            }
        }

        if($middle){
            $middle_arr = explode(",",$middle);
            if($middle_arr){
                foreach($middle_arr as $book_2){
                    $public_data["grade"] = 200;
                    $public_data["book"] = $book_2;
                    $this->t_info_resource_book->row_insert($public_data);
                }
            }
        }

        if($high){
            $high_arr = explode(",",$high);
            if($high_arr){
                foreach($high_arr as $book_3){
                    $public_data["grade"] = 300;
                    $public_data["book"] = $book_3;
                    $this->t_info_resource_book->row_insert($public_data);
                }
            }
        }

        return $this->output_succ();
    }
}