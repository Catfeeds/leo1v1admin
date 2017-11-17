<?php
namespace App\Models;
use \App\Enums as E;
class t_rule_detail_info extends \App\Models\Zgen\z_t_rule_detail_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_rule_detail($rule_id){
        $where_arr = ["rule_id=$rule_id"];
        $sql = $this->gen_sql_new(
            "select detail_id,level,name,content,deduct_marks,punish_type,add_punish,rank_num,create_time from %s where %s"
            ." order by level,rank_num"
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_max_rank_num($rule_id,$level){
        $where_arr = [
            ['rule_id=%u', $rule_id,-1],
            ['level=%u', $level,-1],
        ];
        $sql = $this->gen_sql_new("select max(rank_num) from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);

    }

    public function update_rank_num($rule_id,$level,$old_num,$new_num){
        $where_arr = [
            ['rule_id=%u', $rule_id,-1],
            ['level=%u', $level,-1],
            ['rank_num=%u', $old_num,-1],
        ];

        $sql = $this->gen_sql_new("update %s set rank_num=$new_num where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }
    public function get_detail_id_by_info($rule_id,$level,$rank_num,$type){
        $where_arr = [
            ['rule_id=%u', $rule_id,-1],
            ['level=%u', $level,-1],
        ];
        if($type == 'up'){
            $where_arr[] = ["rank_num<%u",$rank_num,-1];
            $sql = $this->gen_sql_new("select max(detail_id) from %s where %s"
                                      ,self::DB_TABLE_NAME
                                      ,$where_arr
            );
       } else {
            $where_arr[] = ["rank_num>%u",$rank_num,-1];
            $sql = $this->gen_sql_new("select min(detail_id) from %s where %s"
                                      ,self::DB_TABLE_NAME
                                      ,$where_arr
            );
       }

        return $this->main_get_value($sql);
    }
}
