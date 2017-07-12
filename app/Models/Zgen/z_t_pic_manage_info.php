<?php
namespace App\Models\Zgen;
class z_t_pic_manage_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_pic_manage_info";


	/*int(10) unsigned */
	const C_id='id';

	/*int(11) */
	const C_type='type';

	/*varchar(100) */
	const C_name='name';

	/*int(11) */
	const C_time_type='time_type';

	/*timestamp */
	const C_created_at='created_at';

	/*timestamp */
	const C_updated_at='updated_at';

	/*int(11) */
	const C_order_by='order_by';

	/*int(11) */
	const C_usage_type='usage_type';

	/*varchar(500) */
	const C_img_tags_url='img_tags_url';

	/*varchar(500) */
	const C_img_url='img_url';

	/*int(11) */
	const C_status='status';

	/*int(11) */
	const C_subject='subject';

	/*int(11) */
	const C_grade='grade';

	/*int(11) */
	const C_start_time='start_time';

	/*int(11) */
	const C_end_time='end_time';

	/*varchar(500) */
	const C_jump_url='jump_url';

	/*varchar(100) */
	const C_title_share='title_share';

	/*varchar(100) */
	const C_info_share='info_share';
	function get_type($id ){
		return $this->field_get_value( $id , self::C_type );
	}
	function get_name($id ){
		return $this->field_get_value( $id , self::C_name );
	}
	function get_time_type($id ){
		return $this->field_get_value( $id , self::C_time_type );
	}
	function get_created_at($id ){
		return $this->field_get_value( $id , self::C_created_at );
	}
	function get_updated_at($id ){
		return $this->field_get_value( $id , self::C_updated_at );
	}
	function get_order_by($id ){
		return $this->field_get_value( $id , self::C_order_by );
	}
	function get_usage_type($id ){
		return $this->field_get_value( $id , self::C_usage_type );
	}
	function get_img_tags_url($id ){
		return $this->field_get_value( $id , self::C_img_tags_url );
	}
	function get_img_url($id ){
		return $this->field_get_value( $id , self::C_img_url );
	}
	function get_status($id ){
		return $this->field_get_value( $id , self::C_status );
	}
	function get_subject($id ){
		return $this->field_get_value( $id , self::C_subject );
	}
	function get_grade($id ){
		return $this->field_get_value( $id , self::C_grade );
	}
	function get_start_time($id ){
		return $this->field_get_value( $id , self::C_start_time );
	}
	function get_end_time($id ){
		return $this->field_get_value( $id , self::C_end_time );
	}
	function get_jump_url($id ){
		return $this->field_get_value( $id , self::C_jump_url );
	}
	function get_title_share($id ){
		return $this->field_get_value( $id , self::C_title_share );
	}
	function get_info_share($id ){
		return $this->field_get_value( $id , self::C_info_share );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_pic_manage_info";
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
  CREATE TABLE `t_pic_manage_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `name` varchar(100) COLLATE latin1_bin NOT NULL,
  `time_type` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_by` int(11) NOT NULL DEFAULT '0',
  `usage_type` int(11) NOT NULL DEFAULT '0',
  `img_tags_url` varchar(500) COLLATE latin1_bin NOT NULL,
  `img_url` varchar(500) COLLATE latin1_bin NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `subject` int(11) NOT NULL DEFAULT '0',
  `grade` int(11) NOT NULL DEFAULT '0',
  `start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `jump_url` varchar(500) COLLATE latin1_bin NOT NULL,
  `title_share` varchar(100) COLLATE latin1_bin NOT NULL,
  `info_share` varchar(100) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
