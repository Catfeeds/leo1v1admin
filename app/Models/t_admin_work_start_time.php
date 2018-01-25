<?php
namespace App\Models;
use \App\Enums as E;
class t_admin_work_start_time extends \App\Models\Zgen\z_t_admin_work_start_time
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_today_work_start_time($adminid) {
        $log_date=strtotime( date("Y-m-d") ) ;
        $where_arr=[
            "adminid" =>$adminid,
            "log_date" =>$log_date,
        ];
        $sql=$this->gen_sql_new("select work_start_time  from %s where %s ",
                                self::DB_TABLE_NAME ,
                                $where_arr );
        return $this->main_get_value($sql);

    }
    public function check_existed($adminid,$log_date) {
        $where_arr=[
            "adminid" =>$adminid,
            "log_date" =>$log_date,
        ];
        $sql=$this->gen_sql_new("select count(*) from %s where %s ",
                                self::DB_TABLE_NAME ,
                                $where_arr );
        return $this->main_get_value($sql)>=1;

    }
    public function add_work_start_time($adminid) {
        $log_date=strtotime( date("Y-m-d") ) ;
        if (!$this->check_existed($adminid, $log_date)) {
            $this->row_insert([
                "adminid"         => $adminid,
                "log_date"        => $log_date,
                "work_start_time" => time(NULL),
            ]);
        }
    }

}
