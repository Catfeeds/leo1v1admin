<?php
namespace App\Models\Zgen;
class z_t_todo  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_todo";


	/*int(11) */
	const C_todoid='todoid';

	/*int(11) */
	const C_todo_type='todo_type';

	/*int(11) */
	const C_create_time='create_time';

	/*int(11) */
	const C_start_time='start_time';

	/*int(11) */
	const C_end_time='end_time';

	/*int(11) */
	const C_adminid='adminid';

	/*varchar(4096) */
	const C_msg='msg';

	/*int(11) */
	const C_from_key_int='from_key_int';

	/*int(11) */
	const C_from_key2_int='from_key2_int';

	/*int(11) */
	const C_todo_status='todo_status';

	/*int(11) */
	const C_todo_status_time='todo_status_time';
	function get_todo_type($todoid ){
		return $this->field_get_value( $todoid , self::C_todo_type );
	}
	function get_create_time($todoid ){
		return $this->field_get_value( $todoid , self::C_create_time );
	}
	function get_start_time($todoid ){
		return $this->field_get_value( $todoid , self::C_start_time );
	}
	function get_end_time($todoid ){
		return $this->field_get_value( $todoid , self::C_end_time );
	}
	function get_adminid($todoid ){
		return $this->field_get_value( $todoid , self::C_adminid );
	}
	function get_msg($todoid ){
		return $this->field_get_value( $todoid , self::C_msg );
	}
	function get_from_key_int($todoid ){
		return $this->field_get_value( $todoid , self::C_from_key_int );
	}
	function get_from_key2_int($todoid ){
		return $this->field_get_value( $todoid , self::C_from_key2_int );
	}
	function get_todo_status($todoid ){
		return $this->field_get_value( $todoid , self::C_todo_status );
	}
	function get_todo_status_time($todoid ){
		return $this->field_get_value( $todoid , self::C_todo_status_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="todoid";
        $this->field_table_name="db_weiyi_admin.t_todo";
  }
    public function field_get_list( $todoid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $todoid, $set_field_arr) {
        return parent::field_update_list( $todoid, $set_field_arr);
    }


    public function field_get_value(  $todoid, $field_name ) {
        return parent::field_get_value( $todoid, $field_name);
    }

    public function row_delete(  $todoid) {
        return parent::row_delete( $todoid);
    }

}

/*
  CREATE TABLE `t_todo` (
  `todoid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'todoid',
  `todo_type` int(11) NOT NULL COMMENT 'todo 类型',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `start_time` int(11) NOT NULL COMMENT '开始时间',
  `end_time` int(11) NOT NULL COMMENT '结束时间',
  `adminid` int(11) NOT NULL COMMENT '负责人',
  `msg` varchar(4096) COLLATE latin1_bin NOT NULL COMMENT '信息',
  `from_key_int` int(11) NOT NULL COMMENT '外部键',
  `from_key2_int` int(11) NOT NULL COMMENT '外部键2',
  `todo_status` int(11) NOT NULL COMMENT 'todo 状态',
  `todo_status_time` int(11) NOT NULL COMMENT 'todo 状态 设置时间',
  PRIMARY KEY (`todoid`),
  UNIQUE KEY `from_key` (`todo_type`,`from_key_int`,`from_key2_int`),
  KEY `db_weiyi_admin_t_todo_adminid_create_time_index` (`adminid`,`create_time`),
  KEY `db_weiyi_admin_t_todo_adminid_start_time_index` (`adminid`,`start_time`),
  KEY `db_weiyi_admin_t_todo_create_time_index` (`create_time`),
  KEY `db_weiyi_admin_t_todo_start_time_index` (`start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
