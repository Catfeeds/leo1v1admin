<?php
namespace App\Models;
class t_teacher_closest_grade extends \App\Models\Zgen\z_t_teacher_closest_grade
{
	public function __construct()
	{
		parent::__construct();
	}
    
    public function get_grade_info($teacherid)
    {
        $sql = $this->gen_sql("select teacherid, grade from %s where teacherid = %u ",
                              self::DB_TABLE_NAME,
                              $teacherid
        );
        return $this->main_get_list($sql,function($item){
            return $item["grade"];
        });
    }
}











