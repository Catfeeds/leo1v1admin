<?php
namespace App\Models\Zgen;
class z_t_seller_new_count  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_seller_new_count";


	/*int(11) */
	const C_new_count_id='new_count_id';

	/*int(11) */
	const C_seller_new_count_type='seller_new_count_type';

	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_start_time='start_time';

	/*int(11) */
	const C_end_time='end_time';

	/*int(11) */
	const C_count='count';

	/*int(11) */
	const C_value_ex='value_ex';
	function get_seller_new_count_type($new_count_id ){
		return $this->field_get_value( $new_count_id , self::C_seller_new_count_type );
	}
	function get_adminid($new_count_id ){
		return $this->field_get_value( $new_count_id , self::C_adminid );
	}
	function get_add_time($new_count_id ){
		return $this->field_get_value( $new_count_id , self::C_add_time );
	}
	function get_start_time($new_count_id ){
		return $this->field_get_value( $new_count_id , self::C_start_time );
	}
	function get_end_time($new_count_id ){
		return $this->field_get_value( $new_count_id , self::C_end_time );
	}
	function get_count($new_count_id ){
		return $this->field_get_value( $new_count_id , self::C_count );
	}
	function get_value_ex($new_count_id ){
		return $this->field_get_value( $new_count_id , self::C_value_ex );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="new_count_id";
        $this->field_table_name="db_weiyi_admin.t_seller_new_count";
  }
    public function field_get_list( $new_count_id, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $new_count_id, $set_field_arr) {
        return parent::field_update_list( $new_count_id, $set_field_arr);
    }


    public function field_get_value(  $new_count_id, $field_name ) {
        return parent::field_get_value( $new_count_id, $field_name);
    }

    public function row_delete(  $new_count_id) {
        return parent::row_delete( $new_count_id);
    }

}

/*
  CREATE TABLE `t_seller_new_count` (
  `new_count_id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_new_count_type` int(11) NOT NULL COMMENT '类型',
  `adminid` int(11) NOT NULL COMMENT '销售id',
  `add_time` int(11) NOT NULL COMMENT '加入时间',
  `start_time` int(11) NOT NULL COMMENT '有效开始时间',
  `end_time` int(11) NOT NULL COMMENT '有效终止时间',
  `count` int(11) NOT NULL COMMENT '个数',
  `value_ex` int(11) NOT NULL COMMENT '扩展说明value',
  PRIMARY KEY (`new_count_id`),
  KEY `db_weiyi_admin_t_seller_new_count_end_time_index` (`end_time`),
  KEY `db_weiyi_admin_t_seller_new_count_add_time_index` (`add_time`),
  KEY `db_weiyi_admin_t_seller_new_count_adminid_index` (`adminid`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
