<?php
namespace App\Models\Zgen;
class z_t_group_user_month  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_group_user_month";


	/*int(11) */
	const C_groupid='groupid';

	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_month='month';

	/*varchar(255) */
	const C_assign_percent='assign_percent';
	function get_adminid($groupid ){
		return $this->field_get_value( $groupid , self::C_adminid );
	}
	function get_month($groupid ){
		return $this->field_get_value( $groupid , self::C_month );
	}
	function get_assign_percent($groupid ){
		return $this->field_get_value( $groupid , self::C_assign_percent );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="groupid";
        $this->field_table_name="db_weiyi_admin.t_group_user_month";
  }
    public function field_get_list( $groupid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $groupid, $set_field_arr) {
        return parent::field_update_list( $groupid, $set_field_arr);
    }


    public function field_get_value(  $groupid, $field_name ) {
        return parent::field_get_value( $groupid, $field_name);
    }

    public function row_delete(  $groupid) {
        return parent::row_delete( $groupid);
    }

}

/*
  CREATE TABLE `t_group_user_month` (
  `groupid` int(11) NOT NULL COMMENT 'groupid',
  `adminid` int(11) NOT NULL COMMENT '后台adminid',
  `month` int(11) NOT NULL COMMENT '月度时间,以每月一日',
  `assign_percent` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '分配比例',
  PRIMARY KEY (`groupid`,`adminid`,`month`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
