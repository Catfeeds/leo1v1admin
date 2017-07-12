<?php
namespace App\Models\Zgen;
class z_t_agent_order  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_agent_order";


	/*int(11) */
	const C_orderid='orderid';

	/*int(11) */
	const C_pid='pid';

	/*int(11) */
	const C_p_price='p_price';

	/*int(11) */
	const C_ppid='ppid';

	/*int(11) */
	const C_pp_price='pp_price';

	/*int(11) */
	const C_create_time='create_time';

	/*int(11) */
	const C_aid='aid';
	function get_pid($orderid ){
		return $this->field_get_value( $orderid , self::C_pid );
	}
	function get_p_price($orderid ){
		return $this->field_get_value( $orderid , self::C_p_price );
	}
	function get_ppid($orderid ){
		return $this->field_get_value( $orderid , self::C_ppid );
	}
	function get_pp_price($orderid ){
		return $this->field_get_value( $orderid , self::C_pp_price );
	}
	function get_create_time($orderid ){
		return $this->field_get_value( $orderid , self::C_create_time );
	}
	function get_aid($orderid ){
		return $this->field_get_value( $orderid , self::C_aid );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="orderid";
        $this->field_table_name="db_weiyi.t_agent_order";
  }
    public function field_get_list( $orderid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $orderid, $set_field_arr) {
        return parent::field_update_list( $orderid, $set_field_arr);
    }


    public function field_get_value(  $orderid, $field_name ) {
        return parent::field_get_value( $orderid, $field_name);
    }

    public function row_delete(  $orderid) {
        return parent::row_delete( $orderid);
    }

}

/*
  CREATE TABLE `t_agent_order` (
  `orderid` int(11) NOT NULL COMMENT '订单id',
  `pid` int(11) NOT NULL COMMENT '上级转介绍id',
  `p_price` int(11) NOT NULL COMMENT '上级转介绍费',
  `ppid` int(11) NOT NULL COMMENT '上上级转介绍id',
  `pp_price` int(11) NOT NULL COMMENT '上上级转介绍费',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `aid` int(11) NOT NULL COMMENT 'agent表关联id',
  PRIMARY KEY (`orderid`),
  KEY `db_weiyi_t_agent_order_pid_index` (`pid`),
  KEY `db_weiyi_t_agent_order_ppid_index` (`ppid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
