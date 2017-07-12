<?php
namespace App\Models\Zgen;
class z_t_online_count_log  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_online_count_log";


	/*int(11) */
	const C_logtime='logtime';

	/*int(11) */
	const C_online_count='online_count';
	function get_online_count($logtime ){
		return $this->field_get_value( $logtime , self::C_online_count );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="logtime";
        $this->field_table_name="db_weiyi_admin.t_online_count_log";
  }
    public function field_get_list( $logtime, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $logtime, $set_field_arr) {
        return parent::field_update_list( $logtime, $set_field_arr);
    }


    public function field_get_value(  $logtime, $field_name ) {
        return parent::field_get_value( $logtime, $field_name);
    }

    public function row_delete(  $logtime) {
        return parent::row_delete( $logtime);
    }

}

/*
  CREATE TABLE `t_online_count_log` (
  `logtime` int(11) NOT NULL COMMENT '时间',
  `online_count` int(11) NOT NULL COMMENT '在线人数',
  PRIMARY KEY (`logtime`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
