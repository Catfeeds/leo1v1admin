<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_resource_dir extends \App\Models\Zgen\z_t_teacher_resource_dir
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_name_by_ids($dir_id, $teacherid){
        $where_arr = [
            ["dir_id=%u", $dir_id, 0],
            ["teacherid=%u", $teacherid, -1],
            "is_del=0",
        ];

        $sql = $this->gen_sql_new("select name from %s where %s"
                                  , self::DB_TABLE_NAME
                                  , $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_par_dir( $dir_id){
        $where_arr = [
            ["d.dir_id=%u", $dir_id, 0],
            "dd.is_del=0",
        ];

        $sql = $this->gen_sql_new("select dd.dir_id,dd.name from %s d left join %s dd on dd.dir_id=d.pid where %s"
                                  , self::DB_TABLE_NAME
                                  , self::DB_TABLE_NAME
                                  , $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_next_dir($teacherid, $pid){
        $where_arr = [
            ["pid=%u", $pid, -1],
            ["teacherid=%u", $teacherid, -1],
            "is_del=0",
        ];

        $sql = $this->gen_sql_new("select dir_id,name,create_time from %s where %s"
                                  , self::DB_TABLE_NAME
                                  , $where_arr
        );
        return $this->main_get_list($sql);

    }

    public function get_dir_id_by_pid($pid){
        $where_arr = [
            ["pid=%u", $pid, -1],
            "is_del=0",
        ];

        $sql = $this->gen_sql_new("select dir_id from %s where %s", self::DB_TABLE_NAME, $where_arr);
        return $this->main_get_list($sql);
    }

    public function get_tea_all_dir($teacherid){
        $where_arr = [
            ["teacherid=%u", $teacherid, -1],
            "is_del=0",
        ];

        $sql = $this->gen_sql_new("select dir_id,name,pid from %s where %s order by dir_id", self::DB_TABLE_NAME, $where_arr);
        return $this->main_get_list($sql);
    }
}
