<?php
//自动生成枚举类  不要手工修改
//source  file: config_grade_type.php
namespace  App\Enums;

class Egrade_type extends \App\Core\Enum_base
{
	static public $field_name = "grade_type"  ;
	static public $name = "grade_type"  ;
	 static $desc_map= array(
		0 => "公开课",
		1 => "1对1",
		2 => "小班课",
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

	//公开课
	const V_0=0;
	//1对1
	const V_1=1;
	//小班课
	const V_2=2;

	//公开课
	const S_0="";
	//1对1
	const S_1="";
	//小班课
	const S_2="";



};
