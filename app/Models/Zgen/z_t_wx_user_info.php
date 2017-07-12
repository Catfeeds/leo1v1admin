<?php
namespace App\Models\Zgen;
class z_t_wx_user_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_wx_user_info";


	/*varchar(255) */
	const C_openid='openid';

	/*int(11) */
	const C_update_time='update_time';

	/*int(11) */
	const C_sex='sex';

	/*varchar(255) */
	const C_language='language';

	/*varchar(255) */
	const C_city='city';

	/*varchar(255) */
	const C_country='country';

	/*varchar(255) */
	const C_province='province';

	/*varchar(255) */
	const C_headimgurl='headimgurl';

	/*varchar(255) */
	const C_nickname='nickname';
	function get_update_time($openid ){
		return $this->field_get_value( $openid , self::C_update_time );
	}
	function get_sex($openid ){
		return $this->field_get_value( $openid , self::C_sex );
	}
	function get_language($openid ){
		return $this->field_get_value( $openid , self::C_language );
	}
	function get_city($openid ){
		return $this->field_get_value( $openid , self::C_city );
	}
	function get_country($openid ){
		return $this->field_get_value( $openid , self::C_country );
	}
	function get_province($openid ){
		return $this->field_get_value( $openid , self::C_province );
	}
	function get_headimgurl($openid ){
		return $this->field_get_value( $openid , self::C_headimgurl );
	}
	function get_nickname($openid ){
		return $this->field_get_value( $openid , self::C_nickname );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="openid";
        $this->field_table_name="db_weiyi_admin.t_wx_user_info";
  }
    public function field_get_list( $openid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $openid, $set_field_arr) {
        return parent::field_update_list( $openid, $set_field_arr);
    }


    public function field_get_value(  $openid, $field_name ) {
        return parent::field_get_value( $openid, $field_name);
    }

    public function row_delete(  $openid) {
        return parent::row_delete( $openid);
    }

}

/*
  CREATE TABLE `t_wx_user_info` (
  `openid` varchar(255) COLLATE latin1_bin NOT NULL,
  `update_time` int(11) NOT NULL,
  `sex` int(11) NOT NULL,
  `language` varchar(255) COLLATE latin1_bin NOT NULL,
  `city` varchar(255) COLLATE latin1_bin NOT NULL,
  `country` varchar(255) COLLATE latin1_bin NOT NULL,
  `province` varchar(255) COLLATE latin1_bin NOT NULL,
  `headimgurl` varchar(255) COLLATE latin1_bin NOT NULL,
  `nickname` varchar(255) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`openid`),
  KEY `db_weiyi_admin_t_wx_user_info_update_time_index` (`update_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
