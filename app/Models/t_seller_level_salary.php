<?php
namespace App\Models;
use \App\Enums as E;
class t_seller_level_salary extends \App\Models\Zgen\z_t_seller_level_salary
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_list($seller_level,$page_info){
        $where_arr = [
            ['seller_level =%u',$seller_level,-1],
        ];
        $sql = $this->gen_sql_new(
            " select * "
            ." from %s "
            ." where % s"
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

    public function get_row_by_seller_level_define_date($seller_level=-1,$define_date=-1,$base_salary=-1,$sup_salary=-1,$per_salary=-1){
        $where_arr = [
            ['seller_level=%u',$seller_level,-1],
            ['define_date=%u',$define_date,-1],
            ['base_salary=%u',$base_salary,-1],
            ['sup_salary=%u',$sup_salary,-1],
            ['per_salary=%u',$per_salary,-1],
        ];
        $sql = $this->gen_sql_new(
            " select * "
            ." from %s "
            ." where % s limit 1 "
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);
    }

}
