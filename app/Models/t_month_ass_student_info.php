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

    public function get_ass_month_info_lesson($month,$adminid=-1,$kpi_type=1){
        $where_arr=[
            ["adminid=%u",$adminid,-1],
            ["month=%u",$month,-1],
            ["kpi_type=%u",$kpi_type,-1]
        ];
        $sql = $this->gen_sql_new("select lesson_total lesson_count,read_student_new user_count,m.name assistant_nick "
                                  . "from %s ma left join %s m on ma.adminid = m.uid "
                                  ."where %s order by lesson_total desc",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_list_as_page($sql);
    }

    public function get_ass_hand_kk_num($month,$adminid=-1,$kpi_type=1){
        $where_arr=[
            ["adminid=%u",$adminid,-1],
            ["month=%u",$month,-1],
            ["kpi_type=%u",$kpi_type,-1]
        ];
        $sql = $this->gen_sql_new("select ma.adminid,ma.hand_kk_num,m.name,n.master_adminid,ma.month,ma.kpi_type "
                                  ."from %s ma left join %s m on ma.adminid = m.uid"
                                  ." left join %s u on ma.adminid = u.adminid"
                                  ." left join %s n on u.groupid = n.groupid"
                                  ." where %s ",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  t_admin_group_user::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  $where_arr);
        return $this->main_get_list_as_page($sql);
    }



}











