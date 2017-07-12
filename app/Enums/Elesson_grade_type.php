<?php
//自动生成枚举类  不要手工修改
//source  file: config_lesson_grade_type.php
namespace  App\Enums;

class Elesson_grade_type extends \App\Enums\Enum_base
{
	static public $field_name = "lesson_grade_type"  ;
	static public $name = "lesson_grade_type"  ;
	 static $desc_map= array(
		0 => "学生年级",
		1 => "课程包年级",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
	);

	//学生年级
	const V_0=0;
	//课程包年级
	const V_1=1;

	//学生年级
	const S_0="";
	//课程包年级
	const S_1="";



};
