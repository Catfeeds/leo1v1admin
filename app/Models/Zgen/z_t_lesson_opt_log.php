<?php
namespace App\Models\Zgen;
class z_t_lesson_opt_log  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_lesson_opt_log";


	/*int(10) unsigned */
	const C_lessonid='lessonid';

	/*int(10) unsigned */
	const C_opt_time='opt_time';

	/*int(10) unsigned */
	const C_userid='userid';

	/*int(10) unsigned */
	const C_opt_type='opt_type';

	/*int(10) unsigned */
	const C_server_type='server_type';

	/*int(10) unsigned */
	const C_server_ip='server_ip';

	/*int(10) unsigned */
	const C_program_id='program_id';
	function get_lessonid($__id ){
		return $this->field_get_value( $__id , self::C_lessonid );
	}
	function get_opt_time($__id ){
		return $this->field_get_value( $__id , self::C_opt_time );
	}
	function get_userid($__id ){
		return $this->field_get_value( $__id , self::C_userid );
	}
	function get_opt_type($__id ){
		return $this->field_get_value( $__id , self::C_opt_type );
	}
	function get_server_type($__id ){
		return $this->field_get_value( $__id , self::C_server_type );
	}
	function get_server_ip($__id ){
		return $this->field_get_value( $__id , self::C_server_ip );
	}
	function get_program_id($__id ){
		return $this->field_get_value( $__id , self::C_program_id );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="__id";
        $this->field_table_name="db_weiyi.t_lesson_opt_log";
  }
    public function field_get_list( $__id, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $__id, $set_field_arr) {
        return parent::field_update_list( $__id, $set_field_arr);
    }


    public function field_get_value(  $__id, $field_name ) {
        return parent::field_get_value( $__id, $field_name);
    }

    public function row_delete(  $__id) {
        return parent::row_delete( $__id);
    }

}

/*
  CREATE TABLE `t_lesson_opt_log` (
  `lessonid` int(10) unsigned NOT NULL COMMENT '课次id',
  `opt_time` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL,
  `opt_type` int(10) unsigned NOT NULL COMMENT '1:login,2:logout',
  `server_type` int(10) unsigned NOT NULL COMMENT '1:webrtc 2:xmpp',
  `server_ip` int(10) unsigned NOT NULL COMMENT 'ip',
  `program_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`lessonid`,`opt_time`,`userid`,`opt_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
