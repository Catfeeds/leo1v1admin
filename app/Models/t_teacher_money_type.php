<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_money_type extends \App\Models\Zgen\z_t_teacher_money_type
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_teacher_money_type($teacher_money_type,$level){
        $where_arr=[
            ["teacher_money_type=%u",$teacher_money_type,-1],
            ["level=%u",$level,-1],
        ];
        $sql = $this->gen_sql_new(" select grade,money,type "
                                  ." from %s"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['grade'];
        });
    }

    public function get_teacher_money_type_list($type,$level){
        $where_arr=[
            ["teacher_money_type=%d",$type,-1],
            ["level=%d",$level,-1],
        ];
        $sql=$this->gen_sql_new("select grade,money"
                                ." from %s "
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_type($type,$level){
        $where_arr=[
            ["teacher_money_type=%d",$type,-1],
            ["level=%d",$level,-1],
        ];
        $sql=$this->gen_sql_new("select type from %s where %s "
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function check_is_exists($teacher_money_type,$level,$grade){
        $where_arr = [
            ["teacher_money_type=%u",$teacher_money_type,0],
            ["level=%u",$level,0],
            ["grade=%u",$grade,0],
        ];
        $sql = $this->gen_sql_new("select 1 "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function update_teacher_money_type($teacher_money_type,$level,$money_101,$money_106,$money_203,$money_301,$money_303){
        $where_arr = [
            ["teacher_money_type=%u",$teacher_money_type,0],
            ["level=%u",$level,0],
        ];
        $where_arr_101 = $where_arr;
        $where_arr_101 = array_push($where_arr,"grade<106");

        $sql = $this->gen_sql_new("update %s set "
                                  ." money "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);

    }
}