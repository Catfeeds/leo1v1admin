<?php
namespace App\Models\Zgen;
class z_t_assistant_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_assistant_info";


	/*int(10) unsigned */
	const C_assistantid='assistantid';

	/*varchar(32) */
	const C_nick='nick';

	/*varchar(100) */
	const C_face='face';

	/*int(10) unsigned */
	const C_birth='birth';

	/*varchar(16) */
	const C_phone='phone';

	/*tinyint(4) */
	const C_level='level';

	/*varchar(600) */
	const C_base_intro='base_intro';

	/*varchar(600) */
	const C_advantage='advantage';

	/*varchar(32) */
	const C_course='course';

	/*varchar(100) */
	const C_school='school';

	/*varchar(32) */
	const C_title='title';

	/*smallint(6) */
	const C_rate_score='rate_score';

	/*smallint(6) */
	const C_rate_attitude='rate_attitude';

	/*smallint(6) */
	const C_rate_kind='rate_kind';

	/*smallint(6) */
	const C_rate_effect='rate_effect';

	/*int(10) unsigned */
	const C_five_star='five_star';

	/*int(10) unsigned */
	const C_four_star='four_star';

	/*int(10) unsigned */
	const C_three_star='three_star';

	/*int(10) unsigned */
	const C_two_star='two_star';

	/*int(10) unsigned */
	const C_one_star='one_star';

	/*timestamp */
	const C_last_modified_time='last_modified_time';

	/*smallint(6) */
	const C_grade='grade';

	/*int(10) unsigned */
	const C_work_year='work_year';

	/*int(10) unsigned */
	const C_tutor_subject='tutor_subject';

	/*int(10) unsigned */
	const C_tutor_grade='tutor_grade';

	/*tinyint(4) */
	const C_gender='gender';

	/*int(10) unsigned */
	const C_stu_num='stu_num';

	/*varchar(50) */
	const C_email='email';

	/*tinyint(4) */
	const C_assistant_type='assistant_type';

	/*varchar(1024) */
	const C_prize='prize';

	/*varchar(1024) */
	const C_ass_style='ass_style';

	/*varchar(1024) */
	const C_achievement='achievement';

	/*tinyint(4) */
	const C_is_quit='is_quit';

	/*varchar(255) */
	const C_e_name='e_name';
	function get_nick($assistantid ){
		return $this->field_get_value( $assistantid , self::C_nick );
	}
	function get_face($assistantid ){
		return $this->field_get_value( $assistantid , self::C_face );
	}
	function get_birth($assistantid ){
		return $this->field_get_value( $assistantid , self::C_birth );
	}
	function get_phone($assistantid ){
		return $this->field_get_value( $assistantid , self::C_phone );
	}
	function get_level($assistantid ){
		return $this->field_get_value( $assistantid , self::C_level );
	}
	function get_base_intro($assistantid ){
		return $this->field_get_value( $assistantid , self::C_base_intro );
	}
	function get_advantage($assistantid ){
		return $this->field_get_value( $assistantid , self::C_advantage );
	}
	function get_course($assistantid ){
		return $this->field_get_value( $assistantid , self::C_course );
	}
	function get_school($assistantid ){
		return $this->field_get_value( $assistantid , self::C_school );
	}
	function get_title($assistantid ){
		return $this->field_get_value( $assistantid , self::C_title );
	}
	function get_rate_score($assistantid ){
		return $this->field_get_value( $assistantid , self::C_rate_score );
	}
	function get_rate_attitude($assistantid ){
		return $this->field_get_value( $assistantid , self::C_rate_attitude );
	}
	function get_rate_kind($assistantid ){
		return $this->field_get_value( $assistantid , self::C_rate_kind );
	}
	function get_rate_effect($assistantid ){
		return $this->field_get_value( $assistantid , self::C_rate_effect );
	}
	function get_five_star($assistantid ){
		return $this->field_get_value( $assistantid , self::C_five_star );
	}
	function get_four_star($assistantid ){
		return $this->field_get_value( $assistantid , self::C_four_star );
	}
	function get_three_star($assistantid ){
		return $this->field_get_value( $assistantid , self::C_three_star );
	}
	function get_two_star($assistantid ){
		return $this->field_get_value( $assistantid , self::C_two_star );
	}
	function get_one_star($assistantid ){
		return $this->field_get_value( $assistantid , self::C_one_star );
	}
	function get_last_modified_time($assistantid ){
		return $this->field_get_value( $assistantid , self::C_last_modified_time );
	}
	function get_grade($assistantid ){
		return $this->field_get_value( $assistantid , self::C_grade );
	}
	function get_work_year($assistantid ){
		return $this->field_get_value( $assistantid , self::C_work_year );
	}
	function get_tutor_subject($assistantid ){
		return $this->field_get_value( $assistantid , self::C_tutor_subject );
	}
	function get_tutor_grade($assistantid ){
		return $this->field_get_value( $assistantid , self::C_tutor_grade );
	}
	function get_gender($assistantid ){
		return $this->field_get_value( $assistantid , self::C_gender );
	}
	function get_stu_num($assistantid ){
		return $this->field_get_value( $assistantid , self::C_stu_num );
	}
	function get_email($assistantid ){
		return $this->field_get_value( $assistantid , self::C_email );
	}
	function get_assistant_type($assistantid ){
		return $this->field_get_value( $assistantid , self::C_assistant_type );
	}
	function get_prize($assistantid ){
		return $this->field_get_value( $assistantid , self::C_prize );
	}
	function get_ass_style($assistantid ){
		return $this->field_get_value( $assistantid , self::C_ass_style );
	}
	function get_achievement($assistantid ){
		return $this->field_get_value( $assistantid , self::C_achievement );
	}
	function get_is_quit($assistantid ){
		return $this->field_get_value( $assistantid , self::C_is_quit );
	}
	function get_e_name($assistantid ){
		return $this->field_get_value( $assistantid , self::C_e_name );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="assistantid";
        $this->field_table_name="db_weiyi.t_assistant_info";
  }
    public function field_get_list( $assistantid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $assistantid, $set_field_arr) {
        return parent::field_update_list( $assistantid, $set_field_arr);
    }


    public function field_get_value(  $assistantid, $field_name ) {
        return parent::field_get_value( $assistantid, $field_name);
    }

    public function row_delete(  $assistantid) {
        return parent::row_delete( $assistantid);
    }

}

/*
  CREATE TABLE `t_assistant_info` (
  `assistantid` int(10) unsigned NOT NULL COMMENT '助教id',
  `nick` varchar(32) NOT NULL COMMENT '助教名字',
  `face` varchar(100) NOT NULL DEFAULT '' COMMENT '助教的头像',
  `birth` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '生日（格式如19910101）',
  `phone` varchar(16) NOT NULL COMMENT '手机助教端登陆',
  `level` tinyint(4) NOT NULL DEFAULT '0' COMMENT '等级（1-5）',
  `base_intro` varchar(600) NOT NULL COMMENT '基本信息',
  `advantage` varchar(600) NOT NULL COMMENT '个人优势',
  `course` varchar(32) NOT NULL COMMENT '所负责课程',
  `school` varchar(100) NOT NULL DEFAULT '' COMMENT '毕业院校',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '职称（exp.）',
  `rate_score` smallint(6) NOT NULL DEFAULT '0' COMMENT '用户评分',
  `rate_attitude` smallint(6) NOT NULL DEFAULT '0' COMMENT '服务态度',
  `rate_kind` smallint(6) NOT NULL DEFAULT '0' COMMENT '亲和程度',
  `rate_effect` smallint(6) NOT NULL DEFAULT '0' COMMENT '教学效果',
  `five_star` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '五星评价',
  `four_star` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '四星评价',
  `three_star` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '三星评价',
  `two_star` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二星评价',
  `one_star` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一星评价',
  `last_modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后修改时间',
  `grade` smallint(6) NOT NULL DEFAULT '0' COMMENT '用户年级（100 小学 200 初中 300 高中 400 大学 500 硕士 600 博士 900 毕业）',
  `work_year` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '工作年限',
  `tutor_subject` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所教课程',
  `tutor_grade` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所教的年级',
  `gender` tinyint(4) NOT NULL DEFAULT '0' COMMENT '用户性别（0保密，1男2女）',
  `stu_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学生个数',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '助教邮箱',
  `assistant_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '助教类型0全职1兼职',
  `prize` varchar(1024) NOT NULL DEFAULT '' COMMENT '曾经获取的奖励或成就',
  `ass_style` varchar(1024) NOT NULL DEFAULT '' COMMENT '带教风格',
  `achievement` varchar(1024) NOT NULL DEFAULT '' COMMENT '成功案例',
  `is_quit` tinyint(4) NOT NULL DEFAULT '0' COMMENT '助教是否已经离职 0 未离职 1 已离职',
  `e_name` varchar(255) NOT NULL COMMENT '后台登陆的英文名',
  PRIMARY KEY (`assistantid`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
