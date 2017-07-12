<?php
namespace App\Models\Zgen;
class z_t_taobao_item  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_taobao_item";


	/*int(10) unsigned */
	const C_id='id';

	/*varchar(200) */
	const C_cid='cid';

	/*varchar(100) */
	const C_open_iid='open_iid';

	/*varchar(500) */
	const C_title='title';

	/*varchar(500) */
	const C_pict_url='pict_url';

	/*int(11) */
	const C_price='price';

	/*varchar(20) */
	const C_last_modified='last_modified';

	/*varchar(200) */
	const C_product_id='product_id';

	/*int(11) */
	const C_status='status';

	/*int(11) */
	const C_sort_order='sort_order';
	function get_id($open_iid ){
		return $this->field_get_value( $open_iid , self::C_id );
	}
	function get_cid($open_iid ){
		return $this->field_get_value( $open_iid , self::C_cid );
	}
	function get_title($open_iid ){
		return $this->field_get_value( $open_iid , self::C_title );
	}
	function get_pict_url($open_iid ){
		return $this->field_get_value( $open_iid , self::C_pict_url );
	}
	function get_price($open_iid ){
		return $this->field_get_value( $open_iid , self::C_price );
	}
	function get_last_modified($open_iid ){
		return $this->field_get_value( $open_iid , self::C_last_modified );
	}
	function get_product_id($open_iid ){
		return $this->field_get_value( $open_iid , self::C_product_id );
	}
	function get_status($open_iid ){
		return $this->field_get_value( $open_iid , self::C_status );
	}
	function get_sort_order($open_iid ){
		return $this->field_get_value( $open_iid , self::C_sort_order );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="open_iid";
        $this->field_table_name="db_weiyi.t_taobao_item";
  }
    public function field_get_list( $open_iid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $open_iid, $set_field_arr) {
        return parent::field_update_list( $open_iid, $set_field_arr);
    }


    public function field_get_value(  $open_iid, $field_name ) {
        return parent::field_get_value( $open_iid, $field_name);
    }

    public function row_delete(  $open_iid) {
        return parent::row_delete( $open_iid);
    }

}

/*
  CREATE TABLE `t_taobao_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` varchar(200) COLLATE latin1_bin DEFAULT NULL,
  `open_iid` varchar(100) COLLATE latin1_bin NOT NULL,
  `title` varchar(500) COLLATE latin1_bin NOT NULL,
  `pict_url` varchar(500) COLLATE latin1_bin NOT NULL,
  `price` int(11) NOT NULL,
  `last_modified` varchar(20) COLLATE latin1_bin NOT NULL,
  `product_id` varchar(200) COLLATE latin1_bin NOT NULL COMMENT '产品id',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 已下架 1 未下架',
  `sort_order` int(11) NOT NULL COMMENT '淘宝商品的排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
