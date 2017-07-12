<?php
namespace App\Models\Zgen;
class z_t_teacher_label  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_label";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_label_origin='label_origin';

	/*varchar(255) */
	const C_interaction='interaction';

	/*varchar(255) */
	const C_class_atmos='class_atmos';

	/*varchar(255) */
	const C_tea_standard='tea_standard';

	/*varchar(255) */
	const C_tea_style='tea_style';

	/*int(11) */
	const C_lessonid='lessonid';

	/*int(11) */
	const C_subject='subject';

	/*int(11) */
	const C_level='level';

	/*int(11) */
	const C_device_level='device_level';

	/*varchar(255) */
	const C_lesson_list='lesson_list';

	/*varchar(500) */
	const C_record_info='record_info';

	/*varchar(255) */
	const C_device_description='device_description';

	/*varchar(500) */
	const C_device_record='device_record';
	function get_teacherid($id ){
		return $this->field_get_value( $id , self::C_teacherid );
	}
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_label_origin($id ){
		return $this->field_get_value( $id , self::C_label_origin );
	}
	function get_interaction($id ){
		return $this->field_get_value( $id , self::C_interaction );
	}
	function get_class_atmos($id ){
		return $this->field_get_value( $id , self::C_class_atmos );
	}
	function get_tea_standard($id ){
		return $this->field_get_value( $id , self::C_tea_standard );
	}
	function get_tea_style($id ){
		return $this->field_get_value( $id , self::C_tea_style );
	}
	function get_lessonid($id ){
		return $this->field_get_value( $id , self::C_lessonid );
	}
	function get_subject($id ){
		return $this->field_get_value( $id , self::C_subject );
	}
	function get_level($id ){
		return $this->field_get_value( $id , self::C_level );
	}
	function get_device_level($id ){
		return $this->field_get_value( $id , self::C_device_level );
	}
	function get_lesson_list($id ){
		return $this->field_get_value( $id , self::C_lesson_list );
	}
	function get_record_info($id ){
		return $this->field_get_value( $id , self::C_record_info );
	}
	function get_device_description($id ){
		return $this->field_get_value( $id , self::C_device_description );
	}
	function get_device_record($id ){
		return $this->field_get_value( $id , self::C_device_record );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_teacher_label";
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
  CREATE TABLE `t_teacher_label` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `teacherid` int(11) NOT NULL COMMENT 'teacherid',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `label_origin` int(11) NOT NULL COMMENT '1 学生试听课,2 教研试听课反馈,3面试评价',
  `interaction` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '师生互动',
  `class_atmos` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '课堂氛围',
  `tea_standard` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '授课规范',
  `tea_style` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '老师风格',
  `lessonid` int(11) NOT NULL COMMENT 'lessonid',
  `subject` int(11) NOT NULL COMMENT '科目',
  `level` int(11) NOT NULL COMMENT '评分等级',
  `device_level` int(11) NOT NULL COMMENT '设备评级',
  `lesson_list` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '试听课lessonid_list',
  `record_info` varchar(500) COLLATE latin1_bin NOT NULL COMMENT '反馈内容',
  `device_description` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '设备描述',
  `device_record` varchar(500) COLLATE latin1_bin NOT NULL COMMENT '设备反馈',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
