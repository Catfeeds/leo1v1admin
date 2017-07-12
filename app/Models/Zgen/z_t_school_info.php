<?php
namespace App\Models\Zgen;
class z_t_school_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_tool.t_school_info";


	/*int(11) */
	const C_schoolid='schoolid';

	/*varchar(50) */
	const C_school_name='school_name';

	/*int(11) */
	const C_school_type='school_type';

	/*int(11) */
	const C_school_sub_type='school_sub_type';

	/*int(11) */
	const C_school_area='school_area';

	/*varchar(100) */
	const C_school_img='school_img';

	/*varchar(100) */
	const C_school_img_min='school_img_min';

	/*varchar(20000) */
	const C_school_intro='school_intro';

	/*varchar(3000) */
	const C_school_charac='school_charac';

	/*varchar(10000) */
	const C_school_contact='school_contact';

	/*varchar(3000) */
	const C_school_address='school_address';

	/*varchar(100) */
	const C_school_web='school_web';

	/*varchar(10000) */
	const C_school_recruit='school_recruit';

	/*varchar(3000) */
	const C_school_study_cost='school_study_cost';

	/*varchar(3000) */
	const C_school_live_cost='school_live_cost';

	/*varchar(3000) */
	const C_school_live_info='school_live_info';

	/*int(11) */
	const C_school_brow='school_brow';

	/*varchar(50) */
	const C_school_edit_date='school_edit_date';

	/*varchar(100) */
	const C_school_edit_user='school_edit_user';

	/*varchar(3000) */
	const C_school_pre='school_pre';

	/*varchar(150) */
	const C_school_intro2='school_intro2';

	/*varchar(100) */
	const C_school_sub_type2='school_sub_type2';

	/*int(11) */
	const C_school_ranking='school_ranking';

	/*int(11) */
	const C_school_ranking_area='school_ranking_area';
	function get_schoolid($id ){
		return $this->field_get_value( $id , self::C_schoolid );
	}
	function get_school_name($id ){
		return $this->field_get_value( $id , self::C_school_name );
	}
	function get_school_type($id ){
		return $this->field_get_value( $id , self::C_school_type );
	}
	function get_school_sub_type($id ){
		return $this->field_get_value( $id , self::C_school_sub_type );
	}
	function get_school_area($id ){
		return $this->field_get_value( $id , self::C_school_area );
	}
	function get_school_img($id ){
		return $this->field_get_value( $id , self::C_school_img );
	}
	function get_school_img_min($id ){
		return $this->field_get_value( $id , self::C_school_img_min );
	}
	function get_school_intro($id ){
		return $this->field_get_value( $id , self::C_school_intro );
	}
	function get_school_charac($id ){
		return $this->field_get_value( $id , self::C_school_charac );
	}
	function get_school_contact($id ){
		return $this->field_get_value( $id , self::C_school_contact );
	}
	function get_school_address($id ){
		return $this->field_get_value( $id , self::C_school_address );
	}
	function get_school_web($id ){
		return $this->field_get_value( $id , self::C_school_web );
	}
	function get_school_recruit($id ){
		return $this->field_get_value( $id , self::C_school_recruit );
	}
	function get_school_study_cost($id ){
		return $this->field_get_value( $id , self::C_school_study_cost );
	}
	function get_school_live_cost($id ){
		return $this->field_get_value( $id , self::C_school_live_cost );
	}
	function get_school_live_info($id ){
		return $this->field_get_value( $id , self::C_school_live_info );
	}
	function get_school_brow($id ){
		return $this->field_get_value( $id , self::C_school_brow );
	}
	function get_school_edit_date($id ){
		return $this->field_get_value( $id , self::C_school_edit_date );
	}
	function get_school_edit_user($id ){
		return $this->field_get_value( $id , self::C_school_edit_user );
	}
	function get_school_pre($id ){
		return $this->field_get_value( $id , self::C_school_pre );
	}
	function get_school_intro2($id ){
		return $this->field_get_value( $id , self::C_school_intro2 );
	}
	function get_school_sub_type2($id ){
		return $this->field_get_value( $id , self::C_school_sub_type2 );
	}
	function get_school_ranking($id ){
		return $this->field_get_value( $id , self::C_school_ranking );
	}
	function get_school_ranking_area($id ){
		return $this->field_get_value( $id , self::C_school_ranking_area );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="id";
        $this->field_table_name="db_tool.t_school_info";
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
  CREATE TABLE `t_school_info` (
  `schoolid` int(11) NOT NULL COMMENT '招生代码',
  `school_name` varchar(50) NOT NULL COMMENT '学校名字',
  `school_type` int(11) DEFAULT NULL COMMENT '学年段：小，初，高',
  `school_sub_type` int(11) DEFAULT NULL COMMENT '学校类型：公办民办，重点高中',
  `school_area` int(11) DEFAULT NULL COMMENT '学校所在区县',
  `school_img` varchar(100) DEFAULT NULL COMMENT '学校焦点图',
  `school_img_min` varchar(100) DEFAULT NULL COMMENT '学校小图',
  `school_intro` varchar(20000) DEFAULT NULL,
  `school_charac` varchar(3000) DEFAULT NULL COMMENT '办学特色',
  `school_contact` varchar(10000) DEFAULT NULL COMMENT '学校地址和联系方式',
  `school_address` varchar(3000) DEFAULT NULL,
  `school_web` varchar(100) DEFAULT NULL COMMENT '学校网址',
  `school_recruit` varchar(10000) DEFAULT NULL COMMENT '招生简章',
  `school_study_cost` varchar(3000) DEFAULT NULL COMMENT '学费',
  `school_live_cost` varchar(3000) DEFAULT NULL COMMENT '住宿费',
  `school_live_info` varchar(3000) DEFAULT NULL COMMENT '住宿条件',
  `school_brow` int(11) DEFAULT '0' COMMENT '学校信息浏览量',
  `school_edit_date` varchar(50) DEFAULT NULL COMMENT '上次信息编辑日期',
  `school_edit_user` varchar(100) DEFAULT NULL COMMENT '上次信息编辑人',
  `school_pre` varchar(3000) DEFAULT NULL COMMENT '预录取信息',
  `school_intro2` varchar(150) DEFAULT NULL,
  `school_sub_type2` varchar(100) DEFAULT NULL COMMENT 'åŠžå­¦æ€§è´¨',
  `school_ranking` int(11) DEFAULT NULL,
  `school_ranking_area` int(11) DEFAULT NULL,
  PRIMARY KEY (`schoolid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
 */
