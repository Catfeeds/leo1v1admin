<?php
namespace App\Models\Zgen;
class z_t_seller_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_seller_info";


	/*int(10) unsigned */
	const C_sellerid='sellerid';

	/*varchar(64) */
	const C_nick='nick';

	/*varchar(100) */
	const C_face='face';

	/*varchar(16) */
	const C_phone='phone';

	/*int(10) */
	const C_add_time='add_time';

	/*tinyint(3) unsigned */
	const C_del_flag='del_flag';
	function get_nick($sellerid ){
		return $this->field_get_value( $sellerid , self::C_nick );
	}
	function get_face($sellerid ){
		return $this->field_get_value( $sellerid , self::C_face );
	}
	function get_phone($sellerid ){
		return $this->field_get_value( $sellerid , self::C_phone );
	}
	function get_add_time($sellerid ){
		return $this->field_get_value( $sellerid , self::C_add_time );
	}
	function get_del_flag($sellerid ){
		return $this->field_get_value( $sellerid , self::C_del_flag );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="sellerid";
        $this->field_table_name="db_weiyi.t_seller_info";
  }
    public function field_get_list( $sellerid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $sellerid, $set_field_arr) {
        return parent::field_update_list( $sellerid, $set_field_arr);
    }


    public function field_get_value(  $sellerid, $field_name ) {
        return parent::field_get_value( $sellerid, $field_name);
    }

    public function row_delete(  $sellerid) {
        return parent::row_delete( $sellerid);
    }

}

/*
  CREATE TABLE `t_seller_info` (
  `sellerid` int(10) unsigned NOT NULL COMMENT '销售id',
  `nick` varchar(64) NOT NULL COMMENT '昵称',
  `face` varchar(100) NOT NULL DEFAULT '' COMMENT '头像',
  `phone` varchar(16) NOT NULL COMMENT '手机号码',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `del_flag` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '删除标记 0 未删除 1 已删除',
  PRIMARY KEY (`sellerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
