<?php
//自动生成枚举类  不要手工修改
//source  file: config_gender.php
namespace  App\Enums;

class Egender extends \App\Core\Enum_base
{
	static public $field_name = "gender"  ;
	static public $name = "gender"  ;
	 static $desc_map= array(
		0 => "未设置",
		1 => "男",
		2 => "女",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
	);

	//未设置
	const V_0=0;
	//男
	const V_1=1;
	//女
	const V_2=2;

	//未设置
	const S_0="";
	//男
	const S_1="";
	//女
	const S_2="";



};
