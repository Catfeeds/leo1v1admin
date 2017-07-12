<?php
namespace App\Models\Zgen;
class z_t_research_teacher_kpi_info extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_research_teacher_kpi_info";


	/*int(11) */
	const C_kid='kid';

	/*int(11) */
	const C_month='month';

	/*int(11) */
	const C_type_flag='type_flag';

	/*varchar(255) */
	const C_name='name';

	/*varchar(20) */
	const C_interview_time='interview_time';

	/*int(11) */
	const C_interview_lesson='interview_lesson';

	/*int(11) */
	const C_interview_order='interview_order';

	/*varchar(20) */
	const C_interview_per='interview_per';

	/*varchar(20) */
	const C_record_time='record_time';

	/*int(11) */
	const C_record_num='record_num';

	/*int(11) */
	const C_first_lesson='first_lesson';

	/*int(11) */
	const C_first_order='first_order';

	/*varchar(20) */
	const C_first_per='first_per';

	/*varchar(20) */
	const C_first_next_per='first_next_per';

	/*varchar(20) */
	const C_next_per='next_per';

	/*varchar(20) */
	const C_add_per='add_per';

	/*varchar(20) */
	const C_other_record_time='other_record_time';

	/*int(11) */
	const C_lesson_num='lesson_num';

	/*varchar(20) */
	const C_lesson_per='lesson_per';

	/*varchar(20) */
	const C_lesson_num_per='lesson_num_per';

	/*varchar(20) */
	const C_lesson_per_other='lesson_per_other';

	/*varchar(20) */
	const C_lesson_per_kk='lesson_per_kk';

	/*varchar(20) */
	const C_lesson_per_change='lesson_per_change';

	/*int(11) */
	const C_interview_time_score='interview_time_score';

	/*int(11) */
	const C_interview_per_score='interview_per_score';

	/*int(11) */
	const C_record_time_score='record_time_score';

	/*int(11) */
	const C_record_num_score='record_num_score';

	/*int(11) */
	const C_first_per_score='first_per_score';

	/*int(11) */
	const C_add_per_score='add_per_score';

	/*int(11) */
	const C_other_record_time_score='other_record_time_score';

	/*int(11) */
	const C_lesson_num_per_score='lesson_num_per_score';

	/*int(11) */
	const C_lesson_per_score='lesson_per_score';

	/*int(11) */
	const C_lesson_per_other_score='lesson_per_other_score';

	/*int(11) */
	const C_lesson_per_kk_score='lesson_per_kk_score';

	/*int(11) */
	const C_lesson_per_change_score='lesson_per_change_score';

	/*int(11) */
	const C_total_score='total_score';
	function get_type_flag($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_type_flag  );
	}
	function get_name($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_name  );
	}
	function get_interview_time($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_interview_time  );
	}
	function get_interview_lesson($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_interview_lesson  );
	}
	function get_interview_order($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_interview_order  );
	}
	function get_interview_per($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_interview_per  );
	}
	function get_record_time($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_record_time  );
	}
	function get_record_num($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_record_num  );
	}
	function get_first_lesson($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_first_lesson  );
	}
	function get_first_order($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_first_order  );
	}
	function get_first_per($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_first_per  );
	}
	function get_first_next_per($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_first_next_per  );
	}
	function get_next_per($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_next_per  );
	}
	function get_add_per($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_add_per  );
	}
	function get_other_record_time($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_other_record_time  );
	}
	function get_lesson_num($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_lesson_num  );
	}
	function get_lesson_per($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_lesson_per  );
	}
	function get_lesson_num_per($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_lesson_num_per  );
	}
	function get_lesson_per_other($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_lesson_per_other  );
	}
	function get_lesson_per_kk($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_lesson_per_kk  );
	}
	function get_lesson_per_change($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_lesson_per_change  );
	}
	function get_interview_time_score($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_interview_time_score  );
	}
	function get_interview_per_score($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_interview_per_score  );
	}
	function get_record_time_score($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_record_time_score  );
	}
	function get_record_num_score($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_record_num_score  );
	}
	function get_first_per_score($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_first_per_score  );
	}
	function get_add_per_score($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_add_per_score  );
	}
	function get_other_record_time_score($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_other_record_time_score  );
	}
	function get_lesson_num_per_score($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_lesson_num_per_score  );
	}
	function get_lesson_per_score($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_lesson_per_score  );
	}
	function get_lesson_per_other_score($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_lesson_per_other_score  );
	}
	function get_lesson_per_kk_score($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_lesson_per_kk_score  );
	}
	function get_lesson_per_change_score($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_lesson_per_change_score  );
	}
	function get_total_score($kid, $month ){
		return $this->field_get_value_2( $kid, $month  , self::C_total_score  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="kid";
        $this->field_id2_name="month";
        $this->field_table_name="db_weiyi.t_research_teacher_kpi_info";
  }

    public function field_get_value_2(  $kid, $month,$field_name ) {
        return parent::field_get_value_2(  $kid, $month,$field_name ) ;
    }

    public function field_get_list_2( $kid,  $month,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $kid, $month,  $set_field_arr ) {
        return parent::field_update_list_2( $kid, $month,  $set_field_arr );
    }
    public function row_delete_2(  $kid ,$month ) {
        return parent::row_delete_2( $kid ,$month );
    }


}
/*
  CREATE TABLE `t_research_teacher_kpi_info` (
  `kid` int(11) NOT NULL COMMENT 'uid/subject',
  `month` int(11) NOT NULL COMMENT '月度时间,以每月一日',
  `type_flag` int(11) NOT NULL COMMENT '类型 1个人,2学科',
  `name` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '用户名/学科名',
  `interview_time` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '审核时长',
  `interview_lesson` int(11) NOT NULL COMMENT '面试试听课数',
  `interview_order` int(11) NOT NULL COMMENT '面试签单数',
  `interview_per` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '面试签单率',
  `record_time` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '新入职反馈时长',
  `record_num` int(11) NOT NULL COMMENT '反馈数量',
  `first_lesson` int(11) NOT NULL COMMENT '首次试听课数',
  `first_order` int(11) NOT NULL COMMENT '首次试听签单数',
  `first_per` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '首次试听签单率',
  `first_next_per` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '反馈前签单率',
  `next_per` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '反馈后签单率',
  `add_per` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '反馈后提升度',
  `other_record_time` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '投诉处理时长',
  `lesson_num` int(11) NOT NULL COMMENT '试听课数(销售)',
  `lesson_per` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '签单率',
  `lesson_num_per` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '试听课数(销售)-占比',
  `lesson_per_other` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '签单率(转介绍)',
  `lesson_per_kk` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '签单率(扩课)',
  `lesson_per_change` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '签单率(换老师)',
  `interview_time_score` int(11) NOT NULL COMMENT '审核时长得分',
  `interview_per_score` int(11) NOT NULL COMMENT '面试签单率得分',
  `record_time_score` int(11) NOT NULL COMMENT '反馈时长得分',
  `record_num_score` int(11) NOT NULL COMMENT '反馈数量得分',
  `first_per_score` int(11) NOT NULL COMMENT '首次签单率得分',
  `add_per_score` int(11) NOT NULL COMMENT '反馈后提升度得分',
  `other_record_time_score` int(11) NOT NULL COMMENT '投诉处理时长得分',
  `lesson_num_per_score` int(11) NOT NULL COMMENT '试听课数(销售)-占比得分',
  `lesson_per_score` int(11) NOT NULL COMMENT '签单率得分',
  `lesson_per_other_score` int(11) NOT NULL COMMENT '签单率(转介绍)得分',
  `lesson_per_kk_score` int(11) NOT NULL COMMENT '签单率(扩课)得分',
  `lesson_per_change_score` int(11) NOT NULL COMMENT '签单率(换老师)得分',
  `total_score` int(11) NOT NULL COMMENT '总得分',
  PRIMARY KEY (`kid`,`month`),
  KEY `type_flag` (`type_flag`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
