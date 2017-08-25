<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_reward_rule_list extends \App\Models\Zgen\z_t_teacher_reward_rule_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_teacher_reward_rule_list($reward_count_type,$rule_type){
        $where_arr = [
            ["reward_count_type=%u",$reward_count_type,-1],
            ["rule_type=%u",$rule_type,-1]
        ];
        $sql = $this->gen_sql_new("select reward_count_type,rule_type,num,money as money"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function update_reward_rule($reward_count_type,$rule_type,$num,$old_num,$money){
        $where_arr = [
            ["reward_count_type=%u",$reward_count_type,-1],
            ["rule_type=%u",$rule_type,-1],
            ["num=%u",$old_num,-1],
        ];
        $sql = $this->gen_sql_new("update %s set num=%u,money=%u"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$num
                                  ,$money
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function check_reward_rule_is_exists($reward_count_type,$rule_type,$num){
        $where_arr = [
            ["reward_count_type=%u",$reward_count_type,-1],
            ["rule_type=%u",$rule_type,-1],
            ["num=%u",$num,-1],
        ];
        $sql = $this->gen_sql_new("select 1 "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_reward_rule_list(){
        $sql = $this->gen_sql_new("select reward_count_type,rule_type,num,money"
                                  ." from %s "
                                  ." order by reward_count_type asc,rule_type asc,num asc"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);

    }
}
