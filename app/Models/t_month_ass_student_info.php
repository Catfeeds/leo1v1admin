<?php
namespace App\Models;
use \App\Enums as E;
class t_month_ass_student_info extends \App\Models\Zgen\z_t_month_ass_student_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_ass_month_info($month,$adminid=-1,$kpi_type=1){
        $where_arr=[
            ["adminid=%u",$adminid,-1],
            ["month=%u",$month,-1],
            ["kpi_type=%u",$kpi_type,-1]
        ];
        $sql = $this->gen_sql_new("select * from %s where %s group by adminid ",self::DB_TABLE_NAME,$where_arr);
        return $this->main_get_list($sql,function($item){
            return $item["adminid"];
        });
    }


    public function get_field_update_arr($adminid,$month,$kpi_type,$update_arr){

        $set_field_list_str= $this->get_sql_set_str($update_arr);
        $sql=sprintf("update %s set  %s  where  month=%u and adminid=%u and kpi_type= %u ",
                     self::DB_TABLE_NAME ,
                     $set_field_list_str,
                     $month,
                     $adminid,
                     $kpi_type
        );

        return $this->main_update($sql);
    }

}











