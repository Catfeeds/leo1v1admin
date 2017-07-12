<?php
namespace App\Models\Zgen;
class z_t_tongji_seller_top_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_tongji_seller_top_info";


	/*int(11) */
	const C_tongji_type='tongji_type';

	/*int(11) */
	const C_logtime='logtime';

	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_value='value';

	/*int(11) */
	const C_top_index='top_index';

	/*int(11) */
	const C_top_index2='top_index2';
	function get_tongji_type($adminid ){
		return $this->field_get_value( $adminid , self::C_tongji_type );
	}
	function get_logtime($adminid ){
		return $this->field_get_value( $adminid , self::C_logtime );
	}
	function get_value($adminid ){
		return $this->field_get_value( $adminid , self::C_value );
	}
	function get_top_index($adminid ){
		return $this->field_get_value( $adminid , self::C_top_index );
	}
	function get_top_index2($adminid ){
		return $this->field_get_value( $adminid , self::C_top_index2 );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="adminid";
        $this->field_table_name="db_weiyi_admin.t_tongji_seller_top_info";
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
  CREATE TABLE `t_tongji_seller_top_info` (
  `tongji_type` int(11) NOT NULL,
  `logtime` int(11) NOT NULL,
  `adminid` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `top_index` int(11) NOT NULL,
  `top_index2` int(11) NOT NULL,
  PRIMARY KEY (`tongji_type`,`logtime`,`adminid`),
  KEY `db_weiyi_admin_t_tongji_seller_top_info_adminid_logtime_index` (`adminid`,`logtime`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
