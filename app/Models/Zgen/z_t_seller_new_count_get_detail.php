<?php
namespace App\Models\Zgen;
class z_t_seller_new_count_get_detail  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_seller_new_count_get_detail";


	/*int(11) */
	const C_detail_id='detail_id';

	/*int(11) */
	const C_new_count_id='new_count_id';

	/*int(11) */
	const C_get_time='get_time';

	/*varchar(255) */
	const C_get_desc='get_desc';
	function get_detail_id($id ){
		return $this->field_get_value( $id , self::C_detail_id );
	}
	function get_new_count_id($id ){
		return $this->field_get_value( $id , self::C_new_count_id );
	}
	function get_get_time($id ){
		return $this->field_get_value( $id , self::C_get_time );
	}
	function get_get_desc($id ){
		return $this->field_get_value( $id , self::C_get_desc );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi_admin.t_seller_new_count_get_detail";
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
  CREATE TABLE `t_seller_new_count_get_detail` (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `new_count_id` int(11) NOT NULL COMMENT 'from t_seller_new_count ',
  `get_time` int(11) NOT NULL COMMENT '获取时间',
  `get_desc` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '获取说明',
  PRIMARY KEY (`detail_id`),
  KEY `db_weiyi_admin_t_seller_new_count_get_detail_new_count_id_index` (`new_count_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
