<?php
namespace App\Models;
use \App\Enums as E;
class t_lesson_all_money_list extends \App\Models\Zgen\z_t_lesson_all_money_list
{
	public function __construct()
	{
		parent::__construct();
	}

    public function update_lesson_all_money_info($lessonid,$teacher_base_money,$teacher_lesson_count_money,$teacher_lesson_cost){
        $where_arr = [
            ["lessonid=%u",$lessonid,-1]
        ];
        $sql = $this->gen_sql_new("update %s set teacher_base_money=%u"
                                  .",teacher_lesson_count_money=%u"
                                  .",teacher_lesson_cost=%u"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$teacher_base_money
                                  ,$teacher_lesson_count_money
                                  ,$teacher_lesson_cost
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

}











