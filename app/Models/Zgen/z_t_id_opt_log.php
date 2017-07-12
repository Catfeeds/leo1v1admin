<?php
namespace App\Models\Zgen;
class z_t_id_opt_log  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_id_opt_log";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_log_type='log_type';

	/*int(11) */
	const C_log_time='log_time';

	/*int(11) */
	const C_opt_id='opt_id';

	/*int(11) */
	const C_value='value';
	function get_log_type($id ){
		return $this->field_get_value( $id , self::C_log_type );
	}
	function get_log_time($id ){
		return $this->field_get_value( $id , self::C_log_time );
	}
	function get_opt_id($id ){
		return $this->field_get_value( $id , self::C_opt_id );
	}
	function get_value($id ){
		return $this->field_get_value( $id , self::C_value );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi_admin.t_id_opt_log";
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
  CREATE TABLE `t_id_opt_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_type` int(11) NOT NULL,
  `log_time` int(11) NOT NULL,
  `opt_id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `db_weiyi_admin_t_id_opt_log_log_type_log_time_index` (`log_type`,`log_time`),
  KEY `db_weiyi_admin_t_id_opt_log_log_type_opt_id_index` (`log_type`,`opt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
