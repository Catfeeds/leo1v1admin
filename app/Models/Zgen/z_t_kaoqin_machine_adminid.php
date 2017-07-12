<?php
namespace App\Models\Zgen;
class z_t_kaoqin_machine_adminid extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_kaoqin_machine_adminid";


	/*int(11) */
	const C_machine_id='machine_id';

	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_auth_flag='auth_flag';
	function get_auth_flag($machine_id, $adminid ){
		return $this->field_get_value_2( $machine_id, $adminid  , self::C_auth_flag  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="machine_id";
        $this->field_id2_name="adminid";
        $this->field_table_name="db_weiyi_admin.t_kaoqin_machine_adminid";
  }

    public function field_get_value_2(  $machine_id, $adminid,$field_name ) {
        return parent::field_get_value_2(  $machine_id, $adminid,$field_name ) ;
    }

    public function field_get_list_2( $machine_id,  $adminid,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $machine_id, $adminid,  $set_field_arr ) {
        return parent::field_update_list_2( $machine_id, $adminid,  $set_field_arr );
    }
    public function row_delete_2(  $machine_id ,$adminid ) {
        return parent::row_delete_2( $machine_id ,$adminid );
    }


}
/*
  CREATE TABLE `t_kaoqin_machine_adminid` (
  `machine_id` int(11) NOT NULL COMMENT '机器id',
  `adminid` int(11) NOT NULL,
  `auth_flag` int(11) NOT NULL COMMENT '管理员标示',
  PRIMARY KEY (`machine_id`,`adminid`),
  KEY `adminid` (`adminid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
