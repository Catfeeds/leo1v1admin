<?php
namespace App\Models\Zgen;
class z_t_complaint_deal_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_complaint_deal_info";


	/*int(10) unsigned */
	const C_deal_id='deal_id';

	/*int(11) */
	const C_complaint_id='complaint_id';

	/*int(11) */
	const C_deal_adminid='deal_adminid';

	/*int(11) */
	const C_deal_time='deal_time';

	/*text */
	const C_deal_info='deal_info';
	function get_complaint_id($deal_id ){
		return $this->field_get_value( $deal_id , self::C_complaint_id );
	}
	function get_deal_adminid($deal_id ){
		return $this->field_get_value( $deal_id , self::C_deal_adminid );
	}
	function get_deal_time($deal_id ){
		return $this->field_get_value( $deal_id , self::C_deal_time );
	}
	function get_deal_info($deal_id ){
		return $this->field_get_value( $deal_id , self::C_deal_info );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="deal_id";
        $this->field_table_name="db_weiyi.t_complaint_deal_info";
  }
    public function field_get_list( $deal_id, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $deal_id, $set_field_arr) {
        return parent::field_update_list( $deal_id, $set_field_arr);
    }


    public function field_get_value(  $deal_id, $field_name ) {
        return parent::field_get_value( $deal_id, $field_name);
    }

    public function row_delete(  $deal_id) {
        return parent::row_delete( $deal_id);
    }

}

/*
  CREATE TABLE `t_complaint_deal_info` (
  `deal_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `complaint_id` int(11) NOT NULL COMMENT '投诉id',
  `deal_adminid` int(11) NOT NULL COMMENT '处理者id',
  `deal_time` int(11) NOT NULL COMMENT '处理时间',
  `deal_info` text COLLATE latin1_bin NOT NULL COMMENT '处理说明',
  PRIMARY KEY (`deal_id`),
  KEY `db_weiyi_t_complaint_deal_info_complaint_id_index` (`complaint_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
