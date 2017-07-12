<?php
//自动生成枚举类  不要手工修改
//source  file: config_is_freeze.php
namespace  App\Enums;

class Eis_freeze extends \App\Enums\Enum_base
{
	static public $field_name = "is_freeze"  ;
	static public $name = "冻结操作类型"  ;
	 static $desc_map= array(
		0 => "未设置",
		1 => "冻结",
		2 => "解冻",
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
	//冻结
	const V_1=1;
	//解冻
	const V_2=2;

	//未设置
	const S_0="";
	//冻结
	const S_1="";
	//解冻
	const S_2="";



};
