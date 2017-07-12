<?php
namespace App\Models\Zgen;
class z_t_good_video_send_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_good_video_send_list";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_send_time='send_time';

	/*varchar(255) */
	const C_account='account';

	/*varchar(255) */
	const C_send_reason='send_reason';

	/*varchar(255) */
	const C_teacher='teacher';

	/*varchar(255) */
	const C_url='url';

	/*int(11) */
	const C_tea_num='tea_num';

	/*int(11) */
	const C_subject='subject';

	/*int(11) */
	const C_grade='grade';
	function get_send_time($id ){
		return $this->field_get_value( $id , self::C_send_time );
	}
	function get_account($id ){
		return $this->field_get_value( $id , self::C_account );
	}
	function get_send_reason($id ){
		return $this->field_get_value( $id , self::C_send_reason );
	}
	function get_teacher($id ){
		return $this->field_get_value( $id , self::C_teacher );
	}
	function get_url($id ){
		return $this->field_get_value( $id , self::C_url );
	}
	function get_tea_num($id ){
		return $this->field_get_value( $id , self::C_tea_num );
	}
	function get_subject($id ){
		return $this->field_get_value( $id , self::C_subject );
	}
	function get_grade($id ){
		return $this->field_get_value( $id , self::C_grade );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_good_video_send_list";
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
  CREATE TABLE `t_good_video_send_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `send_time` int(11) NOT NULL COMMENT '推送时间',
  `account` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '操作人',
  `send_reason` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '推荐理由',
  `teacher` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '推荐老师',
  `url` varchar(255) COLLATE latin1_bin NOT NULL,
  `tea_num` int(11) NOT NULL COMMENT '推送的老师个数',
  `subject` int(11) NOT NULL COMMENT '科目',
  `grade` int(11) NOT NULL COMMENT '年级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
