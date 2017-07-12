<?php
namespace App\Models\Zgen;
class z_t_user_origin_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_user_origin_info";


	/*int(11) */
	const C_id='id';

	/*varchar(32) */
	const C_name='name';

	/*varchar(16) */
	const C_phone='phone';

	/*varchar(32) */
	const C_origin='origin';

	/*int(11) */
	const C_add_time='add_time';
	function get_id($phone ){
		return $this->field_get_value( $phone , self::C_id );
	}
	function get_name($phone ){
		return $this->field_get_value( $phone , self::C_name );
	}
	function get_origin($phone ){
		return $this->field_get_value( $phone , self::C_origin );
	}
	function get_add_time($phone ){
		return $this->field_get_value( $phone , self::C_add_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="phone";
        $this->field_table_name="db_weiyi.t_user_origin_info";
  }
    public function field_get_list( $phone, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $phone, $set_field_arr) {
        return parent::field_update_list( $phone, $set_field_arr);
    }


    public function field_get_value(  $phone, $field_name ) {
        return parent::field_get_value( $phone, $field_name);
    }

    public function row_delete(  $phone) {
        return parent::row_delete( $phone);
    }

}

/*
  CREATE TABLE `t_user_origin_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE latin1_bin NOT NULL,
  `phone` varchar(16) COLLATE latin1_bin NOT NULL,
  `origin` varchar(32) COLLATE latin1_bin NOT NULL,
  `add_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
