<?php
namespace App\Models\Zgen;
class z_t_user_authority_group  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_user_authority_group";


	/*int(11) */
	const C_groupid='groupid';

	/*varchar(100) */
	const C_group_name='group_name';

	/*varchar(10000) */
	const C_group_authority_group='group_authority_group';

	/*int(11) */
	const C_create_time='create_time';

	/*tinyint(4) */
	const C_del_flag='del_flag';

	/*tinyint(4) */
	const C_role='role';
	function get_group_name($groupid ){
		return $this->field_get_value( $groupid , self::C_group_name );
	}
	function get_group_authority_group($groupid ){
		return $this->field_get_value( $groupid , self::C_group_authority_group );
	}
	function get_create_time($groupid ){
		return $this->field_get_value( $groupid , self::C_create_time );
	}
	function get_del_flag($groupid ){
		return $this->field_get_value( $groupid , self::C_del_flag );
	}
	function get_role($groupid ){
		return $this->field_get_value( $groupid , self::C_role );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="groupid";
        $this->field_table_name="db_weiyi.t_user_authority_group";
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
  CREATE TABLE `t_user_authority_group` (
  `groupid` int(11) NOT NULL AUTO_INCREMENT COMMENT '权限组id',
  `group_name` varchar(100) COLLATE latin1_bin NOT NULL COMMENT '组名',
  `group_authority_group` varchar(10000) COLLATE latin1_bin NOT NULL COMMENT '组权限',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `del_flag` tinyint(4) NOT NULL COMMENT '删除标志 0 未删除 1 已删除',
  `role` tinyint(4) NOT NULL COMMENT '权限组所属角色 1学生 2老师 3助教 4家长',
  PRIMARY KEY (`groupid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
