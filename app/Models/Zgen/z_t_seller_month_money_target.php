<?php
namespace App\Models\Zgen;
class z_t_seller_month_money_target extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_seller_month_money_target";


	/*int(11) */
	const C_adminid='adminid';

	/*varchar(20) */
	const C_month='month';

	/*int(11) */
	const C_money='money';

	/*varchar(5000) */
	const C_month_time='month_time';

	/*int(11) */
	const C_personal_money='personal_money';

	/*varchar(5000) */
	const C_leave_and_overtime='leave_and_overtime';

	/*int(11) */
	const C_test_lesson_count='test_lesson_count';
	function get_money($adminid, $month ){
		return $this->field_get_value_2( $adminid, $month  , self::C_money  );
	}
	function get_month_time($adminid, $month ){
		return $this->field_get_value_2( $adminid, $month  , self::C_month_time  );
	}
	function get_personal_money($adminid, $month ){
		return $this->field_get_value_2( $adminid, $month  , self::C_personal_money  );
	}
	function get_leave_and_overtime($adminid, $month ){
		return $this->field_get_value_2( $adminid, $month  , self::C_leave_and_overtime  );
	}
	function get_test_lesson_count($adminid, $month ){
		return $this->field_get_value_2( $adminid, $month  , self::C_test_lesson_count  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="adminid";
        $this->field_id2_name="month";
        $this->field_table_name="db_weiyi_admin.t_seller_month_money_target";
  }

    public function field_get_value_2(  $adminid, $month,$field_name ) {
        return parent::field_get_value_2(  $adminid, $month,$field_name ) ;
    }

    public function field_get_list_2( $adminid,  $month,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $adminid, $month,  $set_field_arr ) {
        return parent::field_update_list_2( $adminid, $month,  $set_field_arr );
    }
    public function row_delete_2(  $adminid ,$month ) {
        return parent::row_delete_2( $adminid ,$month );
    }


}
/*
  CREATE TABLE `t_seller_month_money_target` (
  `adminid` int(11) NOT NULL,
  `month` varchar(20) COLLATE latin1_bin NOT NULL,
  `money` int(11) NOT NULL COMMENT '团队目标',
  `month_time` varchar(5000) COLLATE latin1_bin NOT NULL COMMENT '每月时间安排',
  `personal_money` int(11) NOT NULL COMMENT ' 月度个人目标',
  `leave_and_overtime` varchar(5000) COLLATE latin1_bin NOT NULL COMMENT '请假及加班情况',
  `test_lesson_count` int(11) NOT NULL COMMENT '试听目标数',
  PRIMARY KEY (`adminid`,`month`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
