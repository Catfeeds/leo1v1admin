<?php
namespace App\Models\Zgen;
class z_t_festival_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_festival_info";


	/*int(10) unsigned */
	const C_id='id';

	/*text */
	const C_date_str='date_str';

	/*text */
	const C_festival_str='festival_str';
	function get_date_str($id ){
		return $this->field_get_value( $id , self::C_date_str );
	}
	function get_festival_str($id ){
		return $this->field_get_value( $id , self::C_festival_str );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_festival_info";
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
  CREATE TABLE `t_festival_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_str` text COLLATE latin1_bin NOT NULL,
  `festival_str` text COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
