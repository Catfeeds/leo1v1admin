<?php
namespace App\Models\Zgen;
class z_t_apply_reg  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_apply_reg";


	/*varchar(20) */
	const C_phone='phone';

	/*int(11) */
	const C_add_time='add_time';

	/*varchar(40) */
	const C_name='name';

	/*varchar(20) */
	const C_education='education';

	/*varchar(20) */
	const C_residence='residence';

	/*int(11) */
	const C_gender='gender';

	/*varchar(20) */
	const C_english='english';

	/*varchar(20) */
	const C_polity='polity';

	/*varchar(30) */
	const C_carded='carded';

	/*varchar(20) */
	const C_marry='marry';

	/*varchar(255) */
	const C_child='child';

	/*varchar(20) */
	const C_email='email';

	/*varchar(100) */
	const C_post='post';

	/*varchar(30) */
	const C_dept='dept';

	/*varchar(256) */
	const C_address='address';

	/*varchar(256) */
	const C_strong='strong';

	/*varchar(256) */
	const C_interest='interest';

	/*int(11) */
	const C_non_compete='non_compete';

	/*int(11) */
	const C_is_labor='is_labor';

	/*varchar(5000) */
	const C_work_info='work_info';

	/*varchar(5000) */
	const C_family_info='family_info';

	/*int(11) */
	const C_is_fre='is_fre';

	/*varchar(40) */
	const C_fre_name='fre_name';

	/*varchar(5000) */
	const C_education_info='education_info';

	/*varchar(50) */
	const C_birth='birth';

	/*varchar(255) */
	const C_ccb_card='ccb_card';

	/*int(11) */
	const C_height='height';

	/*varchar(40) */
	const C_minor='minor';

	/*int(11) */
	const C_birth_type='birth_type';

	/*varchar(40) */
	const C_gra_school='gra_school';

	/*varchar(40) */
	const C_gra_major='gra_major';

	/*varchar(20) */
	const C_health_condition='health_condition';

	/*int(11) */
	const C_postcodes='postcodes';

	/*int(11) */
	const C_is_insured='is_insured';

	/*int(11) */
	const C_residence_type='residence_type';

	/*int(11) */
	const C_join_time='join_time';

	/*varchar(20) */
	const C_emergency_contact_nick='emergency_contact_nick';

	/*varchar(255) */
	const C_emergency_contact_address='emergency_contact_address';

	/*varchar(20) */
	const C_trial_dept='trial_dept';

	/*varchar(20) */
	const C_trial_post='trial_post';

	/*varchar(20) */
	const C_native_place='native_place';

	/*int(11) */
	const C_trial_start_time='trial_start_time';

	/*int(11) */
	const C_trial_end_time='trial_end_time';

	/*varchar(255) */
	const C_photo='photo';

	/*varchar(40) */
	const C_emergency_contact_phone='emergency_contact_phone';
	function get_add_time($phone ){
		return $this->field_get_value( $phone , self::C_add_time );
	}
	function get_name($phone ){
		return $this->field_get_value( $phone , self::C_name );
	}
	function get_education($phone ){
		return $this->field_get_value( $phone , self::C_education );
	}
	function get_residence($phone ){
		return $this->field_get_value( $phone , self::C_residence );
	}
	function get_gender($phone ){
		return $this->field_get_value( $phone , self::C_gender );
	}
	function get_english($phone ){
		return $this->field_get_value( $phone , self::C_english );
	}
	function get_polity($phone ){
		return $this->field_get_value( $phone , self::C_polity );
	}
	function get_carded($phone ){
		return $this->field_get_value( $phone , self::C_carded );
	}
	function get_marry($phone ){
		return $this->field_get_value( $phone , self::C_marry );
	}
	function get_child($phone ){
		return $this->field_get_value( $phone , self::C_child );
	}
	function get_email($phone ){
		return $this->field_get_value( $phone , self::C_email );
	}
	function get_post($phone ){
		return $this->field_get_value( $phone , self::C_post );
	}
	function get_dept($phone ){
		return $this->field_get_value( $phone , self::C_dept );
	}
	function get_address($phone ){
		return $this->field_get_value( $phone , self::C_address );
	}
	function get_strong($phone ){
		return $this->field_get_value( $phone , self::C_strong );
	}
	function get_interest($phone ){
		return $this->field_get_value( $phone , self::C_interest );
	}
	function get_non_compete($phone ){
		return $this->field_get_value( $phone , self::C_non_compete );
	}
	function get_is_labor($phone ){
		return $this->field_get_value( $phone , self::C_is_labor );
	}
	function get_work_info($phone ){
		return $this->field_get_value( $phone , self::C_work_info );
	}
	function get_family_info($phone ){
		return $this->field_get_value( $phone , self::C_family_info );
	}
	function get_is_fre($phone ){
		return $this->field_get_value( $phone , self::C_is_fre );
	}
	function get_fre_name($phone ){
		return $this->field_get_value( $phone , self::C_fre_name );
	}
	function get_education_info($phone ){
		return $this->field_get_value( $phone , self::C_education_info );
	}
	function get_birth($phone ){
		return $this->field_get_value( $phone , self::C_birth );
	}
	function get_ccb_card($phone ){
		return $this->field_get_value( $phone , self::C_ccb_card );
	}
	function get_height($phone ){
		return $this->field_get_value( $phone , self::C_height );
	}
	function get_minor($phone ){
		return $this->field_get_value( $phone , self::C_minor );
	}
	function get_birth_type($phone ){
		return $this->field_get_value( $phone , self::C_birth_type );
	}
	function get_gra_school($phone ){
		return $this->field_get_value( $phone , self::C_gra_school );
	}
	function get_gra_major($phone ){
		return $this->field_get_value( $phone , self::C_gra_major );
	}
	function get_health_condition($phone ){
		return $this->field_get_value( $phone , self::C_health_condition );
	}
	function get_postcodes($phone ){
		return $this->field_get_value( $phone , self::C_postcodes );
	}
	function get_is_insured($phone ){
		return $this->field_get_value( $phone , self::C_is_insured );
	}
	function get_residence_type($phone ){
		return $this->field_get_value( $phone , self::C_residence_type );
	}
	function get_join_time($phone ){
		return $this->field_get_value( $phone , self::C_join_time );
	}
	function get_emergency_contact_nick($phone ){
		return $this->field_get_value( $phone , self::C_emergency_contact_nick );
	}
	function get_emergency_contact_address($phone ){
		return $this->field_get_value( $phone , self::C_emergency_contact_address );
	}
	function get_trial_dept($phone ){
		return $this->field_get_value( $phone , self::C_trial_dept );
	}
	function get_trial_post($phone ){
		return $this->field_get_value( $phone , self::C_trial_post );
	}
	function get_native_place($phone ){
		return $this->field_get_value( $phone , self::C_native_place );
	}
	function get_trial_start_time($phone ){
		return $this->field_get_value( $phone , self::C_trial_start_time );
	}
	function get_trial_end_time($phone ){
		return $this->field_get_value( $phone , self::C_trial_end_time );
	}
	function get_photo($phone ){
		return $this->field_get_value( $phone , self::C_photo );
	}
	function get_emergency_contact_phone($phone ){
		return $this->field_get_value( $phone , self::C_emergency_contact_phone );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="phone";
        $this->field_table_name="db_weiyi_admin.t_apply_reg";
  }
    public function field_get_list( $phone, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $phone, $set_field_arr) {
        return parent::field_update_list( $phone, $set_field_arr);
    }


    public function field_get_value(  $phone, $field_name ) {
        return parent::field_get_value( $phone, $field_name);
    }

    public function row_delete(  $phone) {
        return parent::row_delete( $phone);
    }

}

/*
  CREATE TABLE `t_apply_reg` (
  `phone` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '个人手机号',
  `add_time` int(11) NOT NULL COMMENT '生成时间',
  `name` varchar(40) COLLATE latin1_bin NOT NULL COMMENT '姓名',
  `education` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '最高教育程度',
  `residence` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '户口所在地',
  `gender` int(11) NOT NULL COMMENT '性别 :  1 男 ,2 女',
  `english` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '英语水平',
  `polity` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '政治面貌',
  `carded` varchar(30) COLLATE latin1_bin NOT NULL COMMENT '身份证号码',
  `marry` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '婚姻状况',
  `child` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '子女状况',
  `email` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '电子邮件',
  `post` varchar(100) COLLATE latin1_bin NOT NULL COMMENT '岗位',
  `dept` varchar(30) COLLATE latin1_bin NOT NULL COMMENT '部门',
  `address` varchar(256) COLLATE latin1_bin NOT NULL COMMENT '居住地址详细信息',
  `strong` varchar(256) COLLATE latin1_bin NOT NULL COMMENT '特长',
  `interest` varchar(256) COLLATE latin1_bin NOT NULL COMMENT '爱好',
  `non_compete` int(11) NOT NULL COMMENT '是否与原单位存在竞业禁止协议 : 0 否,1 是',
  `is_labor` int(11) NOT NULL COMMENT '与原单位是否解除劳动合同: 0 否,1 是',
  `work_info` varchar(5000) COLLATE latin1_bin NOT NULL COMMENT '工作经历',
  `family_info` varchar(5000) COLLATE latin1_bin NOT NULL COMMENT '家庭成员关系情况',
  `is_fre` int(11) NOT NULL COMMENT '是否有朋友在本公司',
  `fre_name` varchar(40) COLLATE latin1_bin NOT NULL COMMENT '介绍人姓名',
  `education_info` varchar(5000) COLLATE latin1_bin NOT NULL COMMENT '教育背景',
  `birth` varchar(50) COLLATE latin1_bin NOT NULL COMMENT '出生日期',
  `ccb_card` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '建设银行卡号',
  `height` int(11) NOT NULL COMMENT '身高',
  `minor` varchar(40) COLLATE latin1_bin NOT NULL COMMENT '民族',
  `birth_type` int(11) NOT NULL COMMENT '1 农历, 2 公历',
  `gra_school` varchar(40) COLLATE latin1_bin NOT NULL COMMENT '毕业学校',
  `gra_major` varchar(40) COLLATE latin1_bin NOT NULL COMMENT '专业',
  `health_condition` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '健康状况',
  `postcodes` int(11) NOT NULL COMMENT '邮编',
  `is_insured` int(11) NOT NULL COMMENT '是否已参保 1 是 0 否',
  `residence_type` int(11) NOT NULL COMMENT ' 户口性质 : 1 本埠城镇 2 本埠农村 3 外埠城镇 4 外埠农村',
  `join_time` int(11) NOT NULL COMMENT '最快入司时间',
  `emergency_contact_nick` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '紧急联系人',
  `emergency_contact_address` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '紧急联系人地址',
  `trial_dept` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '试用部门',
  `trial_post` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '试用岗位',
  `native_place` varchar(20) COLLATE latin1_bin NOT NULL COMMENT '籍贯',
  `trial_start_time` int(11) NOT NULL COMMENT '试用期开始时间',
  `trial_end_time` int(11) NOT NULL COMMENT '试用期结束时间',
  `photo` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '照片',
  `emergency_contact_phone` varchar(40) COLLATE latin1_bin NOT NULL COMMENT '紧急联系人电话',
  PRIMARY KEY (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
