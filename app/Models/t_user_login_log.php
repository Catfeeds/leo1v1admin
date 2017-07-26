<?php
namespace App\Models;
use \App\Enums as E;
class t_user_login_log extends \App\Models\Zgen\z_t_user_login_log
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login_list($page_info,$userid,$dymanic_flag){
        $where_arr=[
            ["dymanic_flag=%u",$dymanic_flag,-1],
            ["userid=%u",$userid,0],
        ];
        $sql = $this->gen_sql_new("select * "
                                  ." from %s "
                                  ." where %s "
                                  ." order by login_time desc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }




}
