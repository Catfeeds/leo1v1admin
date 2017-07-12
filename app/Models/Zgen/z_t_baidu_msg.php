<?php
namespace App\Models\Zgen;
class z_t_baidu_msg  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_baidu_msg";


	/*int(10) unsigned */
	const C_messageid='messageid';

	/*varchar(500) */
	const C_content='content';

	/*varchar(50) */
	const C_date='date';

	/*varchar(1000) */
	const C_value='value';

	/*int(11) */
	const C_push_num='push_num';

	/*int(11) */
	const C_message_type='message_type';

	/*int(11) */
	const C_userid='userid';

	/*int(11) */
	const C_device_type='device_type';

	/*int(11) */
	const C_status='status';

	/*tinyint(4) */
	const C_use_push_flag='use_push_flag';
	function get_content($messageid ){
		return $this->field_get_value( $messageid , self::C_content );
	}
	function get_date($messageid ){
		return $this->field_get_value( $messageid , self::C_date );
	}
	function get_value($messageid ){
		return $this->field_get_value( $messageid , self::C_value );
	}
	function get_push_num($messageid ){
		return $this->field_get_value( $messageid , self::C_push_num );
	}
	function get_message_type($messageid ){
		return $this->field_get_value( $messageid , self::C_message_type );
	}
	function get_userid($messageid ){
		return $this->field_get_value( $messageid , self::C_userid );
	}
	function get_device_type($messageid ){
		return $this->field_get_value( $messageid , self::C_device_type );
	}
	function get_status($messageid ){
		return $this->field_get_value( $messageid , self::C_status );
	}
	function get_use_push_flag($messageid ){
		return $this->field_get_value( $messageid , self::C_use_push_flag );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="messageid";
        $this->field_table_name="db_weiyi.t_baidu_msg";
  }
    public function field_get_list( $messageid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $messageid, $set_field_arr) {
        return parent::field_update_list( $messageid, $set_field_arr);
    }


    public function field_get_value(  $messageid, $field_name ) {
        return parent::field_get_value( $messageid, $field_name);
    }

    public function row_delete(  $messageid) {
        return parent::row_delete( $messageid);
    }

}

/*
  CREATE TABLE `t_baidu_msg` (
  `messageid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(500) COLLATE latin1_bin NOT NULL,
  `date` varchar(50) COLLATE latin1_bin NOT NULL,
  `value` varchar(1000) COLLATE latin1_bin NOT NULL,
  `push_num` int(11) NOT NULL,
  `message_type` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `device_type` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `use_push_flag` tinyint(4) NOT NULL COMMENT '是否使用公共消息模板 0 不是 1 是',
  PRIMARY KEY (`messageid`),
  KEY `message_type` (`message_type`),
  KEY `date` (`date`),
  KEY `push_num` (`push_num`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=520 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
