<?php
namespace App\Models\Zgen;
class z_t_tq_call_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_tq_call_info";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_uid='uid';

	/*varchar(16) */
	const C_phone='phone';

	/*int(11) */
	const C_start_time='start_time';

	/*int(11) */
	const C_end_time='end_time';

	/*int(11) */
	const C_duration='duration';

	/*int(11) */
	const C_is_called_phone='is_called_phone';

	/*varchar(255) */
	const C_record_url='record_url';
	function get_uid($id ){
		return $this->field_get_value( $id , self::C_uid );
	}
	function get_phone($id ){
		return $this->field_get_value( $id , self::C_phone );
	}
	function get_start_time($id ){
		return $this->field_get_value( $id , self::C_start_time );
	}
	function get_end_time($id ){
		return $this->field_get_value( $id , self::C_end_time );
	}
	function get_duration($id ){
		return $this->field_get_value( $id , self::C_duration );
	}
	function get_is_called_phone($id ){
		return $this->field_get_value( $id , self::C_is_called_phone );
	}
	function get_record_url($id ){
		return $this->field_get_value( $id , self::C_record_url );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi_admin.t_tq_call_info";
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
  CREATE TABLE `t_tq_call_info` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `phone` varchar(16) COLLATE latin1_bin NOT NULL,
  `start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `is_called_phone` int(11) NOT NULL,
  `record_url` varchar(255) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `db_weiyi_admin_t_tq_call_info_start_time_index` (`start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
