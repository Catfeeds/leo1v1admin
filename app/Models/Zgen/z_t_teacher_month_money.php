<?php
namespace App\Models\Zgen;
class z_t_teacher_month_money extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_month_money";


	/*int(11) */
	const C_logtime='logtime';

	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_all_count='all_count';

	/*int(11) */
	const C_l1v1_count='l1v1_count';

	/*int(11) */
	const C_test_count='test_count';

	/*int(11) */
	const C_money_all_count='money_all_count';

	/*int(11) */
	const C_money_l1v1_count='money_l1v1_count';

	/*int(11) */
	const C_money_test_count='money_test_count';

	/*int(11) */
	const C_confirm_flag='confirm_flag';

	/*int(11) */
	const C_confirm_time='confirm_time';

	/*int(11) */
	const C_confirm_adminid='confirm_adminid';

	/*int(11) */
	const C_pay_flag='pay_flag';

	/*int(11) */
	const C_pay_time='pay_time';

	/*int(11) */
	const C_pay_adminid='pay_adminid';
	function get_all_count($logtime, $teacherid ){
		return $this->field_get_value_2( $logtime, $teacherid  , self::C_all_count  );
	}
	function get_l1v1_count($logtime, $teacherid ){
		return $this->field_get_value_2( $logtime, $teacherid  , self::C_l1v1_count  );
	}
	function get_test_count($logtime, $teacherid ){
		return $this->field_get_value_2( $logtime, $teacherid  , self::C_test_count  );
	}
	function get_money_all_count($logtime, $teacherid ){
		return $this->field_get_value_2( $logtime, $teacherid  , self::C_money_all_count  );
	}
	function get_money_l1v1_count($logtime, $teacherid ){
		return $this->field_get_value_2( $logtime, $teacherid  , self::C_money_l1v1_count  );
	}
	function get_money_test_count($logtime, $teacherid ){
		return $this->field_get_value_2( $logtime, $teacherid  , self::C_money_test_count  );
	}
	function get_confirm_flag($logtime, $teacherid ){
		return $this->field_get_value_2( $logtime, $teacherid  , self::C_confirm_flag  );
	}
	function get_confirm_time($logtime, $teacherid ){
		return $this->field_get_value_2( $logtime, $teacherid  , self::C_confirm_time  );
	}
	function get_confirm_adminid($logtime, $teacherid ){
		return $this->field_get_value_2( $logtime, $teacherid  , self::C_confirm_adminid  );
	}
	function get_pay_flag($logtime, $teacherid ){
		return $this->field_get_value_2( $logtime, $teacherid  , self::C_pay_flag  );
	}
	function get_pay_time($logtime, $teacherid ){
		return $this->field_get_value_2( $logtime, $teacherid  , self::C_pay_time  );
	}
	function get_pay_adminid($logtime, $teacherid ){
		return $this->field_get_value_2( $logtime, $teacherid  , self::C_pay_adminid  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="logtime";
        $this->field_id2_name="teacherid";
        $this->field_table_name="db_weiyi.t_teacher_month_money";
  }

    public function field_get_value_2(  $logtime, $teacherid,$field_name ) {
        return parent::field_get_value_2(  $logtime, $teacherid,$field_name ) ;
    }

    public function field_get_list_2( $logtime,  $teacherid,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $logtime, $teacherid,  $set_field_arr ) {
        return parent::field_update_list_2( $logtime, $teacherid,  $set_field_arr );
    }
    public function row_delete_2(  $logtime ,$teacherid ) {
        return parent::row_delete_2( $logtime ,$teacherid );
    }


}
/*
  CREATE TABLE `t_teacher_month_money` (
  `logtime` int(11) NOT NULL,
  `teacherid` int(11) NOT NULL,
  `all_count` int(11) NOT NULL,
  `l1v1_count` int(11) NOT NULL,
  `test_count` int(11) NOT NULL,
  `money_all_count` int(11) NOT NULL,
  `money_l1v1_count` int(11) NOT NULL,
  `money_test_count` int(11) NOT NULL,
  `confirm_flag` int(11) NOT NULL,
  `confirm_time` int(11) NOT NULL,
  `confirm_adminid` int(11) NOT NULL,
  `pay_flag` int(11) NOT NULL,
  `pay_time` int(11) NOT NULL,
  `pay_adminid` int(11) NOT NULL,
  PRIMARY KEY (`logtime`,`teacherid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
