<?php
namespace App\Models\Zgen;
class z_t_ass_weekly_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_ass_weekly_info";


	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_week='week';

	/*int(11) */
	const C_warning_student='warning_student';

	/*varchar(500) */
	const C_warning_student_list='warning_student_list';

	/*int(11) */
	const C_end_class_student='end_class_student';

	/*int(11) */
	const C_renw_student='renw_student';

	/*int(11) */
	const C_renw_student_in_plan='renw_student_in_plan';

	/*int(11) */
	const C_renw_price='renw_price';

	/*int(11) */
	const C_tran_price='tran_price';

	/*int(11) */
	const C_tran_require_count='tran_require_count';

	/*int(11) */
	const C_tran_require_succ='tran_require_succ';

	/*int(11) */
	const C_kk_all='kk_all';

	/*int(11) */
	const C_kk_succ='kk_succ';

	/*int(11) */
	const C_kk_fail='kk_fail';

	/*int(11) */
	const C_read_student='read_student';

	/*int(11) */
	const C_lesson_student='lesson_student';

	/*int(11) */
	const C_lesson_count='lesson_count';

	/*varchar(20) */
	const C_lesson_ratio='lesson_ratio';

	/*int(11) */
	const C_tea_leave_lesson_count='tea_leave_lesson_count';

	/*int(11) */
	const C_stu_leave_lesson_count='stu_leave_lesson_count';

	/*int(11) */
	const C_other_lesson_count='other_lesson_count';

	/*varchar(20) */
	const C_lesson_per='lesson_per';

	/*int(11) */
	const C_complain_num='complain_num';

	/*int(11) */
	const C_improper_refund_num='improper_refund_num';

	/*int(11) */
	const C_improper_refund_money='improper_refund_money';

	/*int(11) */
	const C_force_refund_num='force_refund_num';

	/*int(11) */
	const C_force_refund_money='force_refund_money';

	/*int(11) */
	const C_refund_money='refund_money';

	/*int(11) */
	const C_refund_student='refund_student';

	/*int(11) */
	const C_lesson_money='lesson_money';

	/*varchar(20) */
	const C_lesson_count_per='lesson_count_per';

	/*int(11) */
	const C_new_stu_num='new_stu_num';

	/*int(11) */
	const C_end_stu_num='end_stu_num';

	/*int(11) */
	const C_time_type='time_type';

	/*int(10) unsigned */
	const C_id='id';
	function get_adminid($id ){
		return $this->field_get_value( $id , self::C_adminid );
	}
	function get_week($id ){
		return $this->field_get_value( $id , self::C_week );
	}
	function get_warning_student($id ){
		return $this->field_get_value( $id , self::C_warning_student );
	}
	function get_warning_student_list($id ){
		return $this->field_get_value( $id , self::C_warning_student_list );
	}
	function get_end_class_student($id ){
		return $this->field_get_value( $id , self::C_end_class_student );
	}
	function get_renw_student($id ){
		return $this->field_get_value( $id , self::C_renw_student );
	}
	function get_renw_student_in_plan($id ){
		return $this->field_get_value( $id , self::C_renw_student_in_plan );
	}
	function get_renw_price($id ){
		return $this->field_get_value( $id , self::C_renw_price );
	}
	function get_tran_price($id ){
		return $this->field_get_value( $id , self::C_tran_price );
	}
	function get_tran_require_count($id ){
		return $this->field_get_value( $id , self::C_tran_require_count );
	}
	function get_tran_require_succ($id ){
		return $this->field_get_value( $id , self::C_tran_require_succ );
	}
	function get_kk_all($id ){
		return $this->field_get_value( $id , self::C_kk_all );
	}
	function get_kk_succ($id ){
		return $this->field_get_value( $id , self::C_kk_succ );
	}
	function get_kk_fail($id ){
		return $this->field_get_value( $id , self::C_kk_fail );
	}
	function get_read_student($id ){
		return $this->field_get_value( $id , self::C_read_student );
	}
	function get_lesson_student($id ){
		return $this->field_get_value( $id , self::C_lesson_student );
	}
	function get_lesson_count($id ){
		return $this->field_get_value( $id , self::C_lesson_count );
	}
	function get_lesson_ratio($id ){
		return $this->field_get_value( $id , self::C_lesson_ratio );
	}
	function get_tea_leave_lesson_count($id ){
		return $this->field_get_value( $id , self::C_tea_leave_lesson_count );
	}
	function get_stu_leave_lesson_count($id ){
		return $this->field_get_value( $id , self::C_stu_leave_lesson_count );
	}
	function get_other_lesson_count($id ){
		return $this->field_get_value( $id , self::C_other_lesson_count );
	}
	function get_lesson_per($id ){
		return $this->field_get_value( $id , self::C_lesson_per );
	}
	function get_complain_num($id ){
		return $this->field_get_value( $id , self::C_complain_num );
	}
	function get_improper_refund_num($id ){
		return $this->field_get_value( $id , self::C_improper_refund_num );
	}
	function get_improper_refund_money($id ){
		return $this->field_get_value( $id , self::C_improper_refund_money );
	}
	function get_force_refund_num($id ){
		return $this->field_get_value( $id , self::C_force_refund_num );
	}
	function get_force_refund_money($id ){
		return $this->field_get_value( $id , self::C_force_refund_money );
	}
	function get_refund_money($id ){
		return $this->field_get_value( $id , self::C_refund_money );
	}
	function get_refund_student($id ){
		return $this->field_get_value( $id , self::C_refund_student );
	}
	function get_lesson_money($id ){
		return $this->field_get_value( $id , self::C_lesson_money );
	}
	function get_lesson_count_per($id ){
		return $this->field_get_value( $id , self::C_lesson_count_per );
	}
	function get_new_stu_num($id ){
		return $this->field_get_value( $id , self::C_new_stu_num );
	}
	function get_end_stu_num($id ){
		return $this->field_get_value( $id , self::C_end_stu_num );
	}
	function get_time_type($id ){
		return $this->field_get_value( $id , self::C_time_type );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_ass_weekly_info";
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
  CREATE TABLE `t_ass_weekly_info` (
  `adminid` int(11) NOT NULL COMMENT 'adminid',
  `week` int(11) NOT NULL COMMENT '周时间,以每周第一天',
  `warning_student` int(11) NOT NULL COMMENT '预计结课学员数量',
  `warning_student_list` varchar(500) COLLATE latin1_bin NOT NULL COMMENT '预计结课学员名单',
  `end_class_student` int(11) NOT NULL COMMENT '实际结课学员',
  `renw_student` int(11) NOT NULL COMMENT '续费人数',
  `renw_student_in_plan` int(11) NOT NULL COMMENT '计划内续费人数',
  `renw_price` int(11) NOT NULL COMMENT '续费金额',
  `tran_price` int(11) NOT NULL COMMENT '转介绍金额',
  `tran_require_count` int(11) NOT NULL COMMENT '转介绍量',
  `tran_require_succ` int(11) NOT NULL COMMENT '转介绍成功量',
  `kk_all` int(11) NOT NULL COMMENT '扩课申请量',
  `kk_succ` int(11) NOT NULL COMMENT '扩课成功',
  `kk_fail` int(11) NOT NULL COMMENT '扩课失败',
  `read_student` int(11) NOT NULL COMMENT '在读人数',
  `lesson_student` int(11) NOT NULL COMMENT '上课人数',
  `lesson_count` int(11) NOT NULL COMMENT '实际完成课时量',
  `lesson_ratio` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '实际课时系数',
  `tea_leave_lesson_count` int(11) NOT NULL COMMENT '老师请假课程课时量',
  `stu_leave_lesson_count` int(11) NOT NULL COMMENT '学生请假课程课时量',
  `other_lesson_count` int(11) NOT NULL COMMENT '其他情况课时量',
  `lesson_per` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '到课率',
  `complain_num` int(11) NOT NULL COMMENT '投诉量',
  `improper_refund_num` int(11) NOT NULL COMMENT '非正常退费数量',
  `improper_refund_money` int(11) NOT NULL COMMENT '非正常退费金额',
  `force_refund_num` int(11) NOT NULL COMMENT '不可抗力退费数量',
  `force_refund_money` int(11) NOT NULL COMMENT '不可抗力退费金额',
  `refund_money` int(11) NOT NULL COMMENT '退费总金额',
  `refund_student` int(11) NOT NULL COMMENT '退费人数',
  `lesson_money` int(11) NOT NULL COMMENT '课时收入',
  `lesson_count_per` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '课时完成率',
  `new_stu_num` int(11) NOT NULL COMMENT '新签学生数量',
  `end_stu_num` int(11) NOT NULL COMMENT '结课学生数量',
  `time_type` int(11) NOT NULL COMMENT '时间类型 1周,2月',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_record` (`adminid`,`week`,`time_type`),
  KEY `adminid` (`adminid`),
  KEY `week` (`week`),
  KEY `time_type` (`time_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
