<?php
namespace App\Models\Zgen;
class z_t_admin_card_log extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_admin_card_log";


	/*int(11) */
	const C_logtime='logtime';

	/*int(11) */
	const C_cardid='cardid';


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="logtime";
        $this->field_id2_name="cardid";
        $this->field_table_name="db_weiyi_admin.t_admin_card_log";
  }

    public function field_get_value_2(  $logtime, $cardid,$field_name ) {
        return parent::field_get_value_2(  $logtime, $cardid,$field_name ) ;
    }

    public function field_get_list_2( $logtime,  $cardid,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $logtime, $cardid,  $set_field_arr ) {
        return parent::field_update_list_2( $logtime, $cardid,  $set_field_arr );
    }
    public function row_delete_2(  $logtime ,$cardid ) {
        return parent::row_delete_2( $logtime ,$cardid );
    }


}
/*
  CREATE TABLE `t_admin_card_log` (
  `logtime` int(11) NOT NULL,
  `cardid` int(11) NOT NULL,
  PRIMARY KEY (`logtime`,`cardid`),
  KEY `db_weiyi_admin_t_admin_card_log_cardid_index` (`cardid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
