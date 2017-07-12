<?php
namespace App\Models\Zgen;
class z_t_weixin_msg  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_message.t_weixin_msg";


	/*int(10) unsigned */
	const C_recordid='recordid';

	/*int(10) unsigned */
	const C_userid='userid';

	/*varchar(100) */
	const C_openid='openid';

	/*int(10) unsigned */
	const C_send_time='send_time';

	/*varchar(1024) */
	const C_templateid='templateid';

	/*varchar(1024) */
	const C_title='title';

	/*varchar(1024) */
	const C_notify_data='notify_data';

	/*varchar(1024) */
	const C_notify_url='notify_url';

	/*varchar(1024) */
	const C_receive_content='receive_content';

	/*tinyint(4) */
	const C_is_success='is_success';
	function get_userid($recordid ){
		return $this->field_get_value( $recordid , self::C_userid );
	}
	function get_openid($recordid ){
		return $this->field_get_value( $recordid , self::C_openid );
	}
	function get_send_time($recordid ){
		return $this->field_get_value( $recordid , self::C_send_time );
	}
	function get_templateid($recordid ){
		return $this->field_get_value( $recordid , self::C_templateid );
	}
	function get_title($recordid ){
		return $this->field_get_value( $recordid , self::C_title );
	}
	function get_notify_data($recordid ){
		return $this->field_get_value( $recordid , self::C_notify_data );
	}
	function get_notify_url($recordid ){
		return $this->field_get_value( $recordid , self::C_notify_url );
	}
	function get_receive_content($recordid ){
		return $this->field_get_value( $recordid , self::C_receive_content );
	}
	function get_is_success($recordid ){
		return $this->field_get_value( $recordid , self::C_is_success );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="recordid";
        $this->field_table_name="db_message.t_weixin_msg";
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
  CREATE TABLE `t_weixin_msg` (
  `recordid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '消息id',
  `userid` int(10) unsigned NOT NULL COMMENT '用户id',
  `openid` varchar(100) NOT NULL COMMENT '发送的用户的token',
  `send_time` int(10) unsigned NOT NULL COMMENT '时间戳',
  `templateid` varchar(1024) NOT NULL COMMENT '模板id',
  `title` varchar(1024) NOT NULL COMMENT '提醒标题',
  `notify_data` varchar(1024) NOT NULL COMMENT '提醒内容',
  `notify_url` varchar(1024) NOT NULL COMMENT '提醒详情',
  `receive_content` varchar(1024) NOT NULL COMMENT '服务返回内容',
  `is_success` tinyint(4) NOT NULL DEFAULT '0' COMMENT '消息发送是否成功',
  PRIMARY KEY (`recordid`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8
 */
