<?php
//自动生成枚举类  不要手工修改
//source  file: config_student_type.php
namespace  App\Enums;

class Estudent_type extends \App\Enums\Enum_base
{
	static public $field_name = "student_type"  ;
	static public $name = "student_type"  ;
	 static $desc_map= array(
		0 => "在读学员",
		1 => "已结课学员",
		2 => "停课学员",
		3 => "休学学员",
		4 => "寒暑假停课",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		4 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
	);

	//在读学员
	const V_0=0;
	//已结课学员
	const V_1=1;
	//停课学员
	const V_2=2;
	//休学学员
	const V_3=3;
	//寒暑假停课
	const V_4=4;

	//在读学员
	const S_0="";
	//已结课学员
	const S_1="";
	//停课学员
	const S_2="";
	//休学学员
	const S_3="";
	//寒暑假停课
	const S_4="";



};
