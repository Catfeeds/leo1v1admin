<?php
namespace App\Models\Zgen;
class z_t_lecture_revisit_info extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_lecture_revisit_info";


	/*varchar(255) */
	const C_phone='phone';

	/*int(11) */
	const C_revisit_time='revisit_time';

	/*varchar(255) */
	const C_sys_operator='sys_operator';

	/*int(11) */
	const C_revisit_origin='revisit_origin';

	/*varchar(1000) */
	const C_revisit_note='revisit_note';
	function get_sys_operator($phone, $revisit_time ){
		return $this->field_get_value_2( $phone, $revisit_time  , self::C_sys_operator  );
	}
	function get_revisit_origin($phone, $revisit_time ){
		return $this->field_get_value_2( $phone, $revisit_time  , self::C_revisit_origin  );
	}
	function get_revisit_note($phone, $revisit_time ){
		return $this->field_get_value_2( $phone, $revisit_time  , self::C_revisit_note  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="phone";
        $this->field_id2_name="revisit_time";
        $this->field_table_name="db_weiyi.t_lecture_revisit_info";
  }

    public function field_get_value_2(  $phone, $revisit_time,$field_name ) {
        return parent::field_get_value_2(  $phone, $revisit_time,$field_name ) ;
    }

    public function field_get_list_2( $phone,  $revisit_time,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $phone, $revisit_time,  $set_field_arr ) {
        return parent::field_update_list_2( $phone, $revisit_time,  $set_field_arr );
    }
    public function row_delete_2(  $phone ,$revisit_time ) {
        return parent::row_delete_2( $phone ,$revisit_time );
    }


}
/*
  CREATE TABLE `t_lecture_revisit_info` (
  `phone` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '电话',
  `revisit_time` int(11) NOT NULL COMMENT '回访时间',
  `sys_operator` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '进行回访的人',
  `revisit_origin` int(11) NOT NULL COMMENT '回访渠道 1 微信 ,2 电话,3 其他',
  `revisit_note` varchar(1000) COLLATE latin1_bin NOT NULL COMMENT '回访内容',
  PRIMARY KEY (`phone`,`revisit_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
