<?php

namespace App\Models;
use App\Models\Zgen as Z;
class t_pic_manage_info extends \App\Models\Zgen\z_t_pic_manage_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_pic_info_list($type,$usage_type=-1,$active_status=0,$page_num){
        $where_str = [
            [ "type=%d", $type, -1 ],
            [ "usage_type=%d", $usage_type, -1 ],
        ];
        if ($active_status == 1) { // 待发布
            array_push($where_str, ["start_time>%u", time(), 0]);
            array_push($where_str, 'del_flag=0');
        } elseif ($active_status == 2) {
            array_push($where_str, ["start_time<=%u", time(), 0]);
            array_push($where_str, ["end_time>%u", time(), 0]);
            array_push($where_str, 'del_flag=0');
        } elseif ($active_status == 3) {
            array_push($where_str, ["end_time>%u", time(), 0]);
            array_push($where_str, 'del_flag=0');
        } elseif ($active_status == 4) {
            array_push($where_str, 'del_flag=1');
        }

        if($usage_type==208 || $usage_type==210 || $usage_type==104){
            $order_str="order by order_by asc";
        }else{
            $order_str="order by id desc";
        }

        $sql = $this->gen_sql_new("select * from %s where %s %s"
                              ,self::DB_TABLE_NAME
                              ,$where_str
                              ,$order_str
        );
        return $this->main_get_list_by_page($sql,$page_num, 10);
    }

    public function get_teacher_clothes_list($type){
        $where_str=$this->where_str_gen([
            [ "type=%d", $type, -1 ],
        ]);
        $sql = $this->gen_sql("select id as k,name as v from %s where %s"
                             ,self::DB_TABLE_NAME
                             ,[$where_str]
        );
        return $this->main_get_list($sql);
    }

    public function add_pic_info($opt_type,$id,$name,$type,$usage_type,$pic_url,$tag_url,$click_status,$order_by,$grade,$subject,
                                 $start_time,$end_time,$title_share,$info_share,$jump_url,$jump_type){
        if($opt_type == 'add'){
            $this->row_insert([
                self::C_name         => $name,
                self::C_type         => $type,
                self::C_usage_type   => $usage_type,
                self::C_img_url      => $pic_url,
                self::C_img_tags_url => $tag_url,
                self::C_status       => $click_status,
                self::C_order_by     => $order_by,
                self::C_grade        => $grade,
                self::C_subject      => $subject,
                self::C_start_time  => $start_time,
                self::C_end_time    => $end_time,
                self::C_title_share => $title_share,
                self::C_info_share  => $info_share,
                self::C_jump_url    => $jump_url,
                self::C_jump_type   => $jump_type,
            ]);
        }else{
            $set_field_arr=array(
                self::C_name         => $name,
                self::C_type         => $type,
                self::C_usage_type   => $usage_type,
                self::C_img_url      => $pic_url,
                self::C_img_tags_url => $tag_url,
                self::C_status       => $click_status,
                self::C_order_by     => $order_by,
                self::C_grade        => $grade,
                self::C_subject      => $subject,
                self::C_start_time  => $start_time,
                self::C_end_time    => $end_time,
                self::C_title_share => $title_share,
                self::C_info_share  => $info_share,
                self::C_jump_url    => $jump_url,
                self::C_jump_type   => $jump_type,
            );
            $this->field_update_list($id,$set_field_arr);
        }
    }

    public function get_banner_pic_list($type,$usage_type){
        $where_arr = [
            ["type=%u",$type,0],
            ["usage_type=%u",$usage_type,0],
            "status=1"
        ];
        $sql = $this->gen_sql_new("select img_url as pic_url,name as title,jump_url"
                                  ." from %s "
                                  ." where %s"
                                  ." order by order_by"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_pic_or_mobile_info($type) {
        $current = time();
        $where_arr = [
            ['start_time<=%u', $current, 0],
            ['end_time>%u', $current, 0],
            "usage_type=$type",
            "del_flag=0"
        ];
        $sql = $this->gen_sql_new("select status,img_url,jump_type,jump_url,order_by from %s where %s order by order_by asc, id desc",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

}