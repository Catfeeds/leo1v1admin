<?php
namespace App\Models\Zgen;
class z_t_order_refund_confirm_config  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_order_refund_confirm_config";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_key1='key1';

	/*int(11) */
	const C_key2='key2';

	/*int(11) */
	const C_key3='key3';

	/*int(11) */
	const C_key4='key4';

	/*varchar(255) */
	const C_value='value';
	function get_key1($id ){
		return $this->field_get_value( $id , self::C_key1 );
	}
	function get_key2($id ){
		return $this->field_get_value( $id , self::C_key2 );
	}
	function get_key3($id ){
		return $this->field_get_value( $id , self::C_key3 );
	}
	function get_key4($id ){
		return $this->field_get_value( $id , self::C_key4 );
	}
	function get_value($id ){
		return $this->field_get_value( $id , self::C_value );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi_admin.t_order_refund_confirm_config";
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
  CREATE TABLE `t_order_refund_confirm_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `key1` int(11) NOT NULL COMMENT '部门',
  `key2` int(11) NOT NULL COMMENT '1',
  `key3` int(11) NOT NULL COMMENT '2',
  `key4` int(11) NOT NULL COMMENT '3',
  `value` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_index` (`key1`,`key2`,`key3`,`key4`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
