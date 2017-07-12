<?php
//自动生成枚举类  不要手工修改
//source  file: config_limit_plan_lesson_type.php
namespace  App\Enums;

class Elimit_plan_lesson_type extends \App\Enums\Enum_base
{
	static public $field_name = "limit_plan_lesson_type"  ;
	static public $name = "limit_plan_lesson_type"  ;
	 static $desc_map= array(
		0 => "未限制",
		1 => "一周限排1节",
		3 => "一周限排3节",
		5 => "一周限排5节",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		3 => "",
		5 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 3,
		"" => 5,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 3=>  "",
		 5=>  "",
	);

	//未限制
	const V_0=0;
	//一周限排1节
	const V_1=1;
	//一周限排3节
	const V_3=3;
	//一周限排5节
	const V_5=5;

	//未限制
	const S_0="";
	//一周限排1节
	const S_1="";
	//一周限排3节
	const S_3="";
	//一周限排5节
	const S_5="";



};
