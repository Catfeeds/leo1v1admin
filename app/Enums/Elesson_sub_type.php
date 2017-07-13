<?php
//自动生成枚举类  不要手工修改
//source  file: config_lesson_sub_type.php
namespace  App\Enums;

class Elesson_sub_type extends \App\Core\Enum_base
{
	static public $field_name = "lesson_sub_type"  ;
	static public $name = "lesson_sub_type"  ;
	 static $desc_map= array(
		0 => "默认培训类型",
		1 => "1对1常规课",
		1001 => "普通公开课",
		1002 => "普通公开课",
		3001 => "小班课",
		4001 => "机器人课程",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		1001 => "",
		1002 => "",
		3001 => "",
		4001 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 1001,
		"" => 1002,
		"" => 3001,
		"" => 4001,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 1001=>  "",
		 1002=>  "",
		 3001=>  "",
		 4001=>  "",
	);

	//默认培训类型
	const V_0=0;
	//1对1常规课
	const V_1=1;
	//普通公开课
	const V_1001=1001;
	//普通公开课
	const V_1002=1002;
	//小班课
	const V_3001=3001;
	//机器人课程
	const V_4001=4001;

	//默认培训类型
	const S_0="";
	//1对1常规课
	const S_1="";
	//普通公开课
	const S_1001="";
	//普通公开课
	const S_1002="";
	//小班课
	const S_3001="";
	//机器人课程
	const S_4001="";



};
