<?php
namespace App\Models\Zgen;
class z_t_news_headlines  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_tool.t_news_headlines";


	/*int(10) unsigned */
	const C_id='id';

	/*varchar(1000) */
	const C_h_info='h_info';

	/*int(10) unsigned */
	const C_grade='grade';

	/*int(10) unsigned */
	const C_add_time='add_time';

	/*varchar(1000) */
	const C_add_user='add_user';

	/*int(10) unsigned */
	const C_browse='browse';

	/*int(10) unsigned */
	const C_push_status='push_status';

	/*varchar(255) */
	const C_tags_info='tags_info';
	function get_h_info($id ){
		return $this->field_get_value( $id , self::C_h_info );
	}
	function get_grade($id ){
		return $this->field_get_value( $id , self::C_grade );
	}
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_add_user($id ){
		return $this->field_get_value( $id , self::C_add_user );
	}
	function get_browse($id ){
		return $this->field_get_value( $id , self::C_browse );
	}
	function get_push_status($id ){
		return $this->field_get_value( $id , self::C_push_status );
	}
	function get_tags_info($id ){
		return $this->field_get_value( $id , self::C_tags_info );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_tool.t_news_headlines";
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
  CREATE TABLE `t_news_headlines` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `h_info` varchar(1000) NOT NULL DEFAULT '',
  `grade` int(10) unsigned NOT NULL DEFAULT '0',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0',
  `add_user` varchar(1000) NOT NULL DEFAULT '',
  `browse` int(10) unsigned NOT NULL DEFAULT '0',
  `push_status` int(10) unsigned DEFAULT '0',
  `tags_info` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=latin1
 */
