<?php
namespace App\Models;
class t_news_ad_info extends \App\Models\Zgen\z_t_news_ad_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_ad_info_list($start,$end,$status,$news_ad_info,$page_num){
        $where_str=$this->where_str_gen([
            [ "status=%d", $status, -1 ],
        ]);
        if($news_ad_info!= ""){
            $where_str .= " and ( title like '%%".$this->ensql($news_ad_info)."%%') ";
        }
        $sql=$this->gen_sql("select * from %s where %s and start_time>%u and start_time<%u "
                            ,self::DB_TABLE_NAME
                            ,$where_str
                            ,$start
                            ,$end
        );

        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function add_ad_info($opt_type,$id,$start,$end,$ad_url,$img_url,$url,$title,$intro,$status){
        if($opt_type == 'add'){
            $this->row_insert([
                self::C_ad_url     => $ad_url,
                self::C_img_url    => $img_url,
                self::C_url        => $url,
                self::C_title      => $title,
                self::C_intro      => $intro,
                self::C_status     => $status,
                self::C_start_time => $start,
                self::C_end_time   => $end,
            ]);
        }else{
            $set_field_arr=array(
                self::C_ad_url     => $ad_url,
                self::C_ad_url     => $ad_url,
                self::C_img_url    => $img_url,
                self::C_url        => $url,
                self::C_title      => $title,
                self::C_intro      => $intro,
                self::C_status     => $status,
                self::C_start_time => $start,
                self::C_end_time   => $end,
            );
            $this->field_update_list($id,$set_field_arr);
        }
    }
    
}











