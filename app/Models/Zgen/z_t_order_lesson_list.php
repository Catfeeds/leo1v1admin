<?php
namespace App\Models\Zgen;
class z_t_order_lesson_list extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_order_lesson_list";


	/*int(11) */
	const C_orderid='orderid';

	/*int(11) */
	const C_lessonid='lessonid';

	/*int(11) */
	const C_lesson_count='lesson_count';

	/*int(11) */
	const C_per_price='per_price';

	/*int(11) */
	const C_price='price';

	/*int(11) */
	const C_userid='userid';
	function get_lesson_count($orderid, $lessonid ){
		return $this->field_get_value_2( $orderid, $lessonid  , self::C_lesson_count  );
	}
	function get_per_price($orderid, $lessonid ){
		return $this->field_get_value_2( $orderid, $lessonid  , self::C_per_price  );
	}
	function get_price($orderid, $lessonid ){
		return $this->field_get_value_2( $orderid, $lessonid  , self::C_price  );
	}
	function get_userid($orderid, $lessonid ){
		return $this->field_get_value_2( $orderid, $lessonid  , self::C_userid  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="orderid";
        $this->field_id2_name="lessonid";
        $this->field_table_name="db_weiyi.t_order_lesson_list";
  }

    public function field_get_value_2(  $orderid, $lessonid,$field_name ) {
        return parent::field_get_value_2(  $orderid, $lessonid,$field_name ) ;
    }

    public function field_get_list_2( $orderid,  $lessonid,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $orderid, $lessonid,  $set_field_arr ) {
        return parent::field_update_list_2( $orderid, $lessonid,  $set_field_arr );
    }
    public function row_delete_2(  $orderid ,$lessonid ) {
        return parent::row_delete_2( $orderid ,$lessonid );
    }


}
/*
  CREATE TABLE `t_order_lesson_list` (
  `orderid` int(11) NOT NULL,
  `lessonid` int(11) NOT NULL,
  `lesson_count` int(11) NOT NULL,
  `per_price` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `userid` int(11) NOT NULL COMMENT '用户id',
  PRIMARY KEY (`orderid`,`lessonid`),
  KEY `lessonid` (`lessonid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
