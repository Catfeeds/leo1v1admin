<?php
namespace App\Models\Zgen;
class z_t_admin_main_group_name  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_admin_main_group_name";


	/*int(11) */
	const C_groupid='groupid';

	/*int(11) */
	const C_main_type='main_type';

	/*varchar(255) */
	const C_group_name='group_name';

	/*int(11) */
	const C_master_adminid='master_adminid';

	/*varchar(20) */
	const C_main_assign_percent='main_assign_percent';
	function get_main_type($groupid ){
		return $this->field_get_value( $groupid , self::C_main_type );
	}
	function get_group_name($groupid ){
		return $this->field_get_value( $groupid , self::C_group_name );
	}
	function get_master_adminid($groupid ){
		return $this->field_get_value( $groupid , self::C_master_adminid );
	}
	function get_main_assign_percent($groupid ){
		return $this->field_get_value( $groupid , self::C_main_assign_percent );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="groupid";
        $this->field_table_name="db_weiyi_admin.t_admin_main_group_name";
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
  CREATE TABLE `t_admin_main_group_name` (
  `groupid` int(11) NOT NULL AUTO_INCREMENT COMMENT '分组',
  `main_type` int(11) NOT NULL,
  `group_name` varchar(255) COLLATE latin1_bin NOT NULL,
  `master_adminid` int(11) NOT NULL,
  `main_assign_percent` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '分配比例',
  PRIMARY KEY (`groupid`),
  KEY `db_weiyi_admin_t_admin_main_group_name_main_type_groupid_index` (`main_type`,`groupid`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
