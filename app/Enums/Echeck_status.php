<?php
//自动生成枚举类  不要手工修改
//source  file: config_check_status.php
namespace  App\Enums;

class Echeck_status extends \App\Core\Enum_base
{
	static public $field_name = "check_status"  ;
	static public $name = "check_status"  ;
	 static $desc_map= array(
		0 => "未审核",
		1 => "已通过",
		2 => "未通过",
		3 => "可重审",
		4 => "无效数据",
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

	//未审核
	const V_0=0;
	//已通过
	const V_1=1;
	//未通过
	const V_2=2;
	//可重审
	const V_3=3;
	//无效数据
	const V_4=4;

	//未审核
	const S_0="";
	//已通过
	const S_1="";
	//未通过
	const S_2="";
	//可重审
	const S_3="";
	//无效数据
	const S_4="";



};
