<?php
namespace App\Models\Zgen;
class z_t_ass_group_target  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_ass_group_target";


	/*int(11) */
	const C_month='month';

	/*varchar(255) */
	const C_rate_target='rate_target';
	function get_rate_target($month ){
		return $this->field_get_value( $month , self::C_rate_target );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="month";
        $this->field_table_name="db_weiyi_admin.t_ass_group_target";
  }
    public function field_get_list( $month, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $month, $set_field_arr) {
        return parent::field_update_list( $month, $set_field_arr);
    }


    public function field_get_value(  $month, $field_name ) {
        return parent::field_get_value( $month, $field_name);
    }

    public function row_delete(  $month) {
        return parent::row_delete( $month);
    }

}

/*
  CREATE TABLE `t_ass_group_target` (
  `month` int(11) NOT NULL COMMENT '月开始时间',
  `rate_target` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '系数',
  PRIMARY KEY (`month`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
