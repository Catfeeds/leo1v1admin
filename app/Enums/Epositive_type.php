<?php
//自动生成枚举类  不要手工修改
//source  file: config_positive_type.php
namespace  App\Enums;

class Epositive_type extends \App\Enums\Enum_base
{
	static public $field_name = "positive_type"  ;
	static public $name = "positive_type"  ;
	 static $desc_map= array(
		0 => "未设置",
		1 => "正常转正",
		2 => "提前转正",
		3 => "申请延迟一个月",
		4 => "延迟一个月",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		4 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
	);

	//未设置
	const V_0=0;
	//正常转正
	const V_1=1;
	//提前转正
	const V_2=2;
	//申请延迟一个月
	const V_3=3;
	//延迟一个月
	const V_4=4;

	//未设置
	const S_0="";
	//正常转正
	const S_1="";
	//提前转正
	const S_2="";
	//申请延迟一个月
	const S_3="";
	//延迟一个月
	const S_4="";



};
