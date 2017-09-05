<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_day_luck_draw extends \App\Models\Zgen\z_t_teacher_day_luck_draw
{
    public function __construct()
    {
        parent::__construct();
    }

    public function compute_time($teacherid){
        $sql = $this->gen_sql_new(" select count(*) as num from %s td where td.teacherid = $teacherid  "
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

    public function get_total_money(){
        $sql = $this->gen_sql_new(" select sum(td.money/100) from %s td  "
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }


}
