<?php
//自动生成枚举类  不要手工修改
//source  file: config_work_status.php
namespace  App\Enums;

class Ework_status extends \App\Enums\Enum_base
{
	static public $field_name = "work_status"  ;
	static public $name = "work_status"  ;
	 static $desc_map= array(
		0 => "未传",
		1 => "已传",
		2 => "学生",
		3 => "老师",
		4 => "教研",
		5 => "助教",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
	);
	 static $s2v_map= array(
		"no_upload" => 0,
		"issue" => 1,
		"finish" => 2,
		"check" => 3,
		"tea_research" => 4,
		"ass_research" => 5,
	);
	 static $v2s_map= array(
		 0=>  "no_upload",
		 1=>  "issue",
		 2=>  "finish",
		 3=>  "check",
		 4=>  "tea_research",
		 5=>  "ass_research",
	);

	//未传
	const V_0=0;
	//未传
	const V_NO_UPLOAD=0;
	//已传
	const V_1=1;
	//已传
	const V_ISSUE=1;
	//学生
	const V_2=2;
	//学生
	const V_FINISH=2;
	//老师
	const V_3=3;
	//老师
	const V_CHECK=3;
	//教研
	const V_4=4;
	//教研
	const V_TEA_RESEARCH=4;
	//助教
	const V_5=5;
	//助教
	const V_ASS_RESEARCH=5;

	//未传
	const S_0="no_upload";
	//未传
	const S_NO_UPLOAD="no_upload";
	//已传
	const S_1="issue";
	//已传
	const S_ISSUE="issue";
	//学生
	const S_2="finish";
	//学生
	const S_FINISH="finish";
	//老师
	const S_3="check";
	//老师
	const S_CHECK="check";
	//教研
	const S_4="tea_research";
	//教研
	const S_TEA_RESEARCH="tea_research";
	//助教
	const S_5="ass_research";
	//助教
	const S_ASS_RESEARCH="ass_research";

	static public function check_no_upload ($val){
		 return $val == 0;
	}
	static public function check_issue ($val){
		 return $val == 1;
	}
	static public function check_finish ($val){
		 return $val == 2;
	}
	static public function check_check ($val){
		 return $val == 3;
	}
	static public function check_tea_research ($val){
		 return $val == 4;
	}
	static public function check_ass_research ($val){
		 return $val == 5;
	}


};
