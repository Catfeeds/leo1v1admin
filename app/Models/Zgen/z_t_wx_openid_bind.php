<?php
namespace App\Models\Zgen;
class z_t_wx_openid_bind extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_wx_openid_bind";


	/*varchar(255) */
	const C_openid='openid';

	/*int(11) */
	const C_role='role';

	/*int(11) */
	const C_userid='userid';
	function get_userid($openid, $role ){
		return $this->field_get_value_2( $openid, $role  , self::C_userid  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="openid";
        $this->field_id2_name="role";
        $this->field_table_name="db_weiyi.t_wx_openid_bind";
  }

    public function field_get_value_2(  $openid, $role,$field_name ) {
        return parent::field_get_value_2(  $openid, $role,$field_name ) ;
    }

    public function field_get_list_2( $openid,  $role,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $openid, $role,  $set_field_arr ) {
        return parent::field_update_list_2( $openid, $role,  $set_field_arr );
    }
    public function row_delete_2(  $openid ,$role ) {
        return parent::row_delete_2( $openid ,$role );
    }


}
/*
  CREATE TABLE `t_wx_openid_bind` (
  `openid` varchar(255) COLLATE latin1_bin NOT NULL,
  `role` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`openid`,`role`),
  KEY `t_wx_openid_bind_userid_index` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
