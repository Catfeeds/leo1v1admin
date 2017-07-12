<?php
namespace App\Models\Zgen;
class z_t_sms_msg  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_message.t_sms_msg";


	/*int(10) unsigned */
	const C_recordid='recordid';

	/*varchar(100) */
	const C_phone='phone';

	/*varchar(1024) */
	const C_message='message';

	/*int(10) unsigned */
	const C_send_time='send_time';

	/*varchar(1024) */
	const C_receive_content='receive_content';

	/*tinyint(4) */
	const C_is_success='is_success';

	/*int(10) unsigned */
	const C_type='type';

	/*int(10) unsigned */
	const C_user_ip='user_ip';
	function get_phone($recordid ){
		return $this->field_get_value( $recordid , self::C_phone );
	}
	function get_message($recordid ){
		return $this->field_get_value( $recordid , self::C_message );
	}
	function get_send_time($recordid ){
		return $this->field_get_value( $recordid , self::C_send_time );
	}
	function get_receive_content($recordid ){
		return $this->field_get_value( $recordid , self::C_receive_content );
	}
	function get_is_success($recordid ){
		return $this->field_get_value( $recordid , self::C_is_success );
	}
	function get_type($recordid ){
		return $this->field_get_value( $recordid , self::C_type );
	}
	function get_user_ip($recordid ){
		return $this->field_get_value( $recordid , self::C_user_ip );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="recordid";
        $this->field_table_name="db_message.t_sms_msg";
  }
    public function field_get_list( $recordid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $recordid, $set_field_arr) {
        return parent::field_update_list( $recordid, $set_field_arr);
    }


    public function field_get_value(  $recordid, $field_name ) {
        return parent::field_get_value( $recordid, $field_name);
    }

    public function row_delete(  $recordid) {
        return parent::row_delete( $recordid);
    }

}

/*
  CREATE TABLE `t_sms_msg` (
  `recordid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '消息id',
  `phone` varchar(100) NOT NULL COMMENT '发送地址',
  `message` varchar(1024) NOT NULL COMMENT '发送内容',
  `send_time` int(10) unsigned NOT NULL COMMENT '时间戳',
  `receive_content` varchar(1024) NOT NULL COMMENT '服务返回内容',
  `is_success` tinyint(4) NOT NULL DEFAULT '0' COMMENT '消息发送是否成功',
  `type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'the send type',
  `user_ip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'user ip',
  PRIMARY KEY (`recordid`)
) ENGINE=InnoDB AUTO_INCREMENT=30781 DEFAULT CHARSET=utf8
 */
