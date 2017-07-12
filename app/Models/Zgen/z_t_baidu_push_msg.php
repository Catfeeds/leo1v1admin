<?php
namespace App\Models\Zgen;
class z_t_baidu_push_msg  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_baidu_push_msg";


	/*int(10) unsigned */
	const C_messageid='messageid';

	/*int(11) */
	const C_message_type='message_type';

	/*varchar(1000) */
	const C_message_content='message_content';
	function get_message_type($messageid ){
		return $this->field_get_value( $messageid , self::C_message_type );
	}
	function get_message_content($messageid ){
		return $this->field_get_value( $messageid , self::C_message_content );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="messageid";
        $this->field_table_name="db_weiyi.t_baidu_push_msg";
  }
    public function field_get_list( $messageid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $messageid, $set_field_arr) {
        return parent::field_update_list( $messageid, $set_field_arr);
    }


    public function field_get_value(  $messageid, $field_name ) {
        return parent::field_get_value( $messageid, $field_name);
    }

    public function row_delete(  $messageid) {
        return parent::row_delete( $messageid);
    }

}

/*
  CREATE TABLE `t_baidu_push_msg` (
  `messageid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message_type` int(11) NOT NULL,
  `message_content` varchar(1000) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`messageid`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
