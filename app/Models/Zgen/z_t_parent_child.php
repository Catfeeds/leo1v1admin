<?php
namespace App\Models\Zgen;
class z_t_parent_child extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_parent_child";


	/*int(10) unsigned */
	const C_parentid='parentid';

	/*int(10) unsigned */
	const C_parent_type='parent_type';

	/*int(10) unsigned */
	const C_userid='userid';

	/*timestamp */
	const C_last_modified_time='last_modified_time';
	function get_parent_type($parentid, $userid ){
		return $this->field_get_value_2( $parentid, $userid  , self::C_parent_type  );
	}
	function get_last_modified_time($parentid, $userid ){
		return $this->field_get_value_2( $parentid, $userid  , self::C_last_modified_time  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="parentid";
        $this->field_id2_name="userid";
        $this->field_table_name="db_weiyi.t_parent_child";
  }

    public function field_get_value_2(  $parentid, $userid,$field_name ) {
        return parent::field_get_value_2(  $parentid, $userid,$field_name ) ;
    }

    public function field_get_list_2( $parentid,  $userid,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $parentid, $userid,  $set_field_arr ) {
        return parent::field_update_list_2( $parentid, $userid,  $set_field_arr );
    }
    public function row_delete_2(  $parentid ,$userid ) {
        return parent::row_delete_2( $parentid ,$userid );
    }


}
/*
  CREATE TABLE `t_parent_child` (
  `parentid` int(10) unsigned NOT NULL COMMENT '家长id',
  `parent_type` int(10) unsigned NOT NULL COMMENT '家长类型',
  `userid` int(10) unsigned NOT NULL COMMENT '学生id',
  `last_modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后修改时间',
  PRIMARY KEY (`parentid`,`parent_type`,`userid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
