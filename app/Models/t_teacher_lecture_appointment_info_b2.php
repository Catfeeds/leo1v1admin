<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_lecture_appointment_info_b2 extends \App\Models\Zgen\z_t_teacher_lecture_appointment_info
{
    public function __construct()
    {
        parent::__construct();
    }

    // 拉取招师人员名单(根据老师名拉取对应的招师人员)
    public function get_name_for_tea_name($name)
    {
        $where_arr = [
            ["ta.name= '%s' ",$name, '']
        ];
        $sql=$this->gen_sql_new("select m.name"
                                ." from %s ta left join %s m on ta.accept_adminid=m.uid "
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,t_manager_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }
}