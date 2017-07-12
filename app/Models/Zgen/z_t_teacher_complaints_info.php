<?php
namespace App\Models\Zgen;
class z_t_teacher_complaints_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_complaints_info";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_teacherid='teacherid';

	/*varchar(1000) */
	const C_complaints_info='complaints_info';

	/*varchar(255) */
	const C_complaints_info_url='complaints_info_url';

	/*int(11) */
	const C_subject='subject';

	/*int(11) */
	const C_grade='grade';

	/*varchar(500) */
	const C_record_scheme='record_scheme';

	/*varchar(255) */
	const C_record_scheme_url='record_scheme_url';

	/*int(11) */
	const C_accept_adminid='accept_adminid';

	/*int(11) */
	const C_accept_time='accept_time';

	/*int(11) */
	const C_is_done='is_done';

	/*int(11) */
	const C_done_time='done_time';
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_adminid($id ){
		return $this->field_get_value( $id , self::C_adminid );
	}
	function get_teacherid($id ){
		return $this->field_get_value( $id , self::C_teacherid );
	}
	function get_complaints_info($id ){
		return $this->field_get_value( $id , self::C_complaints_info );
	}
	function get_complaints_info_url($id ){
		return $this->field_get_value( $id , self::C_complaints_info_url );
	}
	function get_subject($id ){
		return $this->field_get_value( $id , self::C_subject );
	}
	function get_grade($id ){
		return $this->field_get_value( $id , self::C_grade );
	}
	function get_record_scheme($id ){
		return $this->field_get_value( $id , self::C_record_scheme );
	}
	function get_record_scheme_url($id ){
		return $this->field_get_value( $id , self::C_record_scheme_url );
	}
	function get_accept_adminid($id ){
		return $this->field_get_value( $id , self::C_accept_adminid );
	}
	function get_accept_time($id ){
		return $this->field_get_value( $id , self::C_accept_time );
	}
	function get_is_done($id ){
		return $this->field_get_value( $id , self::C_is_done );
	}
	function get_done_time($id ){
		return $this->field_get_value( $id , self::C_done_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_teacher_complaints_info";
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
  CREATE TABLE `t_teacher_complaints_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `add_time` int(11) NOT NULL COMMENT '申请时间',
  `adminid` int(11) NOT NULL COMMENT '申请者',
  `teacherid` int(11) NOT NULL COMMENT '老师',
  `complaints_info` varchar(1000) COLLATE latin1_bin NOT NULL COMMENT '投诉内容',
  `complaints_info_url` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '投诉内容对应图片地址',
  `subject` int(11) NOT NULL COMMENT '科目',
  `grade` int(11) NOT NULL COMMENT '年级段',
  `record_scheme` varchar(500) COLLATE latin1_bin NOT NULL COMMENT '处理方案',
  `record_scheme_url` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '处理方案图片地址',
  `accept_adminid` int(11) NOT NULL COMMENT '处理人',
  `accept_time` int(11) NOT NULL COMMENT '处理时间',
  `is_done` int(11) NOT NULL COMMENT '是否解决',
  `done_time` int(11) NOT NULL COMMENT '解决时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
