<?php
namespace App\Models\Zgen;
class z_t_book_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_book_info";


	/*int(10) */
	const C_id='id';

	/*int(10) unsigned */
	const C_userid='userid';

	/*int(10) unsigned */
	const C_book_time='book_time';

	/*int(11) */
	const C_book_time_next='book_time_next';

	/*tinyint(4) */
	const C_status='status';

	/*tinyint(4) */
	const C_preform_lv='preform_lv';

	/*tinyint(4) */
	const C_manage_person='manage_person';

	/*varchar(16) */
	const C_phone='phone';

	/*varchar(32) */
	const C_staff='staff';

	/*int(10) unsigned */
	const C_staff_time='staff_time';

	/*varchar(100) */
	const C_staff_note='staff_note';

	/*int(10) unsigned */
	const C_class_time='class_time';

	/*int(10) unsigned */
	const C_course_id='course_id';

	/*timestamp */
	const C_last_modified_time='last_modified_time';

	/*varchar(32) */
	const C_nick='nick';

	/*smallint(6) */
	const C_grade='grade';

	/*tinyint(4) */
	const C_subject='subject';

	/*varchar(32) */
	const C_origin='origin';

	/*varchar(300) */
	const C_consult_desc='consult_desc';

	/*tinyint(4) */
	const C_has_pad='has_pad';

	/*tinyint(4) */
	const C_trial_type='trial_type';

	/*varchar(255) */
	const C_sys_operator='sys_operator';

	/*int(10) unsigned */
	const C_sys_opt_time='sys_opt_time';

	/*varchar(255) */
	const C_phone_location='phone_location';

	/*int(11) */
	const C_register_flag='register_flag';

	/*int(10) */
	const C_teacherid='teacherid';

	/*varchar(255) */
	const C_assigner='assigner';

	/*varchar(255) */
	const C_qq='qq';

	/*varchar(255) */
	const C_e_name='e_name';
	function get_userid($id ){
		return $this->field_get_value( $id , self::C_userid );
	}
	function get_book_time($id ){
		return $this->field_get_value( $id , self::C_book_time );
	}
	function get_book_time_next($id ){
		return $this->field_get_value( $id , self::C_book_time_next );
	}
	function get_status($id ){
		return $this->field_get_value( $id , self::C_status );
	}
	function get_preform_lv($id ){
		return $this->field_get_value( $id , self::C_preform_lv );
	}
	function get_manage_person($id ){
		return $this->field_get_value( $id , self::C_manage_person );
	}
	function get_phone($id ){
		return $this->field_get_value( $id , self::C_phone );
	}
	function get_staff($id ){
		return $this->field_get_value( $id , self::C_staff );
	}
	function get_staff_time($id ){
		return $this->field_get_value( $id , self::C_staff_time );
	}
	function get_staff_note($id ){
		return $this->field_get_value( $id , self::C_staff_note );
	}
	function get_class_time($id ){
		return $this->field_get_value( $id , self::C_class_time );
	}
	function get_course_id($id ){
		return $this->field_get_value( $id , self::C_course_id );
	}
	function get_last_modified_time($id ){
		return $this->field_get_value( $id , self::C_last_modified_time );
	}
	function get_nick($id ){
		return $this->field_get_value( $id , self::C_nick );
	}
	function get_grade($id ){
		return $this->field_get_value( $id , self::C_grade );
	}
	function get_subject($id ){
		return $this->field_get_value( $id , self::C_subject );
	}
	function get_origin($id ){
		return $this->field_get_value( $id , self::C_origin );
	}
	function get_consult_desc($id ){
		return $this->field_get_value( $id , self::C_consult_desc );
	}
	function get_has_pad($id ){
		return $this->field_get_value( $id , self::C_has_pad );
	}
	function get_trial_type($id ){
		return $this->field_get_value( $id , self::C_trial_type );
	}
	function get_sys_operator($id ){
		return $this->field_get_value( $id , self::C_sys_operator );
	}
	function get_sys_opt_time($id ){
		return $this->field_get_value( $id , self::C_sys_opt_time );
	}
	function get_phone_location($id ){
		return $this->field_get_value( $id , self::C_phone_location );
	}
	function get_register_flag($id ){
		return $this->field_get_value( $id , self::C_register_flag );
	}
	function get_teacherid($id ){
		return $this->field_get_value( $id , self::C_teacherid );
	}
	function get_assigner($id ){
		return $this->field_get_value( $id , self::C_assigner );
	}
	function get_qq($id ){
		return $this->field_get_value( $id , self::C_qq );
	}
	function get_e_name($id ){
		return $this->field_get_value( $id , self::C_e_name );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_book_info";
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
  CREATE TABLE `t_book_info` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL COMMENT '学生账号',
  `book_time` int(10) unsigned NOT NULL COMMENT '预约时间',
  `book_time_next` int(11) DEFAULT NULL COMMENT '下次回访时间',
  `status` tinyint(4) NOT NULL COMMENT '用户状态',
  `preform_lv` tinyint(4) NOT NULL COMMENT '成绩所处区间（1 0-5名 2 5-15名 3 15名之后）',
  `manage_person` tinyint(4) NOT NULL COMMENT '学习总管（1 父亲 2 母亲）',
  `phone` varchar(16) NOT NULL COMMENT '家长联系方式',
  `staff` varchar(32) NOT NULL DEFAULT '' COMMENT '联系人',
  `staff_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '联系时间',
  `staff_note` varchar(100) NOT NULL DEFAULT '' COMMENT '联系小结',
  `class_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '预约上课的时间',
  `course_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '预约课程的id',
  `last_modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后修改时间',
  `nick` varchar(32) NOT NULL DEFAULT '' COMMENT '学生昵称',
  `grade` smallint(6) NOT NULL DEFAULT '0' COMMENT '用户年级（100 小学 200 初中 300 高中 400 大学 500 硕士 600 博士 900 毕业）',
  `subject` tinyint(4) NOT NULL DEFAULT '0' COMMENT '科目 0未设定 1语文 2数学 3英语 4化学 5物理 6生物 7政治 8历史 9地理',
  `origin` varchar(32) NOT NULL DEFAULT '' COMMENT '来源',
  `consult_desc` varchar(300) NOT NULL COMMENT '咨询问题描述',
  `has_pad` tinyint(4) NOT NULL COMMENT 'pad的型号 1ipad 2 android pad 3 无pad',
  `trial_type` tinyint(4) NOT NULL COMMENT '所试听的类型 1 1v1, 2 小班课',
  `sys_operator` varchar(255) NOT NULL COMMENT '负责人',
  `sys_opt_time` int(10) unsigned NOT NULL,
  `phone_location` varchar(255) NOT NULL COMMENT '电话归属地',
  `register_flag` int(11) NOT NULL COMMENT '注册标志 0 注册用户 1 电话用户',
  `teacherid` int(10) DEFAULT NULL COMMENT '分配老师',
  `assigner` varchar(255) NOT NULL,
  `qq` varchar(255) NOT NULL,
  `e_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `phone` (`phone`),
  KEY `register_flag__book_time` (`register_flag`,`book_time`),
  KEY `register_flag__book_time_next` (`register_flag`,`book_time_next`),
  KEY `sys_opt_time` (`sys_opt_time`)
) ENGINE=InnoDB AUTO_INCREMENT=2870 DEFAULT CHARSET=utf8 COMMENT='预约信息'
 */
