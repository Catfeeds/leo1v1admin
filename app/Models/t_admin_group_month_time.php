<?php
namespace App\Models;
use \App\Enums as E;
class t_admin_group_month_time extends \App\Models\Zgen\z_t_admin_group_month_time
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_month_money_by_month($time,$groupid_list=[]){
        $where_arr=[];
        if (count ($groupid_list)>0)  {
            $where_arr[]=sprintf(  "groupid in (%s) ",  join(",", $groupid_list ));
        }

        $month = date("Y-m-01",$time);
        $all_money= 0;
        $sql = $this->gen_sql_new("select sum(month_money) from %s  where %s and month='%s'",
                                  self::DB_TABLE_NAME,
                                  $where_arr,
                                  $month
        );
        return $this->main_get_value($sql);
    }
    public function get_month_monet_new($month){
        $sql = $this->gen_sql_new("select month_money,month,a.groupid,group_name from %s a left join %s g on a.groupid = g.groupid where  month='%s'",
                                  self::DB_TABLE_NAME,
                                  t_admin_group_name::DB_TABLE_NAME,
                                  $month
        );
        return $this->main_get_list($sql);

    }

    public function get_all_target($month){

    }
}
