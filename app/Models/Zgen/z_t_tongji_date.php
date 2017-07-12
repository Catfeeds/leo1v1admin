<?php
namespace App\Models\Zgen;
class z_t_tongji_date  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_tongji_date";


	/*int(11) */
	const C_log_type='log_type';

	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_log_time='log_time';

	/*int(11) */
	const C_count='count';
	function get_log_type($__null_ ){
		return $this->field_get_value( $__null_ , self::C_log_type );
	}
	function get_id($__null_ ){
		return $this->field_get_value( $__null_ , self::C_id );
	}
	function get_log_time($__null_ ){
		return $this->field_get_value( $__null_ , self::C_log_time );
	}
	function get_count($__null_ ){
		return $this->field_get_value( $__null_ , self::C_count );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="__null_";
        $this->field_table_name="db_weiyi.t_tongji_date";
  }
    public function field_get_list( $__null_, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $__null_, $set_field_arr) {
        return parent::field_update_list( $__null_, $set_field_arr);
    }


    public function field_get_value(  $__null_, $field_name ) {
        return parent::field_get_value( $__null_, $field_name);
    }

    public function row_delete(  $__null_) {
        return parent::row_delete( $__null_);
    }

}

/*
  CREATE TABLE `t_tongji_date` (
  `log_type` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `log_time` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`log_type`,`id`,`log_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
