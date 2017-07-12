<?php
namespace App\Models\Zgen;
class z_t_news_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_tool.t_news_info";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_news_type='news_type';

	/*int(11) */
	const C_grade='grade';

	/*varchar(100) */
	const C_news_title='news_title';

	/*varchar(200) */
	const C_news_img='news_img';

	/*varchar(10000) */
	const C_news_info='news_info';

	/*varchar(200) */
	const C_news_intro='news_intro';

	/*varchar(100) */
	const C_add_time='add_time';

	/*varchar(100) */
	const C_add_user='add_user';

	/*int(11) */
	const C_is_top='is_top';

	/*int(10) unsigned */
	const C_browse='browse';

	/*int(11) */
	const C_push_status='push_status';
	function get_news_type($id ){
		return $this->field_get_value( $id , self::C_news_type );
	}
	function get_grade($id ){
		return $this->field_get_value( $id , self::C_grade );
	}
	function get_news_title($id ){
		return $this->field_get_value( $id , self::C_news_title );
	}
	function get_news_img($id ){
		return $this->field_get_value( $id , self::C_news_img );
	}
	function get_news_info($id ){
		return $this->field_get_value( $id , self::C_news_info );
	}
	function get_news_intro($id ){
		return $this->field_get_value( $id , self::C_news_intro );
	}
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_add_user($id ){
		return $this->field_get_value( $id , self::C_add_user );
	}
	function get_is_top($id ){
		return $this->field_get_value( $id , self::C_is_top );
	}
	function get_browse($id ){
		return $this->field_get_value( $id , self::C_browse );
	}
	function get_push_status($id ){
		return $this->field_get_value( $id , self::C_push_status );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_tool.t_news_info";
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
  CREATE TABLE `t_news_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_type` int(11) NOT NULL COMMENT 'ä¿¡æ¯ç±»åž‹',
  `grade` int(11) DEFAULT '0',
  `news_title` varchar(100) DEFAULT '' COMMENT 'ä¿¡æ¯æ ‡é¢˜',
  `news_img` varchar(200) DEFAULT NULL COMMENT 'ä¿¡æ¯ç¼©ç•¥å›¾',
  `news_info` varchar(10000) DEFAULT '',
  `news_intro` varchar(200) DEFAULT '',
  `add_time` varchar(100) DEFAULT '' COMMENT 'ä¿¡æ¯æ·»åŠ æ—¶é—´',
  `add_user` varchar(100) DEFAULT '' COMMENT 'ç¼–è¾‘äºº',
  `is_top` int(11) DEFAULT '1' COMMENT 'æƒé‡',
  `browse` int(10) unsigned DEFAULT '0',
  `push_status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `news_type` (`news_type`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1
 */
