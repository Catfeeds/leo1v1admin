<?php
//自动生成枚举类  不要手工修改
//source  file: config_attendance_type.php
namespace  App\Enums;

class Eattendance_type extends \App\Enums\Enum_base
{
	static public $field_name = "attendance_type"  ;
	static public $name = "attendance_type"  ;
	 static $desc_map= array(
		1 => "在家办公",
		2 => "提前下班",
		3 => "节假日延休",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
		"" => 3,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
		 3=>  "",
	);

	//在家办公
	const V_1=1;
	//提前下班
	const V_2=2;
	//节假日延休
	const V_3=3;

	//在家办公
	const S_1="";
	//提前下班
	const S_2="";
	//节假日延休
	const S_3="";



};
