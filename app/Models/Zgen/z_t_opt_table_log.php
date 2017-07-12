<?php
namespace App\Models\Zgen;
class z_t_opt_table_log  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_opt_table_log";


	/*int(10) unsigned */
	const C_id='id';

	/*int(11) */
	const C_opt_time='opt_time';

	/*int(11) */
	const C_adminid='adminid';

	/*varchar(4096) */
	const C_sql_str='sql_str';

	/*int(11) */
	const C_change_count='change_count';
	function get_opt_time($id ){
		return $this->field_get_value( $id , self::C_opt_time );
	}
	function get_adminid($id ){
		return $this->field_get_value( $id , self::C_adminid );
	}
	function get_sql_str($id ){
		return $this->field_get_value( $id , self::C_sql_str );
	}
	function get_change_count($id ){
		return $this->field_get_value( $id , self::C_change_count );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_opt_table_log";
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
  CREATE TABLE `t_opt_table_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `opt_time` int(11) NOT NULL,
  `adminid` int(11) NOT NULL,
  `sql_str` varchar(4096) COLLATE latin1_bin NOT NULL,
  `change_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=304 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
