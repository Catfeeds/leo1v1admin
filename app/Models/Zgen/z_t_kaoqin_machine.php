<?php
namespace App\Models\Zgen;
class z_t_kaoqin_machine  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_kaoqin_machine";


	/*int(11) */
	const C_machine_id='machine_id';

	/*int(11) */
	const C_open_door_flag='open_door_flag';

	/*int(11) */
	const C_last_post_time='last_post_time';

	/*varchar(255) */
	const C_sn='sn';

	/*varchar(255) */
	const C_title='title';

	/*varchar(255) */
	const C_desc='desc';
	function get_open_door_flag($machine_id ){
		return $this->field_get_value( $machine_id , self::C_open_door_flag );
	}
	function get_last_post_time($machine_id ){
		return $this->field_get_value( $machine_id , self::C_last_post_time );
	}
	function get_sn($machine_id ){
		return $this->field_get_value( $machine_id , self::C_sn );
	}
	function get_title($machine_id ){
		return $this->field_get_value( $machine_id , self::C_title );
	}
	function get_desc($machine_id ){
		return $this->field_get_value( $machine_id , self::C_desc );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="machine_id";
        $this->field_table_name="db_weiyi_admin.t_kaoqin_machine";
  }
    public function field_get_list( $machine_id, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $machine_id, $set_field_arr) {
        return parent::field_update_list( $machine_id, $set_field_arr);
    }


    public function field_get_value(  $machine_id, $field_name ) {
        return parent::field_get_value( $machine_id, $field_name);
    }

    public function row_delete(  $machine_id) {
        return parent::row_delete( $machine_id);
    }

}

/*
  CREATE TABLE `t_kaoqin_machine` (
  `machine_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '机器id',
  `open_door_flag` int(11) NOT NULL COMMENT '是否开启门禁',
  `last_post_time` int(11) NOT NULL COMMENT '最后一次上报时间',
  `sn` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '考勤机序列号',
  `title` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '标题',
  `desc` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '说明',
  PRIMARY KEY (`machine_id`),
  UNIQUE KEY `db_weiyi_admin_t_kaoqin_machine_sn_unique` (`sn`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
