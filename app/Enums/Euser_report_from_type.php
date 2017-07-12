<?php
//自动生成枚举类  不要手工修改
//source  file: config_user_report_from_type.php
namespace  App\Enums;

class Euser_report_from_type extends \App\Enums\Enum_base
{
	static public $field_name = "user_report_from_type"  ;
	static public $name = "user_report_from_type"  ;
	 static $desc_map= array(
		1 => "家长微信申诉",
		2 => "待定",
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

	//家长微信申诉
	const V_1=1;
	//待定
	const V_2=2;

	//家长微信申诉
	const S_1="";
	//待定
	const S_2="";



};
