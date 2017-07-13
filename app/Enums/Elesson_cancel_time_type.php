<?php
//自动生成枚举类  不要手工修改
//source  file: config_lesson_cancel_time_type.php
namespace  App\Enums;

class Elesson_cancel_time_type extends \App\Core\Enum_base
{
	static public $field_name = "lesson_cancel_time_type"  ;
	static public $name = "lesson_cancel_time_type"  ;
	 static $desc_map= array(
		0 => "未设置",
		1 => "4小时内",
		2 => "4小时外",
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

	//未设置
	const V_0=0;
	//4小时内
	const V_1=1;
	//4小时外
	const V_2=2;

	//未设置
	const S_0="";
	//4小时内
	const S_1="";
	//4小时外
	const S_2="";



};
