<?php
namespace App\Models\Zgen;
class z_t_complaint_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_complaint_info";


	/*int(10) unsigned */
	const C_complaint_id='complaint_id';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_complaint_type='complaint_type';

	/*int(11) */
	const C_userid='userid';

	/*int(11) */
	const C_account_type='account_type';

	/*text */
	const C_complaint_info='complaint_info';

	/*varchar(2048) */
	const C_complaint_img_url='complaint_img_url';

	/*varchar(4096) */
	const C_suggest_info='suggest_info';

	/*int(11) */
	const C_current_adminid='current_adminid';

	/*int(11) */
	const C_current_admin_assign_time='current_admin_assign_time';

	/*int(11) */
	const C_complaint_state='complaint_state';

	/*int(11) */
	const C_complained_adminid='complained_adminid';

	/*int(11) */
	const C_complained_adminid_type='complained_adminid_type';

	/*varchar(255) */
	const C_complained_adminid_nick='complained_adminid_nick';
	function get_add_time($complaint_id ){
		return $this->field_get_value( $complaint_id , self::C_add_time );
	}
	function get_complaint_type($complaint_id ){
		return $this->field_get_value( $complaint_id , self::C_complaint_type );
	}
	function get_userid($complaint_id ){
		return $this->field_get_value( $complaint_id , self::C_userid );
	}
	function get_account_type($complaint_id ){
		return $this->field_get_value( $complaint_id , self::C_account_type );
	}
	function get_complaint_info($complaint_id ){
		return $this->field_get_value( $complaint_id , self::C_complaint_info );
	}
	function get_complaint_img_url($complaint_id ){
		return $this->field_get_value( $complaint_id , self::C_complaint_img_url );
	}
	function get_suggest_info($complaint_id ){
		return $this->field_get_value( $complaint_id , self::C_suggest_info );
	}
	function get_current_adminid($complaint_id ){
		return $this->field_get_value( $complaint_id , self::C_current_adminid );
	}
	function get_current_admin_assign_time($complaint_id ){
		return $this->field_get_value( $complaint_id , self::C_current_admin_assign_time );
	}
	function get_complaint_state($complaint_id ){
		return $this->field_get_value( $complaint_id , self::C_complaint_state );
	}
	function get_complained_adminid($complaint_id ){
		return $this->field_get_value( $complaint_id , self::C_complained_adminid );
	}
	function get_complained_adminid_type($complaint_id ){
		return $this->field_get_value( $complaint_id , self::C_complained_adminid_type );
	}
	function get_complained_adminid_nick($complaint_id ){
		return $this->field_get_value( $complaint_id , self::C_complained_adminid_nick );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="complaint_id";
        $this->field_table_name="db_weiyi.t_complaint_info";
  }
    public function field_get_list( $complaint_id, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $complaint_id, $set_field_arr) {
        return parent::field_update_list( $complaint_id, $set_field_arr);
    }


    public function field_get_value(  $complaint_id, $field_name ) {
        return parent::field_get_value( $complaint_id, $field_name);
    }

    public function row_delete(  $complaint_id) {
        return parent::row_delete( $complaint_id);
    }

}

/*
  CREATE TABLE `t_complaint_info` (
  `complaint_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `add_time` int(11) NOT NULL COMMENT '投诉生成时间',
  `complaint_type` int(11) NOT NULL COMMENT '投诉来源类型',
  `userid` int(11) NOT NULL COMMENT '投诉人',
  `account_type` int(11) NOT NULL COMMENT '投诉人类别',
  `complaint_info` text COLLATE latin1_bin NOT NULL COMMENT '投诉内容',
  `complaint_img_url` varchar(2048) COLLATE latin1_bin NOT NULL COMMENT '投诉附件链接',
  `suggest_info` varchar(4096) COLLATE latin1_bin NOT NULL COMMENT '家长建议或其他建议',
  `current_adminid` int(11) NOT NULL COMMENT '当前负责人',
  `current_admin_assign_time` int(11) NOT NULL COMMENT '当前负责人 分配时间',
  `complaint_state` int(11) NOT NULL COMMENT '投诉处理状态 0:未处理 1:已分配 ,2 :   ',
  `complained_adminid` int(11) NOT NULL COMMENT '被投诉人 ',
  `complained_adminid_type` int(11) NOT NULL COMMENT '被投诉人类型',
  `complained_adminid_nick` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '被投诉人昵称',
  PRIMARY KEY (`complaint_id`),
  KEY `db_weiyi_t_complaint_info_add_time_index` (`add_time`),
  KEY `db_weiyi_t_complaint_info_current_adminid_index` (`current_adminid`),
  KEY `db_weiyi_t_complaint_info_userid_add_time_index` (`userid`,`add_time`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
