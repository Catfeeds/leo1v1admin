<?php
namespace App\Models\Zgen;
class z_t_user_report  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_user_report";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_log_time='log_time';

	/*int(11) */
	const C_report_uid='report_uid';

	/*int(11) */
	const C_report_account_type='report_account_type';

	/*varchar(4096) */
	const C_report_msg='report_msg';

	/*int(11) */
	const C_obj_account_type='obj_account_type';

	/*int(11) */
	const C_from_key_int='from_key_int';

	/*int(11) */
	const C_from_type='from_type';
	function get_log_time($id ){
		return $this->field_get_value( $id , self::C_log_time );
	}
	function get_report_uid($id ){
		return $this->field_get_value( $id , self::C_report_uid );
	}
	function get_report_account_type($id ){
		return $this->field_get_value( $id , self::C_report_account_type );
	}
	function get_report_msg($id ){
		return $this->field_get_value( $id , self::C_report_msg );
	}
	function get_obj_account_type($id ){
		return $this->field_get_value( $id , self::C_obj_account_type );
	}
	function get_from_key_int($id ){
		return $this->field_get_value( $id , self::C_from_key_int );
	}
	function get_from_type($id ){
		return $this->field_get_value( $id , self::C_from_type );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi_admin.t_user_report";
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
  CREATE TABLE `t_user_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `log_time` int(11) NOT NULL,
  `report_uid` int(11) NOT NULL,
  `report_account_type` int(11) NOT NULL,
  `report_msg` varchar(4096) COLLATE latin1_bin NOT NULL,
  `obj_account_type` int(11) NOT NULL COMMENT 'account_type',
  `from_key_int` int(11) NOT NULL,
  `from_type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `db_weiyi_admin_t_user_report_log_time_index` (`log_time`),
  KEY `user_report_id` (`log_time`,`from_key_int`,`from_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
