<?php
//自动生成枚举类  不要手工修改
//source  file: config_seller_require_change_flag.php
namespace  App\Enums;

class Eseller_require_change_flag extends \App\Core\Enum_base
{
	static public $field_name = "seller_require_change_flag"  ;
	static public $name = "seller_require_change_flag"  ;
	 static $desc_map= array(
		0 => "正常",
		1 => "请求中",
		2 => "已处理",
		3 => "已驳回",
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

	//正常
	const V_0=0;
	//请求中
	const V_1=1;
	//已处理
	const V_2=2;
	//已驳回
	const V_3=3;

	//正常
	const S_0="";
	//请求中
	const S_1="";
	//已处理
	const S_2="";
	//已驳回
	const S_3="";



};
