<?php
namespace App\Models;
use \App\Enums as E;
class t_new_tea_entry extends \App\Models\Zgen\z_t_new_tea_entry
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_total() {
        $where_arr = ["subject = -2"];
        $sql = $this->gen_sql_new("select interview_pass_num as sum,train_attend_new_tea_num train_tea_sum,train_qual_new_tea_num train_qual_sum,imit_listen_sched_lesson_num imit_sum,imit_listen_attend_lesson_num attend_sum,imit_listen_pass_lesson_num adopt_sum ,grade,subject from %s where %s order by add_time desc limit 14",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_subject_list() {
        $where_arr = ["subject != '' "," subject != -2"];
        $sql = $this->gen_sql_new("select interview_pass_num as sum,train_attend_new_tea_num train_tea_sum,train_qual_new_tea_num train_qual_sum,imit_listen_sched_lesson_num imit_sum,imit_listen_attend_lesson_num attend_sum,imit_listen_pass_lesson_num adopt_sum ,grade,subject from %s where %s order by add_time desc limit 14",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function get_identity_total() {
        $where_arr = ["identity = -2"];
        $sql = $this->gen_sql_new("select interview_pass_num sum,train_attend_new_tea_num train_tea_sum,train_qual_new_tea_num train_qual_sum,imit_listen_sched_lesson_num imit_sum,imit_listen_attend_lesson_num attend_sum,imit_listen_pass_lesson_num adopt_sum,identity from %s where %s order by add_time desc limit 14",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_identity_list() {
        $where_arr = ["identity != '' ", "identity != -2"];
        $sql = $this->gen_sql_new("select interview_pass_num sum,train_attend_new_tea_num train_tea_sum,train_qual_new_tea_num train_qual_sum,imit_listen_sched_lesson_num imit_sum,imit_listen_attend_lesson_num attend_sum,imit_listen_pass_lesson_num adopt_sum,identity from %s where %s order by add_time desc limit 14",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }
    public function add_data() {
        // 添加数据

    }

}











