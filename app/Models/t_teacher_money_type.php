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
        $where_arr = [
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

    public function get_money_by_lesson_info($teacher_money_type,$level,$grade){
        $where_arr = [
            ["teacher_money_type=%u",$teacher_money_type,-1],
            ["level=%u",$level,-1],
            ["grade=%u",$grade,-1],
        ];
        $sql = $this->gen_sql_new("select money"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
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
            ["teacher_money_type=%u",$teacher_money_type,-1],
            ["level=%u",$level,-1],
            ["grade=%u",$grade,-1],
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
            ["teacher_money_type=%u",$teacher_money_type,-1],
            ["level=%u",$level,-1],
        ];
        $sql = $this->gen_sql_new("update %s set money = CASE "
                                  ." WHEN grade<106 THEN %s"
                                  ." WHEN grade in (106,201,202) THEN %s"
                                  ." WHEN grade in (203) THEN %s"
                                  ." WHEN grade in (301,302) THEN %s"
                                  ." WHEN grade in (303) THEN %s"
                                  ." END"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$money_101
                                  ,$money_106
                                  ,$money_203
                                  ,$money_301
                                  ,$money_303
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

    public function get_type_for_money($teacher_money_type, $teacher_type, $level) {
        $sql = $this->gen_sql_new("select type from %s where teacher_money_type = $teacher_money_type and teacher_type = $teacher_type and level = $level", self::DB_TABLE_NAME);
        return $this->main_get_value($sql);
    }
}