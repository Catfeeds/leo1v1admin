<?php
namespace App\Models\Zgen;
class z_t_month_ass_warning_student_info extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_month_ass_warning_student_info";


	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_month='month';

	/*int(11) */
	const C_userid='userid';

	/*int(11) */
	const C_groupid='groupid';

	/*varchar(255) */
	const C_group_name='group_name';

	/*int(11) */
	const C_left_count='left_count';

	/*int(11) */
	const C_end_week='end_week';

	/*int(11) */
	const C_ass_renw_flag='ass_renw_flag';

	/*varchar(255) */
	const C_no_renw_reason='no_renw_reason';

	/*int(11) */
	const C_renw_price='renw_price';

	/*int(11) */
	const C_renw_week='renw_week';

	/*int(11) */
	const C_master_renw_flag='master_renw_flag';

	/*varchar(255) */
	const C_master_no_renw_reason='master_no_renw_reason';
	function get_adminid($userid, $month ){
		return $this->field_get_value_2( $userid, $month  , self::C_adminid  );
	}
	function get_groupid($userid, $month ){
		return $this->field_get_value_2( $userid, $month  , self::C_groupid  );
	}
	function get_group_name($userid, $month ){
		return $this->field_get_value_2( $userid, $month  , self::C_group_name  );
	}
	function get_left_count($userid, $month ){
		return $this->field_get_value_2( $userid, $month  , self::C_left_count  );
	}
	function get_end_week($userid, $month ){
		return $this->field_get_value_2( $userid, $month  , self::C_end_week  );
	}
	function get_ass_renw_flag($userid, $month ){
		return $this->field_get_value_2( $userid, $month  , self::C_ass_renw_flag  );
	}
	function get_no_renw_reason($userid, $month ){
		return $this->field_get_value_2( $userid, $month  , self::C_no_renw_reason  );
	}
	function get_renw_price($userid, $month ){
		return $this->field_get_value_2( $userid, $month  , self::C_renw_price  );
	}
	function get_renw_week($userid, $month ){
		return $this->field_get_value_2( $userid, $month  , self::C_renw_week  );
	}
	function get_master_renw_flag($userid, $month ){
		return $this->field_get_value_2( $userid, $month  , self::C_master_renw_flag  );
	}
	function get_master_no_renw_reason($userid, $month ){
		return $this->field_get_value_2( $userid, $month  , self::C_master_no_renw_reason  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="userid";
        $this->field_id2_name="month";
        $this->field_table_name="db_weiyi.t_month_ass_warning_student_info";
  }

    public function field_get_value_2(  $userid, $month,$field_name ) {
        return parent::field_get_value_2(  $userid, $month,$field_name ) ;
    }

    public function field_get_list_2( $userid,  $month,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $userid, $month,  $set_field_arr ) {
        return parent::field_update_list_2( $userid, $month,  $set_field_arr );
    }
    public function row_delete_2(  $userid ,$month ) {
        return parent::row_delete_2( $userid ,$month );
    }


}
/*
  CREATE TABLE `t_month_ass_warning_student_info` (
  `adminid` int(11) NOT NULL COMMENT '助教adminid',
  `month` int(11) NOT NULL COMMENT '每周第一天',
  `userid` int(11) NOT NULL COMMENT 'userid',
  `groupid` int(11) NOT NULL COMMENT '分组groupid',
  `group_name` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '组名',
  `left_count` int(11) NOT NULL COMMENT '剩余课时',
  `end_week` int(11) NOT NULL COMMENT '预计结课周',
  `ass_renw_flag` int(11) NOT NULL COMMENT '0 未设置,1 续费,2 不续费,3 联络或考虑',
  `no_renw_reason` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '未续费原因',
  `renw_price` int(11) NOT NULL COMMENT '续费金额',
  `renw_week` int(11) NOT NULL COMMENT '计划续费周',
  `master_renw_flag` int(11) NOT NULL COMMENT '组长确认是否续费 ',
  `master_no_renw_reason` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '组长确认未续费原因',
  PRIMARY KEY (`userid`,`month`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
