<?php
namespace App\Models;
use \App\Enums as E;
class t_jw_teacher_month_plan_lesson_info extends \App\Models\Zgen\z_t_jw_teacher_month_plan_lesson_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_info_by_month($month){
        $sql = $this->gen_sql_new("select adminid accept_adminid,all_plan all_count,all_plan_done set_count,un_plan un_count,gz_count,back_count,plan_per set_per,tran_count tra_count,tran_count_seller tra_count_seller,tran_count_ass tra_count_ass,tran_per tra_per_str,m.account  "
                                  ." from %s j left join  %s m on j.adminid = m.uid"
                                  ." where month =%u",
                                  self::DB_TABLE_NAME,
                                  t_manager_info::DB_TABLE_NAME,
                                  $month
        );
        return $this->main_get_list($sql);
    }

    public function get_info_by_month_new($month){
        $sql = $this->gen_sql_new("select adminid ,tran_count ,tran_count_seller ,tran_count_ass ,tran_per,tran_count_green,ass_tran_green_count ,seller_tran_green_count,tran_count_seller_top "
                                  ." from %s "
                                  ." where month =%u",
                                  self::DB_TABLE_NAME,
                                  $month
        );
        return $this->main_get_list($sql,function($item){
            return $item["adminid"];
        });
    }

}
