<?php
namespace App\Models\Zgen;
class z_t_appointment_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_appointment_info";


	/*int(10) unsigned */
	const C_packageid='packageid';

	/*varchar(50) */
	const C_package_name='package_name';

	/*varchar(100) */
	const C_package_pic='package_pic';

	/*varchar(100) */
	const C_package_tags='package_tags';

	/*int(10) unsigned */
	const C_package_type='package_type';

	/*varchar(1024) */
	const C_package_intro='package_intro';

	/*varchar(300) */
	const C_suit_student='suit_student';

	/*varchar(400) */
	const C_package_target='package_target';

	/*int(11) */
	const C_original_price='original_price';

	/*int(10) unsigned */
	const C_package_start='package_start';

	/*int(10) unsigned */
	const C_package_end='package_end';

	/*int(11) */
	const C_current_price='current_price';

	/*int(10) unsigned */
	const C_effect_start='effect_start';

	/*int(10) unsigned */
	const C_effect_end='effect_end';

	/*varchar(60) */
	const C_grade='grade';

	/*int(10) unsigned */
	const C_subject='subject';

	/*int(10) unsigned */
	const C_lesson_total='lesson_total';

	/*int(10) unsigned */
	const C_package_deadline='package_deadline';

	/*varchar(1024) */
	const C_package_outline='package_outline';

	/*varchar(1000) */
	const C_small_classes='small_classes';

	/*int(10) unsigned */
	const C_lesson_start='lesson_start';

	/*int(10) unsigned */
	const C_user_total='user_total';

	/*int(10) unsigned */
	const C_user_buy='user_buy';

	/*varchar(200) */
	const C_package_teachers='package_teachers';

	/*varchar(300) */
	const C_package_feature='package_feature';

	/*int(10) unsigned */
	const C_create_time='create_time';

	/*tinyint(4) */
	const C_del_flag='del_flag';

	/*timestamp */
	const C_last_modified_time='last_modified_time';

	/*tinyint(4) */
	const C_has_outline='has_outline';

	/*int(10) unsigned */
	const C_open_courseid='open_courseid';

	/*int(11) */
	const C_tag_type='tag_type';
	function get_package_name($packageid ){
		return $this->field_get_value( $packageid , self::C_package_name );
	}
	function get_package_pic($packageid ){
		return $this->field_get_value( $packageid , self::C_package_pic );
	}
	function get_package_tags($packageid ){
		return $this->field_get_value( $packageid , self::C_package_tags );
	}
	function get_package_type($packageid ){
		return $this->field_get_value( $packageid , self::C_package_type );
	}
	function get_package_intro($packageid ){
		return $this->field_get_value( $packageid , self::C_package_intro );
	}
	function get_suit_student($packageid ){
		return $this->field_get_value( $packageid , self::C_suit_student );
	}
	function get_package_target($packageid ){
		return $this->field_get_value( $packageid , self::C_package_target );
	}
	function get_original_price($packageid ){
		return $this->field_get_value( $packageid , self::C_original_price );
	}
	function get_package_start($packageid ){
		return $this->field_get_value( $packageid , self::C_package_start );
	}
	function get_package_end($packageid ){
		return $this->field_get_value( $packageid , self::C_package_end );
	}
	function get_current_price($packageid ){
		return $this->field_get_value( $packageid , self::C_current_price );
	}
	function get_effect_start($packageid ){
		return $this->field_get_value( $packageid , self::C_effect_start );
	}
	function get_effect_end($packageid ){
		return $this->field_get_value( $packageid , self::C_effect_end );
	}
	function get_grade($packageid ){
		return $this->field_get_value( $packageid , self::C_grade );
	}
	function get_subject($packageid ){
		return $this->field_get_value( $packageid , self::C_subject );
	}
	function get_lesson_total($packageid ){
		return $this->field_get_value( $packageid , self::C_lesson_total );
	}
	function get_package_deadline($packageid ){
		return $this->field_get_value( $packageid , self::C_package_deadline );
	}
	function get_package_outline($packageid ){
		return $this->field_get_value( $packageid , self::C_package_outline );
	}
	function get_small_classes($packageid ){
		return $this->field_get_value( $packageid , self::C_small_classes );
	}
	function get_lesson_start($packageid ){
		return $this->field_get_value( $packageid , self::C_lesson_start );
	}
	function get_user_total($packageid ){
		return $this->field_get_value( $packageid , self::C_user_total );
	}
	function get_user_buy($packageid ){
		return $this->field_get_value( $packageid , self::C_user_buy );
	}
	function get_package_teachers($packageid ){
		return $this->field_get_value( $packageid , self::C_package_teachers );
	}
	function get_package_feature($packageid ){
		return $this->field_get_value( $packageid , self::C_package_feature );
	}
	function get_create_time($packageid ){
		return $this->field_get_value( $packageid , self::C_create_time );
	}
	function get_del_flag($packageid ){
		return $this->field_get_value( $packageid , self::C_del_flag );
	}
	function get_last_modified_time($packageid ){
		return $this->field_get_value( $packageid , self::C_last_modified_time );
	}
	function get_has_outline($packageid ){
		return $this->field_get_value( $packageid , self::C_has_outline );
	}
	function get_open_courseid($packageid ){
		return $this->field_get_value( $packageid , self::C_open_courseid );
	}
	function get_tag_type($packageid ){
		return $this->field_get_value( $packageid , self::C_tag_type );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="packageid";
        $this->field_table_name="db_weiyi.t_appointment_info";
  }
    public function field_get_list( $packageid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $packageid, $set_field_arr) {
        return parent::field_update_list( $packageid, $set_field_arr);
    }


    public function field_get_value(  $packageid, $field_name ) {
        return parent::field_get_value( $packageid, $field_name);
    }

    public function row_delete(  $packageid) {
        return parent::row_delete( $packageid);
    }

}

/*
  CREATE TABLE `t_appointment_info` (
  `packageid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '课程包编号id',
  `package_name` varchar(50) NOT NULL COMMENT '课程包名称',
  `package_pic` varchar(100) NOT NULL COMMENT '缩略图',
  `package_tags` varchar(100) NOT NULL COMMENT '课程标签，按照英文的逗号分割',
  `package_type` int(10) unsigned NOT NULL COMMENT '课程包类型0 1v1, 1000 公开课　2000答疑课 3000 小班课',
  `package_intro` varchar(1024) NOT NULL COMMENT '课程简介',
  `suit_student` varchar(300) NOT NULL COMMENT '适合人群的信息',
  `package_target` varchar(400) NOT NULL COMMENT '课程实现目标',
  `original_price` int(11) NOT NULL COMMENT '课程包原始价格',
  `package_start` int(10) unsigned NOT NULL COMMENT '课程包上架开始时间',
  `package_end` int(10) unsigned NOT NULL COMMENT '课程包下架结束时间',
  `current_price` int(11) NOT NULL COMMENT '当前课程包价格',
  `effect_start` int(10) unsigned NOT NULL COMMENT '有效时间开始时间',
  `effect_end` int(10) unsigned NOT NULL COMMENT '有效时间结束时间',
  `grade` varchar(60) NOT NULL COMMENT '年纪字符串,已英文逗号分割',
  `subject` int(10) unsigned NOT NULL COMMENT '科目',
  `lesson_total` int(10) unsigned NOT NULL COMMENT '当前课程包的课次',
  `package_deadline` int(10) unsigned NOT NULL COMMENT '最后截止时间',
  `package_outline` varchar(1024) NOT NULL DEFAULT '' COMMENT '课程大纲',
  `small_classes` varchar(1000) NOT NULL DEFAULT '' COMMENT '小班课班级',
  `lesson_start` int(10) unsigned NOT NULL COMMENT '课程开始时间',
  `user_total` int(10) unsigned NOT NULL COMMENT '课程包可承载学生数目',
  `user_buy` int(10) unsigned NOT NULL COMMENT '已购买学生数目',
  `package_teachers` varchar(200) NOT NULL COMMENT 'teacherid1|tags1,teacherid2|tags2',
  `package_feature` varchar(300) NOT NULL COMMENT '课程包的特色',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `del_flag` tinyint(4) NOT NULL COMMENT '是否删除　0未删除　１已删除',
  `last_modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后修改时间',
  `has_outline` tinyint(4) NOT NULL DEFAULT '0' COMMENT '课程大纲 0无大纲 1有大纲',
  `open_courseid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '课堂id',
  `tag_type` int(11) NOT NULL COMMENT '0:无,1:折扣,2:推荐,3:热门',
  PRIMARY KEY (`packageid`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8
 */
