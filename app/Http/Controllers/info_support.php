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

        $city_have = -1;
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

        return $this->pageView(__METHOD__,null,[
            '_publish_version'    => 20180306151440,
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

        $this->save_or_del_books($public_data,100,$low);     
        $this->save_or_del_books($public_data,200,$middle);
        $this->save_or_del_books($public_data,300,$high);

        return $this->output_succ();
    }

    private function save_or_del_books($public_data,$grade_range,$book_str){
        $subject = $public_data['subject'];
        $province = $public_data['province'];
        $city = $public_data['city'];
        $public_data['grade'] = $grade_range;
        if($book_str){
            if(is_array($book_str)){
                $book_arr = $book_str;
            }else{
                $book_arr = explode(",", $book_str);
            }

            if(count($book_arr) > 0){
                $get_old_books = $this->t_info_resource_book->get_old_books($subject,$province,$city,$grade_range);
                $del_book = [];
                $add_book = [];
                if($get_old_books){
                    $old_books = array_column($get_old_books, 'book');
                    $old_books_id = array_column($get_old_books,'id', 'book');
                    $add_book = array_diff($book_arr,$old_books);
                    $del_book = array_diff($old_books,$book_arr);
                    if($del_book){
                        $del_str = "";
                        foreach($del_book as $del_id){
                            if(!empty(@$old_books_id[$del_id]) ){
                                $del_str .= $old_books_id[$del_id].',';
                            }
                        }

                        if($del_str){
                            $del_str = "(".substr($del_str,0,-1).')';
                            //echo $del_str;
                            $del_books = $this->t_info_resource_book->del_books($del_str);
 
                        }
                    }
                }else{
                    $add_book = $book_arr;
                }

                if($add_book){
                    foreach($add_book as $bid){
                        $public_data['book'] = $bid;
                        $this->t_info_resource_book->row_insert($public_data);
                    }
                }
            }
        }
    }
}