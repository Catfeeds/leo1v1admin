<?php
namespace App\Models\Zgen;
class z_t_authority_group  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_authority_group";


	/*int(10) */
	const C_groupid='groupid';

	/*varchar(64) */
	const C_group_name='group_name';

	/*varchar(8192) */
	const C_group_authority='group_authority';

	/*int(10) unsigned */
	const C_create_time='create_time';

	/*tinyint(3) unsigned */
	const C_del_flag='del_flag';
	function get_group_name($groupid ){
		return $this->field_get_value( $groupid , self::C_group_name );
	}
	function get_group_authority($groupid ){
		return $this->field_get_value( $groupid , self::C_group_authority );
	}
	function get_create_time($groupid ){
		return $this->field_get_value( $groupid , self::C_create_time );
	}
	function get_del_flag($groupid ){
		return $this->field_get_value( $groupid , self::C_del_flag );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="groupid";
        $this->field_table_name="db_weiyi_admin.t_authority_group";
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
  CREATE TABLE `t_authority_group` (
  `groupid` int(10) NOT NULL AUTO_INCREMENT COMMENT '权限组id',
  `group_name` varchar(64) NOT NULL DEFAULT '' COMMENT '组名',
  `group_authority` varchar(8192) DEFAULT NULL,
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `del_flag` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`groupid`),
  UNIQUE KEY `group_name` (`group_name`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8
 */
