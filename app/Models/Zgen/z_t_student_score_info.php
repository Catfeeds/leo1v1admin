<?php
namespace App\Models\Zgen;
class z_t_student_score_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_student_score_info";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_userid='userid';

	/*int(11) */
	const C_create_time='create_time';

	/*int(11) */
	const C_create_adminid='create_adminid';

	/*int(11) */
	const C_subject='subject';

	/*int(11) */
	const C_stu_score_type='stu_score_type';

	/*int(11) */
	const C_stu_score_time='stu_score_time';

	/*int(11) */
	const C_score='score';

	/*varchar(255) */
	const C_rank='rank';

	/*varchar(255) */
	const C_file_url='file_url';
	function get_userid($id ){
		return $this->field_get_value( $id , self::C_userid );
	}
	function get_create_time($id ){
		return $this->field_get_value( $id , self::C_create_time );
	}
	function get_create_adminid($id ){
		return $this->field_get_value( $id , self::C_create_adminid );
	}
	function get_subject($id ){
		return $this->field_get_value( $id , self::C_subject );
	}
	function get_stu_score_type($id ){
		return $this->field_get_value( $id , self::C_stu_score_type );
	}
	function get_stu_score_time($id ){
		return $this->field_get_value( $id , self::C_stu_score_time );
	}
	function get_score($id ){
		return $this->field_get_value( $id , self::C_score );
	}
	function get_rank($id ){
		return $this->field_get_value( $id , self::C_rank );
	}
	function get_file_url($id ){
		return $this->field_get_value( $id , self::C_file_url );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_student_score_info";
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
  CREATE TABLE `t_student_score_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `userid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `create_adminid` int(11) NOT NULL COMMENT '添加人',
  `subject` int(11) NOT NULL COMMENT '科目',
  `stu_score_type` int(11) NOT NULL COMMENT '测验分类',
  `stu_score_time` int(11) NOT NULL COMMENT '测验时间',
  `score` int(11) NOT NULL COMMENT '分数',
  `rank` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '排名',
  `file_url` varchar(255) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `db_weiyi_t_student_score_info_userid_index` (`userid`),
  KEY `db_weiyi_t_student_score_info_create_adminid_index` (`create_adminid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
