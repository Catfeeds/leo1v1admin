<?php
namespace App\Models\Zgen;
class z_t_complaint_assign_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_complaint_assign_info";


	/*int(10) unsigned */
	const C_ass_id='ass_id';

	/*int(11) */
	const C_complaint_id='complaint_id';

	/*int(11) */
	const C_assign_adminid='assign_adminid';

	/*int(11) */
	const C_accept_adminid='accept_adminid';

	/*int(11) */
	const C_assign_time='assign_time';

	/*int(11) */
	const C_assign_flag='assign_flag';

	/*varchar(255) */
	const C_assign_remarks='assign_remarks';
	function get_complaint_id($ass_id ){
		return $this->field_get_value( $ass_id , self::C_complaint_id );
	}
	function get_assign_adminid($ass_id ){
		return $this->field_get_value( $ass_id , self::C_assign_adminid );
	}
	function get_accept_adminid($ass_id ){
		return $this->field_get_value( $ass_id , self::C_accept_adminid );
	}
	function get_assign_time($ass_id ){
		return $this->field_get_value( $ass_id , self::C_assign_time );
	}
	function get_assign_flag($ass_id ){
		return $this->field_get_value( $ass_id , self::C_assign_flag );
	}
	function get_assign_remarks($ass_id ){
		return $this->field_get_value( $ass_id , self::C_assign_remarks );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="ass_id";
        $this->field_table_name="db_weiyi.t_complaint_assign_info";
  }
    public function field_get_list( $ass_id, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $ass_id, $set_field_arr) {
        return parent::field_update_list( $ass_id, $set_field_arr);
    }


    public function field_get_value(  $ass_id, $field_name ) {
        return parent::field_get_value( $ass_id, $field_name);
    }

    public function row_delete(  $ass_id) {
        return parent::row_delete( $ass_id);
    }

}

/*
  CREATE TABLE `t_complaint_assign_info` (
  `ass_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `complaint_id` int(11) NOT NULL COMMENT '投诉id',
  `assign_adminid` int(11) NOT NULL COMMENT '分配人id',
  `accept_adminid` int(11) NOT NULL COMMENT '处理人id',
  `assign_time` int(11) NOT NULL COMMENT '分配时间',
  `assign_flag` int(11) NOT NULL COMMENT '0:接受 1:驳回',
  `assign_remarks` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '分配备注',
  PRIMARY KEY (`ass_id`),
  KEY `db_weiyi_t_complaint_assign_info_complaint_id_index` (`complaint_id`),
  KEY `db_weiyi_t_complaint_assign_info_accept_adminid_index` (`accept_adminid`),
  KEY `db_weiyi_t_complaint_assign_info_assign_time_index` (`assign_time`),
  KEY `db_weiyi_t_complaint_assign_info_assign_adminid_index` (`assign_adminid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
