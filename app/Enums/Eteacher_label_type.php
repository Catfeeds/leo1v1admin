<?php
//自动生成枚举类  不要手工修改
//source  file: config_teacher_label_type.php
namespace  App\Enums;

class Eteacher_label_type extends \App\Core\Enum_base
{
	static public $field_name = "teacher_label_type"  ;
	static public $name = "teacher_label_type"  ;
	 static $desc_map= array(
		1 => "师生互动",
		2 => "课堂氛围",
		3 => "授课规范",
		4 => "教师风格",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		4 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
	);

	//师生互动
	const V_1=1;
	//课堂氛围
	const V_2=2;
	//授课规范
	const V_3=3;
	//教师风格
	const V_4=4;

	//师生互动
	const S_1="";
	//课堂氛围
	const S_2="";
	//授课规范
	const S_3="";
	//教师风格
	const S_4="";



};
