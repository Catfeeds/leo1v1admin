<?php
//自动生成枚举类  不要手工修改
//source  file: config_seller_require_change_type.php
namespace  App\Enums;

class Eseller_require_change_type extends \App\Enums\Enum_base
{
	static public $field_name = "seller_require_change_type"  ;
	static public $name = "seller_require_change_type"  ;
	 static $desc_map= array(
		0 => "未请求",
		1 => "申请更换时间",
		2 => "申请更换老师",
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

	//未请求
	const V_0=0;
	//申请更换时间
	const V_1=1;
	//申请更换老师
	const V_2=2;

	//未请求
	const S_0="";
	//申请更换时间
	const S_1="";
	//申请更换老师
	const S_2="";



};
