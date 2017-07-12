<?php
namespace App\Models\Zgen;
class z_t_revisit_info extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_revisit_info";


	/*int(10) unsigned */
	const C_userid='userid';

	/*int(10) unsigned */
	const C_revisit_time='revisit_time';

	/*varchar(32) */
	const C_stu_nick='stu_nick';

	/*varchar(32) */
	const C_revisit_person='revisit_person';

	/*varchar(32) */
	const C_sys_operator='sys_operator';

	/*varchar(300) */
	const C_operator_note='operator_note';

	/*varchar(100) */
	const C_operator_audio='operator_audio';

	/*int(10) unsigned */
	const C_assistantid='assistantid';

	/*int(11) */
	const C_revisit_type='revisit_type';

	/*int(11) */
	const C_call_phone_id='call_phone_id';

	/*int(11) */
	const C_operation_satisfy_flag='operation_satisfy_flag';

	/*int(11) */
	const C_operation_satisfy_type='operation_satisfy_type';

	/*varchar(1000) */
	const C_operation_satisfy_info='operation_satisfy_info';

	/*int(11) */
	const C_record_tea_class_flag='record_tea_class_flag';

	/*varchar(1000) */
	const C_child_performance='child_performance';

	/*int(11) */
	const C_tea_content_satisfy_flag='tea_content_satisfy_flag';

	/*int(11) */
	const C_tea_content_satisfy_type='tea_content_satisfy_type';

	/*varchar(1000) */
	const C_tea_content_satisfy_info='tea_content_satisfy_info';

	/*varchar(1000) */
	const C_other_parent_info='other_parent_info';

	/*int(11) */
	const C_child_class_performance_flag='child_class_performance_flag';

	/*int(11) */
	const C_child_class_performance_type='child_class_performance_type';

	/*varchar(1000) */
	const C_child_class_performance_info='child_class_performance_info';

	/*int(11) */
	const C_school_score_change_flag='school_score_change_flag';

	/*varchar(1000) */
	const C_school_score_change_info='school_score_change_info';

	/*int(11) */
	const C_school_work_change_flag='school_work_change_flag';

	/*int(11) */
	const C_school_work_change_type='school_work_change_type';

	/*varchar(1000) */
	const C_school_work_change_info='school_work_change_info';

	/*varchar(1000) */
	const C_other_warning_info='other_warning_info';

	/*int(11) */
	const C_is_warning_flag='is_warning_flag';

	/*varchar(255) */
	const C_warning_deal_url='warning_deal_url';

	/*varchar(255) */
	const C_warning_deal_info='warning_deal_info';

	/*int(11) */
	const C_warning_deal_time='warning_deal_time';
	function get_stu_nick($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_stu_nick  );
	}
	function get_revisit_person($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_revisit_person  );
	}
	function get_sys_operator($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_sys_operator  );
	}
	function get_operator_note($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_operator_note  );
	}
	function get_operator_audio($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_operator_audio  );
	}
	function get_assistantid($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_assistantid  );
	}
	function get_revisit_type($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_revisit_type  );
	}
	function get_call_phone_id($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_call_phone_id  );
	}
	function get_operation_satisfy_flag($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_operation_satisfy_flag  );
	}
	function get_operation_satisfy_type($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_operation_satisfy_type  );
	}
	function get_operation_satisfy_info($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_operation_satisfy_info  );
	}
	function get_record_tea_class_flag($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_record_tea_class_flag  );
	}
	function get_child_performance($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_child_performance  );
	}
	function get_tea_content_satisfy_flag($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_tea_content_satisfy_flag  );
	}
	function get_tea_content_satisfy_type($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_tea_content_satisfy_type  );
	}
	function get_tea_content_satisfy_info($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_tea_content_satisfy_info  );
	}
	function get_other_parent_info($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_other_parent_info  );
	}
	function get_child_class_performance_flag($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_child_class_performance_flag  );
	}
	function get_child_class_performance_type($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_child_class_performance_type  );
	}
	function get_child_class_performance_info($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_child_class_performance_info  );
	}
	function get_school_score_change_flag($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_school_score_change_flag  );
	}
	function get_school_score_change_info($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_school_score_change_info  );
	}
	function get_school_work_change_flag($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_school_work_change_flag  );
	}
	function get_school_work_change_type($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_school_work_change_type  );
	}
	function get_school_work_change_info($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_school_work_change_info  );
	}
	function get_other_warning_info($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_other_warning_info  );
	}
	function get_is_warning_flag($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_is_warning_flag  );
	}
	function get_warning_deal_url($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_warning_deal_url  );
	}
	function get_warning_deal_info($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_warning_deal_info  );
	}
	function get_warning_deal_time($userid, $revisit_time ){
		return $this->field_get_value_2( $userid, $revisit_time  , self::C_warning_deal_time  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="userid";
        $this->field_id2_name="revisit_time";
        $this->field_table_name="db_weiyi.t_revisit_info";
  }

    public function field_get_value_2(  $userid, $revisit_time,$field_name ) {
        return parent::field_get_value_2(  $userid, $revisit_time,$field_name ) ;
    }

    public function field_get_list_2( $userid,  $revisit_time,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $userid, $revisit_time,  $set_field_arr ) {
        return parent::field_update_list_2( $userid, $revisit_time,  $set_field_arr );
    }
    public function row_delete_2(  $userid ,$revisit_time ) {
        return parent::row_delete_2( $userid ,$revisit_time );
    }


}
/*
  CREATE TABLE `t_revisit_info` (
  `userid` int(10) unsigned NOT NULL COMMENT '学生id',
  `revisit_time` int(10) unsigned NOT NULL COMMENT '回访时间',
  `stu_nick` varchar(32) NOT NULL COMMENT '学生昵称',
  `revisit_person` varchar(32) NOT NULL COMMENT '回访对象',
  `sys_operator` varchar(32) NOT NULL COMMENT '进行回访的人',
  `operator_note` varchar(300) NOT NULL COMMENT '回访记录',
  `operator_audio` varchar(100) NOT NULL DEFAULT '' COMMENT '回访语音',
  `assistantid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '助教id',
  `revisit_type` int(11) NOT NULL COMMENT '0:学情，1:首度，2:月度，3:其他',
  `call_phone_id` int(11) DEFAULT NULL COMMENT '呼叫电话id',
  `operation_satisfy_flag` int(11) NOT NULL COMMENT '家长对于我们的软件操作和体验是否满意',
  `operation_satisfy_type` int(11) NOT NULL COMMENT '家长对于我们的软件操作和体验不满意的类型',
  `operation_satisfy_info` varchar(1000) NOT NULL COMMENT '家长对于我们的软件操作和体验不满意的具体描述',
  `record_tea_class_flag` int(11) NOT NULL COMMENT '反馈老师对于近期课程的评价和不足是否完成',
  `child_performance` varchar(1000) NOT NULL COMMENT '学生近期表现',
  `tea_content_satisfy_flag` int(11) NOT NULL COMMENT '家长对于老师教学内容和水平是否满意',
  `tea_content_satisfy_type` int(11) NOT NULL COMMENT '家长对于老师教学内容和水平不满意的类型',
  `tea_content_satisfy_info` varchar(1000) NOT NULL COMMENT '家长对于老师教学内容和水平不满意的具体描述',
  `other_parent_info` varchar(1000) NOT NULL COMMENT '家长其他意见与建议',
  `child_class_performance_flag` int(11) NOT NULL COMMENT '孩子课堂表现',
  `child_class_performance_type` int(11) NOT NULL COMMENT '孩子课堂表现不好的分类',
  `child_class_performance_info` varchar(1000) NOT NULL COMMENT '孩子课堂表现不好的具体表述',
  `school_score_change_flag` int(11) NOT NULL COMMENT '学校成绩变化',
  `school_score_change_info` varchar(1000) NOT NULL COMMENT '学校成绩变差的具体表述',
  `school_work_change_flag` int(11) NOT NULL COMMENT '学业变化',
  `school_work_change_type` int(11) NOT NULL COMMENT '学业变化的子分类',
  `school_work_change_info` varchar(1000) NOT NULL COMMENT '学业变化的具体表述',
  `other_warning_info` varchar(1000) NOT NULL COMMENT '其他预警问题',
  `is_warning_flag` int(11) NOT NULL COMMENT '是否预警中',
  `warning_deal_url` varchar(255) NOT NULL COMMENT '预警解决相关图片地址',
  `warning_deal_info` varchar(255) NOT NULL COMMENT '预警解决相关描述',
  `warning_deal_time` int(11) NOT NULL COMMENT '预警处置时间',
  PRIMARY KEY (`userid`,`revisit_time`),
  UNIQUE KEY `revisit_time` (`revisit_time`),
  UNIQUE KEY `db_weiyi_t_revisit_info_call_phone_id_unique` (`call_phone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
