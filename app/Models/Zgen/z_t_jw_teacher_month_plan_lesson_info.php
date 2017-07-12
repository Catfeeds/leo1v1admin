<?php
namespace App\Models\Zgen;
class z_t_jw_teacher_month_plan_lesson_info extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_jw_teacher_month_plan_lesson_info";


	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_month='month';

	/*int(11) */
	const C_all_plan='all_plan';

	/*int(11) */
	const C_all_plan_done='all_plan_done';

	/*int(11) */
	const C_un_plan='un_plan';

	/*int(11) */
	const C_gz_count='gz_count';

	/*int(11) */
	const C_back_count='back_count';

	/*varchar(255) */
	const C_plan_per='plan_per';

	/*int(11) */
	const C_tran_count='tran_count';

	/*int(11) */
	const C_tran_count_seller='tran_count_seller';

	/*int(11) */
	const C_tran_count_ass='tran_count_ass';

	/*varchar(255) */
	const C_tran_per='tran_per';
	function get_all_plan($adminid, $month ){
		return $this->field_get_value_2( $adminid, $month  , self::C_all_plan  );
	}
	function get_all_plan_done($adminid, $month ){
		return $this->field_get_value_2( $adminid, $month  , self::C_all_plan_done  );
	}
	function get_un_plan($adminid, $month ){
		return $this->field_get_value_2( $adminid, $month  , self::C_un_plan  );
	}
	function get_gz_count($adminid, $month ){
		return $this->field_get_value_2( $adminid, $month  , self::C_gz_count  );
	}
	function get_back_count($adminid, $month ){
		return $this->field_get_value_2( $adminid, $month  , self::C_back_count  );
	}
	function get_plan_per($adminid, $month ){
		return $this->field_get_value_2( $adminid, $month  , self::C_plan_per  );
	}
	function get_tran_count($adminid, $month ){
		return $this->field_get_value_2( $adminid, $month  , self::C_tran_count  );
	}
	function get_tran_count_seller($adminid, $month ){
		return $this->field_get_value_2( $adminid, $month  , self::C_tran_count_seller  );
	}
	function get_tran_count_ass($adminid, $month ){
		return $this->field_get_value_2( $adminid, $month  , self::C_tran_count_ass  );
	}
	function get_tran_per($adminid, $month ){
		return $this->field_get_value_2( $adminid, $month  , self::C_tran_per  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="adminid";
        $this->field_id2_name="month";
        $this->field_table_name="db_weiyi.t_jw_teacher_month_plan_lesson_info";
  }

    public function field_get_value_2(  $adminid, $month,$field_name ) {
        return parent::field_get_value_2(  $adminid, $month,$field_name ) ;
    }

    public function field_get_list_2( $adminid,  $month,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $adminid, $month,  $set_field_arr ) {
        return parent::field_update_list_2( $adminid, $month,  $set_field_arr );
    }
    public function row_delete_2(  $adminid ,$month ) {
        return parent::row_delete_2( $adminid ,$month );
    }


}
/*
  CREATE TABLE `t_jw_teacher_month_plan_lesson_info` (
  `adminid` int(11) NOT NULL COMMENT '教务adminid',
  `month` int(11) NOT NULL COMMENT '月度时间,以每月一日',
  `all_plan` int(11) NOT NULL COMMENT '总排课量',
  `all_plan_done` int(11) NOT NULL COMMENT '已排课程',
  `un_plan` int(11) NOT NULL COMMENT '待排量',
  `gz_count` int(11) NOT NULL COMMENT '挂载量',
  `back_count` int(11) NOT NULL COMMENT '退回量',
  `plan_per` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '排课完成率',
  `tran_count` int(11) NOT NULL COMMENT '排课转化量',
  `tran_count_seller` int(11) NOT NULL COMMENT '排课转化量(销售)',
  `tran_count_ass` int(11) NOT NULL COMMENT '排课转化量(助教)',
  `tran_per` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '排课转化率',
  PRIMARY KEY (`adminid`,`month`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
