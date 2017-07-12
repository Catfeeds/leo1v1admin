<?php
//自动生成枚举类  不要手工修改
//source  file: config_from_type.php
namespace  App\Enums;

class Efrom_type extends \App\Enums\Enum_base
{
	static public $field_name = "from_type"  ;
	static public $name = "from_type"  ;
	 static $desc_map= array(
		0 => "课程包",
		1 => "按课时计费",
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

	//课程包
	const V_0=0;
	//按课时计费
	const V_1=1;

	//课程包
	const S_0="";
	//按课时计费
	const S_1="";



};
