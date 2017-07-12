<?php
namespace App\Models\Zgen;
class z_t_scores_min  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_tool.t_scores_min";


	/*int(11) */
	const C_scores_year='scores_year';

	/*int(11) */
	const C_scores_zero='scores_zero';

	/*int(11) */
	const C_scores_first='scores_first';

	/*int(11) */
	const C_scores_quota='scores_quota';

	/*int(11) */
	const C_scores_high='scores_high';

	/*int(11) */
	const C_scores_polytechnic='scores_polytechnic';

	/*int(11) */
	const C_scores_undergra='scores_undergra';
	function get_scores_year($id ){
		return $this->field_get_value( $id , self::C_scores_year );
	}
	function get_scores_zero($id ){
		return $this->field_get_value( $id , self::C_scores_zero );
	}
	function get_scores_first($id ){
		return $this->field_get_value( $id , self::C_scores_first );
	}
	function get_scores_quota($id ){
		return $this->field_get_value( $id , self::C_scores_quota );
	}
	function get_scores_high($id ){
		return $this->field_get_value( $id , self::C_scores_high );
	}
	function get_scores_polytechnic($id ){
		return $this->field_get_value( $id , self::C_scores_polytechnic );
	}
	function get_scores_undergra($id ){
		return $this->field_get_value( $id , self::C_scores_undergra );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_tool.t_scores_min";
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
  CREATE TABLE `t_scores_min` (
  `scores_year` int(11) NOT NULL COMMENT '年份',
  `scores_zero` int(11) NOT NULL COMMENT '零志愿',
  `scores_first` int(11) NOT NULL COMMENT '提前批',
  `scores_quota` int(11) NOT NULL COMMENT '名额分配',
  `scores_high` int(11) NOT NULL COMMENT '普通高中',
  `scores_polytechnic` int(11) NOT NULL COMMENT '中职贯通',
  `scores_undergra` int(11) NOT NULL COMMENT '中本贯通'
) ENGINE=InnoDB DEFAULT CHARSET=latin1
 */
