<?php
namespace App\Models\Zgen;
class z_t_news_ad_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_tool.t_news_ad_info";


	/*int(10) unsigned */
	const C_id='id';

	/*varchar(500) */
	const C_ad_url='ad_url';

	/*varchar(500) */
	const C_img_url='img_url';

	/*varchar(500) */
	const C_url='url';

	/*varchar(100) */
	const C_title='title';

	/*varchar(500) */
	const C_intro='intro';

	/*int(11) */
	const C_start_time='start_time';

	/*int(11) */
	const C_end_time='end_time';

	/*int(11) */
	const C_status='status';

	/*timestamp */
	const C_created_at='created_at';

	/*timestamp */
	const C_updated_at='updated_at';
	function get_ad_url($id ){
		return $this->field_get_value( $id , self::C_ad_url );
	}
	function get_img_url($id ){
		return $this->field_get_value( $id , self::C_img_url );
	}
	function get_url($id ){
		return $this->field_get_value( $id , self::C_url );
	}
	function get_title($id ){
		return $this->field_get_value( $id , self::C_title );
	}
	function get_intro($id ){
		return $this->field_get_value( $id , self::C_intro );
	}
	function get_start_time($id ){
		return $this->field_get_value( $id , self::C_start_time );
	}
	function get_end_time($id ){
		return $this->field_get_value( $id , self::C_end_time );
	}
	function get_status($id ){
		return $this->field_get_value( $id , self::C_status );
	}
	function get_created_at($id ){
		return $this->field_get_value( $id , self::C_created_at );
	}
	function get_updated_at($id ){
		return $this->field_get_value( $id , self::C_updated_at );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_tool.t_news_ad_info";
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
  CREATE TABLE `t_news_ad_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ad_url` varchar(500) COLLATE latin1_bin NOT NULL,
  `img_url` varchar(500) COLLATE latin1_bin NOT NULL,
  `url` varchar(500) COLLATE latin1_bin NOT NULL,
  `title` varchar(100) COLLATE latin1_bin NOT NULL,
  `intro` varchar(500) COLLATE latin1_bin NOT NULL,
  `start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
