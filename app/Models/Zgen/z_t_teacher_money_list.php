<?php
namespace App\Models\Zgen;
class z_t_teacher_money_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_money_list";


	/*int(11) */
	const C_teacherid='teacherid';

	/*tinyint(4) */
	const C_type='type';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_money='money';

	/*varchar(255) */
	const C_money_info='money_info';

	/*int(11) */
	const C_id='id';

	/*varchar(50) */
	const C_acc='acc';
	function get_teacherid($id ){
		return $this->field_get_value( $id , self::C_teacherid );
	}
	function get_type($id ){
		return $this->field_get_value( $id , self::C_type );
	}
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_money($id ){
		return $this->field_get_value( $id , self::C_money );
	}
	function get_money_info($id ){
		return $this->field_get_value( $id , self::C_money_info );
	}
	function get_acc($id ){
		return $this->field_get_value( $id , self::C_acc );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_teacher_money_list";
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
  CREATE TABLE `t_teacher_money_list` (
  `teacherid` int(11) NOT NULL COMMENT '老师id',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '金额类型',
  `add_time` int(11) NOT NULL COMMENT '记录添加时间',
  `money` int(11) NOT NULL COMMENT '金额',
  `money_info` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '类型注释/获奖原因',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acc` varchar(50) COLLATE latin1_bin NOT NULL COMMENT '添加人',
  PRIMARY KEY (`id`),
  KEY `t_teacher_money_list_teacherid_type_index` (`teacherid`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
