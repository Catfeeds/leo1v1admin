<?php
//自动生成枚举类  不要手工修改
//source  file: config_contract_status.php
namespace  App\Enums;

class Econtract_status extends \App\Core\Enum_base
{
	static public $field_name = "contract_status"  ;
	static public $name = "状态"  ;
	 static $desc_map= array(
		0 => "未付款",
		1 => "执行中",
		2 => "已结束",
		3 => "提前终止",
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

	//未付款
	const V_0=0;
	//执行中
	const V_1=1;
	//已结束
	const V_2=2;
	//提前终止
	const V_3=3;

	//未付款
	const S_0="";
	//执行中
	const S_1="";
	//已结束
	const S_2="";
	//提前终止
	const S_3="";



};
