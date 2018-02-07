<?php
namespace App\Models;
use App\Enums as E;
class t_teacher_spring extends \App\Models\Zgen\z_t_teacher_spring
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_total($teacherid,$start_time,$end_time){
    	$where_arr = [
            ["teacherid=%u",$teacherid,-1],
            ["add_time>%u",$start_time,-1],
            ["add_time<%u",$end_time,-1],
        ];
        $sql = $this->gen_sql_new(" select count(*) as total "
                                ." from %s "
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,$where_arr);
        return $this->main_get_value($sql);
    }

    public function get_last_rank($start_time){
    	$where_arr = [
            ["add_time>%u",$start_time,-1],
        ];
    	$sql = $this->gen_sql_new("select rank "
    							." from %s "
    							." where %s "
    							." order by id desc limit 1"
    							,self::DB_TABLE_NAME
    							,$where_arr);
    	return $this->main_get_value($sql);
    }
}
