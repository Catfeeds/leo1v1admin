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

    public function get_all_list($where=[]) {
        $sql = $this->gen_sql_new("select teacherid,phone,trial_lecture_pass_time,simul_test_lesson_pass_time,train_through_new_time from %s where %s",self::DB_TABLE_NAME,$where);
        return $this->main_get_list($sql, function( $item) {
            return $item['teacherid'];
        });
    }

    public function get_tea_list($start_time, $end_time) {
        $where_arr = [
            ['tf.trial_lecture_pass_time>%u', $start_time, 0],
            ['tf.trial_lecture_pass_time<%u', $end_time, 0],
            'tf.subject>0',
            'tf.grade>0'
        ];
        $sql = $this->gen_sql_new("select tf.subject,tf.grade,tf.teacherid,tf.trial_lecture_pass_time,"
                                  ." tf.simul_test_lesson_pass_time,tf.train_through_new_time,t.identity "
                                  ." from %s tf "
                                  ." left join %s t on tf.teacherid=t.teacherid "
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql, function( $item) {
            return $item['teacherid'];
        });
    }

    public function get_tea_info($start_time, $end_time, $type='') {
        $where_arr = [
            ['tf.trial_lecture_pass_time>%u', $start_time, 0],
            ['tf.trial_lecture_pass_time<%u', $end_time, 0],
            'tf.subject>0',
            'tf.grade>0'
        ];
        if ($type) {
            $column = 'tf.subject,tf.grade';
            $group = ' group by tf.subject,tf.grade';
        } else {
            $column = 'tf.identity';
            $group = ' group by tf.identity';
        }
        $sql = $this->gen_sql_new("select %s,sum(tf.trial_lecture_pass_time>0) sum,"
                                  ." sum(tf.simul_test_lesson_pass_time>0) train_qual_sum,(tf.train_through_new_time>0) adopt_sum"
                                  ." from %s tf "
                                  ." left join %s t on tf.teacherid=t.teacherid "
                                  ." where %s %s",
                                  $column,
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr,
                                  $group
        );
        dd($sql);
        return $this->main_get_list($sql);

    }

    public function get_tea_list_for_subject($start_time, $end_time) {
        $where_arr = [
            ['trial_lecture_pass_time>%u',$start_time,0],
            ['trial_lecture_pass_time<%u',$end_time,0]
        ];
        $sql = $this->gen_sql_new("select subject,grade,count(*) sum,sum(train_through_new_time>0) train_qual_sum,sum(simul_test_lesson_pass_time) adopt_time"
                                  ." from %s "
                                  ."where %s group by subject,grade",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
    }

}


