<?php
namespace App\Models\Zgen;
class z_t_wx_key_value  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_wx_key_value";


	/*int(11) */
	const C_id='id';

	/*varchar(8192) */
	const C_data='data';
	function get_data($id ){
		return $this->field_get_value( $id , self::C_data );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi_admin.t_wx_key_value";
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
  CREATE TABLE `t_wx_key_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` varchar(8192) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
