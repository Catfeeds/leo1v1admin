<?php
namespace App\Models\Zgen;
class z_t_qingjia  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_qingjia";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_type='type';

	/*int(11) */
	const C_start_time='start_time';

	/*int(11) */
	const C_end_time='end_time';

	/*int(11) */
	const C_hour_count='hour_count';

	/*int(11) */
	const C_del_flag='del_flag';

	/*varchar(255) */
	const C_msg='msg';
	function get_adminid($id ){
		return $this->field_get_value( $id , self::C_adminid );
	}
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_type($id ){
		return $this->field_get_value( $id , self::C_type );
	}
	function get_start_time($id ){
		return $this->field_get_value( $id , self::C_start_time );
	}
	function get_end_time($id ){
		return $this->field_get_value( $id , self::C_end_time );
	}
	function get_hour_count($id ){
		return $this->field_get_value( $id , self::C_hour_count );
	}
	function get_del_flag($id ){
		return $this->field_get_value( $id , self::C_del_flag );
	}
	function get_msg($id ){
		return $this->field_get_value( $id , self::C_msg );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi_admin.t_qingjia";
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
  CREATE TABLE `t_qingjia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adminid` int(11) NOT NULL,
  `add_time` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `hour_count` int(11) NOT NULL COMMENT '请假时长',
  `del_flag` int(11) NOT NULL,
  `msg` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '说明',
  PRIMARY KEY (`id`),
  KEY `db_weiyi_admin_t_qingjia_adminid_index` (`adminid`),
  KEY `db_weiyi_admin_t_qingjia_add_time_index` (`add_time`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
