<?php
namespace App\Models\Zgen;
class z_t_scores_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_tool.t_scores_info";


	/*int(10) unsigned */
	const C_schoolid='schoolid';

	/*varchar(50) */
	const C_school_name='school_name';

	/*varchar(50) */
	const C_school_name_high='school_name_high';

	/*int(10) unsigned */
	const C_school_type='school_type';

	/*varchar(50) */
	const C_school_major='school_major';

	/*varchar(50) */
	const C_scores_school='scores_school';

	/*varchar(50) */
	const C_scores_sum='scores_sum';

	/*varchar(50) */
	const C_scores_chinese='scores_chinese';

	/*varchar(50) */
	const C_scores_math='scores_math';

	/*int(11) */
	const C_scores_year='scores_year';

	/*int(11) */
	const C_scores_area='scores_area';

	/*varchar(10000) */
	const C_school_quota='school_quota';
	function get_schoolid($id ){
		return $this->field_get_value( $id , self::C_schoolid );
	}
	function get_school_name($id ){
		return $this->field_get_value( $id , self::C_school_name );
	}
	function get_school_name_high($id ){
		return $this->field_get_value( $id , self::C_school_name_high );
	}
	function get_school_type($id ){
		return $this->field_get_value( $id , self::C_school_type );
	}
	function get_school_major($id ){
		return $this->field_get_value( $id , self::C_school_major );
	}
	function get_scores_school($id ){
		return $this->field_get_value( $id , self::C_scores_school );
	}
	function get_scores_sum($id ){
		return $this->field_get_value( $id , self::C_scores_sum );
	}
	function get_scores_chinese($id ){
		return $this->field_get_value( $id , self::C_scores_chinese );
	}
	function get_scores_math($id ){
		return $this->field_get_value( $id , self::C_scores_math );
	}
	function get_scores_year($id ){
		return $this->field_get_value( $id , self::C_scores_year );
	}
	function get_scores_area($id ){
		return $this->field_get_value( $id , self::C_scores_area );
	}
	function get_school_quota($id ){
		return $this->field_get_value( $id , self::C_school_quota );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_tool.t_scores_info";
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
  CREATE TABLE `t_scores_info` (
  `schoolid` int(10) unsigned NOT NULL COMMENT '招生代码',
  `school_name` varchar(50) NOT NULL COMMENT '学校名称',
  `school_name_high` varchar(50) DEFAULT NULL COMMENT '高等院校',
  `school_type` int(10) unsigned NOT NULL COMMENT '1零志愿，2名额分配，3普通高中，4中本贯通，5中职贯通，6最低投档线',
  `school_major` varchar(50) DEFAULT NULL COMMENT '专业/志愿名称',
  `scores_school` varchar(50) DEFAULT NULL COMMENT 'å­¦æ ¡åˆ†æ•°çº¿',
  `scores_sum` varchar(50) DEFAULT NULL COMMENT 'ä¸‰ç§‘åˆ†æ•°çº¿',
  `scores_chinese` varchar(50) DEFAULT NULL COMMENT 'è¯­æ–‡åˆ†æ•°çº¿',
  `scores_math` varchar(50) DEFAULT NULL COMMENT 'æ•°å­¦åˆ†æ•°çº¿',
  `scores_year` int(11) DEFAULT NULL COMMENT '年份',
  `scores_area` int(11) DEFAULT NULL COMMENT '区域',
  `school_quota` varchar(10000) DEFAULT NULL COMMENT 'åé¢åˆ†é…ä¿¡æ¯',
  KEY `index_1` (`school_type`,`scores_area`,`scores_year`,`scores_sum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
