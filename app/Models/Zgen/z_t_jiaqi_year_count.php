<?php
namespace App\Models\Zgen;
class z_t_jiaqi_year_count extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_jiaqi_year_count";


	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_year='year';

	/*int(11) */
	const C_year_hour_count='year_hour_count';

	/*int(11) */
	const C_sick_hour_count='sick_hour_count';

	/*int(11) */
	const C_absence_hour_count='absence_hour_count';
	function get_year_hour_count($adminid, $year ){
		return $this->field_get_value_2( $adminid, $year  , self::C_year_hour_count  );
	}
	function get_sick_hour_count($adminid, $year ){
		return $this->field_get_value_2( $adminid, $year  , self::C_sick_hour_count  );
	}
	function get_absence_hour_count($adminid, $year ){
		return $this->field_get_value_2( $adminid, $year  , self::C_absence_hour_count  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="adminid";
        $this->field_id2_name="year";
        $this->field_table_name="db_weiyi_admin.t_jiaqi_year_count";
  }

    public function field_get_value_2(  $adminid, $year,$field_name ) {
        return parent::field_get_value_2(  $adminid, $year,$field_name ) ;
    }

    public function field_get_list_2( $adminid,  $year,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $adminid, $year,  $set_field_arr ) {
        return parent::field_update_list_2( $adminid, $year,  $set_field_arr );
    }
    public function row_delete_2(  $adminid ,$year ) {
        return parent::row_delete_2( $adminid ,$year );
    }


}
/*
  CREATE TABLE `t_jiaqi_year_count` (
  `adminid` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `year_hour_count` int(11) NOT NULL,
  `sick_hour_count` int(11) NOT NULL,
  `absence_hour_count` int(11) NOT NULL,
  PRIMARY KEY (`adminid`,`year`),
  KEY `db_weiyi_admin_t_jiaqi_year_count_year_index` (`year`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
