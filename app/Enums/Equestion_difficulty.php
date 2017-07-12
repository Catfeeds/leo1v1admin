<?php
//自动生成枚举类  不要手工修改
//source  file: config_question_difficulty.php
namespace  App\Enums;

class Equestion_difficulty extends \App\Enums\Enum_base
{
	static public $field_name = "question_difficulty"  ;
	static public $name = "question_difficulty"  ;
	 static $desc_map= array(
		1 => "基础",
		2 => "中档",
		3 => "困难",
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

	//基础
	const V_1=1;
	//中档
	const V_2=2;
	//困难
	const V_3=3;

	//基础
	const S_1="";
	//中档
	const S_2="";
	//困难
	const S_3="";



};
