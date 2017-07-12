<?php
namespace App\Models\Zgen;
class z_t_test_subject_free_list extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_test_subject_free_list";


	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_userid='userid';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_test_subject_free_type='test_subject_free_type';

	/*int(11) */
	const C_test_subject_free_reason='test_subject_free_reason';
	function get_add_time($userid, $adminid ){
		return $this->field_get_value_2( $userid, $adminid  , self::C_add_time  );
	}
	function get_test_subject_free_type($userid, $adminid ){
		return $this->field_get_value_2( $userid, $adminid  , self::C_test_subject_free_type  );
	}
	function get_test_subject_free_reason($userid, $adminid ){
		return $this->field_get_value_2( $userid, $adminid  , self::C_test_subject_free_reason  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="userid";
        $this->field_id2_name="adminid";
        $this->field_table_name="db_weiyi.t_test_subject_free_list";
  }

    public function field_get_value_2(  $userid, $adminid,$field_name ) {
        return parent::field_get_value_2(  $userid, $adminid,$field_name ) ;
    }

    public function field_get_list_2( $userid,  $adminid,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $userid, $adminid,  $set_field_arr ) {
        return parent::field_update_list_2( $userid, $adminid,  $set_field_arr );
    }
    public function row_delete_2(  $userid ,$adminid ) {
        return parent::row_delete_2( $userid ,$adminid );
    }


}
/*
  CREATE TABLE `t_test_subject_free_list` (
  `adminid` int(11) NOT NULL COMMENT '销售adminid',
  `userid` int(11) NOT NULL,
  `add_time` int(11) NOT NULL COMMENT '废除时间',
  `test_subject_free_type` int(11) NOT NULL COMMENT '废除类型',
  `test_subject_free_reason` int(11) NOT NULL COMMENT '废除原因',
  PRIMARY KEY (`userid`,`adminid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
