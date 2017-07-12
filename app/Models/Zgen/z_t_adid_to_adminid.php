<?php
namespace App\Models\Zgen;
class z_t_adid_to_adminid extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_adid_to_adminid";


	/*int(10) unsigned */
	const C_adid='adid';

	/*int(10) unsigned */
	const C_adminid='adminid';


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="adid";
        $this->field_id2_name="adminid";
        $this->field_table_name="db_weiyi_admin.t_adid_to_adminid";
  }

    public function field_get_value_2(  $adid, $adminid,$field_name ) {
        return parent::field_get_value_2(  $adid, $adminid,$field_name ) ;
    }

    public function field_get_list_2( $adid,  $adminid,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $adid, $adminid,  $set_field_arr ) {
        return parent::field_update_list_2( $adid, $adminid,  $set_field_arr );
    }
    public function row_delete_2(  $adid ,$adminid ) {
        return parent::row_delete_2( $adid ,$adminid );
    }


}
/*
  CREATE TABLE `t_adid_to_adminid` (
  `adid` int(10) unsigned NOT NULL COMMENT 'account库中存储的助教id',
  `adminid` int(10) unsigned NOT NULL COMMENT 'admin库中的助教id',
  PRIMARY KEY (`adid`,`adminid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
