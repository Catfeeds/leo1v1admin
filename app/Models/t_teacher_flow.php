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
        $where_arr = [
            ["phone='%s'",$phone,0]
        ];
        $sql = $this->gen_sql_new("select teacherid from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_all_trial_time() {
        $sql = $this->gen_sql_new("select teacherid,trial_lecture_pass_time from %s",self::DB_TABLE_NAME);
        return $this->main_get_list($sql, function( $item) {
            return $item['teacherid'];
        });
    }

    public function get_all_list($where) {
        $sql = $this->gen_sql_new("select teacherid,phone,trial_lecture_pass_time,simul_test_lesson_pass_time,train_through_new_time from %s where %s",self::DB_TABLE_NAME,$where);
        return $this->main_get_list($sql, function( $item) {
            return $item['teacherid'];
        });
    }

    public function get_tea_list($start_time, $end_time) {
        $where_arr = [
            ['tf.trial_lecture_pass_time>%u', $start_time, 0],
            ['tf.trial_lecture_pass_time<%u', $end_time, 0],
            // 'tf.subject>0'
        ];
        $sql = $this->gen_sql_new("select tf.subject,tf.grade,tf.teacherid,tf.trial_lecture_pass_time,tf.simul_test_lesson_pass_time,tf.train_through_new_time,t.identity from %s tf left join %s t on tf.teacherid=t.teacherid where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

}


