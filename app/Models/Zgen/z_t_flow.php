<?php
namespace App\Models\Zgen;
class z_t_flow  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_flow";


	/*int(11) */
	const C_flowid='flowid';

	/*int(11) */
	const C_flow_type='flow_type';

	/*int(11) */
	const C_post_adminid='post_adminid';

	/*int(11) */
	const C_post_time='post_time';

	/*int(11) */
	const C_from_key_int='from_key_int';

	/*varchar(64) */
	const C_from_key_str='from_key_str';

	/*varchar(4096) */
	const C_post_msg='post_msg';

	/*int(11) */
	const C_flow_status='flow_status';

	/*int(11) */
	const C_flow_status_time='flow_status_time';

	/*int(11) */
	const C_from_key2_int='from_key2_int';
	function get_flow_type($flowid ){
		return $this->field_get_value( $flowid , self::C_flow_type );
	}
	function get_post_adminid($flowid ){
		return $this->field_get_value( $flowid , self::C_post_adminid );
	}
	function get_post_time($flowid ){
		return $this->field_get_value( $flowid , self::C_post_time );
	}
	function get_from_key_int($flowid ){
		return $this->field_get_value( $flowid , self::C_from_key_int );
	}
	function get_from_key_str($flowid ){
		return $this->field_get_value( $flowid , self::C_from_key_str );
	}
	function get_post_msg($flowid ){
		return $this->field_get_value( $flowid , self::C_post_msg );
	}
	function get_flow_status($flowid ){
		return $this->field_get_value( $flowid , self::C_flow_status );
	}
	function get_flow_status_time($flowid ){
		return $this->field_get_value( $flowid , self::C_flow_status_time );
	}
	function get_from_key2_int($flowid ){
		return $this->field_get_value( $flowid , self::C_from_key2_int );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="flowid";
        $this->field_table_name="db_weiyi_admin.t_flow";
  }
    public function field_get_list( $flowid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $flowid, $set_field_arr) {
        return parent::field_update_list( $flowid, $set_field_arr);
    }


    public function field_get_value(  $flowid, $field_name ) {
        return parent::field_get_value( $flowid, $field_name);
    }

    public function row_delete(  $flowid) {
        return parent::row_delete( $flowid);
    }

}

/*
  CREATE TABLE `t_flow` (
  `flowid` int(11) NOT NULL AUTO_INCREMENT,
  `flow_type` int(11) NOT NULL,
  `post_adminid` int(11) NOT NULL,
  `post_time` int(11) NOT NULL,
  `from_key_int` int(11) DEFAULT NULL,
  `from_key_str` varchar(64) COLLATE latin1_bin DEFAULT NULL,
  `post_msg` varchar(4096) COLLATE latin1_bin NOT NULL,
  `flow_status` int(11) NOT NULL,
  `flow_status_time` int(11) NOT NULL,
  `from_key2_int` int(11) NOT NULL COMMENT 'from_key2_int',
  PRIMARY KEY (`flowid`),
  UNIQUE KEY `db_weiyi_admin_t_flow_flow_type_from_key_str_unique` (`flow_type`,`from_key_str`),
  UNIQUE KEY `from_key_int_unique` (`flow_type`,`from_key_int`,`from_key2_int`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
