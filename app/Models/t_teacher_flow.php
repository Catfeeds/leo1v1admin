<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_flow extends \App\Models\Zgen\z_t_teacher_flow
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_trial_lecture_pass_time($phone) {
        $where_arr = [['phone=%u',$phone,0]];
        $sql = $this->gen_sql_new("select trial_lecture_pass_time from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_id_for_phone($phone) {
        $where_arr = [["phone=%s",$phone,0]];
        $sql = $this->gen_sql_new("select teacherid from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

}











