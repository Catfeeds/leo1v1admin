<?php
namespace App\Models\Zgen;
class z_t_news_activity_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_tool.t_news_activity_info";


	/*int(11) */
	const C_id='id';

	/*varchar(500) */
	const C_name='name';

	/*varchar(500) */
	const C_img_min='img_min';

	/*varchar(500) */
	const C_img='img';

	/*varchar(500) */
	const C_url='url';

	/*varchar(500) */
	const C_info='info';

	/*int(11) */
	const C_start_time='start_time';

	/*int(11) */
	const C_end_time='end_time';

	/*int(11) */
	const C_add_time='add_time';

	/*varchar(100) */
	const C_add_user='add_user';

	/*varchar(100) */
	const C_title='title';
	function get_name($id ){
		return $this->field_get_value( $id , self::C_name );
	}
	function get_img_min($id ){
		return $this->field_get_value( $id , self::C_img_min );
	}
	function get_img($id ){
		return $this->field_get_value( $id , self::C_img );
	}
	function get_url($id ){
		return $this->field_get_value( $id , self::C_url );
	}
	function get_info($id ){
		return $this->field_get_value( $id , self::C_info );
	}
	function get_start_time($id ){
		return $this->field_get_value( $id , self::C_start_time );
	}
	function get_end_time($id ){
		return $this->field_get_value( $id , self::C_end_time );
	}
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_add_user($id ){
		return $this->field_get_value( $id , self::C_add_user );
	}
	function get_title($id ){
		return $this->field_get_value( $id , self::C_title );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_tool.t_news_activity_info";
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
  CREATE TABLE `t_news_activity_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) DEFAULT NULL COMMENT '活动名称',
  `img_min` varchar(500) DEFAULT NULL,
  `img` varchar(500) NOT NULL COMMENT '活动图片',
  `url` varchar(500) NOT NULL COMMENT '活动链接',
  `info` varchar(500) DEFAULT NULL,
  `start_time` int(11) NOT NULL COMMENT '活动开始时间',
  `end_time` int(11) NOT NULL COMMENT '活动结束时间',
  `add_time` int(11) NOT NULL COMMENT '活动添加时间',
  `add_user` varchar(100) NOT NULL COMMENT '活动添加人',
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1
 */
