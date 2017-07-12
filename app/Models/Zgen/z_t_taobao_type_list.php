<?php
namespace App\Models\Zgen;
class z_t_taobao_type_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_taobao_type_list";


	/*int(11) */
	const C_cid='cid';

	/*int(11) */
	const C_parent_cid='parent_cid';

	/*varchar(255) */
	const C_name='name';

	/*int(11) */
	const C_sort_order='sort_order';

	/*int(11) */
	const C_type='type';

	/*int(11) */
	const C_status='status';
	function get_parent_cid($cid ){
		return $this->field_get_value( $cid , self::C_parent_cid );
	}
	function get_name($cid ){
		return $this->field_get_value( $cid , self::C_name );
	}
	function get_sort_order($cid ){
		return $this->field_get_value( $cid , self::C_sort_order );
	}
	function get_type($cid ){
		return $this->field_get_value( $cid , self::C_type );
	}
	function get_status($cid ){
		return $this->field_get_value( $cid , self::C_status );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="cid";
        $this->field_table_name="db_weiyi.t_taobao_type_list";
  }
    public function field_get_list( $cid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $cid, $set_field_arr) {
        return parent::field_update_list( $cid, $set_field_arr);
    }


    public function field_get_value(  $cid, $field_name ) {
        return parent::field_get_value( $cid, $field_name);
    }

    public function row_delete(  $cid) {
        return parent::row_delete( $cid);
    }

}

/*
  CREATE TABLE `t_taobao_type_list` (
  `cid` int(11) NOT NULL,
  `parent_cid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE latin1_bin NOT NULL,
  `sort_order` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '分类的类型 0 默认值 1 首页显示',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '是否显示此分类 0 不显示 1 显示',
  PRIMARY KEY (`cid`,`parent_cid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
