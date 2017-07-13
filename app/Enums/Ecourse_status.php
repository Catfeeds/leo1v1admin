<?php
//自动生成枚举类  不要手工修改
//source  file: config_course_status.php
namespace  App\Enums;

class Ecourse_status extends \App\Core\Enum_base
{
	static public $field_name = "course_status"  ;
	static public $name = "course_status"  ;
	 static $desc_map= array(
		0 => "正常上课",
		1 => "已结课",
		2 => "停课",
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

	//正常上课
	const V_0=0;
	//已结课
	const V_1=1;
	//停课
	const V_2=2;

	//正常上课
	const S_0="";
	//已结课
	const S_1="";
	//停课
	const S_2="";



};
