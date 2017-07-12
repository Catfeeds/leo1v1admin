<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_apply extends \App\Models\Zgen\z_t_teacher_apply
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_teacher_apply_list($cc_id,$page_info)
    {
        $where_arr=[
            ["ta.cc_id=%u",$cc_id,-1],
        ];
        $sql=$this->gen_sql_new ("select ta.*,t.realname,t.phone"
                                 ." from %s ta"
                                 ." left join %s t on t.teacherid = ta.teacherid"
                                 ." where %s "
                                 ."order by ta.create_time desc"
                                 ,self::DB_TABLE_NAME
                                 ,t_teacher_info::DB_TABLE_NAME
                                 ,$where_arr
        );
        return $this->main_get_list_by_page( $sql,$page_info);
    }

    //$this->where_arr_add_int_or_idlist($where_arr,$field_name,$value);
    // $this->where_arr_add_int_or_idlist($where_arr,"userid",$userid);
    // $this->where_arr_add_str_field($where_arr,"id",$id);
    // $this->where_arr_add_str_field($where_arr,"teacher_id",$teacher_id);
    // $this->where_arr_add_int_or_idlist($where_arr,"parentid",$parentid);
    // if($rate_score == 1){
    //     $where_arr[] = "(rate_score >= 10 and rate_score < 20)";
    // }elseif($rate_score == 2){
    //     $where_arr[] = "(rate_score >= 20 and rate_score < 30)";
    // }elseif($rate_score == 3){
    //     $where_arr[] = "(rate_score >= 30 and rate_score < 40)";
    // }elseif($rate_score == 4){
    //     $where_arr[] = "(rate_score >= 40 and rate_score < 50)";
    // }elseif($rate_score == 5){
    //     $where_arr[] = "rate_score > 50 ";
    // }

}
