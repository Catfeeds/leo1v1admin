<?php
namespace App\Models\Zgen;
class z_t_agent_cash  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_agent_cash";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_aid='aid';

	/*int(11) */
	const C_cash='cash';

	/*int(11) */
	const C_is_suc_flag='is_suc_flag';

	/*int(11) */
	const C_create_time='create_time';

	/*int(11) */
	const C_type='type';
	function get_aid($id ){
		return $this->field_get_value( $id , self::C_aid );
	}
	function get_cash($id ){
		return $this->field_get_value( $id , self::C_cash );
	}
	function get_is_suc_flag($id ){
		return $this->field_get_value( $id , self::C_is_suc_flag );
	}
	function get_create_time($id ){
		return $this->field_get_value( $id , self::C_create_time );
	}
	function get_type($id ){
		return $this->field_get_value( $id , self::C_type );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_agent_cash";
  }
    public function field_get_list( $id, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $id, $set_field_arr) {
        return parent::field_update_list( $id, $set_field_arr);
    }


    public function field_get_value(  $id, $field_name ) {
        return parent::field_get_value( $id, $field_name);
    }

    public function row_delete(  $id) {
        return parent::row_delete( $id);
    }

}

/*
  CREATE TABLE `t_agent_cash` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `aid` int(11) NOT NULL COMMENT 'agent表关联id',
  `cash` int(11) NOT NULL COMMENT '提现金额',
  `is_suc_flag` int(11) NOT NULL COMMENT '是否提现成功1成功,0失败',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `type` int(11) NOT NULL COMMENT '提现类型1银行卡,2支付宝',
  PRIMARY KEY (`id`),
  KEY `db_weiyi_t_agent_cash_aid_index` (`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
