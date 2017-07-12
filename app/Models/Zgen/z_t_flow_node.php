<?php
namespace App\Models\Zgen;
class z_t_flow_node  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_flow_node";


	/*int(11) */
	const C_nodeid='nodeid';

	/*int(11) */
	const C_node_type='node_type';

	/*int(11) */
	const C_flowid='flowid';

	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_flow_check_flag='flow_check_flag';

	/*int(11) */
	const C_check_time='check_time';

	/*varchar(1028) */
	const C_check_msg='check_msg';

	/*int(11) */
	const C_next_nodeid='next_nodeid';
	function get_node_type($nodeid ){
		return $this->field_get_value( $nodeid , self::C_node_type );
	}
	function get_flowid($nodeid ){
		return $this->field_get_value( $nodeid , self::C_flowid );
	}
	function get_adminid($nodeid ){
		return $this->field_get_value( $nodeid , self::C_adminid );
	}
	function get_add_time($nodeid ){
		return $this->field_get_value( $nodeid , self::C_add_time );
	}
	function get_flow_check_flag($nodeid ){
		return $this->field_get_value( $nodeid , self::C_flow_check_flag );
	}
	function get_check_time($nodeid ){
		return $this->field_get_value( $nodeid , self::C_check_time );
	}
	function get_check_msg($nodeid ){
		return $this->field_get_value( $nodeid , self::C_check_msg );
	}
	function get_next_nodeid($nodeid ){
		return $this->field_get_value( $nodeid , self::C_next_nodeid );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="nodeid";
        $this->field_table_name="db_weiyi_admin.t_flow_node";
  }
    public function field_get_list( $nodeid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $nodeid, $set_field_arr) {
        return parent::field_update_list( $nodeid, $set_field_arr);
    }


    public function field_get_value(  $nodeid, $field_name ) {
        return parent::field_get_value( $nodeid, $field_name);
    }

    public function row_delete(  $nodeid) {
        return parent::row_delete( $nodeid);
    }

}

/*
  CREATE TABLE `t_flow_node` (
  `nodeid` int(11) NOT NULL AUTO_INCREMENT,
  `node_type` int(11) NOT NULL,
  `flowid` int(11) NOT NULL,
  `adminid` int(11) NOT NULL,
  `add_time` int(11) NOT NULL,
  `flow_check_flag` int(11) NOT NULL,
  `check_time` int(11) NOT NULL,
  `check_msg` varchar(1028) COLLATE latin1_bin NOT NULL,
  `next_nodeid` int(11) NOT NULL,
  PRIMARY KEY (`nodeid`),
  KEY `db_weiyi_admin_t_flow_node_flowid_index` (`flowid`),
  KEY `db_weiyi_admin_t_flow_node_adminid_flow_check_flag_index` (`adminid`,`flow_check_flag`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
