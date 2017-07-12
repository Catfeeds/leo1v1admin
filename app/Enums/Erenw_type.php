<?php
//自动生成枚举类  不要手工修改
//source  file: config_renw_type.php
namespace  App\Enums;

class Erenw_type extends \App\Enums\Enum_base
{
	static public $field_name = "renw_type"  ;
	static public $name = "renw_type"  ;
	 static $desc_map= array(
		0 => "未设置",
		1 => "续费",
		2 => "不续费",
		3 => "联络或考虑",
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

	//未设置
	const V_0=0;
	//续费
	const V_1=1;
	//不续费
	const V_2=2;
	//联络或考虑
	const V_3=3;

	//未设置
	const S_0="";
	//续费
	const S_1="";
	//不续费
	const S_2="";
	//联络或考虑
	const S_3="";



};
