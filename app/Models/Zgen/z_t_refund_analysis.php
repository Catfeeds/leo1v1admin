<?php
namespace App\Models\Zgen;
class z_t_refund_analysis  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_refund_analysis";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_orderid='orderid';

	/*int(11) */
	const C_apply_time='apply_time';

	/*int(11) */
	const C_configid='configid';

	/*int(11) */
	const C_score='score';

	/*varchar(1000) */
	const C_reason='reason';

	/*varchar(1000) */
	const C_other_reason='other_reason';

	/*varchar(1000) */
	const C_qc_analysia='qc_analysia';

	/*varchar(1000) */
	const C_reply='reply';

	/*int(11) */
	const C_add_time='add_time';
	function get_orderid($id ){
		return $this->field_get_value( $id , self::C_orderid );
	}
	function get_apply_time($id ){
		return $this->field_get_value( $id , self::C_apply_time );
	}
	function get_configid($id ){
		return $this->field_get_value( $id , self::C_configid );
	}
	function get_score($id ){
		return $this->field_get_value( $id , self::C_score );
	}
	function get_reason($id ){
		return $this->field_get_value( $id , self::C_reason );
	}
	function get_other_reason($id ){
		return $this->field_get_value( $id , self::C_other_reason );
	}
	function get_qc_analysia($id ){
		return $this->field_get_value( $id , self::C_qc_analysia );
	}
	function get_reply($id ){
		return $this->field_get_value( $id , self::C_reply );
	}
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi_admin.t_refund_analysis";
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
  CREATE TABLE `t_refund_analysis` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `orderid` int(11) NOT NULL COMMENT '订单id',
  `apply_time` int(11) NOT NULL COMMENT '应用时间',
  `configid` int(11) NOT NULL COMMENT '退费原因id',
  `score` int(11) NOT NULL COMMENT '扣分值',
  `reason` varchar(1000) COLLATE latin1_bin NOT NULL COMMENT '扣分原因',
  `other_reason` varchar(1000) COLLATE latin1_bin NOT NULL COMMENT '其他原因',
  `qc_analysia` varchar(1000) COLLATE latin1_bin NOT NULL COMMENT 'qc整体分析',
  `reply` varchar(1000) COLLATE latin1_bin NOT NULL COMMENT '应对方案',
  `add_time` int(11) NOT NULL COMMENT '处理时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_index` (`orderid`,`apply_time`,`configid`),
  KEY `add_time` (`add_time`),
  KEY `apply_time` (`apply_time`),
  KEY `configid` (`configid`)
) ENGINE=InnoDB AUTO_INCREMENT=300 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
