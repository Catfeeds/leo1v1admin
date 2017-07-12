<?php
namespace App\Models\Zgen;
class z_t_student_type_change_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_student_type_change_list";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_userid='userid';

	/*int(11) */
	const C_add_time='add_time';

	/*tinyint(4) */
	const C_type_before='type_before';

	/*tinyint(4) */
	const C_type_cur='type_cur';

	/*tinyint(4) */
	const C_change_type='change_type';

	/*int(11) */
	const C_adminid='adminid';

	/*varchar(255) */
	const C_reason='reason';
	function get_userid($id ){
		return $this->field_get_value( $id , self::C_userid );
	}
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_type_before($id ){
		return $this->field_get_value( $id , self::C_type_before );
	}
	function get_type_cur($id ){
		return $this->field_get_value( $id , self::C_type_cur );
	}
	function get_change_type($id ){
		return $this->field_get_value( $id , self::C_change_type );
	}
	function get_adminid($id ){
		return $this->field_get_value( $id , self::C_adminid );
	}
	function get_reason($id ){
		return $this->field_get_value( $id , self::C_reason );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_student_type_change_list";
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
  CREATE TABLE `t_student_type_change_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `userid` int(11) NOT NULL COMMENT '学生id',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `type_before` tinyint(4) NOT NULL COMMENT '修改前类型',
  `type_cur` tinyint(4) NOT NULL COMMENT '当前类型',
  `change_type` tinyint(4) NOT NULL COMMENT '修改方式 1,系统;2,手动',
  `adminid` int(11) NOT NULL COMMENT '操作人',
  `reason` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '操作原因',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
