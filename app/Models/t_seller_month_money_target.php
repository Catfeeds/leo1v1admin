<?php
namespace App\Models;
use \App\Enums as E;
/**
 * @property t_admin_main_group_name  $t_admin_main_group_name
 */

class t_seller_month_money_target extends \App\Models\Zgen\z_t_seller_month_money_target
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_seller_month_time_info($start_time){
        $where_arr=[
            ["month = '%s'",$start_time,-1]
        ];
        $sql = $this->gen_sql_new("select month_time,month,money,personal_money,adminid,leave_and_overtime from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['adminid'];
        });
    }

    public function update_money_personal($month){
        $where_arr=[
            ["month = '%s'",$month,-1]
        ];
        $sql = $this->gen_sql_new("update %s set personal_money = money where %s ",self::DB_TABLE_NAME,$where_arr);
        return $this->main_update($sql);
    }

    public function get_seller_num_day($time,$adminid_list=[],$adminid_all=[]){
        $where_arr=[];
        $this->where_arr_adminid_in_list($where_arr,"adminid",$adminid_list);
        $this->where_arr_adminid_in_list($where_arr,"adminid",$adminid_all);
        $day = date("Y-m-d",$time);
        $month = date("Y-m-01",$time);
        $sql = $this->gen_sql_new("select month_time,leave_and_overtime from %s where month='%s' and adminid not in (60,68) and %s",
                                  self::DB_TABLE_NAME,
                                  $month,
                                  $where_arr
        );
        $time_info = $this->main_get_list($sql);
        $num = 0;
        foreach($time_info as $item){
            $month_time = json_decode($item['month_time'],true);
            if(!empty($month_time)){
                foreach($month_time as $val){
                    if(substr($val[0],11,1) ==1 && substr($val[0],0,10) == $day ){
                        $num++;
                    }
                }
                $leave_and_overtime = json_decode($item['leave_and_overtime'],true);
                if(!empty($leave_and_overtime)){
                    foreach($leave_and_overtime as $v){
                        if(substr($v[0],11,1) ==2 && substr($v[0],0,10) == $day){
                            $num--;
                        }
                        if(substr($v[0],11,1) ==3 && substr($v[0],0,10) == $day ){
                            $num++;
                        }

                    }
                }

            }

        }
        return $num;
    }
    public function get_seller_list_day($time){
        $where_arr=[];
        $day = date("Y-m-d",$time);
        $month = date("Y-m-01",$time);
        $sql = $this->gen_sql_new(
            "select adminid, month_time,leave_and_overtime from %s "
            ."where month='%s' and adminid not in (60,68) and %s",
            self::DB_TABLE_NAME,
            $month,
            $where_arr
        );
        $time_info = $this->main_get_list($sql);
        $num = 0;
        $admin_list=[];
        foreach($time_info as $item){
            $month_time = json_decode($item['month_time'],true);
            $adminid=$item["adminid"];
            if(!empty($month_time)){
                $add_flag=false;
                foreach($month_time as $val){
                    if(substr($val[0],11,1) ==1 && substr($val[0],0,10) == $day ){
                        $add_flag=true;
                    }
                }
                $leave_and_overtime = json_decode($item['leave_and_overtime'],true);
                if(!empty($leave_and_overtime)){
                    foreach($leave_and_overtime as $v){
                        if(substr($v[0],11,1) ==2 && substr($v[0],0,10) == $day){
                            $add_flag=false;
                        }
                        if(substr($v[0],11,1) ==3 && substr($v[0],0,10) == $day ){
                            $add_flag=true;
                        }

                    }
                }
                if ($add_flag) {
                    $admin_list[]=$adminid;
                }

            }

        }
        return $admin_list;
    }

    public function get_all_target($month){
        $sql = $this->gen_sql_new(" select  ");
    }


}
