<?php
//自动生成枚举类  不要手工修改
//source  file: config_performance.php
namespace  App\Enums;

class Eperformance extends \App\Core\Enum_base
{
	static public $field_name = "performance"  ;
	static public $name = "performance"  ;
	 static $desc_map= array(
		1 => "差",
		2 => "较差",
		3 => "一般",
		4 => "较好",
		5 => "优秀",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
		"" => 5,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
		 5=>  "",
	);

	//差
	const V_1=1;
	//较差
	const V_2=2;
	//一般
	const V_3=3;
	//较好
	const V_4=4;
	//优秀
	const V_5=5;

	//差
	const S_1="";
	//较差
	const S_2="";
	//一般
	const S_3="";
	//较好
	const S_4="";
	//优秀
	const S_5="";



};
