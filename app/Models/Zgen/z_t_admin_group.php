<?php
namespace App\Models\Zgen;
class z_t_admin_group extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_admin_group";


	/*int(11) */
	const C_groupid='groupid';

	/*int(11) */
	const C_adminid='adminid';


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="groupid";
        $this->field_id2_name="adminid";
        $this->field_table_name="db_weiyi_admin.t_admin_group";
  }

    public function field_get_value_2(  $groupid, $adminid,$field_name ) {
        return parent::field_get_value_2(  $groupid, $adminid,$field_name ) ;
    }

    public function field_get_list_2( $groupid,  $adminid,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $groupid, $adminid,  $set_field_arr ) {
        return parent::field_update_list_2( $groupid, $adminid,  $set_field_arr );
    }
    public function row_delete_2(  $groupid ,$adminid ) {
        return parent::row_delete_2( $groupid ,$adminid );
    }


}
/*
  CREATE TABLE `t_admin_group` (
  `groupid` int(11) NOT NULL,
  `adminid` int(11) NOT NULL,
  PRIMARY KEY (`groupid`,`adminid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
