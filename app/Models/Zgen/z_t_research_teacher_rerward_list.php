<?php
namespace App\Models\Zgen;
class z_t_research_teacher_rerward_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_research_teacher_rerward_list";


	/*int(11) */
	const C_courseid='courseid';

	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_reward='reward';

	/*int(11) */
	const C_first_reward='first_reward';
	function get_adminid($courseid ){
		return $this->field_get_value( $courseid , self::C_adminid );
	}
	function get_add_time($courseid ){
		return $this->field_get_value( $courseid , self::C_add_time );
	}
	function get_teacherid($courseid ){
		return $this->field_get_value( $courseid , self::C_teacherid );
	}
	function get_reward($courseid ){
		return $this->field_get_value( $courseid , self::C_reward );
	}
	function get_first_reward($courseid ){
		return $this->field_get_value( $courseid , self::C_first_reward );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="courseid";
        $this->field_table_name="db_weiyi.t_research_teacher_rerward_list";
  }
    public function field_get_list( $courseid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $courseid, $set_field_arr) {
        return parent::field_update_list( $courseid, $set_field_arr);
    }


    public function field_get_value(  $courseid, $field_name ) {
        return parent::field_get_value( $courseid, $field_name);
    }

    public function row_delete(  $courseid) {
        return parent::row_delete( $courseid);
    }

}

/*
  CREATE TABLE `t_research_teacher_rerward_list` (
  `courseid` int(11) NOT NULL COMMENT '课程包id',
  `adminid` int(11) NOT NULL COMMENT '教研老师adminid',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `teacherid` int(11) NOT NULL COMMENT '签单老师id',
  `reward` int(11) NOT NULL COMMENT '奖励',
  `first_reward` int(11) NOT NULL COMMENT '第一次转化奖',
  PRIMARY KEY (`courseid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
