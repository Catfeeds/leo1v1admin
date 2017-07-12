<?php
//自动生成枚举类  不要手工修改
//source  file: config_seller_resource_type.php
namespace  App\Enums;

class Eseller_resource_type extends \App\Enums\Enum_base
{
	static public $field_name = "seller_resource_type"  ;
	static public $name = "资源"  ;
	 static $desc_map= array(
		0 => "常规资源",
		1 => "历史资源",
		2 => "历史试听",
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

	//常规资源
	const V_0=0;
	//历史资源
	const V_1=1;
	//历史试听
	const V_2=2;

	//常规资源
	const S_0="";
	//历史资源
	const S_1="";
	//历史试听
	const S_2="";



};
