<?php
namespace App\Models\Zgen;
class z_t_news_tags_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_tool.t_news_tags_info";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_month='month';

	/*int(11) */
	const C_grade='grade';

	/*varchar(100) */
	const C_tags_info='tags_info';
	function get_month($id ){
		return $this->field_get_value( $id , self::C_month );
	}
	function get_grade($id ){
		return $this->field_get_value( $id , self::C_grade );
	}
	function get_tags_info($id ){
		return $this->field_get_value( $id , self::C_tags_info );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_tool.t_news_tags_info";
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
  CREATE TABLE `t_news_tags_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month` int(11) NOT NULL,
  `grade` int(11) NOT NULL DEFAULT '0',
  `tags_info` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1
 */
