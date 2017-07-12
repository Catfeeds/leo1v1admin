<?php
namespace App\Models\Zgen;
class z_t_assistant_month_target extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_assistant_month_target";


	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_month='month';

	/*varchar(255) */
	const C_lesson_target='lesson_target';
	function get_lesson_target($adminid, $month ){
		return $this->field_get_value_2( $adminid, $month  , self::C_lesson_target  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="adminid";
        $this->field_id2_name="month";
        $this->field_table_name="db_weiyi_admin.t_assistant_month_target";
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
  CREATE TABLE `t_assistant_month_target` (
  `adminid` int(11) NOT NULL,
  `month` int(11) NOT NULL COMMENT '月份,以每月1日',
  `lesson_target` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '月度系数',
  PRIMARY KEY (`adminid`,`month`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
