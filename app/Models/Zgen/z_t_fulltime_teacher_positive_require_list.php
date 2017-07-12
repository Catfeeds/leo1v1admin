<?php
namespace App\Models\Zgen;
class z_t_fulltime_teacher_positive_require_list  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_fulltime_teacher_positive_require_list";


	/*int(11) */
	const C_id='id';

	/*int(11) */
	const C_adminid='adminid';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_post='post';

	/*int(11) */
	const C_main_department='main_department';

	/*int(11) */
	const C_positive_type='positive_type';

	/*int(11) */
	const C_create_time='create_time';

	/*int(11) */
	const C_positive_time='positive_time';

	/*int(11) */
	const C_level='level';

	/*int(11) */
	const C_positive_level='positive_level';

	/*int(11) */
	const C_assess_id='assess_id';

	/*text */
	const C_self_assessment='self_assessment';

	/*int(11) */
	const C_mater_adminid='mater_adminid';

	/*int(11) */
	const C_master_assess_time='master_assess_time';

	/*int(11) */
	const C_master_deal_flag='master_deal_flag';

	/*int(11) */
	const C_main_mater_adminid='main_mater_adminid';

	/*int(11) */
	const C_main_master_assess_time='main_master_assess_time';

	/*int(11) */
	const C_main_master_deal_flag='main_master_deal_flag';

	/*tinyint(4) */
	const C_rate_stars='rate_stars';
	function get_adminid($id ){
		return $this->field_get_value( $id , self::C_adminid );
	}
	function get_add_time($id ){
		return $this->field_get_value( $id , self::C_add_time );
	}
	function get_post($id ){
		return $this->field_get_value( $id , self::C_post );
	}
	function get_main_department($id ){
		return $this->field_get_value( $id , self::C_main_department );
	}
	function get_positive_type($id ){
		return $this->field_get_value( $id , self::C_positive_type );
	}
	function get_create_time($id ){
		return $this->field_get_value( $id , self::C_create_time );
	}
	function get_positive_time($id ){
		return $this->field_get_value( $id , self::C_positive_time );
	}
	function get_level($id ){
		return $this->field_get_value( $id , self::C_level );
	}
	function get_positive_level($id ){
		return $this->field_get_value( $id , self::C_positive_level );
	}
	function get_assess_id($id ){
		return $this->field_get_value( $id , self::C_assess_id );
	}
	function get_self_assessment($id ){
		return $this->field_get_value( $id , self::C_self_assessment );
	}
	function get_mater_adminid($id ){
		return $this->field_get_value( $id , self::C_mater_adminid );
	}
	function get_master_assess_time($id ){
		return $this->field_get_value( $id , self::C_master_assess_time );
	}
	function get_master_deal_flag($id ){
		return $this->field_get_value( $id , self::C_master_deal_flag );
	}
	function get_main_mater_adminid($id ){
		return $this->field_get_value( $id , self::C_main_mater_adminid );
	}
	function get_main_master_assess_time($id ){
		return $this->field_get_value( $id , self::C_main_master_assess_time );
	}
	function get_main_master_deal_flag($id ){
		return $this->field_get_value( $id , self::C_main_master_deal_flag );
	}
	function get_rate_stars($id ){
		return $this->field_get_value( $id , self::C_rate_stars );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_weiyi.t_fulltime_teacher_positive_require_list";
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
  CREATE TABLE `t_fulltime_teacher_positive_require_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adminid` int(11) NOT NULL,
  `add_time` int(11) NOT NULL COMMENT '提交时间',
  `post` int(11) NOT NULL COMMENT '岗位',
  `main_department` int(11) NOT NULL COMMENT '部门',
  `positive_type` int(11) NOT NULL COMMENT '转正类型',
  `create_time` int(11) NOT NULL COMMENT '入职时间',
  `positive_time` int(11) NOT NULL COMMENT '转正时间',
  `level` int(11) NOT NULL COMMENT '等级',
  `positive_level` int(11) NOT NULL COMMENT '转正后等级',
  `assess_id` int(11) NOT NULL COMMENT '考评表id',
  `self_assessment` text COLLATE latin1_bin NOT NULL COMMENT '自评内容',
  `mater_adminid` int(11) NOT NULL COMMENT '主管',
  `master_assess_time` int(11) NOT NULL COMMENT '主管处理时间',
  `master_deal_flag` int(11) NOT NULL COMMENT '主管处理选项 0未设置,1 同意,2 驳回',
  `main_mater_adminid` int(11) NOT NULL COMMENT '总监',
  `main_master_assess_time` int(11) NOT NULL COMMENT '总监处理时间',
  `main_master_deal_flag` int(11) NOT NULL COMMENT '总监处理选项 0未设置,1 同意,2 驳回',
  `rate_stars` tinyint(4) NOT NULL COMMENT '星级',
  PRIMARY KEY (`id`),
  KEY `adminid` (`adminid`),
  KEY `mater_adminid` (`mater_adminid`),
  KEY `main_mater_adminid` (`main_mater_adminid`),
  KEY `add_time` (`add_time`),
  KEY `assess_id` (`assess_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
