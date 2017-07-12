<?php
namespace App\Models\Zgen;
class z_t_book_revisit extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_book_revisit";


	/*varchar(16) */
	const C_phone='phone';

	/*int(10) unsigned */
	const C_revisit_time='revisit_time';

	/*varchar(1024) */
	const C_operator_note='operator_note';

	/*varchar(100) */
	const C_operator_audio='operator_audio';

	/*varchar(32) */
	const C_sys_operator='sys_operator';
	function get_operator_note($phone, $revisit_time ){
		return $this->field_get_value_2( $phone, $revisit_time  , self::C_operator_note  );
	}
	function get_operator_audio($phone, $revisit_time ){
		return $this->field_get_value_2( $phone, $revisit_time  , self::C_operator_audio  );
	}
	function get_sys_operator($phone, $revisit_time ){
		return $this->field_get_value_2( $phone, $revisit_time  , self::C_sys_operator  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="phone";
        $this->field_id2_name="revisit_time";
        $this->field_table_name="db_weiyi.t_book_revisit";
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
  CREATE TABLE `t_book_revisit` (
  `phone` varchar(16) NOT NULL COMMENT '联系方式',
  `revisit_time` int(10) unsigned NOT NULL COMMENT '回访时间',
  `operator_note` varchar(1024) NOT NULL COMMENT '回访记录',
  `operator_audio` varchar(100) NOT NULL DEFAULT '' COMMENT '回访语音',
  `sys_operator` varchar(32) NOT NULL COMMENT '进行回访的人',
  PRIMARY KEY (`phone`,`revisit_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
