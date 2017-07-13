<?php
//自动生成枚举类  不要手工修改
//source  file: config_degree.php
namespace  App\Enums;

class Edegree extends \App\Core\Enum_base
{
	static public $field_name = "degree"  ;
	static public $name = "degree"  ;
	 static $desc_map= array(
		1 => "普通",
		2 => "优秀",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
	);

	//普通
	const V_1=1;
	//优秀
	const V_2=2;

	//普通
	const S_1="";
	//优秀
	const S_2="";



};
