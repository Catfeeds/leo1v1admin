<?php
namespace App\Models\Zgen;
class z_t_mypraise  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_mypraise";


	/*int(10) unsigned */
	const C_userid='userid';

	/*int(10) unsigned */
	const C_ts='ts';

	/*smallint(6) */
	const C_type='type';

	/*varchar(100) */
	const C_reason='reason';

	/*int(10) unsigned */
	const C_praise_num='praise_num';

	/*int(10) unsigned */
	const C_lessonid='lessonid';

	/*int(11) */
	const C_add_userid='add_userid';
	function get_ts($userid ){
		return $this->field_get_value( $userid , self::C_ts );
	}
	function get_type($userid ){
		return $this->field_get_value( $userid , self::C_type );
	}
	function get_reason($userid ){
		return $this->field_get_value( $userid , self::C_reason );
	}
	function get_praise_num($userid ){
		return $this->field_get_value( $userid , self::C_praise_num );
	}
	function get_lessonid($userid ){
		return $this->field_get_value( $userid , self::C_lessonid );
	}
	function get_add_userid($userid ){
		return $this->field_get_value( $userid , self::C_add_userid );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="userid";
        $this->field_table_name="db_weiyi.t_mypraise";
  }
    public function field_get_list( $userid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $userid, $set_field_arr) {
        return parent::field_update_list( $userid, $set_field_arr);
    }


    public function field_get_value(  $userid, $field_name ) {
        return parent::field_get_value( $userid, $field_name);
    }

    public function row_delete(  $userid) {
        return parent::row_delete( $userid);
    }

}

/*
  CREATE TABLE `t_mypraise` (
  `userid` int(10) unsigned NOT NULL COMMENT '学生账号',
  `ts` int(10) unsigned NOT NULL COMMENT '记录增加时间',
  `type` smallint(6) NOT NULL COMMENT '获取或者是消耗赞 0 消耗 1 获取',
  `reason` varchar(100) NOT NULL COMMENT '获取或者是消耗的原因',
  `praise_num` int(10) unsigned NOT NULL COMMENT '获取或者消耗赞的个数',
  `lessonid` int(10) unsigned DEFAULT NULL COMMENT '获赞的课堂id',
  `add_userid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`ts`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
