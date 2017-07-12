<?php
namespace App\Models\Zgen;
class z_t_origin_key  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_origin_key";


	/*varchar(255) */
	const C_key1='key1';

	/*varchar(255) */
	const C_key2='key2';

	/*varchar(255) */
	const C_key3='key3';

	/*varchar(255) */
	const C_key4='key4';

	/*varchar(255) */
	const C_value='value';

	/*int(11) */
	const C_origin_level='origin_level';

	/*int(11) */
	const C_create_time='create_time';
	function get_key1($value ){
		return $this->field_get_value( $value , self::C_key1 );
	}
	function get_key2($value ){
		return $this->field_get_value( $value , self::C_key2 );
	}
	function get_key3($value ){
		return $this->field_get_value( $value , self::C_key3 );
	}
	function get_key4($value ){
		return $this->field_get_value( $value , self::C_key4 );
	}
	function get_origin_level($value ){
		return $this->field_get_value( $value , self::C_origin_level );
	}
	function get_create_time($value ){
		return $this->field_get_value( $value , self::C_create_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="value";
        $this->field_table_name="db_weiyi.t_origin_key";
  }
    public function field_get_list( $value, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $value, $set_field_arr) {
        return parent::field_update_list( $value, $set_field_arr);
    }


    public function field_get_value(  $value, $field_name ) {
        return parent::field_get_value( $value, $field_name);
    }

    public function row_delete(  $value) {
        return parent::row_delete( $value);
    }

}

/*
  CREATE TABLE `t_origin_key` (
  `key1` varchar(255) COLLATE latin1_bin NOT NULL,
  `key2` varchar(255) COLLATE latin1_bin NOT NULL,
  `key3` varchar(255) COLLATE latin1_bin NOT NULL,
  `key4` varchar(255) COLLATE latin1_bin NOT NULL,
  `value` varchar(255) COLLATE latin1_bin NOT NULL,
  `origin_level` int(11) NOT NULL COMMENT '渠道类别 1:A类 2:B类 3:C类',
  `create_time` int(11) NOT NULL COMMENT '渠道生成时间',
  PRIMARY KEY (`key1`,`key2`,`key3`,`key4`),
  UNIQUE KEY `t_origin_key_value_unique` (`value`),
  KEY `value` (`value`),
  KEY `origin_level` (`origin_level`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
