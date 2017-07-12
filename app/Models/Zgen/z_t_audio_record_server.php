<?php
namespace App\Models\Zgen;
class z_t_audio_record_server  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_audio_record_server";


	/*varchar(255) */
	const C_ip='ip';

	/*int(11) */
	const C_last_active_time='last_active_time';

	/*int(11) */
	const C_priority='priority';

	/*varchar(255) */
	const C_desc='desc';

	/*int(11) */
	const C_config_userid='config_userid';

	/*int(11) */
	const C_max_record_count='max_record_count';
	function get_last_active_time($ip ){
		return $this->field_get_value( $ip , self::C_last_active_time );
	}
	function get_priority($ip ){
		return $this->field_get_value( $ip , self::C_priority );
	}
	function get_desc($ip ){
		return $this->field_get_value( $ip , self::C_desc );
	}
	function get_config_userid($ip ){
		return $this->field_get_value( $ip , self::C_config_userid );
	}
	function get_max_record_count($ip ){
		return $this->field_get_value( $ip , self::C_max_record_count );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="ip";
        $this->field_table_name="db_weiyi.t_audio_record_server";
  }
    public function field_get_list( $ip, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $ip, $set_field_arr) {
        return parent::field_update_list( $ip, $set_field_arr);
    }


    public function field_get_value(  $ip, $field_name ) {
        return parent::field_get_value( $ip, $field_name);
    }

    public function row_delete(  $ip) {
        return parent::row_delete( $ip);
    }

}

/*
  CREATE TABLE `t_audio_record_server` (
  `ip` varchar(255) COLLATE latin1_bin NOT NULL,
  `last_active_time` int(11) NOT NULL,
  `priority` int(11) NOT NULL COMMENT '优先权 ,越大越优先,一般 和机器的CPU个数成正比',
  `desc` varchar(255) COLLATE latin1_bin NOT NULL,
  `config_userid` int(11) DEFAULT NULL COMMENT '登录声网所用的userid',
  `max_record_count` int(11) NOT NULL COMMENT '最大录音数',
  PRIMARY KEY (`ip`),
  UNIQUE KEY `t_audio_record_server_config_userid_unique` (`config_userid`),
  KEY `t_audio_record_server_priority_index` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
