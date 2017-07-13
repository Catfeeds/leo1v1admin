<?php
//自动生成枚举类  不要手工修改
//source  file: config_grade_part.php
namespace  App\Enums;

class Egrade_part extends \App\Core\Enum_base
{
	static public $field_name = "grade_part"  ;
	static public $name = "grade_part"  ;
	 static $desc_map= array(
		100 => "小学",
		200 => "初中",
		300 => "高中",
	);
	 static $simple_desc_map= array(
		100 => "",
		200 => "",
		300 => "",
	);
	 static $s2v_map= array(
		"" => 100,
		"" => 200,
		"" => 300,
	);
	 static $v2s_map= array(
		 100=>  "",
		 200=>  "",
		 300=>  "",
	);

	//小学
	const V_100=100;
	//初中
	const V_200=200;
	//高中
	const V_300=300;

	//小学
	const S_100="";
	//初中
	const S_200="";
	//高中
	const S_300="";



};
