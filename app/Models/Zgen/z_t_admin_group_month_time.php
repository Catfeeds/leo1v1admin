<?php
namespace App\Models\Zgen;
class z_t_admin_group_month_time extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_admin_group_month_time";


	/*int(11) */
	const C_groupid='groupid';

	/*varchar(20) */
	const C_month='month';

	/*varchar(5000) */
	const C_month_time='month_time';

	/*int(11) */
	const C_month_money='month_money';
	function get_month_time($groupid, $month ){
		return $this->field_get_value_2( $groupid, $month  , self::C_month_time  );
	}
	function get_month_money($groupid, $month ){
		return $this->field_get_value_2( $groupid, $month  , self::C_month_money  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="groupid";
        $this->field_id2_name="month";
        $this->field_table_name="db_weiyi_admin.t_admin_group_month_time";
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
  CREATE TABLE `t_admin_group_month_time` (
  `groupid` int(11) NOT NULL,
  `month` varchar(20) COLLATE latin1_bin NOT NULL,
  `month_time` varchar(5000) COLLATE latin1_bin NOT NULL,
  `month_money` int(11) NOT NULL COMMENT ' 月度团队目标',
  PRIMARY KEY (`groupid`,`month`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
