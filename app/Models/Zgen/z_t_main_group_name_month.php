<?php
namespace App\Models\Zgen;
class z_t_main_group_name_month extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_main_group_name_month";


	/*int(11) */
	const C_groupid='groupid';

	/*int(11) */
	const C_month='month';

	/*int(11) */
	const C_main_type='main_type';

	/*varchar(255) */
	const C_group_name='group_name';

	/*int(11) */
	const C_master_adminid='master_adminid';

	/*int(11) */
	const C_main_assign_percent='main_assign_percent';
	function get_main_type($groupid, $month ){
		return $this->field_get_value_2( $groupid, $month  , self::C_main_type  );
	}
	function get_group_name($groupid, $month ){
		return $this->field_get_value_2( $groupid, $month  , self::C_group_name  );
	}
	function get_master_adminid($groupid, $month ){
		return $this->field_get_value_2( $groupid, $month  , self::C_master_adminid  );
	}
	function get_main_assign_percent($groupid, $month ){
		return $this->field_get_value_2( $groupid, $month  , self::C_main_assign_percent  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="groupid";
        $this->field_id2_name="month";
        $this->field_table_name="db_weiyi_admin.t_main_group_name_month";
  }

    public function field_get_value_2(  $groupid, $month,$field_name ) {
        return parent::field_get_value_2(  $groupid, $month,$field_name ) ;
    }

    public function field_get_list_2( $groupid,  $month,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $groupid, $month,  $set_field_arr ) {
        return parent::field_update_list_2( $groupid, $month,  $set_field_arr );
    }
    public function row_delete_2(  $groupid ,$month ) {
        return parent::row_delete_2( $groupid ,$month );
    }


}
/*
  CREATE TABLE `t_main_group_name_month` (
  `groupid` int(11) NOT NULL COMMENT 'groupid',
  `month` int(11) NOT NULL COMMENT '月度时间,以每月一日',
  `main_type` int(11) NOT NULL COMMENT '部门类型',
  `group_name` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '组名',
  `master_adminid` int(11) NOT NULL COMMENT '助长id',
  `main_assign_percent` int(11) NOT NULL COMMENT '分配比例',
  PRIMARY KEY (`groupid`,`month`),
  KEY `main_type` (`main_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
