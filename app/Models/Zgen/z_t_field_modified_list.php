<?php
namespace App\Models\Zgen;
class z_t_field_modified_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_field_modified_list";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_modified_time='modified_time';

	/*varchar(255) */
	const C_last_value='last_value';

	/*varchar(255) */
	const C_cur_value='cur_value';

	/*int(11) */
	const C_adminid='adminid';

	/*varchar(255) */
	const C_t_name='t_name';

	/*varchar(255) */
	const C_f_name='f_name';

	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_userid='userid';
	function get_modified_time($id ){
		return $this->field_get_value( $id , self::C_modified_time );
	}
	function get_last_value($id ){
		return $this->field_get_value( $id , self::C_last_value );
	}
	function get_cur_value($id ){
		return $this->field_get_value( $id , self::C_cur_value );
	}
	function get_adminid($id ){
		return $this->field_get_value( $id , self::C_adminid );
	}
	function get_t_name($id ){
		return $this->field_get_value( $id , self::C_t_name );
	}
	function get_f_name($id ){
		return $this->field_get_value( $id , self::C_f_name );
	}
	function get_teacherid($id ){
		return $this->field_get_value( $id , self::C_teacherid );
	}
	function get_userid($id ){
		return $this->field_get_value( $id , self::C_userid );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_field_modified_list";
  }
    public function field_get_list( $id, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $id, $set_field_arr) {
        return parent::field_update_list( $id, $set_field_arr);
    }


    public function field_get_value(  $id, $field_name ) {
        return parent::field_get_value( $id, $field_name);
    }

    public function row_delete(  $id) {
        return parent::row_delete( $id);
    }

}

/*
  CREATE TABLE `t_field_modified_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `modified_time` int(11) NOT NULL COMMENT '修改时间',
  `last_value` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '修改前的值',
  `cur_value` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '修改后的值',
  `adminid` int(11) NOT NULL COMMENT '操作人',
  `t_name` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '表',
  `f_name` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '字段',
  `teacherid` int(11) NOT NULL COMMENT 'teacherid',
  `userid` int(11) NOT NULL COMMENT 'userid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
