<?php
namespace App\Models\Zgen;
class z_t_month_ass_student_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_month_ass_student_info";


	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_month='month';

	/*int(11) */
	const C_read_student='read_student';

	/*int(11) */
	const C_stop_student='stop_student';

	/*int(11) */
	const C_month_stop_student='month_stop_student';

	/*int(11) */
	const C_all_student='all_student';

	/*int(11) */
	const C_warning_student='warning_student';

	/*int(11) */
	const C_renw_price='renw_price';

	/*int(11) */
	const C_renw_student='renw_student';

	/*int(11) */
	const C_tran_price='tran_price';

	/*int(11) */
	const C_lesson_total='lesson_total';

	/*varchar(255) */
	const C_lesson_ratio='lesson_ratio';

	/*int(11) */
	const C_kk_num='kk_num';

	/*varchar(5000) */
	const C_userid_list='userid_list';

	/*int(11) */
	const C_refund_student='refund_student';

	/*int(11) */
	const C_new_refund_money='new_refund_money';

	/*int(11) */
	const C_renw_refund_money='renw_refund_money';

	/*int(11) */
	const C_read_student_last='read_student_last';

	/*text */
	const C_userid_list_last='userid_list_last';

	/*int(11) */
	const C_lesson_total_old='lesson_total_old';

	/*int(11) */
	const C_kpi_type='kpi_type';
	function get_month($adminid ){
		return $this->field_get_value( $adminid , self::C_month );
	}
	function get_read_student($adminid ){
		return $this->field_get_value( $adminid , self::C_read_student );
	}
	function get_stop_student($adminid ){
		return $this->field_get_value( $adminid , self::C_stop_student );
	}
	function get_month_stop_student($adminid ){
		return $this->field_get_value( $adminid , self::C_month_stop_student );
	}
	function get_all_student($adminid ){
		return $this->field_get_value( $adminid , self::C_all_student );
	}
	function get_warning_student($adminid ){
		return $this->field_get_value( $adminid , self::C_warning_student );
	}
	function get_renw_price($adminid ){
		return $this->field_get_value( $adminid , self::C_renw_price );
	}
	function get_renw_student($adminid ){
		return $this->field_get_value( $adminid , self::C_renw_student );
	}
	function get_tran_price($adminid ){
		return $this->field_get_value( $adminid , self::C_tran_price );
	}
	function get_lesson_total($adminid ){
		return $this->field_get_value( $adminid , self::C_lesson_total );
	}
	function get_lesson_ratio($adminid ){
		return $this->field_get_value( $adminid , self::C_lesson_ratio );
	}
	function get_kk_num($adminid ){
		return $this->field_get_value( $adminid , self::C_kk_num );
	}
	function get_userid_list($adminid ){
		return $this->field_get_value( $adminid , self::C_userid_list );
	}
	function get_refund_student($adminid ){
		return $this->field_get_value( $adminid , self::C_refund_student );
	}
	function get_new_refund_money($adminid ){
		return $this->field_get_value( $adminid , self::C_new_refund_money );
	}
	function get_renw_refund_money($adminid ){
		return $this->field_get_value( $adminid , self::C_renw_refund_money );
	}
	function get_read_student_last($adminid ){
		return $this->field_get_value( $adminid , self::C_read_student_last );
	}
	function get_userid_list_last($adminid ){
		return $this->field_get_value( $adminid , self::C_userid_list_last );
	}
	function get_lesson_total_old($adminid ){
		return $this->field_get_value( $adminid , self::C_lesson_total_old );
	}
	function get_kpi_type($adminid ){
		return $this->field_get_value( $adminid , self::C_kpi_type );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="adminid";
        $this->field_table_name="db_weiyi.t_month_ass_student_info";
  }
    public function field_get_list( $adminid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $adminid, $set_field_arr) {
        return parent::field_update_list( $adminid, $set_field_arr);
    }


    public function field_get_value(  $adminid, $field_name ) {
        return parent::field_get_value( $adminid, $field_name);
    }

    public function row_delete(  $adminid) {
        return parent::row_delete( $adminid);
    }

}

/*
  CREATE TABLE `t_month_ass_student_info` (
  `adminid` int(11) NOT NULL COMMENT '助教adminid',
  `month` int(11) NOT NULL COMMENT '月度时间,以每月一日',
  `read_student` int(11) NOT NULL COMMENT '在读学员人数',
  `stop_student` int(11) NOT NULL COMMENT '结课学员人数',
  `month_stop_student` int(11) NOT NULL COMMENT '当月结课人数',
  `all_student` int(11) NOT NULL COMMENT '在册人数',
  `warning_student` int(11) NOT NULL COMMENT '预警学员人数',
  `renw_price` int(11) NOT NULL COMMENT '续费金额',
  `renw_student` int(11) NOT NULL COMMENT '续费人数',
  `tran_price` int(11) NOT NULL COMMENT '转介绍金额',
  `lesson_total` int(11) NOT NULL COMMENT '当月课时总量',
  `lesson_ratio` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '课时系数',
  `kk_num` int(11) NOT NULL COMMENT '扩课成功数',
  `userid_list` varchar(5000) COLLATE latin1_bin NOT NULL COMMENT '在读学员名单',
  `refund_student` int(11) NOT NULL COMMENT '退费人数',
  `new_refund_money` int(11) NOT NULL COMMENT '新签退费金额',
  `renw_refund_money` int(11) NOT NULL COMMENT '续费退费金额',
  `read_student_last` int(11) NOT NULL COMMENT '上月有效人数',
  `userid_list_last` text COLLATE latin1_bin NOT NULL COMMENT '上月有效详单',
  `lesson_total_old` int(11) NOT NULL COMMENT '月有效课时数',
  `kpi_type` int(11) NOT NULL COMMENT '助教月kpi版本',
  PRIMARY KEY (`adminid`,`month`,`kpi_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
