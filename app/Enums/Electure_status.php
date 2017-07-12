<?php
//自动生成枚举类  不要手工修改
//source  file: config_lecture_status.php 
namespace  App\Enums;

class Electure_status extends \App\Enums\Enum_base
{
	static public $field_name = "lecture_status"  ;
	static public $name = "lecture_status"  ;
	 static $desc_map= array(
		0 => "未审核",
		1 => "已通过",
		2 => "未通过",
		3 => "可重审",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
	);

	//未审核
	const V_0=0;
	//已通过
	const V_1=1;
	//未通过
	const V_2=2;
	//可重审
	const V_3=3;

	//未审核
	const S_0="";
	//已通过
	const S_1="";
	//未通过
	const S_2="";
	//可重审
	const S_3="";



};
