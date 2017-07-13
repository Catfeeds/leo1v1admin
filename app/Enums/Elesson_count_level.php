<?php
//自动生成枚举类  不要手工修改
//source  file: config_lesson_count_level.php
namespace  App\Enums;

class Elesson_count_level extends \App\Core\Enum_base
{
	static public $field_name = "lesson_count_level"  ;
	static public $name = "lesson_count_level"  ;
	 static $desc_map= array(
		1 => "0-9",
		2 => "10-29",
		3 => "30-49",
		4 => "50-69",
		5 => "70-99",
		6 => "100-119",
		7 => ">=120",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
		6 => "",
		7 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
		"" => 5,
		"" => 6,
		"" => 7,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
		 5=>  "",
		 6=>  "",
		 7=>  "",
	);

	//0-9
	const V_1=1;
	//10-29
	const V_2=2;
	//30-49
	const V_3=3;
	//50-69
	const V_4=4;
	//70-99
	const V_5=5;
	//100-119
	const V_6=6;
	//>=120
	const V_7=7;

	//0-9
	const S_1="";
	//10-29
	const S_2="";
	//30-49
	const S_3="";
	//50-69
	const S_4="";
	//70-99
	const S_5="";
	//100-119
	const S_6="";
	//>=120
	const S_7="";



};
