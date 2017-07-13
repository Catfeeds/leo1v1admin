<?php
//自动生成枚举类  不要手工修改
//source  file: config_lesson_status.php
namespace  App\Enums;

class Elesson_status extends \App\Core\Enum_base
{
	static public $field_name = "lesson_status"  ;
	static public $name = "lesson_status"  ;
	 static $desc_map= array(
		0 => "未开始",
		1 => "进行中",
		2 => "已结束",
		3 => "课程终结或取消",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
	);
	 static $s2v_map= array(
		"no_start" => 0,
		"start" => 1,
		"end" => 2,
		"course_finish" => 3,
	);
	 static $v2s_map= array(
		 0=>  "no_start",
		 1=>  "start",
		 2=>  "end",
		 3=>  "course_finish",
	);

	//未开始
	const V_0=0;
	//未开始
	const V_NO_START=0;
	//进行中
	const V_1=1;
	//进行中
	const V_START=1;
	//已结束
	const V_2=2;
	//已结束
	const V_END=2;
	//课程终结或取消
	const V_3=3;
	//课程终结或取消
	const V_COURSE_FINISH=3;

	//未开始
	const S_0="no_start";
	//未开始
	const S_NO_START="no_start";
	//进行中
	const S_1="start";
	//进行中
	const S_START="start";
	//已结束
	const S_2="end";
	//已结束
	const S_END="end";
	//课程终结或取消
	const S_3="course_finish";
	//课程终结或取消
	const S_COURSE_FINISH="course_finish";

	static public function check_no_start ($val){
		 return $val == 0;
	}
	static public function check_start ($val){
		 return $val == 1;
	}
	static public function check_end ($val){
		 return $val == 2;
	}
	static public function check_course_finish ($val){
		 return $val == 3;
	}


};
