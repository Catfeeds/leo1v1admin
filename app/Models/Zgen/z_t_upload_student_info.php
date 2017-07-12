<?php
namespace App\Models\Zgen;
class z_t_upload_student_info extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_upload_student_info";


	/*int(11) */
	const C_postid='postid';

	/*int(11) */
	const C_add_time='add_time';

	/*varchar(20) */
	const C_phone='phone';

	/*varchar(64) */
	const C_phone_location='phone_location';

	/*varchar(255) */
	const C_name='name';

	/*varchar(255) */
	const C_origin='origin';

	/*int(11) */
	const C_subject='subject';

	/*int(11) */
	const C_grade='grade';

	/*varchar(255) */
	const C_user_desc='user_desc';

	/*int(11) */
	const C_has_pad='has_pad';

	/*int(11) */
	const C_is_new_flag='is_new_flag';
	function get_add_time($postid, $phone ){
		return $this->field_get_value_2( $postid, $phone  , self::C_add_time  );
	}
	function get_phone_location($postid, $phone ){
		return $this->field_get_value_2( $postid, $phone  , self::C_phone_location  );
	}
	function get_name($postid, $phone ){
		return $this->field_get_value_2( $postid, $phone  , self::C_name  );
	}
	function get_origin($postid, $phone ){
		return $this->field_get_value_2( $postid, $phone  , self::C_origin  );
	}
	function get_subject($postid, $phone ){
		return $this->field_get_value_2( $postid, $phone  , self::C_subject  );
	}
	function get_grade($postid, $phone ){
		return $this->field_get_value_2( $postid, $phone  , self::C_grade  );
	}
	function get_user_desc($postid, $phone ){
		return $this->field_get_value_2( $postid, $phone  , self::C_user_desc  );
	}
	function get_has_pad($postid, $phone ){
		return $this->field_get_value_2( $postid, $phone  , self::C_has_pad  );
	}
	function get_is_new_flag($postid, $phone ){
		return $this->field_get_value_2( $postid, $phone  , self::C_is_new_flag  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="postid";
        $this->field_id2_name="phone";
        $this->field_table_name="db_weiyi_admin.t_upload_student_info";
  }

    public function field_get_value_2(  $postid, $phone,$field_name ) {
        return parent::field_get_value_2(  $postid, $phone,$field_name ) ;
    }

    public function field_get_list_2( $postid,  $phone,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $postid, $phone,  $set_field_arr ) {
        return parent::field_update_list_2( $postid, $phone,  $set_field_arr );
    }
    public function row_delete_2(  $postid ,$phone ) {
        return parent::row_delete_2( $postid ,$phone );
    }


}
/*
  CREATE TABLE `t_upload_student_info` (
  `postid` int(11) NOT NULL COMMENT '批次',
  `add_time` int(11) NOT NULL COMMENT '加入时间',
  `phone` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '电话',
  `phone_location` varchar(64) COLLATE latin1_bin NOT NULL COMMENT '电话地区',
  `name` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '姓名',
  `origin` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '来源',
  `subject` int(11) NOT NULL COMMENT '科目',
  `grade` int(11) NOT NULL COMMENT '年级',
  `user_desc` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '备注',
  `has_pad` int(11) NOT NULL COMMENT '设备类型',
  `is_new_flag` int(11) NOT NULL COMMENT '是否新例子',
  PRIMARY KEY (`postid`,`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
