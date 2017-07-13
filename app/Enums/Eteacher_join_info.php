<?php
//自动生成枚举类  不要手工修改
//source  file: config_teacher_join_info.php
namespace  App\Enums;

class Eteacher_join_info extends \App\Core\Enum_base
{
	static public $field_name = "teacher_join_info"  ;
	static public $name = "teacher_join_info"  ;
	 static $desc_map= array(
		0 => "出席",
		1 => "请假",
		2 => "缺席",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
	);

	//出席
	const V_0=0;
	//请假
	const V_1=1;
	//缺席
	const V_2=2;

	//出席
	const S_0="";
	//请假
	const S_1="";
	//缺席
	const S_2="";



};
