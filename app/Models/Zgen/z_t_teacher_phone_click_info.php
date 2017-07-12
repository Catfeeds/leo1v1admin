<?php
namespace App\Models\Zgen;
class z_t_teacher_phone_click_info extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_phone_click_info";


	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_click_time='click_time';

	/*int(11) */
	const C_num='num';
	function get_num($adminid, $click_time ){
		return $this->field_get_value_2( $adminid, $click_time  , self::C_num  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="adminid";
        $this->field_id2_name="click_time";
        $this->field_table_name="db_weiyi.t_teacher_phone_click_info";
  }

    public function field_get_value_2(  $adminid, $click_time,$field_name ) {
        return parent::field_get_value_2(  $adminid, $click_time,$field_name ) ;
    }

    public function field_get_list_2( $adminid,  $click_time,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $adminid, $click_time,  $set_field_arr ) {
        return parent::field_update_list_2( $adminid, $click_time,  $set_field_arr );
    }
    public function row_delete_2(  $adminid ,$click_time ) {
        return parent::row_delete_2( $adminid ,$click_time );
    }


}
/*
  CREATE TABLE `t_teacher_phone_click_info` (
  `adminid` int(11) NOT NULL COMMENT 'adminid',
  `click_time` int(11) NOT NULL COMMENT '时间,以天计',
  `num` int(11) NOT NULL COMMENT '点击次数',
  PRIMARY KEY (`adminid`,`click_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
